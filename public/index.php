<?php // shared-media-gallery  index.php  v0.0.4

use Attogram\SharedMedia\Gallery\Gallery;

$lib = '../vendor/autoload.php';
if (!is_readable($lib)) {
    exit('ERROR: Autoloader Not Found: ' . $lib);
}
require_once($lib);

new Gallery();
