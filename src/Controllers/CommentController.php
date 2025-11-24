<?php

namespace App\Controllers;

use App\Models\Comment;
use App\Models\IDbAdapter;
use Monolog\Logger;

class CommentController {
    /**
     * @var \App\Models\IDbAdapter
     */
    protected $db;

    /**
     * @var Logger
     */
    protected $log;

    /**
     * @param IDbAdapter $dbAdapter
     */
    public function __construct(IDbAdapter $dbAdapter, Logger $log) {
        $this->db = $dbAdapter;
        $this->log = $log;
    }

    /**
     * Insert a comment record
     * 
     * @param Comment $comment
     * 
     * @return array
     */
    public function insert(Comment $comment) : array {
        $commentRecord = [
            'post_id'    => $comment->getPostId(),
            'author_id'  => $comment->getAuthorId(),
            'comment'    => $comment->getComment()
        ];

        $id = $this->db->insert('comments', $commentRecord);
        $user_controller = new UserController($this->db, $this->log);
        $user = $user_controller->fetchById($comment->getAuthorId());

        return array(
            'id' => $id, 
            'post_id' => $comment->getPostId(), 
            'author_id' => $comment->getAuthorId(),  
            'author_name' => $user['username'],
            'comment' => $comment->getComment(),
        );
    }

    /**
     * Update a comment record
     * 
     * @param Comment $comment
     * 
     * @return array
     */
    public function update(Comment $comment) : array {
        $commentRecord = [
            'post_id'    => $comment->getPostId(),
            'author_id'  => $comment->getAuthorId(),
            'comment'    => $comment->getComment()
        ];

        $id = $this->db->update('comments', $commentRecord, ['id' => $comment->getId()]);
        $user_controller = new UserController($this->db, $this->log);
        $user = $user_controller->fetchById($comment->getAuthorId());

        return array(
            'id' => $id, 
            'post_id' => $comment->getPostId(), 
            'author_id' => $comment->getAuthorId(), 
            'author_name' => $user['username'],
            'comment' => $comment->getComment(),
        );
    }

    public function fetchAll() : array {
        $comments = array();

        $sql = 'SELECT c.id, c.post_id, c.author_id, u.username as author_name, c.comment FROM comment c INNER JOIN users u ON c.author_id = u.id';
        $comments = $this->db->fetchAll($sql);

        return $comments;
    }

    public function fetchPostById(int $post_id) : array {
        $comments = array();

        $sql = 'SELECT c.id, c.post_id, c.author_id, u.username as author_name, c.comment FROM comment c INNER JOIN users u ON c.author_id = u.id WHERE c.post_id =?';
        $comments = $this->db->fetchAll($sql, [$post_id]);

        return $comments;
    }


    public function fetchById(int $id) : array {
        $comment = array();
        
        $sql = 'SELECT c.id, c.post_id, c.author_id, u.username as author_name, c.comment FROM comment c INNER JOIN users u ON c.author_id = u.id WHERE c.id =?';
        $comment = $this->db->fetchOne($sql, [$id]);

        return $comment;
    }
}