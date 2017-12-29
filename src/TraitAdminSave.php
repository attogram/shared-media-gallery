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
        foreach ($this->getFieldNames() as $field) {
            $this->{$field} = $this->getPost($field);
        }
    }

    /**
     * @param object $orm
     * @return object
     */
    private function setValues($orm)
    {
        foreach ($this->getFieldNames() as $field) {
            $orm->{'set' . ucfirst($field)}($this->values[$field]);
        }
        return $orm;
    }
}
