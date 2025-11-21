<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Models\Post;

final class PostModelTest extends TestCase {
    public function testPostTitle() : void {
        $post = new Post();
        $postTitle = 'This is a blog post';
        $post->setPostTitle($postTitle);

        $this->assertEquals($postTitle, $post->getPostTitle());
        $this->assertIsString($post->getPostTitle());
    }

    public function testPostAuthorId() : void {
        $post = new Post();
        $authorId = 1;
        $post->setAuthorId($authorId);

        $this->assertEquals($authorId, $post->getAuthorId());
        $this->assertIsInt($post->getAuthorId());
    }

    public function testPostAuthor() : void {
        $post = new Post();
        $author = "CodingwithRK";
        $post->setAuthor($author);

        $this->assertEquals($author, $post->getAuthor());
        $this->assertIsString($post->getAuthor());
    }

    public function testPostBody() : void {
        $post = new Post();
        $postBody = "Lorem Ipsum is simply dummy text of the printing and typesetting industry.";
        $post->setPost($postBody);

        $this->assertEquals($postBody, $post->getPost());
        $this->assertIsString($post->getPost());
    }
}