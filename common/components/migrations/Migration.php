<?php

namespace common\components\migrations;

use yii\db\Migration as BaseClassMigration;
use yii\helpers\ArrayHelper;

class Migration extends BaseClassMigration
{
    /**
     * Создаёт таблицу с названием и временными отпечатками created_at и updated_at
     * а также если движок - MySQL, то устанавливает нужную кодировку для таблицы:
     * вместо *_general_ci ставит *_unicode_ci.
     *
     * @param string $tableName название таблицы.
     * @param array  $columns   колонки.
     * @param string $options   опции таблицы.
     */
    public function createTableWithTimestamps ($tableName, $columns, $options = '')
    {
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $options .= ' CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $columns = ArrayHelper::merge($columns, [
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        parent::createTable($tableName, $columns, $options);
    }
}