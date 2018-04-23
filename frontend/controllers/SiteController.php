<?php

namespace frontend\controllers;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function actions ()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
                'view'  => 'error',
            ],
        ];
    }

    /**
     * Отоборажает главную страницу.
     *
     * @return mixed
     */
    public function actionIndex ()
    {
        return $this->redirect(Url::toRoute(['post/index']));
        // return $this->render('index');
    }

    /**
     * Хэндлер ошибки на сайте.
     *
     * @return mixed
     */
    public function actionError ($message = null)
    {
        if (\Yii::$app->request->isAjax) {
            $response = \Yii::$app->response;
            $response->format = Response::FORMAT_JSON;
            $response->setStatusCode(400);

            return [
                'success'      => false,
                'data'         => null,
                'errorMessage' => 'ACHTUNG!',
            ];
        }

        if (null === $message) {
            $message = 'Неизвестная ошибка.';
        }

        return $this->render('error', [
            'message' => $message,
        ]);
    }
}
