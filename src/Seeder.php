<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Api\Sources;
use Attogram\SharedMedia\Orm\Source;
use Attogram\SharedMedia\Orm\SourceQuery;

/**
 * Attogram SharedMedia Gallery Seeder
 */
class Seeder
{
    const VERSION = '0.0.1';

    public function seedSources()
    {
        foreach (Sources::$sources as $title => $url) {
            $source = SourceQuery::create()
                ->filterByTitle($title)
                ->filterByHost($url)
                ->filterByEndpoint($url)
                ->findOneOrCreate();
            if (!$source instanceof Source) {
                print '<pre>Error: seedSources: title:'.$title.' source:'.$source.'</pre>';
            }
        }
    }
}
