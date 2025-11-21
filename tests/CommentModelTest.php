<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Comment;

final class CommentModelTest extends TestCase {
    public function testCommentId() : void {
        $comment = new Comment();
        $id = 1;
        $comment->setId($id);

        $this->assertSame($id, $comment->getId());
    }

    public function testCommentPostId() : void {
        $comment = new Comment();
        $postId = 5;
        $comment->setPostId($postId);

        $this->assertSame($postId, $comment->getPostId());
    }

    public function testCommentAuthorId() : void {
        $comment = new Comment();
        $authorId = 1;
        $comment->setAuthorId($authorId);

        $this->assertSame($authorId, $comment->getAuthorId());
    }

    public function testCommentAuthor() : void {
        $comment = new Comment();
        $author = "CodingwithRK";
        $comment->setAuthor($author);

        $this->assertSame($author, $comment->getAuthor());
    }

    public function testCommentBody() : void {
        $comment = new Comment();
        $commentBody = "Lorem Ipsum is simply dummy text of the printing and typesetting industry.";
        $comment->setComment($commentBody);

        $this->assertSame($commentBody, $comment->getComment());
    }
}

