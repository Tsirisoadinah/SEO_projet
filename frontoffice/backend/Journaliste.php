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
    public function getJournalisteNom($id_article, $journaliste_article, $journalistes) {
        foreach ($journaliste_article as $ja) {
            if ($ja['id_articles'] == $id_article) {
                foreach ($journalistes as $j) {
                    if ($j['id_journaliste'] == $ja['id_journaliste']) {
                        return $j['nom'];
                    }
                }
            }
        }
        return "Inconnu";
    }
}