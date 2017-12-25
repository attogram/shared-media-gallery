<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\Seeder;
use Attogram\SharedMedia\Orm\SourceQuery;
use Propel\Runtime\Map\TableMap;

class AdminSource
{
    use TraitView;

    private $data = [];

    public function source($data)
    {
        $seeder = new Seeder();
        $seeder->seedSources();
        foreach (SourceQuery::create()->find() as $source) {
            $data['sources'][] = $source->toArray(TableMap::TYPE_FIELDNAME);
        }
        $this->displayView('admin/source', $data);
    }

    public function save($data)
    {
        print 'AdminSource::save';
    }
}
