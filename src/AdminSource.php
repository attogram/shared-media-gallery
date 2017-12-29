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
    use TraitErrors;
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
            print $error->getMessage();
        }
        $this->displayView('admin/source.list');
    }

    public function save()
    {
        $title = $this->getPost('t');
        $host = $this->getPost('h');
        $endpoint = $this->getPost('e');
        $listUrl = $this->data['uriBase'] . '/admin/source/list/';
        if (!$title || !$host || !$endpoint) {
            $this->redirect302($listUrl);
        }
        $source = new Source();
        try {
            $source
                ->setTitle($title)
                ->setHost($host)
                ->setEndpoint($endpoint)
                ->save();
        } catch (Throwable $error) {
            $this->error403('ERROR: ' . $error->getMessage());
        }
        $this->redirect302($listUrl);
    }
}
