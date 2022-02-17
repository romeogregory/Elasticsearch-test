<?php
session_start();
use src\Elastic\Index;

include '..\Elastic\functions.php';

$obj = new Index();

if(isset($_GET['range']) || isset($_GET['expired']))
{
    $range = $_GET['range'];
    $expired = $_GET['expired'];
    

    $min = null;
    $max = null;

    if($range == '0-10')
    {
        $min = 0;
        $max = 10;
        $filters[] = [
            'type'  => 'Range: €0 to €10',
            'min'   => $min,
            'max'   => $max
        ];
    }
    if($range == '10-50')
    {
        $min = 10;
        $max = 50;
        $filters[] = [
            'type'  => 'Range: €10 to €50',
            'min'   => $min,
            'max'   => $max
        ];
    }
    if($range == '50-100')
    {
        $min = 50;
        $max = 100;
        $filters[] = [
            'type'  => 'Range: €50 to €100',
            'min'   => $min,
            'max'   => $max
        ];
    }

    if($range == 'all')
    {
        $min = 0;
        $max = 999999999;
        $filters[] = [
            'type'  => 'Range: All',
            'min'   => $min,
            'max'   => $max
        ];
    }

    if($expired == 'true')
    {
        $filters[] = [
            'type'          => "Expired: Expired",
            'identifier'    => true,
        ];
    }
    else
    {
        $filters[] = [
            'type'          => "Expired: Not Expired",
            'identifier'    => false,
        ];
    }
    $response = $obj->search([
        'body' => [
            'size' => 5,
            'query' => [
                'bool' => [
                    'must' => [
                    [
                        'range' => [
                            'c_amount' => [
                                'gte' => $min,
                                'lte' => $max,
                                'boost' => 2.0
                            ],
                        ],
                    ],
                        [
                            'term' => [
                                'c_expired' => $expired
                            ],
                        ]
                    ],
                ],
            ],
            'aggs' => [
                'quantity_ranges' => [
                    'range' => [
                        'field' => 'c_amount',
                        'ranges' => [
                            ['from' => 0, 'to' => 10],
                            ['from' => 10, 'to' => 50],
                            ['from' => 50, 'to' => 100],
                        ],
                    ],
                ],
                'terms' => [
                    'terms' => [
                        'field' => 'c_expired'
                    ]
                ]
            ],
        ],
    ]);


    if($response['hits']['total'] >= 1 )
    {
        $_SESSION['data'] = true;
        $_SESSION['idle'] = false;
        $_SESSION['results'] = $response;
        $_SESSION['filters'] = $filters;

        header("Location:/elastic/index.php?filters=true&range=$range&expired=$expired");
    }
}