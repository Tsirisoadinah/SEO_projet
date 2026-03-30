<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
}

include('../config/db.php');

$stmt = $pdo->query("SELECT * FROM articles");
$articles = $stmt->fetchAll();
?>

<h1>Dashboard</h1>

<a href="add_article.php">Ajouter</a>

<?php foreach($articles as $a): ?>
  <div>
    <?= $a['titre'] ?>
    <a href="edit_article.php?id=<?= $a['id_articles'] ?>">Modifier</a>
  </div>
<?php endforeach; ?>