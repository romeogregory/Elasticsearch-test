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

namespace Elasticsearch\Endpoints\Indices;

use Elasticsearch\Endpoints\AbstractEndpoint;

/**
 * Class Stats
 * Elasticsearch API name indices.stats
 *
 * NOTE: this file is autogenerated using util/GenerateEndpoints.php
 * and Elasticsearch 8.0.0-SNAPSHOT (a10236e3287cbdc7b936e15dcddc9b8b19716a4b)
 */
class Stats extends AbstractEndpoint
{
    protected $metric;

    public function getURI(): string
    {
        $metric = $this->metric ?? null;
        $index = $this->index ?? null;

        if (isset($index) && isset($metric)) {
            return "/$index/_stats/$metric";
        }
        if (isset($metric)) {
            return "/_stats/$metric";
        }
        if (isset($index)) {
            return "/$index/_stats";
        }
        return "/_stats";
    }

    public function getParamWhitelist(): array
    {
        return [
            'completion_fields',
            'fielddata_fields',
            'fields',
            'groups',
            'level',
            'types',
            'include_segment_file_sizes',
            'include_unloaded_segments',
            'expand_wildcards',
            'forbid_closed_indices'
        ];
    }

    public function getMethod(): string
    {
        return 'GET';
    }

    public function setMetric($metric): Stats
    {
        if (isset($metric) !== true) {
            return $this;
        }
        if (is_array($metric) === true) {
            $metric = implode(",", $metric);
        }
        $this->metric = $metric;

        return $this;
    }
}
