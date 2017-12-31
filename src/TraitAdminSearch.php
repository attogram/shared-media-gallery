<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\SourceQuery;
use Throwable;
use Propel\Runtime\Map\TableMap;

trait TraitAdminSearch
{
    private function setSources()
    {
        $sources = SourceQuery::create()->find();
        foreach ($sources as $source) {
            $this->data['sources'][$source->getId()] = $source->toArray(TableMap::TYPE_FIELDNAME);
        }
        $this->data['source'] = $this->getGet('source');
    }

    /**
     * @param object $api
     * @return void
     */
    private function adminSearch($api)
    {
        $this->data['query'] = $this->getGet('q');
        if (!$this->data['query']) {
            return;
        }

        $this->setSources();
        if (!$this->data['source']) {
            $this->fatalError('Source Not Found');
        }

        $this->setLimit();

        $this->data['apiUrl'] =
            $this->data['sources'][$this->data['source']]['host']
            . $this->data['sources'][$this->data['source']]['endpoint'];
        $api->setApiEndpoint($this->data['apiUrl']);

        $limit = $this->getGet('limit');
        if ($this->isNumber($limit) && $limit) {
            $api->setApiLimit($limit);
            $this->data['limit'] = $limit;
        }

        foreach ($api->search($this->data['query']) as $result) {
            $this->data['results'][] = $result->toArray(TableMap::TYPE_FIELDNAME);
        }
    }
}
