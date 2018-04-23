<?php

namespace frontend\tests\functional;

use common\fixtures\CommentFixture;
use common\fixtures\PostFixture;
use common\models\Comment;
use common\models\Post;
use frontend\tests\FunctionalTester;
use yii\helpers\Url;

class PostCest
{
    public function _fixtures ()
    {
        return [
            'posts'    => PostFixture::class,
            'comments' => CommentFixture::class,
        ];
    }

    public function _before (FunctionalTester $I)
    {
        $I->am('visitor of blog');
    }

    public function indexPage (FunctionalTester $I)
    {
        $I->amGoingTo('check that main page works properly');

        $post = $this->getPost($I);

        $I->amOnPage(Url::toRoute(['/post/index']));
        $I->see($post->title);
    }

    public function readUnexistingPost (FunctionalTester $I)
    {
        $I->amGoingTo('view unexisting post');

        $I->amOnPage(Url::toRoute([
            '/post/view',
            'id' => 100500,
        ]));
        $I->see('Страница не найдена.');
    }

    public function readSinglePost (FunctionalTester $I)
    {
        $I->amGoingTo('view single post');

        $post = $this->getPost($I);

        $I->amOnPage(Url::toRoute([
            '/post/view',
            'id' => $post->id,
        ]));
        $I->see($post->title);
        $I->see($post->author->username);
    }

    public function readCommentsOfPost (FunctionalTester $I)
    {
        $I->amGoingTo('read comments of post');

        $post = $this->getPost($I);
        $commentFixture = $I->grabFixture('comments', 0);
        $comment = new Comment($commentFixture);

        $I->amOnPage(Url::toRoute([
            '/post/view',
            'id' => $post->id,
        ]));
        $I->see($comment->author->username);
        $I->see($comment->content);
    }

    private function getPost (FunctionalTester $I): Post
    {
        $postFixture = $I->grabFixture('posts', 'post1');
        return new Post($postFixture);
    }
}