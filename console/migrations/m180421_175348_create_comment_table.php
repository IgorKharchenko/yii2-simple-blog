<?php

use common\components\migrations\Migration;

/**
 * Handles the creation of table `comment`.
 */
class m180421_175348_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp ()
    {
        $this->createTableWithTimestamps('comment', [
            'id'        => $this->primaryKey(),
            'content'   => $this->text()->notNull(),
            'post_id'   => $this->integer()->notNull(),
            'author_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            'FK_comment_author',
            'comment',
            'author_id',
            'author',
            'id'
        );

        $this->addForeignKey(
            'FK_comment_post',
            'comment',
            'post_id',
            'author',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown ()
    {
        $this->dropForeignKey(
            'FK_comment_author',
            'comment'
        );

        $this->dropForeignKey(
            'FK_comment_post',
            'comment'
        );

        $this->dropTable('comment');
    }
}
