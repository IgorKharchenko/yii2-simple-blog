<?php

use common\components\migrations\Migration;

class m130524_201442_init extends Migration
{
    public function safeUp ()
    {
        $this->createTableWithTimestamps('{{%author}}', [
            'id' => $this->primaryKey(),

            'full_name'            => $this->string(64)->notNull(),
            'username'             => $this->string(24)->notNull()->unique(),
            'password_hash'        => $this->string(64)->notNull(),
            'password_reset_token' => $this->string(64)->unique(),
            'email'                => $this->string(128)->notNull()->unique(),

            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);
    }

    public function safeDown ()
    {
        $this->dropTable('{{%author}}');
    }
}
