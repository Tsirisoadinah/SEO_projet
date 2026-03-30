<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

include('../config/db.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slug = isset($_GET['slug']) ? $_GET['slug'] : null;

// Même logique de slug que dans le dashboard
function slugify($title) {
    $title = strtolower($title);
    $title = preg_replace('/[^a-z0-9]+/', '-', $title);
    $title = trim($title, '-');
    if ($title === '') {
        $title = 'article';
    }
    return $title;
}

if ($id > 0) {
  // Recherche par id classique
  $stmt = $pdo->prepare(
    "SELECT a.*, c.libelle AS categorie, j.nom AS journaliste_nom
     FROM articles a
     LEFT JOIN categorie c ON a.id_categorie = c.id_categorie
     LEFT JOIN journaliste_article ja ON a.id_articles = ja.id_articles
     LEFT JOIN journaliste j ON ja.id_journaliste = j.id_journaliste
     WHERE a.id_articles = ?
     LIMIT 1"
  );
  $stmt->execute([$id]);
  $article = $stmt->fetch(PDO::FETCH_ASSOC);
} elseif ($slug) {
  // Recherche par slug en PHP pour rester 100% cohérent avec slugify()
  $stmt = $pdo->query(
    "SELECT a.*, c.libelle AS categorie, j.nom AS journaliste_nom
     FROM articles a
     LEFT JOIN categorie c ON a.id_categorie = c.id_categorie
     LEFT JOIN journaliste_article ja ON a.id_articles = ja.id_articles
     LEFT JOIN journaliste j ON ja.id_journaliste = j.id_journaliste"
  );

  $article = null;
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if (slugify($row['titre']) === $slug) {
      $article = $row;
      break;
    }
  }
} else {
  $article = false;
}

if (!$article) {
    die('Article introuvable');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <?php
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $canonical = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/backoffice/article.php');
    $cssPath = __DIR__ . '/backoffice.css';
    $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();
    $metaSource = $article['introduction'] ?? strip_tags($article['contenu'] ?? '');
    $metaSource = trim($metaSource);
    if ($metaSource === '') {
        $metaSource = 'Article du backoffice du journal numérique.';
    }
    $metaDescription = mb_substr($metaSource, 0, 157, 'UTF-8');
    if (mb_strlen($metaSource, 'UTF-8') > 157) {
        $metaDescription .= '...';
    }
  ?>
  <title><?= htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8') ?> - Backoffice</title>
  <meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
  <!-- <meta name="robots" content="noindex, nofollow"> -->
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="stylesheet" href="backoffice.css?v=<?= (int)$cssVersion ?>">
</head>
<body>
  <div class="page page--narrow">
    <div class="top-bar">
      <a href="dashboard.html">← Retour au dashboard</a>
      <a href="logout.php">Déconnexion</a>
    </div>

    <?php if (!empty($article['categorie'])): ?>
      <div class="sur-titre"><?= htmlspecialchars($article['categorie'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <h1 class="article-title"><?= htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8') ?></h1>

    <div class="meta">
      <?php if (!empty($article['journaliste_nom'])): ?>
        Par <?= htmlspecialchars($article['journaliste_nom'], ENT_QUOTES, 'UTF-8') ?>
      <?php endif; ?>
      <?php if (!empty($article['creation'])): ?>
        • <?= date('d/m/Y H:i', strtotime($article['creation'])) ?>
      <?php endif; ?>
    </div>

      <?php if (!empty($article['image'])): ?>
        <div class="article-image">
          <img class="article-image__img" src="<?= htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($article['alt'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
      <?php endif; ?>

    <?php if (!empty($article['introduction'])): ?>
      <p class="article-intro">
        <?= htmlspecialchars($article['introduction'], ENT_QUOTES, 'UTF-8') ?>
      </p>
    <?php endif; ?>

    <div class="article-content">
      <?= nl2br(htmlspecialchars($article['contenu'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
    </div>
  </div>
</body>
</html>
