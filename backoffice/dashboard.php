<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
}

include('../config/db.php');

// Récupération des catégories pour le filtre
$stmtCat = $pdo->query("SELECT id_categorie, libelle FROM categorie ORDER BY libelle");
$categories = $stmtCat->fetchAll(PDO::FETCH_ASSOC);

// Paramètres de recherche (GET ou URL réécrite)
$search_query = isset($_GET['q']) ? trim($_GET['q']) : '';
$search_categorie_raw = isset($_GET['categorie']) ? trim($_GET['categorie']) : '';

// Convertit un libellé de catégorie en id
$search_categorie = null;
if ($search_categorie_raw !== '') {
  foreach ($categories as $cat) {
    if (strcasecmp($cat['libelle'], $search_categorie_raw) === 0) {
      $search_categorie = (int)$cat['id_categorie'];
      break;
    }
  }
}

// Redirection vers l'URL réécrite /backoffice/dashboard/q/.../categorie/...
if ($search_query !== '' || $search_categorie_raw !== '') {
  $segments = [];
  if ($search_query !== '') {
    $segments[] = 'q/' . rawurlencode($search_query);
  }
  if ($search_categorie_raw !== '') {
    $segments[] = 'categorie/' . rawurlencode($search_categorie_raw);
  }

  $prettyPath = '/backoffice/dashboard';
  if (!empty($segments)) {
    $prettyPath .= '/' . implode('/', $segments);
  }

  $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '';
  if ($currentPath !== $prettyPath) {
    header('Location: ' . $prettyPath);
    exit;
  }
}

// Récupération des articles avec catégorie et journaliste (avec filtre)
$sql = "SELECT a.*, c.libelle AS categorie, j.nom AS journaliste_nom
    FROM articles a
    LEFT JOIN categorie c ON a.id_categorie = c.id_categorie
    LEFT JOIN journaliste_article ja ON a.id_articles = ja.id_articles
    LEFT JOIN journaliste j ON ja.id_journaliste = j.id_journaliste";

$conditions = [];
$params = [];

if ($search_query !== '') {
  $conditions[] = "(a.titre ILIKE :q OR a.introduction ILIKE :q OR a.contenu ILIKE :q)";
  $params[':q'] = '%' . $search_query . '%';
}

if ($search_categorie !== null) {
  $conditions[] = "a.id_categorie = :cat";
  $params[':cat'] = $search_categorie;
}

if (!empty($conditions)) {
  $sql .= ' WHERE ' . implode(' AND ', $conditions);
}

$sql .= ' ORDER BY a.creation DESC NULLS LAST, a.id_articles DESC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Génération d'un slug à partir du titre (ex: "Mon Titre" -> "mon-titre")
function slugify($title) {
  $title = strtolower($title);
  $title = preg_replace('/[^a-z0-9]+/', '-', $title);
  $title = trim($title, '-');
  if ($title === '') {
    $title = 'article';
  }
  return $title;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>IRAN CRISIS - Gestion des articles</title>
  <meta name="description" content="Dashboard du backoffice du journal : vue d'ensemble des articles, accès rapide à l'édition et à la création de contenus.">
  <!-- <meta name="robots" content="noindex, nofollow"> -->
  <?php
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $canonical = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/backoffice/dashboard.html');
  ?>
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="stylesheet" href="/backoffice/backoffice.css">
</head>
<body>
  <div class="page">
    <div class="top-bar">
      <div class="brand">Iran Crisis Backoffice</div>
      <div class="top-actions">
        <a href="/backoffice/ajouter_article.html" class="btn-link">Nouvel article</a>
        <a href="/backoffice/logout.php" class="btn-link">Déconnexion</a>
      </div>
    </div>

    <form method="get" class="filters" style="margin:16px 0; display:flex; gap:8px; align-items:center;">
      <input type="text" name="q" placeholder="Rechercher dans les titres, introductions, contenus" value="<?= htmlspecialchars($search_query, ENT_QUOTES, 'UTF-8') ?>" style="flex:2; padding:6px 8px;">
      <select name="categorie" style="flex:1; padding:6px 8px;">
        <option value="">Toutes les catégories</option>
        <?php foreach ($categories as $cat): ?>
          <?php $selected = ($search_categorie !== null && (int)$search_categorie === (int)$cat['id_categorie']) ? 'selected' : ''; ?>
          <option value="<?= htmlspecialchars($cat['libelle'], ENT_QUOTES, 'UTF-8') ?>" <?= $selected ?>>
            <?= htmlspecialchars($cat['libelle'], ENT_QUOTES, 'UTF-8') ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="btn-link" style="padding:7px 14px;">Filtrer</button>
    </form>

    <?php if (!empty($articles)): ?>
      <?php
        $lead = $articles[0];
        $others = array_slice($articles, 1);
        $side = array_slice($others, 0, 3);
        $bottom = array_slice($others, 3, 6);
      ?>

      <div class="layout">
        <div class="main-column">
          <article class="lead-article">
            <?php if (!empty($lead['categorie'])): ?>
              <div class="sur-titre"><?= htmlspecialchars($lead['categorie'], ENT_QUOTES, 'UTF-8') ?></div>
            <?php else: ?>
              <div class="sur-titre">À la une</div>
            <?php endif; ?>

            <h1 class="lead-title">
              <a href="/backoffice/<?= htmlspecialchars(slugify($lead['titre']), ENT_QUOTES, 'UTF-8') ?>.html">
                <?= htmlspecialchars($lead['titre'], ENT_QUOTES, 'UTF-8') ?>
              </a>
            </h1>

            <div class="meta">
              <?php if (!empty($lead['journaliste_nom'])): ?>
                Par <?= htmlspecialchars($lead['journaliste_nom'], ENT_QUOTES, 'UTF-8') ?>
              <?php endif; ?>
              <?php if (!empty($lead['creation'])): ?>
                • <?= date('d/m/Y H:i', strtotime($lead['creation'])) ?>
              <?php endif; ?>
            </div>

            <?php if (!empty($lead['image'])): ?>
              <div class="lead-image-wrapper">
                <img src="<?= htmlspecialchars($lead['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($lead['alt'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                <span class="photo-credit">Crédit photo</span>
              </div>
            <?php endif; ?>

            <?php if (!empty($lead['introduction'])): ?>
              <p class="lead-intro">
                <?= htmlspecialchars($lead['introduction'], ENT_QUOTES, 'UTF-8') ?>
              </p>
            <?php endif; ?>

            <div class="lead-links">
              <a href="/backoffice/<?= htmlspecialchars(slugify($lead['titre']), ENT_QUOTES, 'UTF-8') ?>.html">Voir l'article</a>
              <a href="/backoffice/edit_article-<?= (int)$lead['id_articles'] ?>.html">Modifier</a>
            </div>
          </article>
        </div>

        <aside class="side-column">
          <div class="side-list">
            <?php foreach ($side as $a): ?>
              <article class="side-item">
                <?php if (!empty($a['categorie'])): ?>
                  <div class="sur-titre" style="background:#ffd54f;">
                    <?= htmlspecialchars($a['categorie'], ENT_QUOTES, 'UTF-8') ?>
                  </div>
                <?php endif; ?>

                <h2 class="side-title">
                  <a href="/backoffice/<?= htmlspecialchars(slugify($a['titre']), ENT_QUOTES, 'UTF-8') ?>.html">
                    <?= htmlspecialchars($a['titre'], ENT_QUOTES, 'UTF-8') ?>
                  </a>
                </h2>

                <?php if (!empty($a['introduction'])): ?>
                  <p class="side-intro">
                    <?= htmlspecialchars($a['introduction'], ENT_QUOTES, 'UTF-8') ?>
                  </p>
                <?php endif; ?>

                <div class="side-meta">
                  <?php if (!empty($a['journaliste_nom'])): ?>
                    <?= htmlspecialchars($a['journaliste_nom'], ENT_QUOTES, 'UTF-8') ?>
                  <?php endif; ?>
                  <?php if (!empty($a['creation'])): ?>
                    • <?= date('d/m/Y', strtotime($a['creation'])) ?>
                  <?php endif; ?>
                </div>

                <div class="article-actions">
                  <a href="/backoffice/<?= htmlspecialchars(slugify($a['titre']), ENT_QUOTES, 'UTF-8') ?>.html">Voir</a>
                  <a href="/backoffice/edit_article-<?= (int)$a['id_articles'] ?>.html">Modifier</a>
                </div>
              </article>
            <?php endforeach; ?>
          </div>

          <?php if (!empty($bottom)): ?>
            <div class="bottom-grid">
              <?php foreach ($bottom as $b): ?>
                <article class="bottom-item">
                  <?php if (!empty($b['categorie'])): ?>
                    <div class="sur-titre" style="background:#ff9800;">
                      <?= htmlspecialchars($b['categorie'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                  <?php endif; ?>

                  <h3 class="bottom-title">
                    <a href="/backoffice/<?= htmlspecialchars(slugify($b['titre']), ENT_QUOTES, 'UTF-8') ?>.html">
                      <?= htmlspecialchars($b['titre'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                  </h3>

                  <?php if (!empty($b['introduction'])): ?>
                    <p class="bottom-intro">
                      <?= htmlspecialchars($b['introduction'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                  <?php endif; ?>

                  <div class="article-actions">
                    <a href="/backoffice/<?= htmlspecialchars(slugify($b['titre']), ENT_QUOTES, 'UTF-8') ?>.html">Voir</a>
                    <a href="/backoffice/edit_article-<?= (int)$b['id_articles'] ?>.html">Modifier</a>
                  </div>
                </article>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </aside>
      </div>
    <?php else: ?>
      <p>Aucun article pour le moment. <a href="ajouter_article.html">Créer le premier article</a>.</p>
    <?php endif; ?>
  </div>
</body>
</html>