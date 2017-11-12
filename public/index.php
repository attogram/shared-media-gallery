<?php // shared-media-gallery  index.php  v0.0.2

use Attogram\SharedMedia\Gallery\Gallery;

$lib = '../vendor/autoload.php';
if (!is_readable($lib)) {
    print 'ERROR: Autoloader Not Found: ' . $lib;
    return false;
}
require_once($lib);

$lib = '../config/config.php';
if (!is_readable($lib)) {
    print 'ERROR: Propel Config Not Found: ' . $lib;
    return false;
}
require_once($lib);

if (!class_exists('Attogram\SharedMedia\Gallery\Gallery')) {
    print 'ERROR: Gallery Class Not Found';
    return false;
}

$gallery = new Gallery();
