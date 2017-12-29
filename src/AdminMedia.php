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
        $this->adminSave(new MediaQuery(), new Media());
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

    public function categories()
    {
        $this->displayView('admin/media.categories');
    }
}
