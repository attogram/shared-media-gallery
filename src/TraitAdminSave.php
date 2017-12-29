<?php

namespace Attogram\SharedMedia\Gallery;

trait TraitAdminSave
{
    private function setPostVars()
    {
        $this->pageids = $this->getPost('pageid');
        if (empty($this->pageids) || !is_array($this->pageids)) {
            $this->error404('404 Nothing Selected');
        }
        $this->sourceId = $this->getPost('source_id');
        if (!$this->sourceId) {
            $this->error404('404 Source Not Found');
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
            $ormQuery->{'set' . ucfirst($field)}($this->values[$field]);
        }
        return $ormQuery;
    }

    /**
     * @param object $orm
     * @param int $pageid
     * @return bool
     */
    private function updateItemIfExists($orm, $pageid)
    {
        $result = $orm->filterBySourceId($this->sourceId)
            ->filterByPageid($pageid)
            ->findOne();
        if (!$result) {
            return false;
        }
        $this->setValues($orm)
            ->save();
        return true;
    }

    /**
     * @param object $ormQuery
     * @param object $ormItem
     */
    private function adminSave($ormQuery, $ormItem)
    {
        $this->setPostVars();
        foreach ($this->pageids as $pageid) {
            foreach ($this->fieldNames as $field) {
                $this->values[$field] = $this->{$field}[$pageid];
            }
            if (!$this->updateItemIfExists($ormQuery, $pageid)) {
                $this->setValues($ormItem)
                    ->setSourceId($this->sourceId)
                    ->setPageid($pageid)
                    ->save();
            }
        }
    }
}
