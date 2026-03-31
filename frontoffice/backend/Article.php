<?php
include('../config/db.php');

class Article {

    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // 🔥 Article principal (le plus récent)
    public function getLatestArticle() {
        $sql = "SELECT a.*, c.libelle AS categorie, j.Nom AS journaliste
                FROM articles a
                JOIN Categorie c ON a.Id_Categorie = c.Id_Categorie
                JOIN journaliste_article ja ON a.Id_articles = ja.Id_articles
                JOIN Journaliste j ON ja.Id_Journaliste = j.Id_Journaliste
                ORDER BY a.creation DESC
                LIMIT 1";

        return $this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    // 🔥 Articles secondaires (2 suivants)
    public function getSecondaryArticles() {
        $sql = "SELECT a.*, c.libelle AS categorie, j.Nom AS journaliste
                FROM articles a
                JOIN Categorie c ON a.Id_Categorie = c.Id_Categorie
                JOIN journaliste_article ja ON a.Id_articles = ja.Id_articles
                JOIN Journaliste j ON ja.Id_Journaliste = j.Id_Journaliste
                ORDER BY a.creation DESC
                LIMIT 2 OFFSET 1";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔥 Liste "À lire également"
    public function getListArticles() {
        $sql = "SELECT a.*, c.libelle AS categorie
                FROM articles a
                JOIN Categorie c ON a.Id_Categorie = c.Id_Categorie
                ORDER BY a.creation DESC
                LIMIT 5 OFFSET 3";

        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // 🔍 Recherche d'articles par mot-clé et/ou catégorie
    public function searchArticles(?string $motCle = null, ?int $idCategorie = null): array
    {
        $sql = "SELECT a.*, c.libelle AS categorie
                FROM articles a
                JOIN Categorie c ON a.Id_Categorie = c.Id_Categorie
                WHERE 1=1";

        $params = [];

        if ($motCle !== null && trim($motCle) !== '') {
            $sql .= " AND (a.titre ILIKE :motcle OR a.contenu ILIKE :motcle)";
            $params['motcle'] = '%' . trim($motCle) . '%';
        }

        if ($idCategorie !== null) {
            $sql .= " AND a.Id_Categorie = :idCategorie";
            $params['idCategorie'] = $idCategorie;
        }

        $sql .= " ORDER BY a.creation DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}