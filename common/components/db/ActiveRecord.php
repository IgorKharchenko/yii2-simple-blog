<?php

namespace common\components\db;

use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveRecord
 *
 * @property int $created_at
 * @property int $updated_at
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
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ]);
    }

    /**
     * Возвращает дату и время создания экземпляра модели ActiveRecord.
     *
     * @return null|string
     */
    public function getCreationDatetime() : ?string
    {
        $datetime = \DateTime::createFromFormat(self::DATETIME_FORMAT, $this->created_at);

        if (false === $datetime) {
            return null;
        }

        return $datetime->format(self::DATETIME_FORMAT);
    }
}