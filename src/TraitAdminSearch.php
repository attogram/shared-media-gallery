<?php

namespace Attogram\SharedMedia\Gallery;

use Throwable;
use Propel\Runtime\Map\TableMap;

trait TraitAdminSearch
{
    /**
     * @param object $api
     * @return void
     */
    private function adminSearch($api)
    {
        $this->data['query'] = $this->getGet('q');
        if (!$this->data['query']) {
            $this->fatalError('Query Not Found');
        }
		
		$this->data['endpoint'] = $this->getGet('endpoint');
        if (!$this->data['endpoint']) {
            $this->fatalError('Endpoint Not Found');
        }
		$api->setEndpoint($endpoint);
		
        $limit = $this->getGet('limit');
        if ($this->isNumber($limit) && $limit) {
            $api->setApiLimit($limit);
            $this->data['limit'] = $limit;
        }

        foreach ($api->search($query) as $result) {
            $this->data['results'][] = $result->toArray(TableMap::TYPE_FIELDNAME);
        }
    }
}
