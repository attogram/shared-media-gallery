<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\MediaQuery;
use Throwable;

class PublicSite
{
    use TraitErrors;
    use TraitQueryPublic;
    use TraitTools;
    use TraitView;

    private $data = [];

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function home()
    {
        try {
            $this->data['media'] = MediaQuery::create()
                ->setOffset(rand(1, $this->data['media_count'] - 1))
                ->findOne();
        } catch (Throwable $error) {
            //print $error->getMessage();
        }
        $this->displayView('home');
    }

    public function about()
    {
        $this->displayView('about');
    }
}
