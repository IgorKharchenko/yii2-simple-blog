<?php

namespace frontend\tests\functional;

use common\fixtures\PostFixture;
use frontend\tests\FunctionalTester;

class PostCest
{
    public function _fixtures ()
    {
        return [
            'posts' => PostFixture::class,
        ];
    }

    public function indexPage (FunctionalTester $I)
    {
        $I->am('visitor of blog');
        $I->amGoingTo('view descriptions of first three posts');
    }
}