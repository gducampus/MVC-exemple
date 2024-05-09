<?php

require_once __DIR__ . '/../models/Comment.php';

class CommentController
{
    private $commentModel;

    public function __construct()
    {
        $this->commentModel = new Comment();
    }

    public function createComment($postId, $userId, $content)
    {
        $this->commentModel->post_id = $postId;
        $this->commentModel->user_id = $userId;
        $this->commentModel->content = $content;
        if ($this->commentModel->create()) {
            return true;
        } else {
            return false;
        }
    }

    public function getCommentsByPostId($postId)
    {
        $this->commentModel->post_id = $postId;
        return $this->commentModel->readByPostId();
    }

    public function updateComment($commentId, $content)
    {
        $this->commentModel->id = $commentId;
        $this->commentModel->content = $content;
        if ($this->commentModel->update()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteComment($commentId)
    {
        $this->commentModel->id = $commentId;
        if ($this->commentModel->delete()) {
            return true;
        } else {
            return false;
        }
    }
}
