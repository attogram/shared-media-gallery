<?php

namespace Attogram\SharedMedia\Gallery;

class GalleryAdmin extends Router
{
    const VERSION = '0.0.4';

    protected function getRoutes()
    {
        return [
            'admin/home'     => [''],
            'admin/media'    => ['media'],
            'admin/category' => ['category'],
            'admin/debug'    => ['debug'],
        ];
    }
	
    protected function getViewData()
    {
        $data = parent::getViewData();
		$data['title'] = 'Shared Media Gallery ADMIN';
		return $data;
    }

}
