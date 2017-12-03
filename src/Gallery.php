<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Gallery\GalleryTools;
use Attogram\SharedMedia\Gallery\Tools;
use Attogram\SharedMedia\Orm\MediaQuery;
use Propel\Runtime\Map\TableMap;

class Gallery extends Router
{
    const VERSION = '0.0.10';

    public function __construct(int $level = 0)
    {
        $this->data['title'] = 'Shared Media Gallery';
        $this->data['version'] = self::VERSION;
        parent::__construct($level);
    }

    protected function getRoutes()
    {
        return [
            'home'       => [''],
            'about'      => ['about'],
            'categories' => ['category'],
            'category'   => ['category', '*'],
            'medias'     => ['media'],
            'media'      => ['media', '*'],
        ];
    }
	protected function controlHome()
	{
		$this->data['media'] = MediaQuery::create()
			->setOffset(rand(1, $this->data['category_count'] - 1))
			->findOne();
		return true;
	}

    protected function controlMedias()
    {
        $page = 1;
        $maxPerPage = 20;
        $medias = MediaQuery::create()->paginate($page, $maxPerPage);
        if (!$medias) {
            return true;
        }
        foreach ($medias as $media) {
            $this->data['medias'][] = $media->toArray(TableMap::TYPE_FIELDNAME);
        }
        return true;
    }

    protected function controlMedia()
    {
        if (!Tools::isNumber($this->uri[1])) {
            $this->error404('400 Media Not Found');
            return false;
        }
        $media = MediaQuery::create()->filterByPageid($this->uri[1])->findOne();
        if (!$media) {
            $this->error404('404 Media Not Found');
            return false;
        }
        $this->data['media'] = $media;

        return true;
    }

    protected function controlCategory()
    {
        if (!Tools::isNumber($this->uri[1])) {
            $this->error404('404 Category Not Found');
            return false;
        }
        return true;
    }
}
