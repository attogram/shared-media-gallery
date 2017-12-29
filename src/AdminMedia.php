<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\Media;
use Attogram\SharedMedia\Orm\MediaQuery;

class AdminMedia
{
    use TraitAccessControl;
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
            $this->values = [
                'title' => $this->title[$pageid],
                'url' => $this->url[$pageid],
                'mime' => $this->mime[$pageid],
                'width' => $this->width[$pageid],
                'height' => $this->height[$pageid],
                'size' => $this->size[$pageid],
                'sha1' => $this->sha1[$pageid],
                'thumburl' => $this->thumburl[$pageid],
                'thumbmime' => $this->thumbmime[$pageid],
                'thumbwidth' => $this->thumbwidth[$pageid],
                'thumbheight' => $this->thumbheight[$pageid],
                'thumbsize' => $this->thumbsize[$pageid],
                'descriptionurl' => $this->descriptionurl[$pageid],
                'descriptionurlshort' => $this->descriptionurlshort[$pageid],
                'imagedescription' => $this->imagedescription[$pageid],
                'datetimeoriginal' => $this->datetimeoriginal[$pageid],
                'artist' => $this->artist[$pageid],
                'licenseshortname' => $this->licenseshortname[$pageid],
                'usageterms' => $this->usageterms[$pageid],
                'attributionrequired' => $this->attributionrequired[$pageid],
                'restrictions' => $this->restrictions[$pageid],
                'timestamp' => $this->timestamp[$pageid],
                'user' => $this->user[$pageid],
                'userid' => $this->userid[$pageid],
            ];
            if ($this->updateMediaIfExists($this->sourceId, $pageid)) {
                continue;
            }
            $this->setMediaValues(new Media())
                ->setSourceId($this->sourceId)
                ->setPageid($pageid)
                ->save();
        }
        $this->redirect301($this->data['uriBase'] . '/admin/media/list/');
    }

    private function setPostVars()
    {
        $this->pageids = $this->getPost('pageid');
        if (empty($this->pageids) || !is_array($this->pageids)) {
            $this->error404('404 Media Not Selected');
        }
        $this->sourceId = $this->getPost('source_id');
        if (!$this->sourceId) {
            $this->error404('404 Source Not Found');
        }
        $this->title = $this->getPost('title');
        $this->url = $this->getPost('url');
        $this->mime = $this->getPost('mime');
        $this->width = $this->getPost('width');
        $this->height = $this->getPost('height');
        $this->size = $this->getPost('size');
        $this->sha1 = $this->getPost('sha1');
        $this->thumburl = $this->getPost('thumburl');
        $this->thumbmime = $this->getPost('thumbmime');
        $this->thumbwidth = $this->getPost('thumbwidth');
        $this->thumbheight = $this->getPost('thumbheight');
        $this->thumbsize = $this->getPost('thumbsize');
        $this->descriptionurl = $this->getPost('descriptionurl');
        $this->descriptionurlshort = $this->getPost('descriptionurlshort');
        $this->imagedescription = $this->getPost('imagedescription');
        $this->datetimeoriginal = $this->getPost('datetimeoriginal');
        $this->artist = $this->getPost('artist');
        $this->licenseshortname = $this->getPost('licenseshortname');
        $this->usageterms = $this->getPost('usageterms');
        $this->attributionrequired = $this->getPost('attributionrequired');
        $this->restrictions = $this->getPost('restrictions');
        $this->timestamp = $this->getPost('timestamp');
        $this->user = $this->getPost('user');
        $this->userid = $this->getPost('userid');
    }

    /**
     * @param int $sourceId
     * @param int $pageid
     * @return bool
     */
    private function updateMediaIfExists($sourceId, $pageid)
    {
        $orm = MediaQuery::create()
            ->filterBySourceId($sourceId)
            ->filterByPageid($pageid)
            ->findOne();
        if (!$orm instanceof Media) {
            return false;
        }
        $this->setMediaValues($orm)
            ->save();
        return true;
    }

    /**
     * @param object $orm
     * @return object
     */
    private function setMediaValues($orm)
    {
        return $orm
            ->setTitle($this->values['title'])
            ->setUrl($this->values['url'])
            ->setMime($this->values['mime'])
            ->setWidth($this->values['width'])
            ->setHeight($this->values['height'])
            ->setSize($this->values['size'])
            ->setSha1($this->values['sha1'])
            ->setThumburl($this->values['thumburl'])
            ->setThumbmime($this->values['thumbmime'])
            ->setThumbwidth($this->values['thumbwidth'])
            ->setThumbheight($this->values['thumbheight'])
            ->setThumbsize($this->values['thumbsize'])
            ->setDescriptionurl($this->values['descriptionurl'])
            ->setDescriptionurlshort($this->values['descriptionurlshort'])
            ->setImagedescription($this->values['imagedescription'])
            ->setDatetimeoriginal($this->values['datetimeoriginal'])
            ->setArtist($this->values['artist'])
            ->setLicenseshortname($this->values['licenseshortname'])
            ->setUsageterms($this->values['usageterms'])
            ->setAttributionrequired($this->values['attributionrequired'])
            ->setRestrictions($this->values['restrictions'])
            ->setTimestamp($this->values['timestamp'])
            ->setUser($this->values['user'])
            ->setUserid($this->values['userid']);
    }

    public function categories()
    {
        $this->displayView('admin/media.categories');
    }
}
