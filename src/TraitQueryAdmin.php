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

    /**
     * @deprecated
     * @param object $api
     * @return void
     */
    private function adminSave($api)
    {
        $pageids = $this->getPost('pageid');
        if (!$pageids) {
            return;
        }
        if (is_array($pageids)) {
            $pageids = implode('|', $pageids);
        }
        $api->setApiPageid($pageids);
        foreach ($api->info() as $result) {
            try {
                $result->setSourceId(1);
                $this->adminSaveOrUpdate($api, $result);
            } catch (Throwable $error) {
                $this->data['errors'][] = 'adminSave: pageid:'
                    . $result->getPageid() . ': ' . $error->getMessage();
            }
        }
    }

    /**
     * @deprecated
     * @param object $api
     * @param object $result
     * @return void
     */
    private function adminSaveOrUpdate($api, $result)
    {
        $exists = $api::create()
            ->filterByPageid($result->getPageid())
            ->findOne();
        if (!$exists) {
            $result->save();
            $this->data['results'][] = $result->toArray(TableMap::TYPE_FIELDNAME);
            return;
        }
        $exists->fromArray($result->toArray());
        $exists->save();
        $this->data['results'][] = $exists->toArray(TableMap::TYPE_FIELDNAME);
    }
}
