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
}