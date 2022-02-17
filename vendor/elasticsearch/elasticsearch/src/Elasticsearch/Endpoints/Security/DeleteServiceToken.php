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

namespace Elasticsearch\Endpoints\Security;

use Elasticsearch\Common\Exceptions\RuntimeException;
use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class DeleteServiceToken
 * Elasticsearch API name security.delete_service_token
 *
 * NOTE: this file is autogenerated using util/GenerateEndpoints.php
 * and Elasticsearch 8.0.0-SNAPSHOT (a10236e3287cbdc7b936e15dcddc9b8b19716a4b)
 */
class DeleteServiceToken extends AbstractEndpoint
{
    protected $namespace;
    protected $service;
    protected $name;

    public function getURI(): string
    {
        $namespace = $this->namespace ?? null;
        $service = $this->service ?? null;
        $name = $this->name ?? null;

        if (isset($namespace) && isset($service) && isset($name)) {
            return "/_security/service/$namespace/$service/credential/token/$name";
        }
        throw new RuntimeException('Missing parameter for the endpoint security.delete_service_token');
    }

    public function getParamWhitelist(): array
    {
        return [
            'refresh'
        ];
    }

    public function getMethod(): string
    {
        return 'DELETE';
    }

    public function setNamespace($namespace): DeleteServiceToken
    {
        if (isset($namespace) !== true) {
            return $this;
        }
        $this->namespace = $namespace;

        return $this;
    }

    public function setService($service): DeleteServiceToken
    {
        if (isset($service) !== true) {
            return $this;
        }
        $this->service = $service;

        return $this;
    }

    public function setName($name): DeleteServiceToken
    {
        if (isset($name) !== true) {
            return $this;
        }
        $this->name = $name;

        return $this;
    }
}
