<?php

use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Post;

$this->title = 'Посты в блоге';

/** @var $this \yii\web\View */
/** @var $posts array */
/** @var $postsPerPage int */
/** @var $postListUrl string */
/** @var $postViewUrl string */
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="container posts">
    <?php /** @var Post $post */ ?>
    <?php foreach ($posts as $post): ?>
        <div class="row item">
            <div class="col">
                <span class="post-id" style="display: none"><?= Html::encode($post->id) ?></span>
                <h2><?= Html::encode($post->title) ?></h2>
                <span><?= Html::encode($post->short_description) ?></span>
                <br/>
                <span><?= Html::encode($post->description) ?></span>
                <br/>
                <span>Создана в <i><?= Html::encode($post->getCreationDatetime()) ?></i></span>
                <br/>
                <br/>

                <?= Html::a('Перейти к записи', Url::to([
                    'post/view',
                    'id' => $post->id,
                ], true)) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?= Html::button('Показать ещё', [
    'id'    => 'button-more-posts',
    'class' => 'btn btn-primary',
]) ?>

<?php $this->registerJsFile('@web/js/loadPosts.js', [
    'depends' => [yii\web\JqueryAsset::class],
]) ?>
<?php $this->registerJs("
    $('#button-more-posts').on('click', function () {
        loadPosts({
            postListUrl: '$postListUrl',
            postViewUrl: '$postViewUrl',
            postsPerPage: $postsPerPage,
            lastPostId:   $('.item:last .post-id').text(),
        });
    })
", \yii\web\View::POS_END) ?>
