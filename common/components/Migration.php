<?php

namespace common\components;
use yii\db\Migration as BaseClassMigration;

class Migration extends BaseClassMigration
{
    public function createTable($tableName, $columns, $options = null)
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        parent::createTable($tableName, $columns, $options);
    }
}