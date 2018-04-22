<?php

use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $post \common\models\Post */
/** @var $comments array */
/** @var $commentsAmount int */
/** @var $commentsDataProvider \yii\data\ActiveDataProvider */

$this->title = $post->title;
?>

<div class="container">
    <h1><?= Html::encode($this->title) ?></h1>
    <span><?= Html::encode($post->short_description) ?></span>
    <h3><?= Html::encode($post->description) ?></h3>

    <p><?= $post->content ?></p>

    <span>Создан пользователем <b><?= Html::encode($post->author->username) ?></b>
        в <i><?= $post->getCreationDatetime() ?></i></span>

    <?= $this->render('comments', [
        'comments'       => $comments,
        'commentsAmount' => $commentsAmount,
    ]) ?>
</div>
