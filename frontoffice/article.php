<?php
include('../config/db.php');

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM articles WHERE id_articles = ?");
$stmt->execute([$id]);
$article = $stmt->fetch();
?>

<h1><?= $article['titre'] ?></h1>
<img src="<?= $article['image'] ?>" alt="<?= $article['alt'] ?>" width="300">

<p><?= $article['contenu'] ?></p>