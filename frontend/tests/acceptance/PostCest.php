<?php

namespace frontend\tests\acceptance;

use common\fixtures\PostFixture;
use common\models\Post;
use frontend\tests\AcceptanceTester;

class PostCest
{
    public function _fixtures ()
    {
        return [
            'posts' => PostFixture::class,
        ];
    }

    public function _before (AcceptanceTester $I)
    {
        $I->am('visitor of blog');
    }

    public function ajaxLoadPosts (AcceptanceTester $I)
    {
        $I->amGoingTo('click on button "Show more"');
        $I->wantTo('check that three more posts loaded');

        $I->amOnPage('/post/index');
        $I->click('Показать ещё');

        $I->tryWaitForElementVisible('.item:nth-child(4)', 10);

        $I->seeNumberOfElements('.item', 6);
    }

    public function returnToPostList (AcceptanceTester $I)
    {
        $I->amGoingTo('view single post');
        $I->wantTo('click on "Return to posts list" button');

        $post = $this->getPost($I);

        $I->amOnPage("/index-test.php?r=post/view&id={$post->id}");
        $I->tryWaitForElementVisible('.container h1', 10);
        $I->click('Перейти к списку постов');

        $I->tryWaitForElementVisible('.container h1', 10);

        $I->seeNumberOfElements('.item', 3);
    }

    public function checkPostLinksVisible (AcceptanceTester $I)
    {
        $I->amGoingTo('view single post');
        $I->wantTo('checks that posts have link "Go to posts"');

        $I->amOnPage('post/index');
        $I->canSeeLink('Перейти к записи');
    }

    public function checkLinksClickable (AcceptanceTester $I)
    {
        $I->amGoingTo('view single post');
        $I->wantTo('click on "Go to post" link of first post');

        $I->amOnPage('post/index');
        $I->click('Перейти к записи', '.item:nth-child(1) .col');

        $I->wantTo('check that I see first post');

        $I->tryWaitForElementVisible('.container h1', 10);
        $I->see('Перейти к списку постов');
    }

    private function getPost (AcceptanceTester $I): Post
    {
        $postFixture = $I->grabFixture('posts', 'post1');
        return new Post($postFixture);
    }
}