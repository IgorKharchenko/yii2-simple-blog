<?php

use yii\helpers\Html;
use common\models\Comment;

/** @var $this \yii\web\View */
/** @var $comments array */
/** @var $commentsAmount int */
$counter = 1;
?>

<div class="container">
    <h1>Комментарии (<?= $commentsAmount ?>)</h1>

    <?php /** @var $comment Comment */ ?>
    <?php foreach ($comments as $comment): ?>
        <div class="item row">
            <div class="col">
                <p>
                    <i>#<?= $counter++ ?></i>
                    &nbsp;&nbsp;&nbsp;&nbsp;<b><?= Html::encode($comment->author->username); ?></b>
                </p>

                <p>
                    <?= $comment->content ?>
                </p>

                <p>
                    Создан в <i><?= $comment->getCreationDatetime() ?></i>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
</div>