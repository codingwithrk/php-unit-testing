<?php

namespace App\Models;

class Comment {
  /**
   * @var int
   */
  protected $id;

  /**
   * @var int
   */
  protected $post_id;

   /**
   * @var int
   */
  protected $author_id;

   /**
   * @var string
   */
  protected $author;

   /**
   * @var string
   */
  protected $comment;

  public function __construct() {

  }

  /**
   * Get comment id
   * 
   * @return int
   */
  public function getId() : int {
    return $this->id;
  }

  /**
   * Sets comment id
   * 
   * @param int $id
   */
  public function setId(int $id) : void {
    $this->id = $id;
  }

  /**
   * Get post id
   * 
   * @return int
   */
  public function getPostId() : int {
    return $this->post_id;
  }

  /**
   * Sets post id
   * 
   * @param int $post_id
   */
  public function setPostId(int $post_id) : void {
    $this->post_id = $post_id;
  }

  /**
   * Get comment author id
   * 
   * @return int
   */
  public function getAuthorId() : int {
    return $this->author_id;
  }

  /**
   * Sets comment author id
   * 
   * @param int $author_id
   */
  public function setAuthorId(int $author_id) : void {
    $this->author_id = $author_id;
  }

  /**
   * Get comment author
   * 
   * @return string
   */
  public function getAuthor() : string {
    return $this->author;
  }

  /**
   * Sets comment author id
   * 
   * @param string $author
   */
  public function setAuthor(string $author) : void {
    $this->author = $author;
  }

  /**
   * Get comment body
   * 
   * @return string
   */
  public function getComment() : string {
    return $this->comment;
  }

  /**
   * Sets comment body
   * 
   * @param string $comment
   */
  public function setComment(string $comment) : void {
    $this->comment = $comment;
  }
}