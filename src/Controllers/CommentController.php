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

        return array(
            'id' => $id, 
            'post_id' => $comment->getPostId(), 
            'author_id' => $comment->getAuthorId(),  
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

        return array(
            'id' => $id, 
            'post_id' => $comment->getPostId(), 
            'author_id' => $comment->getAuthorId(), 
            'comment' => $comment->getComment(),
        );
    }
}