<?php

namespace common\components\test;

use yii\base\Model;

trait UnitTestHelper
{
    /**
     * Валидирует созданную модель.
     *
     * @param Model $model       модель.
     * @param bool  $mustBeValid должен ли валидатор возвращать true или false.
     * @param array $attributes  если есть, то проверяет, что каждый атрибут валиден/невалиден.
     *
     * @return void
     */
    public function validateModel (Model $model, bool $mustBeValid, array $attributes = [])
    {
        if ($mustBeValid) {
            $this->assertTrue($model->validate(), 'validation must pass');
            $this->assertEmpty($model->errors, 'error array MUST be empty');
        } else {
            $this->assertFalse($model->validate(), 'validation must fail');
            $this->assertNotEmpty($model->errors, 'error array MUST NOT be empty');
        }

        if (!empty($attributes)) {
            foreach ($attributes as $attribute) {
                if ($mustBeValid) {
                    $this->assertFalse(
                        $model->hasErrors($attribute),
                        "model must NOT contain error in attribute '$attribute'"
                    );
                } else {
                    $this->assertTrue(
                        $model->hasErrors($attribute),
                        "model must NOT contain error in attribute '$attribute'"
                    );
                }
            }
        }
    }
}