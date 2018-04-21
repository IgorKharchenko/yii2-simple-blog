<?php

use common\components\migrations\Migration;

/**
 * Handles the creation of table `post`.
 */
class m180421_143114_create_post_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $this->createTableWithTimestamps('post', [
            'id' => $this->primaryKey(),

            'title'             => $this->text()->notNull(),
            'short_description' => $this->tinyText()->notNull(),
            'description'       => $this->text()->notNull(),
            'content'           => $this->longText()->notNull(),

            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'FK_post_author',
            'post',
            'author_id',
            'author',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
        $this->dropForeignKey('FK_post_author', 'post');

        $this->dropTable('post');
    }
}
