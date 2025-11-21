<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Depends;
use \App\Models\MysqlAdapter;
use App\Controllers\CommentController;
use App\Models\Comment;
use Monolog\Logger;

final class CommentControllerTest extends TestCase {
    public function testCommentInsert() : array {
        $mysql_adapter = new MysqlAdapter('mysql:host=localhost;dbname=phpunit', 'root', '');
        $logger = new Logger('logger');

        $comment_controller = new CommentController($mysql_adapter, $logger);

        $comment = new Comment();
        $comment->setPostId(1);
        $comment->setAuthorId(1);
        $comment->setComment("This is testing Comment");

        $results = $comment_controller->insert($comment);

        $this->assertIsArray($results);
        $this->assertArrayHasKey('id', $results);

        $this->arrayHasKey('post_id', $results);
        $this->assertEquals(1, $results['post_id']);

        $this->arrayHasKey('author_id', $results);
        $this->assertEquals(1, $results['author_id']);

        $this->arrayHasKey('comment', $results);
        $this->assertEquals("This is testing Comment", $results['comment']);

        return array();
    }
}