<?php

// Rolling windows, an asof join, and calendar groups over a year of daily
// sales. The site runs this script on each request; see site/README.md for the format.

use Phpolars\DataFrame;

use function Phpolars\{col, date_range, int_range, len};

return [
    'inputs' => [
        'window' => ['type' => 'radio', 'label' => 'Rolling window', 'options' => ['7d' => '7 days', '28d' => '28 days', '91d' => '91 days'], 'default' => '28d'],
        'every' => ['type' => 'radio', 'label' => 'Summary period', 'options' => ['1mo' => 'Month', '1q' => 'Quarter'], 'default' => '1mo'],
    ],
    'run' => function (array $input, string $data): array {
        $window = $input['window'];
        $every = $input['every'];
        $pi = M_PI;

        // A year of daily unit sales with a weekly cycle, a seasonal swing, and
        // noise computed from the day number, so every run sees the same data.
        $daily = (new DataFrame(['i' => [0]]))
            ->select(date_range(new DateTimeImmutable('2024-01-01'), new DateTimeImmutable('2024-12-31'), '1d')->alias('date'))
            ->withColumns(int_range(0, len())->alias('day'))
            ->withColumns(
                col('day')->mul(2 * $pi / 7)->sin()->mul(25)
                    ->add(col('day')->sub(80)->mul(2 * $pi / 366)->sin()->mul(40))
                    ->add(col('day')->mul(7919)->mod(41)->sub(20))
                    ->add(200)->round()->cast('Int64')->alias('units'),
            )
            ->drop('day');

        // List prices, each in force from its date until the next change.
        $prices = (new DataFrame([
            'from' => array_map(fn ($d) => new DateTimeImmutable($d), ['2024-01-01', '2024-03-18', '2024-07-01', '2024-10-14']),
            'list_price' => [4.50, 4.95, 5.25, 4.75],
        ]))->cast(['from' => 'Date']);

        $priced = $daily
            ->joinAsof($prices, leftOn: 'date', rightOn: 'from')
            ->withColumns(
                col('units')->mul(col('list_price'))->alias('revenue'),
                col('units')->rollingMeanBy('date', $window)->round(1)->alias('rolling_units'),
            );

        $summary = $priced
            ->groupByDynamic('date', every: $every)
            ->agg(
                col('units')->sum()->alias('units'),
                col('revenue')->sum()->round(2)->alias('revenue'),
                col('list_price')->last()->alias('price_at_end'),
            );

        $points = array_map(
            fn (array $r) => ['date' => $r['date']->format('Y-m-d'), 'units' => $r['units'], 'rolling' => $r['rolling_units']],
            $priced->select('date', 'units', 'rolling_units')->toDicts(),
        );

        return [
            ['type' => 'text', 'content' => 'The frame holds a year of daily unit sales and a table of list price changes. Three steps turn it into a report: `joinAsof()` gives each day the price in force on that date, `rollingMeanBy()` smooths the units over a time window, and `groupByDynamic()` sums them by calendar period.'],
            ['type' => 'code', 'chain' => <<<PHP
                use function Phpolars\\col;

                \$priced = \$daily
                    ->joinAsof(\$prices, leftOn: 'date', rightOn: 'from')   // the latest price on or before each date
                    ->withColumns(
                        col('units')->mul(col('list_price'))->alias('revenue'),
                        col('units')->rollingMeanBy('date', '$window')->round(1)->alias('rolling_units'),
                    );

                \$summary = \$priced
                    ->groupByDynamic('date', every: '$every')
                    ->agg(
                        col('units')->sum()->alias('units'),
                        col('revenue')->sum()->round(2)->alias('revenue'),
                        col('list_price')->last()->alias('price_at_end'),
                    );
                PHP],
            ['type' => 'chart', 'title' => "Daily units and the $window rolling mean", 'plot' => [
                '$schema' => 'https://vega.github.io/schema/vega-lite/v5.json',
                'width' => 'container',
                'height' => 260,
                'data' => ['values' => $points],
                'encoding' => ['x' => ['field' => 'date', 'type' => 'temporal', 'title' => null]],
                'layer' => [
                    ['mark' => ['type' => 'line', 'opacity' => 0.35, 'strokeWidth' => 1],
                        'encoding' => ['y' => ['field' => 'units', 'type' => 'quantitative', 'title' => 'Units', 'scale' => ['zero' => false]]]],
                    ['mark' => ['type' => 'line', 'strokeWidth' => 2.5],
                        'encoding' => ['y' => ['field' => 'rolling', 'type' => 'quantitative']]],
                ],
            ]],
            ['type' => 'table', 'title' => $every === '1mo' ? 'By month' : 'By quarter',
                'columns' => $summary->columns(),
                'rows' => array_map(fn (array $r) => [$r[0]->format('Y-m-d'), ...array_slice($r, 1)], $summary->rows()),
                'digits' => 2],
            ['type' => 'note', 'content' => 'A window such as `28d` covers calendar time, so it stays correct when days are missing. `rollingMean(28)` would count rows instead. `groupByDynamic()` labels each period by its start date.'],
        ];
    },
];
