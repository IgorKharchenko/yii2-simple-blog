<?php

namespace frontend\controllers;

use common\models\Comment;
use common\models\Post;
use yii\base\InvalidArgumentException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

class PostController extends Controller
{
    private $postsPerPage = 3;

    /**
     * {@inheritdoc}
     */
    public function behaviors (): array
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'verbs' => [
                'class'   => VerbFilter::class,
                'actions' => [
                    'list' => ['GET'],
                ],
            ],
        ]);
    }

    /**
     * Отображает страницу с постами.
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function actionIndex ()
    {
        $posts = Post::find()
                     ->where([
                         '>',
                         'id',
                         0,
                     ])
                     ->orderBy('created_at')
                     ->limit($this->postsPerPage)
                     ->all();

        $postListUrl = Url::to(['/post/list'], true);
        // Url, который будет устанавливаться всем постам. В js-е 'postId' заменяется на id поста.
        $postViewUrl = Url::to([
            '/post/view',
            'id' => 'postId',
        ], true);

        return $this->render('index', [
            'posts'        => $posts,
            'postsPerPage' => $this->postsPerPage,
            'postListUrl'  => $postListUrl,
            'postViewUrl'  => $postViewUrl,
        ]);
    }

    /**
     * Просмотр конкретного поста.
     *
     * @param int $id id поста.
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function actionView (int $id)
    {
        $post = Post::findOne(['id' => $id]);

        if (null === $post) {
            return $this->render('error', [
                'message' => 'Пост не найден!',
            ]);
        }

        $comments = Comment::find()
                           ->where([
                               '=',
                               'post_id',
                               $post->id,
                           ]);

        return $this->render('view', [
            'post'           => $post,
            'comments'       => $comments->all(),
            'commentsAmount' => $comments->count(),
        ]);
    }

    /**
     * REST-экшен.
     * Возвращает список из постов.
     *
     * @param int $lastId     id последнего поста. Используется вместо offset (см. линк ниже).
     *                        По умолчанию равен 0, что означает, что будут возвращены первые посты
     *                        в запросе.
     * @param int $amount     количество постов, т.е. sql-ный limit.
     *
     * @return string
     *
     * @link https://use-the-index-luke.com/no-offset
     */
    public function actionList ($amount, $lastId = 0)
    {
        $amount = (int)$amount;
        $lastId = (int)$lastId;

        $response = \Yii::$app->response;
        $response->format = Response::FORMAT_JSON;

        $responseData = [
            'success'      => false,
            'data'         => null,
            'errorMessage' => null,
        ];

        if ($lastId < 0) {
            $response->setStatusCode(400);
            $responseData['errorMessage'] = 'Last ID must be greater than 0 or equal.';
            return $responseData;
        }
        if ($amount <= 0) {
            $response->setStatusCode(400);
            $responseData['errorMessage'] = 'Amount of posts must be greater than 0.';
            return $responseData;
        }

        $posts = Post::find()
                     ->where([
                         '>',
                         'id',
                         $lastId,
                     ])
                     ->orderBy('created_at')
                     ->limit($amount)
                     ->all();

        $out = [];
        /** @var Post $post */
        foreach ($posts as $post) {
            $postAttributes = $post->getAttributes();
            $postAttributes['created_at'] = $post->getCreationDatetime();
            $out[] = $postAttributes;
        }

        $response->setStatusCode(200);
        $responseData['success'] = true;
        $responseData['data'] = $out;

        return $responseData;
    }
}