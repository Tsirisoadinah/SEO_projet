<?php
include('../config/db.php');

class JournalisteArticle {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // 🔥 Récupérer les relations journaliste-article
    public function getRelations() {
        $sql = "SELECT * FROM journaliste_article";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

}