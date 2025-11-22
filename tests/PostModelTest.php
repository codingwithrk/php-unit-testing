<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Post;

final class PostModelTest extends TestCase {
    protected static $post;

    public function setUp() : void {
        self::$post = new Post();
    }

    public function tearDown() : void {
        self::$post = null;
    }
    
    public function testPostTitle() : void {
        $postTitle = 'This is a blog post';
        self::$post->setPostTitle($postTitle);

        $this->assertEquals($postTitle, self::$post->getPostTitle());
        $this->assertIsString(self::$post->getPostTitle());
    }

    public function testPostAuthorId() : void {
        $authorId = 1;
        self::$post->setAuthorId($authorId);

        $this->assertEquals($authorId, self::$post->getAuthorId());
        $this->assertIsInt(self::$post->getAuthorId());
    }

    public function testPostAuthor() : void {
        $author = "CodingwithRK";
        self::$post->setAuthor($author);

        $this->assertEquals($author, self::$post->getAuthor());
        $this->assertIsString(self::$post->getAuthor());
    }

    public function testPostBody() : void {
        $postBody = "Lorem Ipsum is simply dummy text of the printing and typesetting industry.";
        self::$post->setPost($postBody);

        $this->assertEquals($postBody, self::$post->getPost());
        $this->assertIsString(self::$post->getPost());
    }
}