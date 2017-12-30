<?php

namespace Attogram\SharedMedia\Gallery;

use DateTime;
use Throwable;

trait TraitAdminSave
{
    private function setPostVars()
    {
        $this->pageids = $this->getPost('pageid');
        if (empty($this->pageids) || !is_array($this->pageids)) {
            $this->fatalError('Nothing Selected');
        }
        $this->sourceId = $this->getPost('source_id');
        if (!$this->sourceId) {
            $this->fatalError('Source Not Found');
        }
        foreach ($this->fieldNames as $field) {
            $this->{$field} = $this->getPost($field);
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
                $ormQuery->{'set' . ucfirst($field)}($this->values[$field]);
            } catch (Throwable $error) {
                $this->fatalError($error->getMessage());
            }
        }
        $ormQuery->setUpdatedAt(new DateTime());
        return $ormQuery;
    }

    /**
     * @param object $ormQuery
     * @param int $pageid
     * @return object - ormItem
     */
    private function getItem($ormQuery, $pageid)
    {
        try {
            $query = $ormQuery
                ->filterBySourceId($this->sourceId)
                ->filterByPageid($pageid)
                ->keepQuery(false);
            $ormItem = $query->findOne();
        } catch (Throwable $error) {
            $this->fatalError($error->getMessage());
        }
        if (!$ormItem) {
            return;
        }
        return $ormItem;
    }

    /**
     * @param string $ormQueryName
     * @param int $pageid
     * @return bool
     */
    private function updateItemIfExists($ormQueryName, $pageid)
    {
        $ormQuery = new $ormQueryName;
        $ormItem = $this->getItem($ormQuery, $pageid);
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
     * @param int $pageid
     */
    private function saveItem($ormName, $pageid)
    {
        try {
            $item = new $ormName;
            $item = $this->setValues($item)
                ->setSourceId($this->sourceId)
                ->setPageid($pageid);
            $item->save();
        } catch (Throwable $error) {
            $this->fatalError($error->getMessage());
        }
    }

    /**
     * @param string $ormName
     */
    private function adminSave($ormName)
    {
        $this->setPostVars();
        foreach ($this->pageids as $pageid) {
            foreach ($this->fieldNames as $field) {
                $this->values[$field] = $this->{$field}[$pageid];
            }
            if (!$this->updateItemIfExists($ormName . 'Query', $pageid)) {
                $this->saveItem($ormName, $pageid);
            }
        }
    }
}
