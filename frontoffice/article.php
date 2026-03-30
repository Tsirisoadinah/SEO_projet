<?php
include('../config/db.php');

// Génération de slugs comme dans le backoffice
function slugify($title) {
	$title = strtolower($title);
	$title = preg_replace('/[^a-z0-9]+/', '-', $title);
	$title = trim($title, '-');
	if ($title === '') {
		$title = 'article';
	}
	return $title;
}

// Formatage de la date comme sur la page d'accueil
function formaterDate($datetime) {
	setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'French');
	$timestamp = strtotime($datetime);
	$mois = ['', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
	return (int)date('j', $timestamp) . ' ' . $mois[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp) . ' · ' . date('H\hi', $timestamp);
}

$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$slug = isset($_GET['slug']) ? $_GET['slug'] : null;

// Récupération de l'article (par id ou par slug)
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
	$article = $stmt->fetch(PDO::FETCH_ASSOC);

	// Si on accède via ?id=, on redirige vers l'URL propre /slug.html
	if ($article && $slug === null) {
		$prettySlug = slugify($article['titre']);
		$scriptName = $_SERVER['SCRIPT_NAME'] ?? '/frontoffice/article.php';
		$basePath   = rtrim(dirname($scriptName), '/\\');
		if ($basePath === '' || $basePath === '/') {
			$prettyPath = '/' . $prettySlug . '.html';
		} else {
			$prettyPath = $basePath . '/' . $prettySlug . '.html';
		}

		$currentUri = $_SERVER['REQUEST_URI'] ?? '';
		if ($currentUri !== $prettyPath) {
			$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
			$host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
			$absoluteUrl = $scheme . '://' . $host . $prettyPath;
			header('Location: ' . $absoluteUrl, true, 301);
			exit;
		}
	}
} elseif ($slug) {
	// Recherche par slug côté front (même logique que le backoffice)
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

// Récupération des catégories pour le header / footer
$categories = [];
$stmtCat = $pdo->query("SELECT id_categorie, libelle FROM categorie ORDER BY id_categorie");
while ($row = $stmtCat->fetch(PDO::FETCH_ASSOC)) {
	$categories[$row['id_categorie']] = $row;
}

$site_nom        = 'IRAN CRISIS';
$site_slogan     = 'Actualités · Analyses · Terrain';
$date_mise_a_jour = date('d/m/Y à H:i');

$scheme    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$canonical = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/frontoffice/article.php');

$metaSource = $article['introduction'] ?? strip_tags($article['contenu'] ?? '');
$metaSource = trim($metaSource);
if ($metaSource === '') {
	$metaSource = "Analyse et actualités sur la crise en Iran.";
}
$metaDescription = mb_substr($metaSource, 0, 157, 'UTF-8');
if (mb_strlen($metaSource, 'UTF-8') > 157) {
	$metaDescription .= '...';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($site_nom, ENT_QUOTES, 'UTF-8') ?></title>
	<meta name="description" content="<?= htmlspecialchars($metaDescription, ENT_QUOTES, 'UTF-8') ?>">
	<link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">

	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Source+Serif+4:opsz,wght@8..60,300;8..60,400;8..60,600&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="assets/css/accueil.css">
</head>
<body>

<!-- BARRE D'ALERTE EN TEMPS RÉEL (même structure que l'accueil) -->
<div class="barre-alerte">
	<div class="alerte-inner container" style="overflow: hidden;">
		<span class="alerte-label">⚡ EN DIRECT</span>
		<div style="overflow: hidden; flex: 1;">
			<div class="alerte-ticker">
				<span>Tirs de missiles signalés dans la région de Tabriz</span>
				<span>Le Conseil de sécurité de l'ONU convoqué en urgence</span>
				<span>Les prix du pétrole grimpent à +8 % suite aux tensions</span>
				<span>Ambassade française évacuée — ressortissants invités à quitter le pays</span>
				<span>Corridors humanitaires ouverts côté nord de l'Irak</span>
				<span>Tirs de missiles signalés dans la région de Tabriz</span>
				<span>Le Conseil de sécurité de l'ONU convoqué en urgence</span>
				<span>Les prix du pétrole grimpent à +8 % suite aux tensions</span>
				<span>Ambassade française évacuée — ressortissants invités à quitter le pays</span>
				<span>Corridors humanitaires ouverts côté nord de l'Irak</span>
			</div>
		</div>
	</div>
</div>

<!-- EN-TÊTE -->
<header class="entete">
	<div class="entete-top">
		<div class="logo">
			<div class="logo-principal">IRAN<span>CRISIS</span></div>
			<div class="logo-slogan"><?= htmlspecialchars($site_slogan, ENT_QUOTES, 'UTF-8') ?></div>
		</div>
		<div class="entete-meta">
			<div class="entete-date">Lundi <?= date('j F Y') ?></div>
			<div class="entete-mise-a-jour">Mis à jour · <?= htmlspecialchars($date_mise_a_jour, ENT_QUOTES, 'UTF-8') ?></div>
		</div>
	</div>
	<nav class="nav">
		<a href="index.php" class="actif">Accueil</a>
		<?php
		$nav_categories = [2, 3, 6, 7]; // Diplomatie, Humanitaire, Analyse, Chronologie
		foreach ($nav_categories as $cat_id):
			if (!isset($categories[$cat_id])) {
				continue;
			}
		?>
			<a href="#"><?= htmlspecialchars($categories[$cat_id]['libelle'], ENT_QUOTES, 'UTF-8') ?></a>
		<?php endforeach; ?>
		<a href="#">Carte du conflit</a>
		<div class="nav-recherche">
			<input type="text" placeholder="Rechercher...">
			<button class="btn-recherche">→</button>
		</div>
	</nav>
</header>

<!-- CONTENU ARTICLE -->
<main class="container">
	<article class="article-page">
		<?php if (!empty($article['categorie'])): ?>
			<div class="article-page-categorie"><?= htmlspecialchars(strtoupper($article['categorie']), ENT_QUOTES, 'UTF-8') ?></div>
		<?php endif; ?>

		<h1 class="article-page-titre"><?= htmlspecialchars($article['titre'], ENT_QUOTES, 'UTF-8') ?></h1>

		<div class="article-page-meta">
			<?php if (!empty($article['journaliste_nom'])): ?>
				<span class="article-page-auteur">Par <?= htmlspecialchars($article['journaliste_nom'], ENT_QUOTES, 'UTF-8') ?></span>
			<?php endif; ?>
			<?php if (!empty($article['creation'])): ?>
				<span class="article-page-separateur">•</span>
				<span class="article-page-date"><?= formaterDate($article['creation']) ?></span>
			<?php endif; ?>
		</div>

		<?php if (!empty($article['image'])): ?>
			<figure class="article-page-image-wrapper">
				<img src="<?= htmlspecialchars($article['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($article['alt'] ?? '', ENT_QUOTES, 'UTF-8') ?>" class="article-page-image">
				<?php if (!empty($article['alt'])): ?>
					<figcaption class="article-page-legende"><?= htmlspecialchars($article['alt'], ENT_QUOTES, 'UTF-8') ?></figcaption>
				<?php endif; ?>
			</figure>
		<?php endif; ?>

		<?php if (!empty($article['introduction'])): ?>
			<p class="article-page-intro">
				<?= htmlspecialchars($article['introduction'], ENT_QUOTES, 'UTF-8') ?>
			</p>
		<?php endif; ?>

		<div class="article-page-contenu">
			<?= nl2br(htmlspecialchars($article['contenu'] ?? '', ENT_QUOTES, 'UTF-8')) ?>
		</div>
	</article>
</main>

<!-- PIED DE PAGE -->
<footer>
	<div class="footer-grille">
		<div>
			<div class="footer-logo">IRAN<span>CRISIS</span></div>
			<p class="footer-desc">
				Site d'information indépendant dédié à la couverture du conflit iranien. Nos correspondants sur le terrain et nos analystes vous offrent une information rigoureuse, vérifiée et sans parti pris.
			</p>
		</div>
		<div>
			<div class="footer-col-titre">Rubriques</div>
			<ul class="footer-liens">
				<?php foreach (array_slice(array_values($categories), 0, 5) as $cat): ?>
					<li><a href="#"><?= htmlspecialchars($cat['libelle'], ENT_QUOTES, 'UTF-8') ?></a></li>
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
			Les informations publiées sont issues de sources vérifiées. Ce site n'est affilié à aucun gouvernement ni organisation politique.
		</div>
	</div>
</footer>

</body>
</html>