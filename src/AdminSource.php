<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Sources;
use Attogram\SharedMedia\Gallery\Seeder;
use Attogram\SharedMedia\Orm\Source;
use Attogram\SharedMedia\Orm\SourceQuery;
use Exception;
use Propel\Runtime\Map\TableMap;

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

    public function source()
    {
        $this->seedSources();
        try {
            $sources = SourceQuery::create()->find();
            foreach ($sources as $source) {
                $data['sources'][] = $source->toArray(TableMap::TYPE_FIELDNAME);
            }
        } catch (Exception $error) {
            //
        }
        $this->displayView('admin/source');
    }

    public function save()
    {
        $this->displayView('admin/source.save');
    }

    private function seedSources()
    {
        foreach (Sources::$sources as $title => $url) {
            try {
                SourceQuery::create()
                    ->filterByTitle($title)
                    ->filterByHost($url)
                    ->filterByEndpoint($url)
                    ->findOneOrCreate();
            } catch (Exception $error) {
                print '<pre>ERROR: seedSources: title='.$title.' url='.$url
                .' error: ' . $error->getMessage() . '</pre>';
            }
        }
    }
}
