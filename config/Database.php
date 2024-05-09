<?php

class Database
{
    private $host = "sql.freedb.tech";
    private $db_name = "freedb_monblog";
    private $username = "freedb_myblogUser";
    private $password = "6pMJ9Wd!zg*?Mee";
    private $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Affiche les erreurs PDO
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Erreur de connexion : " . $exception->getMessage();
        }
        return $this->conn;
    }
}
