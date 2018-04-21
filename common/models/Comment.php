<?php

namespace common\models;

use common\components\db\ActiveRecord;

/**
 * Class Comment
 * Комментарий к статье.
 *
 * @property int    $id
 * @property string $content
 * @property int    $created_at
 * @property int    $updated_at
 * @property int    $post_id
 * @property int    $author_id
 * @property Author $author
 * @property Post   $post
 *
 * @package common\models
 */
class Comment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName (): string
    {
        return '{{%comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules (): array
    {
        return [
            [
                ['content'],
                'required',
            ],
            [
                ['content'],
                'string',
                'max' => 1024 * 64,
            ],
            [
                [
                    'author_id',
                    'post_id',
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
                ['post_id'],
                'exist',
                'skipOnError'     => false,
                'targetClass'     => Post::class,
                'targetAttribute' => [
                    'post_id' => 'id',
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels ()
    {
        return [
            'content' => 'Содержимое комментария',
        ];
    }

    /**
     * Возвращает автора комментария.
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getAuthor ()
    {
        return $this->hasOne(Author::class, [
            'id' => 'author_id',
        ])->one();
    }

    /**
     * Возвращает статью, к которой принадлежит комментарий.
     *
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getPost ()
    {
        return $this->hasOne(Post::class, [
            'id' => 'post_id',
        ])->one();
    }
}