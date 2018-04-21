<?php

namespace common\tests\unit\models;

use common\components\test\UnitTestHelper;
use common\fixtures\AuthorFixture;
use common\models\Author;
use common\tests\UnitTester;

/**
 * Class AuthorValidationTest
 * Спека для валидации класса Author.
 *
 * @package common\tests\unit\models
 */
class AuthorTest extends \Codeception\Test\Unit
{
    use UnitTestHelper;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function _fixtures (): array
    {
        return [
            'authors' => AuthorFixture::class,
        ];
    }

    public function testEmpty ()
    {
        $model = new Author();

        $this->validateModel($model, false);
    }

    public function testValidate ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');
        $model = new Author($fixture);
        $this->unicalizeAuthor($model);

        $this->validateModel($model, true);
    }

    public function testInvalidEmail ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');
        $fixture['email'] = 'not an email';
        $model = new Author($fixture);

        $this->validateModel($model, false);
    }

    public function testValidUsername ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');

        $valid = [
            'denchik_1034',
            'denchik-194',
            'username_0',
            'Ziga',
        ];

        $model = new Author($fixture);

        foreach ($valid as $username) {
            $this->unicalizeAuthor($model);
            $model->username = $username;

            $this->validateModel($model, true, ['username']);
        }
    }

    public function testInvalidUsername ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');

        $usernames = [
            '@#^%&$@^@',
            'Zim_bab^we',
            'Ziga*',
        ];

        $model = new Author($fixture);

        foreach ($usernames as $username) {
            $this->unicalizeAuthor($model);
            $model->username = $username;

            $this->validateModel($model, false, ['username']);
        }
    }

    public function testUniqueFields ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');

        $model = new Author($fixture);
        $model->id = 100500;
        $model->save();

        $this->validateModel($model, false, [
            'email',
            'username',
            'password_reset_token',
        ]);
    }

    public function testLongAttribute ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');

        $attributes = [
            'username',
            'full_name',
            'password_hash',
            'password_reset_token',
        ];

        $model = new Author($fixture);

        foreach ($attributes as $attribute) {
            $model->{$attribute} = $this->getVeryLongString();

            $this->validateModel($model, false, [$attribute]);
        }
    }

    public function testEmptyPasswordResetToken ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');
        $model = new Author($fixture);

        $this->unicalizeAuthor($model);
        $model->password_reset_token = null;

        $this->validateModel($model, true);
    }

    public function testSetPassword ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');
        $model = new Author($fixture);

        $this->assertTrue($model->setPassword('password_0'));
    }

    public function testSetBadPassword ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');

        $model = new Author($fixture);
        $this->unicalizeAuthor($model);

        $this->assertFalse($model->setPassword('%$^#&E%^Y$%Yw45ts'));
    }

    public function testSetLongPassword ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');

        $model = new Author($fixture);
        $this->unicalizeAuthor($model);

        $this->assertFalse($model->setPassword($this->getVeryLongString()));
    }

    public function testValidatePassword ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');
        $model = new Author($fixture);
        $model->setPassword('password_0');

        $this->assertTrue($model->validatePassword('password_0'));
    }

    public function testValidateLongPassword ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');
        $model = new Author($fixture);

        $actual = $model->setPassword($this->getVeryLongString());
        $this->assertFalse($actual);
    }

    public function testValidateBadPassword ()
    {
        $fixture = $this->tester->grabFixture('authors', 'admin');
        $model = new Author($fixture);

        $actual = $model->setPassword('%$^#&E%^Y$%Yw45ts');
        $this->assertFalse($actual);
    }

    private function unicalizeAuthor (Author $model)
    {
        $randomString = \Yii::$app->getSecurity()->generateRandomString(20);

        $model->password_reset_token = $randomString;
        $model->username = $randomString;
        $model->email = $randomString . '@mail.ru';
    }

    private function getVeryLongString (): string
    {
        return 'v' . str_repeat('e-', 100) . 'ery long password';
    }
}