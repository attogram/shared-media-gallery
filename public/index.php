<?php // shared-media-gallery  index.php  v0.0.1

use Attogram\SharedMedia\Gallery\Gallery;

function loadLib($lib)
{
	if (!is_readable($lib)) {
		throw new Exception('File Not Found: '.$lib);
	}
	require_once($lib);
}

try {
	loadLib('../vendor/autoload.php');
	loadLib('../vendor/attogram/shared-media-orm/config/config.php');
} catch (Exception $error) {
	print 'ERROR: '.$error->getMessage();
	return false;
}

$gallery = new Gallery();
