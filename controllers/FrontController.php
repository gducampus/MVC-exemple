<?php

require_once __DIR__ . '/../models/Post.php';

class FrontController
{
    private $postModel;

    public function __construct()
    {
        $this->postModel = new Post();
    }

    public function getAllPosts()
    {
        return $this->postModel->read();
    }

    public function getLastPosts()
    {
        return $this->postModel->readLast();
    }

    public function getPostById($postId)
    {
        $this->postModel->id = $postId;
        $this->postModel->readOne();
        return $this->postModel;
    }


}
