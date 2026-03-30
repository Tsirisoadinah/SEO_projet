<?php
include('../config/db.php');

class Journaliste {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // 🔥 Liste journalistes actifs
    public function getActifs() {
        $sql = "SELECT * FROM Journaliste WHERE Actif = 'TRUE'";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

}