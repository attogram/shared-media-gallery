<?php // shared-media-gallery  index.php  v0.0.3

use Attogram\SharedMedia\Gallery\Gallery;

$vendorDir = '../vendor/';
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

$gallery = new Gallery();
