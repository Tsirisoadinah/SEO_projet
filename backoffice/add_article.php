<?php
include('../config/db.php');

if ($_POST) {
    $stmt = $pdo->prepare("INSERT INTO articles (titre, contenu, creation) VALUES (?, ?, NOW())");
    $stmt->execute([$_POST['titre'], $_POST['contenu']]);
}
?>

<form method="POST">
  <input name="titre" placeholder="Titre">
  <textarea name="contenu"></textarea>
  <button>Ajouter</button>
</form>