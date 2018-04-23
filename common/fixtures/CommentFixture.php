<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class CommentFixture extends ActiveFixture
{
    public $modelClass = 'common\models\Comment';

    public $depends = [
        'common\fixtures\PostFixture',
        'common\fixtures\AuthorFixture',
    ];
}