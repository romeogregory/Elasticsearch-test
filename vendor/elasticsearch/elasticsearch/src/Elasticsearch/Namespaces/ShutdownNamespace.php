<?php
/**
 * Elasticsearch PHP client
 *
 * @link      https://github.com/elastic/elasticsearch-php/
 * @copyright Copyright (c) Elasticsearch B.V (https://www.elastic.co)
 * @license   http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @license   https://www.gnu.org/licenses/lgpl-2.1.html GNU Lesser General Public License, Version 2.1 
 * 
 * Licensed to Elasticsearch B.V under one or more agreements.
 * Elasticsearch B.V licenses this file to you under the Apache 2.0 License or
 * the GNU Lesser General Public License, Version 2.1, at your option.
 * See the LICENSE file in the project root for more information.
 */
declare(strict_types = 1);

namespace Elasticsearch\Namespaces;

use Elasticsearch\Namespaces\AbstractNamespace;

/**
 * Class ShutdownNamespace
 *
 * NOTE: this file is autogenerated using util/GenerateEndpoints.php
 * and Elasticsearch 8.0.0-SNAPSHOT (a10236e3287cbdc7b936e15dcddc9b8b19716a4b)
 */
class ShutdownNamespace extends AbstractNamespace
{

    /**
     * Removes a node from the shutdown list. Designed for indirect use by ECE/ESS and ECK. Direct use is not supported.
     *
     * $params['node_id'] = (string) The node id of node to be removed from the shutdown state
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current
     */
    public function deleteNode(array $params = [])
    {
        $node_id = $this->extractArgument($params, 'node_id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Shutdown\DeleteNode');
        $endpoint->setParams($params);
        $endpoint->setNodeId($node_id);

        return $this->performRequest($endpoint);
    }
    /**
     * Retrieve status of a node or nodes that are currently marked as shutting down. Designed for indirect use by ECE/ESS and ECK. Direct use is not supported.
     *
     * $params['node_id'] = (string) Which node for which to retrieve the shutdown status
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current
     */
    public function getNode(array $params = [])
    {
        $node_id = $this->extractArgument($params, 'node_id');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Shutdown\GetNode');
        $endpoint->setParams($params);
        $endpoint->setNodeId($node_id);

        return $this->performRequest($endpoint);
    }
    /**
     * Adds a node to be shut down. Designed for indirect use by ECE/ESS and ECK. Direct use is not supported.
     *
     * $params['node_id'] = (string) The node id of node to be shut down
     * $params['body']    = (array) The shutdown type definition to register (Required)
     *
     * @param array $params Associative array of parameters
     * @return array
     * @see https://www.elastic.co/guide/en/elasticsearch/reference/current
     */
    public function putNode(array $params = [])
    {
        $node_id = $this->extractArgument($params, 'node_id');
        $body = $this->extractArgument($params, 'body');

        $endpointBuilder = $this->endpoints;
        $endpoint = $endpointBuilder('Shutdown\PutNode');
        $endpoint->setParams($params);
        $endpoint->setNodeId($node_id);
        $endpoint->setBody($body);

        return $this->performRequest($endpoint);
    }
}
