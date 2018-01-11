<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\Source;
use Attogram\SharedMedia\Orm\SourceQuery;
use Propel\Runtime\Map\TableMap;
use Throwable;

/**
 * Class AdminSource
 * @package Attogram\SharedMedia\Gallery
 */
class AdminSource
{
    use TraitAccessControl;
    use TraitEnvironment;
    use TraitErrors;
    use TraitTools;
    use TraitView;

    private $data = [];
    private $listUrl = '';

    /**
     * AdminSource constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
        $this->listUrl = $this->data['uriBase'] . '/admin/source/list/';
    }

    /**
     * @return \Attogram\SharedMedia\Orm\SourceQuery
     */
    private function getSourceQuery()
    {
        try {
            return SourceQuery::create()
                ->addAsColumn(
                    'category_count',
                    '(SELECT COUNT(*) FROM category WHERE category.source_id = source.id)'
                )->addAsColumn(
                    'media_count',
                    '(SELECT COUNT(*) FROM media WHERE media.source_id = source.id)'
                )->addAsColumn(
                    'page_count',
                    '(SELECT COUNT(*) FROM page WHERE page.source_id = source.id)'
                );
        } catch (Throwable $error) {
            $this->fatalError('getSourceQuery: ' . $error->getMessage());
        }
    }

    public function list()
    {
        try {
            $sources = $this->getSourceQuery()->find();
            foreach ($sources as $source) {
                $this->data['sources'][] = $source->toArray(TableMap::TYPE_FIELDNAME);
            }
        } catch (Throwable $error) {
            $this->fatalError($error->getMessage());
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
            $this->fatalError($error->getMessage());
        }
        $this->redirect302($this->listUrl);
    }

    /**
     * @return void
     */
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
            $source = $this->getSourceQuery()->findOneById($sourceId);
            $source->delete();
        } catch (Throwable $error) {
            $this->fatalError($error->getMessage());
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
            $source = $this->getSourceQuery()->findOneById($sourceId);
            if (!$source instanceof Source) {
                return false;
            }
            $this->data['source'] = $source->toArray(TableMap::TYPE_FIELDNAME);
        } catch (Throwable $error) {
            $this->fatalError('setSource: Source Query Failed');
        }
        return true;
    }
}
