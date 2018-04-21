<?php

namespace frontend\controllers;

use common\models\Post;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

class PostController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors (): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            /*
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'list' => ['GET'],
                ],
            ],
            */
        ]);
    }

    public function actionIndex ()
    {
        $response = \Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $response->setStatusCode(200);
        return [
            'hi!',
        ];
    }

    public $enableCsrfValidation = false;

    /**
     * Возвращает список из постов.
     *
     * @param int $previousId id предыдущего поста. Используется вместо offset (см. линк ниже).
     *                        По умолчанию равен 0, что означает, что будут возвращены первые посты
     *                        в запросе.
     * @param int $amount     количество постов, т.е. sql-ный limit.
     *
     * @return string
     *
     * @link https://use-the-index-luke.com/no-offset
     */
    public function actionList (int $amount, int $previousId = 0)
    {
        $response = \Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $responseData = [
            'success'      => false,
            'data'         => null,
            'errorMessage' => null,
        ];

        if (0 > $previousId) {
            $response->setStatusCode(400);
            $responseData['errorMessage'] = 'Previous ID must be greater than 0 or equal.';
            return $responseData;
        }
        if (0 >= $amount) {
            $response->setStatusCode(400);
            $responseData['errorMessage'] = 'Amount of posts must be greater than 0.';
            return $responseData;
        }

        $posts = Post::find()
                     ->where([
                         '>',
                         'id',
                         $previousId,
                     ])
                     ->orderBy('created_at')
                     ->limit($amount)
                     ->all();

        $out = [];
        foreach ($posts as $post) {
            $out[] = $post->getAttributes();
        }

        $response->setStatusCode(200);
        $responseData['success'] = true;
        $responseData['data'] = $out;

        return $responseData;
    }
}