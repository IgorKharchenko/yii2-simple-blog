<?php

namespace common\tests\unit\models;

use common\components\test\UnitTestHelper;
use common\fixtures\AuthorFixture;
use common\fixtures\CommentFixture;
use common\fixtures\PostFixture;
use common\models\Author;
use common\models\Comment;
use common\models\Post;
use common\tests\UnitTester;

class CommentTest extends \Codeception\Test\Unit
{
    use UnitTestHelper;

    /**
     * @var UnitTester
     */
    protected $tester;

    public function _fixtures ()
    {
        return [
            'authors'  => AuthorFixture::class,
            'posts'    => PostFixture::class,
            'comments' => CommentFixture::class,
        ];
    }

    public function testEmpty ()
    {
        $this->validateModel(new Comment(), false);
    }

    public function testValidate ()
    {
        $fixture = $this->tester->grabFixture('comments', 0);
        $model = new Comment($fixture);

        $this->validateModel($model, true);
    }

    public function testLongContent ()
    {
        $fixture = $this->tester->grabFixture('comments', 0);
        $model = new Comment($fixture);
        $model->content = str_repeat('a', 1024 * 64 + 100500);

        $this->validateModel($model, false, ['content']);
    }

    public function testGetAuthor ()
    {
        $fixture = $this->tester->grabFixture('comments', 0);
        $model = new Comment($fixture);

        $this->assertInstanceOf(Author::class, $model->getAuthor());
    }

    public function testGetAuthorOnNull ()
    {
        $model = Comment::findOne(['id' => 1]);
        $model->author_id = null;

        $this->assertNull($model->getAuthor());
    }

    public function testGetPost ()
    {
        $fixture = $this->tester->grabFixture('comments', 0);
        $model = new Comment($fixture);

        $this->assertInstanceOf(Post::class, $model->getPost());
    }

    public function testGetPostOnNull ()
    {
        $model = Comment::findOne(['id' => 1]);
        $model->post_id = null;

        $this->assertNull($model->getPost());
    }
}