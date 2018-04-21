<?php

namespace common\models;

use common\components\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class Post
 *
 * @property int    $id
 * @property string $title
 * @property string $short_description
 * @property string $description
 * @property string $content
 * @property int    $author_id
 * @property int    $created_at
 * @property int    $updated_at
 * @property Author $author
 *
 * @package common\models
 */
class Post extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName (): string
    {
        return '{{%post}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules (): array
    {
        return ArrayHelper::merge(parent::rules(), [
            [
                [
                    'title',
                    'short_description',
                    'description',
                    'content',
                ],
                'required',
            ],
            [
                [
                    'title',
                    'short_description',
                    'description',
                    'content',
                ],
                'string',
                'min' => 10,
            ],
            [
                ['title'],
                'string',
                'max' => 1024 * 64,
            ],
            [
                ['short_description'],
                'string',
                'max' => 255,
            ],
            [
                ['description'],
                'string',
                'max' => 1024 * 64,
            ],
            [
                ['content'],
                'string',
                'max' => 1024 * 1024 * 1024 * 4,
            ],
            [
                ['author_id'],
                'exist',
                'skipOnError'     => false,
                'targetClass'     => Author::class,
                'targetAttribute' => [
                    'author_id' => 'id',
                ],
            ],
            [
                [
                    'created_at',
                    'updated_at',
                ],
                'integer',
            ],
            [
                [
                    'created_at',
                    'updated_at',
                ],
                'safe',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels ()
    {
        return [
            'title'             => 'Заголовок статьи',
            'short_description' => 'Краткое описание статьи',
            'description'       => 'Описание статьи',
            'content'           => 'Содержимое статьи',
        ];
    }

    /**
     * Возвращает автора статьи.
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getAuthor ()
    {
        return $this->hasOne(Author::class, [
            'id' => 'author_id',
        ])->one();
    }
}