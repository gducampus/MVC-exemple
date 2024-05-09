<?php

require_once '../config/Database.php';

class User
{
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $password;
    public $is_admin;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Créer un utilisateur
    public function create()
    {
        $query = "INSERT INTO " . $this->table . " (username, password, is_admin) VALUES (:username, :password, :is_admin)";
        $stmt = $this->conn->prepare($query);

        // Nettoyer et lier les valeurs
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT);
        $this->is_admin = htmlspecialchars(strip_tags($this->is_admin));

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':is_admin', $this->is_admin);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Lire les utilisateurs
    public function read()
    {
        $query = "SELECT id, username, password, is_admin FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Lire un seul utilisateur
    public function readOne()
    {
        $query = "SELECT id, username, is_admin FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Mettre les propriétés
        $this->username = $row['username'];
        $this->is_admin = $row['is_admin'];
    }

    // Mettre à jour un utilisateur
    public function update()
    {
        $query = "UPDATE " . $this->table . " SET username = :username, password = :password, is_admin = :is_admin WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->password = password_hash($this->password, PASSWORD_DEFAULT); // Assurez-vous de hasher le mot de passe aussi lors de la mise à jour
        $this->is_admin = htmlspecialchars(strip_tags($this->is_admin));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':is_admin', $this->is_admin);
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Supprimer un utilisateur
    public function delete()
    {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
