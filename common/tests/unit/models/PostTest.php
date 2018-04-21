<?php

namespace common\tests\unit\models;

use common\components\test\UnitTestHelper;
use common\fixtures\AuthorFixture;
use common\fixtures\PostFixture;
use common\models\Author;
use common\models\Post;
use common\tests\UnitTester;

/**
 * Class PostValidationTest
 * Спека для валидации модели Post.
 *
 * @package common\tests\unit\models
 */
class PostTest extends \Codeception\Test\Unit
{
    use UnitTestHelper;

    const TYPE_TINYTEXT = 255;

    const TYPE_TEXT = 1024 * 64;

    const TYPE_MEDIUMTEXT = 1024 * 1024 * 16;

    /**
     * content я не тестирую на выход за допустимые пределы,
     * поскольку язык C накладывает ограничения работы со строками в пыхе на два гига.
     * Подробнее см. устройство работы zval в PHP и union в C.
     */
    const TYPE_LONGTEXT = 1024 * 1024 * 1024 * 4;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function _fixtures ()
    {
        return [
            'authors' => AuthorFixture::class,
            'posts'   => PostFixture::class,
        ];
    }

    public function testEmpty ()
    {
        $model = new Post();

        $this->validateModel($model, false);
    }

    public function testValidate ()
    {
        $fixture = $this->tester->grabFixture('posts', 'post1');

        $model = new Post($fixture);

        $this->validateModel($model, true);
    }

    public function testGetAuthor ()
    {
        $model = Post::findOne(['id' => 1]);

        $this->assertInstanceOf(Author::class, $model->getAuthor());
    }

    public function testGetAuthorOnNullId ()
    {
        $model = Post::findOne(['id' => 1]);
        $model->author_id = null;

        $this->assertNull($model->getAuthor());
    }

    public function testLongTitle ()
    {
        $model = Post::findOne(['id' => 1]);
        $model->title = $this->getVeryLongString(self::TYPE_TEXT);

        $this->validateModel($model, false, ['title']);
    }

    public function testLongShortDescription()
    {
        $model = Post::findOne(['id' => 1]);
        $model->short_description = $this->getVeryLongString(self::TYPE_TINYTEXT);

        $this->validateModel($model, false, ['short_description']);
    }

    public function testLongDescription()
    {
        $model = Post::findOne(['id' => 1]);
        $model->description = $this->getVeryLongString(self::TYPE_TEXT);

        $this->validateModel($model, false, ['description']);
    }

    /**
     * Возвращает строку размером больше, чем её допустимый размер.
     *
     * @param int $length - максимально допустимая длина строки.
     *
     * @return string
     */
    private function getVeryLongString (int $length): string
    {
        return str_repeat('a', $length + 100500);
    }
}