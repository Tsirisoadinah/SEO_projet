<?php
declare(strict_types=1);

include_once __DIR__ . '/../config/db.php';

/* ─── Helpers ──────────────────────────────────────────── */

function slugify(string $title): string {
    $title = strtolower($title);
    $title = preg_replace('/[^a-z0-9]+/', '-', $title);
    $title = trim($title, '-');
    return $title !== '' ? $title : 'article';
}

function formaterDate(string $datetime): string {
    static $mois = ['','janvier','février','mars','avril','mai','juin',
                    'juillet','août','septembre','octobre','novembre','décembre'];
    $ts = strtotime($datetime);
    return (int)date('j', $ts) . ' ' . $mois[(int)date('n', $ts)]
         . ' ' . date('Y', $ts) . ' · ' . date('H\hi', $ts);
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function isHttps(): bool {
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

/* ─── Résolution article ────────────────────────────────── */

$id   = isset($_GET['id'])   ? (int)$_GET['id']         : 0;
$slug = isset($_GET['slug']) ? (string)$_GET['slug']     : null;

$article = null;

if ($id > 0) {
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
    $article = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;

    // Redirect ?id= → pretty URL
    if ($article && $slug === null) {
        $prettySlug = slugify($article['titre']);
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '/frontoffice/article.php';
        $base       = rtrim(dirname($scriptName), '/\\');
        $prettyPath = ($base === '' || $base === '/') ? "/{$prettySlug}.html" : "{$base}/{$prettySlug}.html";

        if (($_SERVER['REQUEST_URI'] ?? '') !== $prettyPath) {
            $url = (isHttps() ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $prettyPath;
            header('Location: ' . $url, true, 301);
            exit;
        }
    }
} elseif ($slug !== null && $slug !== '') {
    // Slug lookup — scan all titles (considérer un champ slug DB pour la prod)
    $stmt = $pdo->query(
        "SELECT a.*, c.libelle AS categorie, j.nom AS journaliste_nom
         FROM articles a
         LEFT JOIN categorie c ON a.id_categorie = c.id_categorie
         LEFT JOIN journaliste_article ja ON a.id_articles = ja.id_articles
         LEFT JOIN journaliste j ON ja.id_journaliste = j.id_journaliste"
    );
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        if (slugify($row['titre']) === $slug) {
            $article = $row;
            break;
        }
    }
}

if (!$article) {
    http_response_code(404);
    echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>404 — Article introuvable</title></head>'
       . '<body><h1>Article introuvable</h1><p><a href="index.php">← Retour à l\'accueil</a></p></body></html>';
    exit;
}

/* ─── Catégories pour nav / footer ─────────────────────── */

$categories = [];
$stmtCat = $pdo->query("SELECT id_categorie, libelle FROM categorie ORDER BY id_categorie");
while ($row = $stmtCat->fetch(PDO::FETCH_ASSOC)) {
    $categories[(int)$row['id_categorie']] = $row;
}

/* ─── Méta page ─────────────────────────────────────────── */

$site_nom    = 'IRAN CRISIS';
$site_slogan = 'Actualités · Analyses · Terrain';
$date_maj    = date('d/m/Y à H:i');

$scheme    = isHttps() ? 'https' : 'http';
$canonical = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/');

$metaRaw = trim($article['introduction'] ?? strip_tags($article['contenu'] ?? ''));
if ($metaRaw === '') $metaRaw = 'Analyse et actualités sur la crise en Iran.';
$metaDescription = mb_strimwidth($metaRaw, 0, 157, '…', 'UTF-8');

$nav_ids = [2, 3, 6, 7];

$ticker_items = [
    'Tirs de missiles signalés dans la région de Tabriz',
    'Le Conseil de sécurité de l\'ONU convoqué en urgence',
    'Les prix du pétrole grimpent à +8 % suite aux tensions',
    'Ambassade française évacuée — ressortissants invités à quitter le pays',
    'Corridors humanitaires ouverts côté nord de l\'Irak',
];
$ticker_double = array_merge($ticker_items, $ticker_items);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($article['titre']) ?> — <?= e($site_nom) ?></title>
  <meta name="description" content="<?= e($metaDescription) ?>">
  <link rel="canonical" href="<?= e($canonical) ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Source+Serif+4:opsz,wght@8..60,300;8..60,400;8..60,600&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

  <?php if (!empty($article['image'])): ?>
  <link rel="preload" as="image" href="<?= e($article['image']) ?>">
  <?php endif; ?>

  <link rel="stylesheet" href="assets/css/accueil.css">
</head>
<body>

<!-- BARRE D'ALERTE -->
<div class="barre-alerte">
  <div class="alerte-inner container" style="overflow:hidden">
    <span class="alerte-label">⚡ EN DIRECT</span>
    <div style="overflow:hidden;flex:1">
      <div class="alerte-ticker">
        <?php foreach ($ticker_double as $item): ?>
          <span><?= e($item) ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- EN-TÊTE -->
<header class="entete">
  <div class="entete-top">
    <div class="logo">
      <div class="logo-principal">IRAN<span>CRISIS</span></div>
      <div class="logo-slogan"><?= e($site_slogan) ?></div>
    </div>
    <div class="entete-meta">
      <div class="entete-date">Lundi <?= date('j F Y') ?></div>
      <div class="entete-mise-a-jour">Mis à jour · <?= e($date_maj) ?></div>
    </div>
  </div>
  <nav class="nav" aria-label="Navigation principale">
    <a href="index.php">Accueil</a>
    <?php foreach ($nav_ids as $cat_id):
        if (!isset($categories[$cat_id])) continue; ?>
      <a href="#"><?= e($categories[$cat_id]['libelle']) ?></a>
    <?php endforeach; ?>
    <a href="#">Carte du conflit</a>
    <div class="nav-recherche">
      <label for="search-input" class="sr-only">Rechercher</label>
      <input id="search-input" type="search" placeholder="Rechercher..." autocomplete="off">
      <button class="btn-recherche" type="button" aria-label="Lancer la recherche">→</button>
    </div>
  </nav>
</header>

<!-- CONTENU ARTICLE -->
<main class="container" id="main-content">
  <article class="article-page">

    <?php if (!empty($article['categorie'])): ?>
      <div class="article-page-categorie"><?= e(strtoupper($article['categorie'])) ?></div>
    <?php endif; ?>

    <h1 class="article-page-titre"><?= e($article['titre']) ?></h1>

    <div class="article-page-meta">
      <?php if (!empty($article['journaliste_nom'])): ?>
        <span class="article-page-auteur">Par <?= e($article['journaliste_nom']) ?></span>
      <?php endif; ?>
      <?php if (!empty($article['creation'])): ?>
        <span class="article-page-separateur" aria-hidden="true">•</span>
        <time class="article-page-date" datetime="<?= e($article['creation']) ?>">
          <?= formaterDate($article['creation']) ?>
        </time>
      <?php endif; ?>
    </div>

    <?php if (!empty($article['image'])): ?>
      <figure class="article-page-image-wrapper">
        <img
          src="<?= e($article['image']) ?>"
          alt="<?= e($article['alt'] ?? '') ?>"
          class="article-page-image"
          fetchpriority="high"
          decoding="sync"
          width="860" height="460"
        >
        <?php if (!empty($article['alt'])): ?>
          <figcaption class="article-page-legende"><?= e($article['alt']) ?></figcaption>
        <?php endif; ?>
      </figure>
    <?php endif; ?>

    <?php if (!empty($article['introduction'])): ?>
      <p class="article-page-intro"><?= e($article['introduction']) ?></p>
    <?php endif; ?>

    <div class="article-page-contenu">
      <?= nl2br(e($article['contenu'] ?? '')) ?>
    </div>

  </article>
</main>

<!-- PIED DE PAGE -->
<footer>
  <div class="footer-grille">
    <div>
      <div class="footer-logo">IRAN<span>CRISIS</span></div>
      <p class="footer-desc">
        Site d'information indépendant dédié à la couverture du conflit iranien.
        Nos correspondants sur le terrain et nos analystes vous offrent une information rigoureuse, vérifiée et sans parti pris.
      </p>
    </div>
    <div>
      <div class="footer-col-titre">Rubriques</div>
      <ul class="footer-liens">
        <?php foreach (array_slice(array_values($categories), 0, 5) as $cat): ?>
          <li><a href="#"><?= e($cat['libelle']) ?></a></li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div>
      <div class="footer-col-titre">Ressources</div>
      <ul class="footer-liens">
        <li><a href="#">Chronologie</a></li>
        <li><a href="#">Carte du conflit</a></li>
        <li><a href="#">Glossaire</a></li>
        <li><a href="#">Sources officielles</a></li>
        <li><a href="#">Archives</a></li>
      </ul>
    </div>
    <div>
      <div class="footer-col-titre">À propos</div>
      <ul class="footer-liens">
        <li><a href="#">Notre mission</a></li>
        <li><a href="#">Notre équipe</a></li>
        <li><a href="#">Contact</a></li>
        <li><a href="#">Mentions légales</a></li>
        <li><a href="#">Politique de confidentialité</a></li>
      </ul>
    </div>
  </div>
  <div class="footer-bas">
    <div class="footer-copyright">© <?= date('Y') ?> IranCrisis · Tous droits réservés</div>
    <div class="footer-disclaimer">
      Les informations publiées sont issues de sources vérifiées.
      Ce site n'est affilié à aucun gouvernement ni organisation politique.
    </div>
  </div>
</footer>

<style>.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}</style>

</body>
</html>
