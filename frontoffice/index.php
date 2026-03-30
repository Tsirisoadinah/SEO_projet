<?php
include('../config/db.php');

$stmt = $pdo->query("SELECT * FROM articles ORDER BY creation DESC");
$articles = $stmt->fetchAll();
?>

<h1>Actualités Iran</h1>

<?php foreach($articles as $a): ?>
  <div>
    <h2>
      <a href="article.php?id=<?= $a['id_articles'] ?>">
        <?= $a['titre'] ?>
      </a>
    </h2>
    <img src="<?= $a['image'] ?>" width="200">
    
  </div>
<?php endforeach; ?>