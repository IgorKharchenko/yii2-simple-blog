<?php

namespace common\components\db;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveRecord
 *
 * @package common\components
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    const DATETIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * Подключает поведения к базовой модели.
     *
     * @return array
     */
    public function behaviors ()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'timestamp' => [
                'class'              => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value'              => new Expression('NOW()'),
            ],
        ]);
    }
}