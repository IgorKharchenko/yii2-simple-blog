<?php

namespace common\tests\unit\components\db;

use common\models\Post;
use common\tests\UnitTester;

class ActiveRecordTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    public function testGetCreationDatetime()
    {
        $model = new Post();
        $model->created_at = date($model::DATETIME_FORMAT);

        $date = $model->getCreationDatetime();
        $actual = $this->validateDate($date, $model::DATETIME_FORMAT);

        $this->assertTrue($actual);
    }

    public function testGetBadCreationDatetime()
    {
        $model = new Post();
        $model->created_at = null;

        $this->assertNull($model->getCreationDatetime());
    }

    /**
     * Валидирует дату.
     *
     * @param string $date   дата.
     * @param string $format формат даты.
     *
     * @return bool
     */
    private function validateDate(string $date, string $format)
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date;
    }
}