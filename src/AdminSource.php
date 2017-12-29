<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Sources;
use Attogram\SharedMedia\Gallery\Seeder;
use Attogram\SharedMedia\Orm\Source;
use Attogram\SharedMedia\Orm\SourceQuery;
use Propel\Runtime\Map\TableMap;
use Throwable;

class AdminSource
{
    use TraitAccessControl;
    use TraitEnvironment;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function list()
    {
        try {
            $sources = SourceQuery::create()->find();
            foreach ($sources as $source) {
                $this->data['sources'][] = $source->toArray(TableMap::TYPE_FIELDNAME);
            }
        } catch (Throwable $error) {
            // ...
        }
        $this->displayView('admin/source.list');
    }

    public function save()
    {
		// ...
        $this->displayView('admin/source.save');
    }
}
