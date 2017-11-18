<?php // shared-media-gallery  index.php  v0.0.4

use Attogram\SharedMedia\Gallery\Gallery;

// Bootstrap ////////////////////////////////////
$projectDir = '../';
$lib = $projectDir . 'vendor/autoload.php';
if (!is_readable($lib)) {
    print 'ERROR: Autoloader Not Found: ' . $lib;
    return false;
}
require_once($lib);
/////////////////////////////////////////////////

$gallery = new Gallery();
