<?php
session_start();
use src\Elastic\Index;

include '..\Elastic\functions.php';

$obj = new Index();

if(isset($_GET['search']))
{
    $q = $_GET['search'];

    $response = $obj->search([
        'body' => [
            'size' => 5,
            'query' => [
                'prefix' => [
                    'c_provider' => $q
                ],
            ],
        ],
    ]);

    if($response['hits']['total'] >= 1 )
    {
        $filters[] = [
            'identifier' => 'search',
            'type' => "Search: $q",
        ];

        $_SESSION['data'] = true;
        $_SESSION['idle'] = false;
        $_SESSION['results'] = $response;
        $_SESSION['filters'] = $filters;

        header("Location:/elastic/index.php?filters=true&search=$q");
    }
}