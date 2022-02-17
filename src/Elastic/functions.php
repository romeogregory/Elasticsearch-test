<?php
namespace src\Elastic;

require __DIR__ . '../../../vendor/autoload.php';
use Elasticsearch\ClientBuilder;

class Index
{
    public $client;

    public function __construct()
    {
    $hosts = [
        'http://elastic:rXvBaUJdnH*tKcKSrVY+@localhost:9200',
    ];

    $this->client = ClientBuilder::create()
                    ->setHosts($hosts)
                    ->build();    
    }

    public function createIndex(array $params)
    {
        $response = $this->client->index($params);
        $_SESSION['response'] = $response;
    }

    public function delete(array $params)
    {
        $response = $this->client->delete($params);
        $_SESSION['response'] = $response;
    }

    public function search(array $params)
    {
        $response = $this->client->search($params);
        return $response;
    }

}