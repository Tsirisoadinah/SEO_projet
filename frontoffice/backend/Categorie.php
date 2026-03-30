<?php
include('../config/db.php');

class Categorie {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // 🔥 Menu navigation
    public function getNavCategories() {
        $sql = "SELECT * FROM Categorie ORDER BY Id_Categorie";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategorieLibelle($id_categorie) {
        $sql = "SELECT libelle FROM Categorie WHERE Id_Categorie = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id_categorie]);
        return $stmt->fetchColumn();
    }

}