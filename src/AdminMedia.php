<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\Media;
use Attogram\SharedMedia\Orm\MediaQuery;

class AdminMedia
{
    use TraitAccessControl;
    use TraitAdminSave;
    use TraitEnvironment;
    use TraitErrors;
    use TraitQueryAdmin;
    use TraitQueryPublic;
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
        $orm = MediaQuery::create()
            ->joinWith('Source')
            ->withColumn('source.title');
        $this->setItems($orm, 'medias');
        $this->displayView('admin/media.list');
    }

    public function search()
    {
        $this->data['sourceSelect'] = $this->getSourcePulldown();
        $this->adminSearch(new MediaQuery());
        $this->displayView('admin/media.search');
    }

    public function save()
    {
        $this->setPostVars();
        foreach ($this->pageids as $pageid) {
            foreach ($this->getFieldNames() as $field) {
                $this->values[$field] = $this->{$field}[$pageid];
            }
            if (!$this->updateMediaIfExists($pageid)) {
                $this->setValues(new Media())
                    ->setSourceId($this->sourceId)
                    ->setPageid($pageid)
                    ->save();
            }
        }
        $this->redirect301($this->data['uriBase'] . '/admin/media/list/');
    }

    private function getFieldNames()
    {
        return [
            'title',
            'url', 'mime', 'width', 'height', 'size',
            'sha1',
            'thumburl', 'thumbmime', 'thumbwidth', 'thumbheight', 'thumbsize',
            'descriptionurl', 'descriptionurlshort',
            'imagedescription',
            'datetimeoriginal',
            'artist',
            'licenseshortname', 'usageterms', 'attributionrequired', 'restrictions',
            'timestamp',
            'user', 'userid',
        ];
    }

    /**
     * @param int $pageid
     * @return bool
     */
    private function updateMediaIfExists($pageid)
    {
        $orm = MediaQuery::create()
            ->filterBySourceId($this->sourceId)
            ->filterByPageid($pageid)
            ->findOne();
        if (!$orm instanceof Media) {
            return false;
        }
        $this->setValues($orm)
            ->save();
        return true;
    }

    public function categories()
    {
        $this->displayView('admin/media.categories');
    }
}
