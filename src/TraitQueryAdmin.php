<?php

namespace Attogram\SharedMedia\Gallery;

use Throwable;
use Propel\Runtime\Map\TableMap;

trait TraitQueryAdmin
{
    /**
     * @param object $api
     * @return void
     */
    private function adminSearch($api)
    {
        $query = $this->getGet('q');
        if (!$query) {
            return;
        }
        $this->data['query'] = $query;
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
