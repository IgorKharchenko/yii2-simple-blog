<?php

namespace frontend\tests\acceptance;

use common\fixtures\PostFixture;
use frontend\tests\AcceptanceTester;

class PostCest
{
    public function _fixtures()
    {
        return [
            'posts' => PostFixture::class,
        ];
    }

    public function ajaxLoadPosts(AcceptanceTester $I)
    {
        $I->am('visitor of blog');
        $I->amGoingTo('click on button "Show more"');
        $I->wantTo('see that three more posts loaded');

        $I->amOnPage('post/index');
        $I->click('Показать ещё');

        $I->tryWaitForElementVisible('.posts:nth-child(4)', 10);

        $post4 = $I->grabFixture('posts', 'post4');
        $I->seeNumberOfElements('.posts', 6);
    }
}