<?php

use common\components\migrations\Migration;

/**
 * Class m180423_114533_add_index_to_post
 */
class m180423_114533_add_index_to_post extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // По этому индексу ведётся order by
        $this->createIndex('idx_post_created_at', 'post', 'created_at');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx_post_created_at', 'post');
    }
}
