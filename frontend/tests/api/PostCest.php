<?php

namespace frontend\tests\api;

use common\fixtures\AuthorFixture;
use common\models\Author;
use common\models\Post;
use frontend\tests\ApiTester;
use yii\helpers\Url;

class PostCest
{
    public function _fixtures ()
    {
        return [
            'authors' => AuthorFixture::class,
        ];
    }

    public function getPostsBadAmount (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/list']), [
            'amount' => -1,
            'lastId' => 1,
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'success'      => false,
            'data'         => null,
            'errorMessage' => 'Amount of posts must be greater than 0.',
        ]);
    }

    public function getPostsBadPreviousId (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/list']), [
            'amount' => 1,
            'lastId' => -1,
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'success'      => false,
            'data'         => null,
            'errorMessage' => 'Last ID must be greater than 0 or equal.',
        ]);
    }

    public function getEmptyPosts (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/list']), [
            'amount' => 1,
            'lastId' => 100500,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'success'      => true,
            'data'         => [],
            'errorMessage' => null,
        ]);
    }

    public function getPosts (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/list']), [
            'amount' => 1,
            'lastId' => 1,
        ]);

        $post = Post::findOne(['id' => 2]);
        $attributes = [
            'id'                => $post->id,
            'title'             => $post->title,
            'short_description' => $post->short_description,
            'description'       => $post->description,
            'content'           => $post->content,
            'author_id'         => $post->author_id,
            'created_at'        => $post->getCreationDatetime(),
            'updated_at'        => $post->updated_at,
        ];

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'success'      => true,
            'data'         => [
                $attributes,
            ],
            'errorMessage' => null,
        ]);
    }

    public function authorCommentsBadPostId (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/author-comments']), [
            'postId' => -1,
        ]);

        $I->seeResponseCodeIs(400);
        $I->seeResponseContainsJson([
            'success'      => false,
            'data'         => null,
            'errorMessage' => 'Post ID must be greater than 0.',
        ]);
    }

    public function authorCommentsOfUnexistingPost (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/author-comments']), [
            'postId' => 100500,
        ]);

        $I->seeResponseCodeIs(404);
        $I->seeResponseContainsJson([
            'success'      => false,
            'data'         => null,
            'errorMessage' => 'Post not found.',
        ]);
    }

    public function postWithoutComments (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/author-comments']), [
            'postId' => 7,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'success'      => true,
            'data'         => [],
            'errorMessage' => null,
        ]);
    }

    public function getAuthorComments (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/author-comments']), [
            'postId' => 1,
        ]);

        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            'success'      => true,
            'data'         => $this->getExpectedAuthorComments($I),
            'errorMessage' => null,
        ]);
    }

    public function authorsSortedByAmountOfComments (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/author-comments']), [
            'postId' => 1,
        ]);

        $response = json_decode($I->grabResponse());
        $responseData = $response->data;

        $I->assertEquals(3, $responseData[0]->author->id);
        $I->assertEquals(1, $responseData[1]->author->id);
        $I->assertEquals(2, $responseData[2]->author->id);
    }

    private function getExpectedAuthorComments (ApiTester $I): array
    {
        $admin = $I->grabFixture('authors', 'admin');
        $user1 = $I->grabFixture('authors', 'user1');
        $user2 = $I->grabFixture('authors', 'user2');

        $admin = new Author($admin);
        $user1 = new Author($user1);
        $user2 = new Author($user2);

        return [
            [
                'author'           => $user2->getAttributes(),
                'amountOfComments' => 1,
            ],
            [
                'author'           => $admin->getAttributes(),
                'amountOfComments' => 2,
            ],
            [
                'author'           => $user1->getAttributes(),
                'amountOfComments' => 2,
            ],
        ];
    }
}