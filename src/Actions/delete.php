<?php
session_start();
use src\Elastic\Index;

include '..\Elastic\functions.php';

$obj = new Index();

if(!empty($_POST))
{
    if(isset($_POST['id']))
    {
        $id = $_POST['id'];

        if(!empty($id))
        {
            $search = $obj->search([
                'index' => 'giftcards',

                'body' => [
                    'query' => [
                        'match_all' => [
                            'boost' => 2.0
                        ]
                    ],
                ],
            ]);

            foreach ($search['hits']['hits'] as $exist)
            {
                if(in_array($id, $exist))
                {
                    $response = $obj->delete([
                        'index' => 'giftcards',
                        'id'    => $id
                    ]);
        
                    header("Location:/elastic");
                }
                else
                {
                    header("Location:/elastic");
                }
            }
            

            
        }
        else
        {
            header("Location:/elastic");
        }
    }
}
else
{
    header("Location:/elastic");
}