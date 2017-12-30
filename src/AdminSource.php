<?php

namespace Attogram\SharedMedia\Gallery;

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
    private $listUrl = '';

    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
        $this->listUrl = $this->data['uriBase'] . '/admin/source/list/';
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
        if (!$title || !$host || !$endpoint) {
            $this->redirect302($this->listUrl);
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
        $this->redirect302($this->listUrl);
    }

    public function delete()
    {
        $sourceId = (int) $this->data['vars'][0];
        if (!$sourceId || !$this->isNumber($sourceId)) {
            $this->error404('404 Source ID Not Found');
        }

        $postSourceId = (int) $this->getPost('sourceId');
        if (!$postSourceId) {
            $this->deleteConfirm($sourceId);
            return;
        }
        if (!$postSourceId || !$this->isNumber($postSourceId) || $sourceId !== $postSourceId) {
            $this->error404('404 Source ID Not Found');
        }
        try {
            $source = SourceQuery::create()->findOneById($sourceId);
            $source->delete();
        } catch (Throwable $error) {
            print $error->getMessage();
            exit;
        }
        $this->redirect302($this->listUrl);
    }

    /**
     * @param int $sourceId
     */
    private function deleteConfirm($sourceId)
    {
        if (!$this->setSource($sourceId)) {
            $this->error404('404 Source Not Found');
        }
        $this->displayView('admin/source.delete');
    }

    /**
     * @param int $sourceId
     * @return bool
     */
    private function setSource($sourceId)
    {
        try {
            $source = SourceQuery::create()->findOneById($sourceId);
            if (!$source instanceof Source) {
                return false;
            }
            $this->data['source'] = $source->toArray(TableMap::TYPE_FIELDNAME);
            return true;
        } catch (Throwable $error) {
            $this->error404('404 Source Query Failed');
        }
    }
}
