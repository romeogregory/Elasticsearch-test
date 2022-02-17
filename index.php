<?php
session_start();
require __DIR__ . '/vendor/autoload.php';
include 'src/Elastic/functions.php';
use src\Elastic\Index;

$obj = new Index();

if(isset($_GET['filters']))
{
    $filters = $_SESSION['filters'];
    $results = $_SESSION['results'];
}
else
{
    unset($_SESSION['filters']);
    unset($_SESSION['results']);
    $_SESSION['data'] = false;
    $filters = [];
}

$count = $obj->search([
    'index' => 'giftcards',
    'body' => [
        'query' => [
            'match_all' => (object)[],
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


    // for($i = 1; $i < 10; $i++)
    // {
    //     $response = $obj->createIndex([
    //         'refresh' => [
    //             'refresh'   => 'wait_for'
    //         ],
    //         'body' => [
    //             'c_number'      => $i,
    //             'c_provider'    => 'Bol',
    //             'c_amount'      => rand(1, 100),
    //             'c_expired'     => false,
    //         ],
    //         'index' => [
    //             '_index'    => 'giftcards',
    //         ],
    //     ]);
    // }

    if(isset($_GET['search']))
    {
        $records = $obj->search([
            'index' => 'giftcards',
            'body' => [
                'query' => [
                    'prefix' => [
                        'c_provider' => $_GET['search']
                    ],
                ],
            ],
        ]);
    }
    elseif(isset($_GET['range']))
    {
        $records = $obj->search([
            'index' => 'giftcards',
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                        [
                            'range' => [
                                'c_amount' => [
                                    'gte' => $filters[0]['min'],
                                    'lte' => $filters[0]['max'],
                                    'boost' => 2.0
                                ],
                            ],
                        ],
                            [
                                'term' => [
                                    'c_expired' => $filters[1]['identifier']
                                ],
                            ]
                        ],
                    ],
                ],
            ],
        ]);
    }
    else
    {
        $records = $obj->search([
            'index' => 'giftcards',
            'body' => [
                'query' => [
                    'match_all' => (object)[],
                ],
            ],
        ]);
    }
    $total_records = $records['hits']['total']['value'];
    $per_page = 5;

    $max_pages = ceil($total_records / $per_page);


    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;

    if(isset($_GET['page']))
    {
        $records_on_page = ceil($_GET['page'] * $per_page - $per_page);
    }
    else
    {
        $records_on_page = ceil(0 * $per_page);
    }

    if(isset($_GET['search']))
    {
        $response = $obj->search([
            'body' => [
                'from' => $records_on_page,
                'size' => $per_page,
                'query' => [
                    'prefix' => [
                        'c_provider' => $_GET['search']
                    ],
                ],
            ],
        ]);
    }
    elseif(isset($_GET['range']))
    {
        $response = $obj->search([
            'index' => 'giftcards',
            'from' => $records_on_page,
            'size' => $per_page,
            'body' => [
                'query' => [
                    'bool' => [
                        'must' => [
                        [
                            'range' => [
                                'c_amount' => [
                                    'gte' => $filters[0]['min'],
                                    'lte' => $filters[0]['max'],
                                    'boost' => 2.0
                                ],
                            ],
                        ],
                            [
                                'term' => [
                                    'c_expired' => $filters[1]['identifier']
                                ],
                            ]
                        ],
                    ],
                ],
            ],
        ]);
    }
    else
    {
        $response = $obj->search([
            'index' => 'giftcards',
            'from' => $records_on_page,
            'size' => $per_page,
            'body' => [
                'query' => [
                    'match_all' => (object)[],
                ],
            ],
        ]);
    }

    if(isset($_GET['page']))
    {
        $_SESSION['data'] = true;
        $_SESSION['results'] = $response;
        $results = $_SESSION['results'];
    }



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elasticsearch</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>
    <nav class="navbar navbar-light bg-primary"></nav>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <section class="terminal-container">
                    <header class="terminal">
                        <span class="button red"></span>
                        <span class="button yellow"></span>
                        <span class="button green"></span>
                    </header>
                    <div class="terminal-home">
                        <?php
                            if(isset($_SESSION['response'])) {
                                $response = $_SESSION['response'];
                                unset($_SESSION['response']);

                                if($response['result'] == 'created')
                                {
                                    echo '<p class="console">Creating new giftcard...</p>';
                                    echo '<p class="console" style="color: #0f0">=> New giftcard has been created</p>';
                                
                                }
                                if($response['result'] == 'deleted')
                                {
                                    echo '<p class="console">Deleting existing giftcard...</p>';
                                    echo '<p class="console" style="color: #0f0">=> Giftcard has been deleted</p>';
                                }

                                echo '<p class="console">* Index: '.$response['_index'].'</p>';
                                echo '<p class="console">* ID: '.$response['_id'].'</p>';
                                echo '<p class="console">* Type: '.$response['_type'].'</p>';
                            }
                            if(isset($_SESSION['data']) && $_SESSION['data'] == true)
                            {
                                
                                echo '<p class="console">Receiving giftcards...</p>';
                                if(empty($results['hits']['hits']))
                                {
                                    echo '<p class="console" style="color: red">=> No matching giftcards has been found.</p>';
                                }
                                else
                                {
                                    echo '<p class="console">=> '.$results['hits']['total']['value'].' giftcard(s) has been found...</p>';
                                }
                                foreach($results['hits']['hits'] as $res)
                                {
                                    $color = $res['_source']['c_expired'] ? 'red' : '#0f0';
                                    echo '<p class="console">=> Provider: '.$res['_source']['c_provider'].' [<span>'.$res['_id'].'</span>]</p>';
                                    echo '<p class="console" style="color: '.$color.'">* Giftcard number: '.$res['_source']['c_number'].'</p>';
                                    echo '<p class="console" style="color: '.$color.'">* Giftcard amount: €'.$res['_source']['c_amount'].'</p></p>';
                                }
                            }
                        ?>
                    </div>
                    <div class="position-relative">
                        <div class="position-absolute  top-10 end-0 mt-3">
                            <?php if($max_pages > 0 ) : ?>
                            <nav aria-label="Page navigation examplemb-5">
                                <ul class="pagination">
                                    <?php if ($page > 1): ?>
                                    <?php  
                                        if(isset($_GET['search']))
                                        {
                                            echo '<li class="page-item"><a class="page-link" href="index.php?page='. $page - 1 .'&search='.$_GET['search'].'">Previous</a></li>';
                                        }
                                        elseif(isset($_GET['range']))
                                        {
                                            echo '<li class="page-item"><a class="page-link" href="index.php?page='. $page - 1 .'&filters=true&range='.$_GET['range'].'&expired='.$_GET['expired'].'">Previous</a></li>';
                                        }
                                        else
                                        {
                                            echo '<li class="page-item"><a class="page-link" href="index.php?page='. $page - 1 .'">Previous</a></li>';

                                        }
                                    ?>
                                    <?php endif; ?>
                                    <?php 
                                        for($i = 1; $i < $max_pages + 1; $i++) {
                                            if($page == $i)
                                            {
                                                $active = 'active';
                                            }
                                            else {
                                                $active = '';   
                                            }

                                            if(isset($_GET['search']))
                                            {
                                            
                                                echo '<li class="page-item '.$active.'"><a class="page-link" href="index.php?page='.$i.'&search='.$_GET['search'].'">'.$i.'</a></li>';
                                            }
                                            elseif(isset($_GET['range']))
                                            {
                                                echo '<li class="page-item '.$active.'"><a class="page-link" href="index.php?page='.$i.'&filters=true&range='.$_GET['range'].'&expired='.$_GET['expired'].'">'.$i.'</a></li>';
                                            }
                                            else
                                            {
                                                echo '<li class="page-item  '.$active.'"><a class="page-link" href="index.php?page='.$i.'">'.$i.'</a></li>';
                                            }
                                        }
                                    ?>
                                    <?php if($page < $max_pages) : ?>
                                        <?php  
                                        if(isset($_GET['search']))
                                        {
                                            echo '<li class="page-item"><a class="page-link" href="index.php?page='. $page + 1 .'&search='.$_GET['search'].'">Next</a></li>';
                                        }
                                        elseif(isset($_GET['range']))
                                        {
                                            echo '<li class="page-item"><a class="page-link" href="index.php?page='. $page + 1 .'&filters=true&range='.$_GET['range'].'&expired='.$_GET['expired'].'">Next</a></li>';
                                        }
                                        else
                                        {
                                            echo '<li class="page-item"><a class="page-link" href="index.php?page='. $page + 1 .'">Next</a></li>';

                                        }
                                    ?>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
            <div class="col-md-4 mb-5">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="src/Actions/search.php">
                            <div class="row">
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="search" id="search"
                                        placeholder="Search for a giftcard e.g. bol" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    <button type="submit" class="btn btn-primary">Search</button>

                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-body">
                        <?php
                            if(!empty($filters)) 
                            {
                                echo '<small class="fw-bold ">Active Filters:</small>';
                                foreach($filters as $filter)
                                {
                                    echo '<span class="badge fw-lighter bg-primary m-1">'.$filter['type'].'</span>';
                                }
                            }
                        ?>
                        <form method="GET" action="src/Actions/filters.php">
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" name="range">
                                        <option value="0-10"
                                            <?php if(!empty($_GET['range']) && $_GET['range'] === '0-10') { echo 'selected'; } ?>>
                                            €0 - €10 (<?php echo $count['aggregations']['quantity_ranges']['buckets'][0]['doc_count'] ?>)</option>
                                        <option value="10-50"
                                            <?php if(!empty($_GET['range']) && $_GET['range'] === '10-50') { echo 'selected'; } ?>>
                                            €10 - €50 (<?php echo $count['aggregations']['quantity_ranges']['buckets'][1]['doc_count'] ?>)</option>
                                        <option value="50-100"
                                            <?php if(!empty($_GET['range']) && $_GET['range'] === '50-100') { echo 'selected'; } ?>>
                                            €50 - €100 (<?php echo $count['aggregations']['quantity_ranges']['buckets'][2]['doc_count'] ?>)</option>
                                        <option value="all"
                                            <?php if(!empty($_GET['range']) && $_GET['range'] === 'all') { echo 'selected'; } ?>>
                                            All (<?php echo $count['hits']['total']['value'] ?>)</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" aria-label="Default select example" name="expired">
                                        <option value="false"
                                            <?php if(!empty($_GET['range']) && $_GET['expired'] === 'false') { echo 'selected'; } ?>>
                                            Not Expired (<?php echo $count['aggregations']['terms']['buckets'][0]['doc_count'] ?>)</option>
                                        <option value="true"
                                            <?php if(!empty($_GET['range']) && $_GET['expired'] === 'true') { echo 'selected'; } ?>>
                                            Expired (<?php echo $count['aggregations']['terms']['buckets'][1]['doc_count'] ?>)</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary mt-1">Apply</button>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">Create a new Index</div>
                    <div class="card-body">
                        <form method="POST" action="src/Actions/new.php">
                            <div class="mb-3">
                                <label for="c_number" class="form-label">Giftcard Number</label>
                                <input type="text" class="form-control" name="c_number" id="c_number"
                                    autocomplete="off">
                            </div>
                            <label for="basic-url" class="form-label">Amount</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="c_amount">€</span>
                                <input type="text" class="form-control" name="c_amount" aria-label="c_amount"
                                    aria-describedby="c_amount" autocomplete="off">
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Provider</label>
                                <select class="form-select" name="c_provider">
                                    <option value="Bol">Bol</option>
                                    <option value="Mediamarkt">Mediamarkt</option>
                                    <option value="Zalando">Zalando</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input" name="c_expired" id="c_expired"
                                            value="true">
                                        <label class="form-check-label" for="c_expired">Expired</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">Create new
                                        Giftcard</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mt-4">
                    <div class="card-header">Delete document by ID</div>
                    <div class="card-body">
                        <form method="POST" action="src/Actions/delete.php">
                            <div class="mb-3">
                                <label for="id" class="form-label">Document ID</label>
                                <input type="text" class="form-control" name="id" id="id">
                            </div>
                            <button type="submit" class="btn btn-danger">Delete document</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>