<?php

namespace frontend\tests\api;

use common\models\Post;
use frontend\tests\ApiTester;
use yii\helpers\Url;

class PostCest
{
    public function getPostsBadAmount (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/list']), [
            'amount'     => -1,
            'previousId' => 1,
        ]);
        $I->seeResponseContainsJson([
            'success'      => false,
            'data'         => null,
            'errorMessage' => 'Amount of posts must be greater than 0.',
        ]);
    }

    public function getPostsBadPreviousId (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/list']), [
            'amount'     => 1,
            'previousId' => -1,
        ]);
        $I->seeResponseContainsJson([
            'success'      => false,
            'data'         => null,
            'errorMessage' => 'Previous ID must be greater than 0 or equal.',
        ]);
    }

    public function getEmptyPosts (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/list']), [
            'amount'     => 1,
            'previousId' => 100500,
        ]);

        $I->seeResponseContainsJson([
            'success'      => true,
            'data'         => [],
            'errorMessage' => null,
        ]);
    }

    public function getPosts (ApiTester $I)
    {
        $I->sendGET(Url::toRoute(['/post/list']), [
            'amount'     => 1,
            'previousId' => 1,
        ]);

        $post = Post::findOne(['id' => 2]);
        $attributes = [
            'id'                => $post->id,
            'title'             => $post->title,
            'short_description' => $post->short_description,
            'description'       => $post->description,
            'content'           => $post->content,
            'author_id'         => $post->author_id,
            'created_at'        => $post->created_at,
            'updated_at'        => $post->updated_at,
        ];

        $I->seeResponseContainsJson([
            'success'      => true,
            'data'         => [
                $attributes,
            ],
            'errorMessage' => null,
        ]);
    }
}