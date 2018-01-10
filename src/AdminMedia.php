<?php

namespace Attogram\SharedMedia\Gallery;

/**
 * Class AdminMedia
 * @package Attogram\SharedMedia\Gallery
 */
class AdminMedia
{
    use TraitAccessControl;
    use TraitAdminSave;
    use TraitAdminSearch;
    use TraitEnvironment;
    use TraitErrors;
    use TraitQueryPublic;
    use TraitTools;
    use TraitView;

    private $data = [];
    private $fieldNames = [];

    /**
     * AdminMedia constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->accessControl();
    }

    public function list()
    {
        $this->setItems($this->getMediaQuery(), 'medias');
        $this->displayView('admin/media.list');
    }

    public function save()
    {
        $this->setFieldNames();
        $this->adminSave('Attogram\\SharedMedia\\Orm\\Media');
        $this->redirect301($this->data['uriBase'] . '/admin/media/list/');
    }

    private function setFieldNames()
    {
        $this->fieldNames = [
            'title', 'imagedescription',
            'url', 'mime', 'width', 'height', 'size', 'sha1',
            'thumburl', 'thumbmime', 'thumbwidth', 'thumbheight', 'thumbsize',
            'descriptionurl', 'descriptionurlshort',
            'datetimeoriginal', 'artist',
            'licenseshortname', 'usageterms', 'attributionrequired', 'restrictions',
            'timestamp', 'user', 'userid',
        ];
    }

    public function search()
    {
        $this->adminSearch($this->getMediaQuery());
        $this->displayView('admin/media.search');
    }

    public function categories()
    {
        $this->displayView('admin/media.categories');
    }

	public function delete()
	{
		$this->adminDelete($this->getMediaQuery());
        $this->redirect301($this->data['uriBase'] . '/admin/media/list/');
	}
}
