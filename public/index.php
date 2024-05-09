<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../controllers/UserController.php';
require_once __DIR__ . '/../controllers/FrontController.php';
require_once __DIR__ . '/../controllers/PostController.php';
require_once __DIR__ . '/../controllers/CommentController.php';

// Démarrage de la session
session_start();

// Création de l'instance de Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../views');
$twig = new \Twig\Environment($loader, [
    'cache' => __DIR__ . '/../cache',
    'debug' => true // Mettre en false en production
]);
$twig->addGlobal('session', $_SESSION);


// Instance des contrôleurs
$userController = new UserController($twig);
$frontController = new FrontController($twig);
$postController = new PostController($twig);
$commentController = new CommentController($twig);

// Parsing de l'URL pour extraire l'identifiant du post si nécessaire
$uri = $_SERVER['REQUEST_URI'];
$uriParts = explode('?', $uri);
$route = $uriParts[0];
$queryString = $uriParts[1] ?? '';

// Routage simple
switch ($route) {
    case '/':
        $lastPosts = $frontController->getLastPosts();
        echo $twig->render('home.twig', ['posts' => $lastPosts]);
        break;
    case '/blog':
        $posts = $frontController->getAllPosts();
        echo $twig->render('blog.twig', ['posts' => $posts]);
        break;

    case '/contact':
         echo $twig->render('contact.twig');
       break;
    case '/blog/view':
        parse_str($queryString, $queryParams);
        $postId = $queryParams['id'] ?? null;
        if ($postId) {
            $post = $frontController->getPostById($postId);
            echo $twig->render('post_detail.twig', ['post' => $post]);
        } else {
            http_response_code(404);
            echo $twig->render('404.twig');
        }
        break;
    case '/login':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userController->login();
            } else {
                echo $twig->render('login.twig');
            }
            break;
     case '/register':
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $userController->register();
            } else {
                echo $twig->render('register.twig');
            }
            break;
    case '/logout':
        // Détruire toutes les informations de session
        $_SESSION = array(); // Efface toutes les données de session
        session_destroy(); // Détruit la session
        header('Location: /login');
        exit;
        break;
    case '/dashboard':
        // Assurez-vous que l'utilisateur est connecté et est un admin
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
        echo $twig->render('dashboard.twig');
        break;
       
    case '/dashboard/post/add':
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
        $postController->createPost();
        break;
    case '/dashboard/post/edit':
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
        $postController->editPost();
        break;
    case '/dashboard/post/delete':
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
        $postController->deletePost();
        break;
    case '/dashboard/post':
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
        $posts = $frontController->getAllPosts();
        echo $twig->render('dashboard/posts.twig', ['posts' => $posts]);
        break;
    default:
        http_response_code(404);
        echo $twig->render('404.twig');
}
?>
