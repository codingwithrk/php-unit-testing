<?php

declare(strict_types=1);

use \App\Models\MysqlAdapter;
use App\Controllers\CommentController;
use App\Models\Comment;
use Monolog\Logger;
use Mockery\Adapter\Phpunit\MockeryTestCase;

final class CommentControllerTest extends MockeryTestCase
{
    protected static $comment_controller;
    protected $comment;
    protected $insert_data;
    protected $fetch_all;
    protected $fetch_by_id;
    protected $mockMySqlAdapter;

    public static function setUpBeforeClass(): void
    {
        $mysql_adapter = new MysqlAdapter('mysql:host=localhost;dbname=phpunit', 'root', '');
        $logger = new Logger('logger');

        self::$comment_controller = new CommentController($mysql_adapter, $logger);
    }

    protected function setUp(): void
    {
        $this->comment = new Comment();

        $this->insert_data = [
            'post_id' => 1,
            'author_id' => 1,
            'comment' => "This is testing Comment"
        ];

        $this->fetch_all = [
            ['id' => 1, 'post_id' => 1, 'author_id' => 1, 'author_name' => "CodingwithRK", 'comment' => "This is testing Comment"],
            ['id' => 2, 'post_id' => 1, 'author_id' => 1, 'author_name' => "CodingwithRK", 'comment' => "This is another testing Comment"]
        ];

        $this->fetch_by_id = ['id' => 1, 'post_id' => 1, 'author_id' => 1, 'author_name' => "CodingwithRK", 'comment' => "This is testing Comment"];

        $this->mockMySqlAdapter = $this->getMockBuilder(MysqlAdapter::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testCommentFetchAll(): void
    {
        $mysql_adapter = $this->createStub(MysqlAdapter::class);
        $mysql_adapter->method('fetchAll')->willReturn($this->fetch_all);

        $logger = $this->createStub(Logger::class);
        $logger->method('info');

        $comment_controller = new CommentController($mysql_adapter, $logger);
        $results = $comment_controller->fetchAll();

        $this->assertIsArray($results);

        $comment = $results[0];
        $this->assertIsArray($comment);

        $this->assertArrayHasKey('id', $comment);
        $this->assertEquals($this->fetch_all[0]['id'], $comment['id']);

        $this->assertArrayHasKey('post_id', $comment);
        $this->assertEquals($this->fetch_all[0]['post_id'], $comment['post_id']);

        $this->assertArrayHasKey('author_id', $comment);
        $this->assertEquals($this->fetch_all[0]['author_id'], $comment['author_id']);

        $this->assertArrayHasKey('author_name', $comment);
        $this->assertEquals($this->fetch_all[0]['author_name'], $comment['author_name']);

        $this->assertArrayHasKey('comment', $comment);
        $this->assertEquals($this->fetch_all[0]['comment'], $comment['comment']);
    }

    public function testCommentFetchByPostId() : void {
        $mysql_adapter = $this->createConfiguredMock(
            MysqlAdapter::class, 
            ['fetchAll' => $this->fetch_all]
        );

        $logger = $this->createStub(Logger::class);
        $logger->method('info');

        $comment_controller = new CommentController($mysql_adapter, $logger);
        $results = $comment_controller->fetchPostById(1);

        $this->assertIsArray($results);

        $comment = $results[0];
        $this->assertIsArray($comment);

        $this->assertArrayHasKey('id', $comment);
        $this->assertEquals($this->fetch_all[0]['id'], $comment['id']);

        $this->assertArrayHasKey('post_id', $comment);
        $this->assertEquals($this->fetch_all[0]['post_id'], $comment['post_id']);

        $this->assertArrayHasKey('author_id', $comment);
        $this->assertEquals($this->fetch_all[0]['author_id'], $comment['author_id']);

        $this->assertArrayHasKey('author_name', $comment);
        $this->assertEquals($this->fetch_all[0]['author_name'], $comment['author_name']);

        $this->assertArrayHasKey('comment', $comment);
        $this->assertEquals($this->fetch_all[0]['comment'], $comment['comment']);

        $this->assertJson(json_encode($results));
    }

    public function testCommentFetchById() : void {
        $this->mockMySqlAdapter->expects($this->once())
            ->method('fetchOne')
            ->with($this->equalTo(
                'SELECT c.id, c.post_id, c.author_id, u.username as author_name, c.comment FROM comment c INNER JOIN users u ON c.author_id = u.id WHERE c.id =?', 
                [$this->fetch_by_id['id']]
            ))
            ->willReturn($this->fetch_by_id);

        $logger = $this->createStub(Logger::class);
        $logger->method('info');

        $comment_controller = new CommentController($this->mockMySqlAdapter, $logger);
        $results = $comment_controller->fetchById($this->fetch_by_id['id']);

        $this->assertIsArray($results);

        $this->assertArrayHasKey('id', $results);
        $this->assertEquals($this->fetch_by_id['id'], $results['id']);

        $this->assertArrayHasKey('post_id', $results);
        $this->assertEquals($this->fetch_by_id['post_id'], $results['post_id']);

        $this->assertArrayHasKey('author_id', $results);
        $this->assertEquals($this->fetch_by_id['author_id'], $results['author_id']);

        $this->assertArrayHasKey('author_name', $results);
        $this->assertEquals($this->fetch_by_id['author_name'], $results['author_name']);

        $this->assertArrayHasKey('comment', $results);
        $this->assertEquals($this->fetch_by_id['comment'], $results['comment']);

        $this->assertJson(json_encode($results));
    }

    public function testCommentInsert() : array {
        $this->comment->setPostId($this->insert_data['post_id']);   
        $this->comment->setAuthorId($this->insert_data['author_id']);
        $this->comment->setComment($this->insert_data['comment']);

        $mysql_adapter = Mockery::mock(MysqlAdapter::class);
        $mysql_adapter->shouldReceive('fetchOne')
            ->once()
            ->with('SELECT * FROM users WHERE id =?', [1])
            ->andReturn([
                'id' => 1,
                'first_name' => 'Pappala',
                'last_name' => 'Raj Kumar',
                'username' => 'codingwithrk',
                'email' => 'connect@codingwithrk.com'
            ]);
        $mysql_adapter->shouldReceive('insert')
            ->once()
            ->with('comments', $this->insert_data)
            ->andReturn(1);

        $logger = $this->createStub(Logger::class);
        $logger->method('info');

        $comment_controller = new CommentController($mysql_adapter, $logger);
        $results = $comment_controller->insert($this->comment);

        $this->assertIsArray($results);
        $this->assertArrayHasKey('id', $results);

        $this->arrayHasKey('post_id', $results);
        $this->assertEquals(1, $results['post_id']);

        $this->arrayHasKey('author_id', $results);
        $this->assertEquals(1, $results['author_id']);

        $this->arrayHasKey('comment', $results);
        $this->assertEquals("This is testing Comment", $results['comment']);

        return $results;
    }

    public function testCommentUpdate() : void {
        $this->comment->setId($this->fetch_by_id['id']);
        $this->comment->setPostId($this->fetch_by_id['post_id']);
        $this->comment->setAuthorId($this->fetch_by_id['author_id']);
        $this->comment->setComment("Updated Comment");

        $mysql_adapter = Mockery::mock(MysqlAdapter::class);
        $mysql_adapter->shouldReceive('fetchOne')
            ->once()
            ->with('SELECT * FROM users WHERE id =?', [1])
            ->andReturn([
                'id' => 1,
                'first_name' => 'Pappala',
                'last_name' => 'Raj Kumar',
                'username' => 'codingwithrk',
                'email' => 'connect@codingwithrk.com'
            ]);
        $mysql_adapter->shouldReceive('update')
            ->once()
            ->with('comments', [
                'post_id' => $this->fetch_by_id['post_id'],
                'author_id' => $this->fetch_by_id['author_id'],
                'comment' => "Updated Comment",
            ], ['id' => $this->fetch_by_id['id']])
            ->andReturn(1);

        $logger = $this->createStub(Logger::class);
        $logger->method('info');

        $comment_controller = new CommentController($mysql_adapter, $logger);
        $results = $comment_controller->update($this->comment);

        $this->assertIsArray($results);
        $this->assertArrayHasKey('id', $results);

        $this->arrayHasKey('post_id', $results);
        $this->assertEquals($this->fetch_by_id['post_id'], $results['post_id']);

        $this->arrayHasKey('author_id', $results);
        $this->assertEquals($this->fetch_by_id['author_id'], $results['author_id']);

        $this->arrayHasKey('comment', $results);
        $this->assertEquals("Updated Comment", $results['comment']); 
    }
}
