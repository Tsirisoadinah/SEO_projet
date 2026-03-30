<?php
include('../config/db.php');

$id = $_GET['id'];

if ($_POST) {
    $stmt = $pdo->prepare("UPDATE articles SET titre=?, contenu=? WHERE id_articles=?");
    $stmt->execute([$_POST['titre'], $_POST['contenu'], $id]);
}

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id_articles=?");
$stmt->execute([$id]);
$a = $stmt->fetch();
?>

<form method="POST">
  <input name="titre" value="<?= $a['titre'] ?>">
  <textarea name="contenu"><?= $a['contenu'] ?></textarea>
  <button>Modifier</button>
</form>