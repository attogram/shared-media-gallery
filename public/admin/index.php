<?php // shared-media-gallery  admin/index.php  v0.0.3

use Attogram\SharedMedia\Gallery\GalleryAdmin;

$vendorDir = '../../vendor/';
$lib = $vendorDir . 'autoload.php';
if (!is_readable($lib)) {
    print 'ERROR: Autoloader Not Found: ' . $lib;
    return false;
}
require_once($lib);

$lib = $vendorDir . 'attogram/shared-media-orm/config/config.php';
if (!is_readable($lib)) {
    print 'ERROR: Propel Config Not Found: ' . $lib;
    return false;
}
require_once($lib);

if (!class_exists('Attogram\SharedMedia\Gallery\GalleryAdmin')) {
    print 'ERROR: GalleryAdmin Class Not Found';
    return false;
}

$gallery = new GalleryAdmin(1);
