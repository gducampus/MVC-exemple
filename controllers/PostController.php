<?php

class PostController
{
    private $postModel;
    private $twig;

    public function __construct($twig)
    {
        $this->postModel = new Post();
        $this->twig = $twig;
    }

    public function getAllPosts()
    {
        return $this->postModel->read();
    }

    public function createPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user_id']; // Assume user is logged in and ID is stored in session
            $title = $_POST['title'];
            $content = $_POST['content'];

            $this->postModel->user_id = $userId;
            $this->postModel->title = $title;
            $this->postModel->content = $content;

            if ($this->postModel->create()) {
                header('Location: /dashboard/post');
                exit;
            } else {
                echo $this->twig->render('add_post.twig', ['error' => 'Failed to create post.']);
            }
        } else {
            echo $this->twig->render('dashboard/add_post.twig');
        }
    }

    public function editPost()
    {
        $postId = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $postId) {
            $userId = $_SESSION['user_id'];
            $title = $_POST['title'];
            $content = $_POST['content'];

            $this->postModel->id = $postId;
            $this->postModel->user_id = $userId;
            $this->postModel->title = $title;
            $this->postModel->content = $content;

            if ($this->postModel->update()) {
                header('Location: /blog/view?id=' . $postId);
                exit;
            } else {
                echo $this->twig->render('edit_post.twig', ['error' => 'Failed to update post.']);
            }
        } else {
            if ($postId) {
                $post = $this->getPostById($postId);
                echo $this->twig->render('edit_post.twig', ['post' => $post]);
            } else {
                http_response_code(404);
                echo $this->twig->render('404.twig');
            }
        }
    }

    public function deletePost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $postId = $_POST['id'];

            if ($this->postModel->delete($postId)) {
                header('Location: /blog');
                exit;
            } else {
                echo "Failed to delete post.";
            }
        }
    }
}
