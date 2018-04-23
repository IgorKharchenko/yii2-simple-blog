<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $message string */
?>

<h1></h1>
<h2><?= Html::encode($message) ?></h2>
<p>
    Произошла ошибка.<br/>
    <br/>
    Скорее всего, что-то пошло не так на сайте.<br/>
    Но вы в любой момент можете вернуться <?= Html::a('на главную страницу', Url::to(['post/index'], true)) ?>
    для того, чтобы прочитать свежие посты в нашем блоге!
</p>