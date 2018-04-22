<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $message string */
?>

<h1></h1>
<h2><?= Html::encode($message) ?></h2>
<p>
    Произошла ошибка.

    Вы можете перейти <?= Html::a('на главную страницу', Url::to(['post/index'], true)) ?>.
</p>