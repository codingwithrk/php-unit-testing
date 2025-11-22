<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Comment;

final class CommentModelTest extends TestCase {
    protected static $comment;

    public function setUp() : void {
        self::$comment = new Comment();
    }

    public function tearDown() : void {
        self::$comment = null;
    }
    
    public function testCommentId() : void {
        $id = 1;
        self::$comment->setId($id);

        $this->assertSame($id, self::$comment->getId());
    }

    public function testCommentPostId() : void {
        $postId = 5;
        self::$comment->setPostId($postId);

        $this->assertSame($postId, self::$comment->getPostId());
    }

    public function testCommentAuthorId() : void {
        $authorId = 1;
        self::$comment->setAuthorId($authorId);

        $this->assertSame($authorId, self::$comment->getAuthorId());
    }

    public function testCommentAuthor() : void {
        $author = "CodingwithRK";
        self::$comment->setAuthor($author);

        $this->assertSame($author, self::$comment->getAuthor());
    }

    public function testCommentBody() : void {
        $commentBody = "Lorem Ipsum is simply dummy text of the printing and typesetting industry.";
        self::$comment->setComment($commentBody);

        $this->assertSame($commentBody, self::$comment->getComment());
    }
}

