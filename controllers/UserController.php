<?php

require_once __DIR__ . '/../models/User.php';

class UserController
{
    private $userModel;
    private $twig;

    public function __construct($twig)
    {
        $this->userModel = new User();
        $this->twig = $twig;
    }

    public function register()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $isAdmin = isset($_POST['is_admin']) ? 1 : 0;

        if ($this->userModel->create($username, $password, $isAdmin)) {
            header('Location: /login');
            exit;
        } else {
            echo $this->twig->render('register.twig', ['error' => 'Inscription échouée. Veuillez réessayer.']);
        }
    }

    public function login()
    {

        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;  // Utilisation de null comme valeur par défaut pour vérifier explicitement la présence

        if (empty($username) || empty($password)) {
            echo $this->twig->render('login.twig', ['error' => 'Veuillez remplir tous les champs.']);
            return;
        }

        // Supposons que cela récupère tous les utilisateurs
        $users = $this->userModel->read(); 
        foreach ($users as $user) {
            if ($user['username'] === $username && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['is_admin'] = $user['is_admin'];
                echo $this->twig->render('dashboard.twig', [
                    'session' => $_SESSION
                ]);
                exit;
            }
        }

        echo $this->twig->render('login.twig', ['error' => 'Nom d’utilisateur ou mot de passe incorrect.']);
    }


    public function updateUser($id, $username, $password, $isAdmin)
    {
        $this->userModel->id = $id;
        $this->userModel->username = $username;
        $this->userModel->password = $password;
        $this->userModel->is_admin = $isAdmin;
        if ($this->userModel->update()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteUser($id)
    {
        $this->userModel->id = $id;
        if ($this->userModel->delete()) {
            return true;
        } else {
            return false;
        }
    }
}
