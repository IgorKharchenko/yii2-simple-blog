<?php

use common\components\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp ()
    {
        $tableOptions = null;

        $this->createTable('{{%author}}', [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string()->notNull()->unique(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email'                => $this->string()->notNull()->unique(),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down ()
    {
        $this->dropTable('{{%author}}');
    }
}
