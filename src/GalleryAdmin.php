<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\CategoryQuery;

class GalleryAdmin extends Router
{
    const VERSION = '0.0.6';

    public function __construct(int $level = 0)
    {
        $this->data['title'] = 'Shared Media Gallery ADMIN';
        $this->data['version'] = self::VERSION;
        parent::__construct($level);
    }

    protected function getRoutes()
    {
        return [
            'admin/home'     => [''],
            'admin/media'    => ['media'],
            'admin/category' => ['category'],
            'admin/debug'    => ['debug'],
        ];
    }

    protected function controlAdminMedia()
    {
        return true;
    }

    protected function controlAdminCategory()
    {
        if (!Tools::hasGet('q')) {
			return true;
		}
		
        $this->data['query'] = Tools::getGet('q');
		$categoryQuery = new CategoryQuery();
		$results = $categoryQuery->search($this->data['query']);
		$this->data['results'] = $categoryQuery->format($results);

        return true;
    }
}
