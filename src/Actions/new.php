<?php
session_start();
use src\Elastic\Index;

include '..\Elastic\functions.php';

$obj = new Index();


if(!empty($_POST))
{
    if(isset($_POST['c_number'], $_POST['c_provider']))
    {
        $c_number       = $_POST['c_number'];
        $c_amount       = $_POST['c_amount'];
        $c_provider     = $_POST['c_provider'];
        $c_expired      = false;

        if(isset($_POST['c_expired']))
        {
            $c_expired = true;
        }

        // die($c_expired);
        
        if(!empty($c_number) && !empty($c_provider) && !empty($c_amount))
        {
            $response = $obj->createIndex([
                'refresh' => [
                    'refresh'   => 'wait_for'
                ],
                'body' => [
                    'c_number'      => $c_number,
                    'c_provider'    => $c_provider,
                    'c_amount'      => intval($c_amount),
                    'c_expired'     => $c_expired,
                ],
                'index' => [
                    '_index'    => 'giftcards',
                ],
            ]);
            $_SESSION['flash_message'] = [
                'message' => '<b>Success</b>: Successfully created a new Giftcard inside Elasticsearch!',
                'type'    => 'success'
            ];
            header("Location:/elastic");
        }
        else
        {
            $_SESSION['flash_message'] = [
                'message' => '<b>Error:</b> Fill in all fields!',
                'type'    => 'danger'
            ];

            header("Location:/elastic");
        }

    }
}
else
{
    $_SESSION['flash_message'] = [
        'message' => '<b>Error:</b> Fill in all fields!',
        'type'    => 'danger'
    ];
    
    header("Location:/elastic");
}