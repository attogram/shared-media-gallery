<?php // shared-media-gallery  admin/index.php  v0.0.4

use Attogram\SharedMedia\Gallery\GalleryAdmin;

// Bootstrap ////////////////////////////////////
$projectDir = '../../';
$lib = $projectDir . 'vendor/autoload.php';
if (!is_readable($lib)) {
    print 'ERROR: Autoloader Not Found: ' . $lib;
    return false;
}
require_once($lib);
/////////////////////////////////////////////////

$gallery = new GalleryAdmin(1);
