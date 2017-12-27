<?php

namespace Attogram\SharedMedia\Gallery;

use Attogram\SharedMedia\Orm\MediaQuery;
use Exception;

class PublicSite
{
    use TraitQueryItem;
    use TraitTools;
    use TraitView;

    /**
     * @param array $data
     */
    public function home($data)
    {
        try {
            $data['media'] = MediaQuery::create()
                ->setOffset(rand(1, $data['category_count'] - 1))
                ->findOne();
        } catch (Exception $error) {
            //$data['error'] = $error->getMessage();
        }
        $this->displayView('home', $data);
    }

    public function about($data)
    {
        $this->displayView('about', $data);
    }
}
