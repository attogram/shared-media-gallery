<?php

namespace Attogram\SharedMedia\Gallery;

use DateTime;
use Throwable;

trait TraitAdminSave
{
    private $pageid;

    /**
     * @param string $ormName
     */
    private function adminSave($ormName)
    {
        $this->setPostVars();
        foreach ($this->pageids as $pageid) {
            $this->setValuesByPageid($pageid);
            $this->pageid = $pageid;
            if (!$this->updateItemIfExists($ormName . 'Query')) {
                $this->saveItem($ormName);
            }
        }
    }

    private function setPostVars()
    {
        $this->pageids = $this->getPost('pageid');
        if (empty($this->pageids) || !is_array($this->pageids)) {
            $this->fatalError('Nothing Selected');
        }
        $this->sourceId = $this->getPost('source');
        if (!$this->sourceId) {
            $this->fatalError('Source Not Found');
        }
        foreach ($this->fieldNames as $field) {
            $this->{$field} = $this->getPost($field);
        }
    }

    private function setValuesByPageid($pageid)
    {
        foreach ($this->fieldNames as $field) {
            if (!isset($this->{$field}[$pageid])) {
                $this->fatalError(
                    'Field Array Value Not Found: ' . get_class($this) . ': ' . $field
                );
            }
            $this->values[$field] = $this->{$field}[$pageid];
        }
    }

    /**
     * @param string $ormQueryName
     * @return bool
     */
    private function updateItemIfExists($ormQueryName)
    {
        $ormQuery = new $ormQueryName;
        $ormItem = $this->getItem($ormQuery);
        if (!$ormItem) {
            return false;
        }
        try {
            $ormItem = $this->setValues($ormItem);
            $ormItem->save();
        } catch (Throwable $error) {
            $this->fatalError($error->getMessage());
        }
        return true;
    }

    /**
     * @param string $ormName
     */
    private function saveItem($ormName)
    {
        try {
            $item = new $ormName;
            $item = $this->setValues($item)
                ->setSourceId($this->sourceId)
                ->setPageid($this->pageid);
            $item->save();
        } catch (Throwable $error) {
            $this->fatalError($error->getMessage());
        }
    }

    /**
     * @param object $ormQuery
     * @return object
     */
    private function setValues($ormQuery)
    {
        foreach ($this->fieldNames as $field) {
            try {
                $setMethod = 'set' . ucfirst(str_replace('_', '', $field));
                $ormQuery->{$setMethod}($this->values[$field]);
            } catch (Throwable $error) {
                $this->fatalError($error->getMessage());
            }
        }
        $ormQuery->setUpdatedAt(new DateTime());
        return $ormQuery;
    }

    /**
     * @param object $ormQuery
     * @return mixed
     */
    private function getItem($ormQuery)
    {
        try {
            $query = $ormQuery
                ->filterBySourceId($this->sourceId)
                ->filterByPageid($this->pageid);
            $ormItem = $query->findOne();
        } catch (Throwable $error) {
            $this->fatalError($error->getMessage());
        }
        if (!$ormItem) {
            return;
        }
        return $ormItem;
    }
}
