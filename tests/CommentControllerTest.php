<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Depends;
use \App\Models\MysqlAdapter;
use App\Controllers\CommentController;
use App\Models\Comment;
use Monolog\Logger;

final class CommentControllerTest extends TestCase {
    protected static $comment_controller;
    protected $comment;

    public static function setUpBeforeClass() : void {
        $mysql_adapter = new MysqlAdapter('mysql:host=localhost;dbname=phpunit', 'root', '');
        $logger = new Logger('logger');

        self::$comment_controller = new CommentController($mysql_adapter, $logger);
    }

    protected function setUp() : void {
        $this->comment = new Comment();
    }

    public function testCommentInsert() : array {
        $this->comment->setPostId(1);   
        $this->comment->setAuthorId(1);
        $this->comment->setComment("This is testing Comment");

        $results = self::$comment_controller->insert($this->comment);

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

    #[Depends('testCommentInsert')]
    public function testCommentUpdate(array $comment) : void {
        $this->comment->setId($comment['id']);
        $this->comment->setPostId($comment['post_id']);
        $this->comment->setAuthorId($comment['author_id']);
        $this->comment->setComment("Updated Comment");

        $results = self::$comment_controller->update($this->comment);

        $this->assertIsArray($results);
        $this->assertArrayHasKey('id', $results);

        $this->arrayHasKey('post_id', $results);
        $this->assertEquals($comment['post_id'], $results['post_id']);

        $this->arrayHasKey('author_id', $results);
        $this->assertEquals($comment['author_id'], $results['author_id']);

        $this->arrayHasKey('comment', $results);
        $this->assertEquals("Updated Comment", $results['comment']); 
    }
}