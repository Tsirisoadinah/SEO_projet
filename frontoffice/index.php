<?php
declare(strict_types=1);

require_once __DIR__ . '/backend/Article.php';
require_once __DIR__ . '/backend/Categorie.php';
require_once __DIR__ . '/backend/Journaliste.php';
require_once __DIR__ . '/backend/JournalisteArticle.php';

/* ─── Helpers ──────────────────────────────────────────── */

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

/* ─── Données ──────────────────────────────────────────── */

$articleModel     = new Article();
$categorieModel   = new Categorie();
$journalisteModel = new Journaliste();
$jaModel          = new JournalisteArticle();

$article_principal  = $articleModel->getLatestArticle();
$articles_secondaires = $articleModel->getSecondaryArticles();
$articles_liste     = $articleModel->getListArticles();
$journalistes       = $journalisteModel->getActifs();
$categories         = $categorieModel->getNavCategories();
$relations          = $jaModel->getRelations();

$site_nom    = 'IRAN CRISIS';
$site_slogan = 'Actualités · Analyses · Terrain';
$date_maj    = date('d/m/Y à H:i');

$ap_categorie   = $categorieModel->getCategorieLibelle($article_principal['id_categorie']);
$ap_journaliste = $journalisteModel->getJournalisteNom(
    $article_principal['id_articles'], $relations, $journalistes
);

$nav_ids = [2, 3, 6, 7]; // Diplomatie, Humanitaire, Analyse, Chronologie

$ticker_items = [
    'Tirs de missiles signalés dans la région de Tabriz',
    'Le Conseil de sécurité de l\'ONU convoqué en urgence',
    'Les prix du pétrole grimpent à +8 % suite aux tensions',
    'Ambassade française évacuée — ressortissants invités à quitter le pays',
    'Corridors humanitaires ouverts côté nord de l\'Irak',
];
// Dupliqué pour l'animation seamless
$ticker_double = array_merge($ticker_items, $ticker_items);

$jours_conflit = (int)floor((time() - strtotime('2025-09-01')) / 86400);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Couverture complète du conflit en Iran : actualités, analyses géopolitiques, situation humanitaire.">
  <title><?= e($site_nom) ?> — <?= e($site_slogan) ?></title>

  <!-- Preconnect fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <!-- font-display=swap via Google Fonts param -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Source+Serif+4:opsz,wght@8..60,300;8..60,400;8..60,600&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

  <!-- Preload hero image -->
  <?php if (!empty($article_principal['image'])): ?>
  <link rel="preload" as="image" href="<?= e($article_principal['image']) ?>">
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
    <a href="index.php" class="actif">Accueil</a>
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

<!-- HERO — UNE PRINCIPALE -->
<section class="hero">
  <img
    src="<?= e($article_principal['image']) ?>"
    alt="<?= e($article_principal['alt'] ?? '') ?>"
    class="hero-image"
    fetchpriority="high"
    decoding="sync"
    width="1300" height="580"
  >
  <div class="hero-overlay" aria-hidden="true"></div>
  <div class="hero-contenu">
    <span class="hero-categorie"><?= e(strtoupper($ap_categorie)) ?></span>
    <h1 class="hero-titre"><?= e($article_principal['titre']) ?></h1>
    <p class="hero-resume"><?= e($article_principal['contenu']) ?></p>
    <div class="hero-meta">
      <span class="hero-auteur"><?= e($ap_journaliste) ?></span>
      <span class="hero-date"><?= formaterDate($article_principal['creation']) ?></span>
      <span class="hero-lecture">Lecture : 4 min</span>
    </div>
    <a href="article.php?id=<?= (int)$article_principal['id_articles'] ?>" class="btn-lire">
      Lire l'article complet →
    </a>
  </div>
</section>

<!-- CONTENU PRINCIPAL -->
<main class="container" id="main-content">
  <div class="grille-principale">

    <!-- COLONNE ARTICLES -->
    <div>
      <div class="section-titre"><h2>Dernières dépêches</h2></div>

      <div class="articles-grille">
        <?php foreach ($articles_secondaires as $article):
            $cat   = $categorieModel->getCategorieLibelle($article['id_categorie'], $categories);
            $auteur = $journalisteModel->getJournalisteNom($article['id_articles'], $relations, $journalistes);
        ?>
        <a href="article.php?id=<?= (int)$article['id_articles'] ?>" class="article-card-link">
          <article class="article-card">
            <img
              src="<?= e($article['image']) ?>"
              alt="<?= e($article['alt'] ?? '') ?>"
              class="article-card-image"
              loading="lazy"
              decoding="async"
              width="600" height="200"
            >
            <div class="article-card-cat"><?= e(strtoupper($cat)) ?></div>
            <h2 class="article-card-titre"><?= e($article['titre']) ?></h2>
            <p class="article-card-resume"><?= e($article['contenu']) ?></p>
            <div class="article-card-meta">
              <span><?= e($auteur) ?></span>
              <span>·</span>
              <span><?= formaterDate($article['creation']) ?></span>
              <span>·</span>
              <span>5 min</span>
            </div>
          </article>
        </a>
        <?php endforeach; ?>
      </div>

      <!-- Bandeau témoignage -->
      <div class="bandeau-temoin">
        <div class="temoin-contenu">
          <div class="temoin-label">📍 TÉMOIGNAGE DU TERRAIN</div>
          <blockquote class="temoin-citation">
            "Nous ne dormons plus. Chaque nuit, les sirènes résonnent sur la ville.
            Les enfants ont appris à courir vers les abris sans que nous ayons besoin de les appeler."
          </blockquote>
          <div class="temoin-source">— Nadia K., résidente de Téhéran · recueilli le 28 mars 2026</div>
        </div>
      </div>

      <div class="section-titre"><h2>À lire également</h2></div>

      <div class="liste-articles">
        <?php foreach ($articles_liste as $i => $item):
            $cat = $categorieModel->getCategorieLibelle($item['id_categorie'], $categories);
        ?>
        <a href="article.php?id=<?= (int)$item['id_articles'] ?>" class="liste-item-link">
          <div class="liste-item">
            <div class="liste-num"><?= str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT) ?></div>
            <div>
              <div class="liste-cat"><?= e(strtoupper($cat)) ?></div>
              <div class="liste-titre"><?= e($item['titre']) ?></div>
              <div class="liste-date"><?= formaterDate($item['creation']) ?></div>
            </div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div><!-- /colonne articles -->

    <!-- SIDEBAR -->
    <aside class="sidebar" aria-label="Informations complémentaires">

      <!-- Chiffres clés -->
      <div class="widget">
        <div class="widget-titre">Chiffres clés</div>
        <div class="chiffres-liste">
          <div class="chiffre-item">
            <span class="chiffre-label">Déplacés internes</span>
            <span class="chiffre-valeur rouge">2,1M</span>
          </div>
          <div class="chiffre-item">
            <span class="chiffre-label">Jours de conflit</span>
            <span class="chiffre-valeur"><?= $jours_conflit ?></span>
          </div>
          <div class="chiffre-item">
            <span class="chiffre-label">Blessés civils</span>
            <span class="chiffre-valeur rouge">12 400</span>
          </div>
          <div class="chiffre-item">
            <span class="chiffre-label">ONG présentes</span>
            <span class="chiffre-valeur or">47</span>
          </div>
          <div class="chiffre-item">
            <span class="chiffre-label">Résolutions ONU</span>
            <span class="chiffre-valeur">8</span>
          </div>
        </div>
      </div>

      <!-- Équipe -->
      <div class="widget">
        <div class="widget-titre">Notre équipe</div>
        <div class="journalistes-liste">
          <?php foreach ($journalistes as $j):
              if (empty($j['actif'])) continue; ?>
          <div class="journaliste-item">
            <img
              src="<?= e($j['image'] ?? '') ?>"
              alt="<?= e($j['nom'] ?? '') ?>"
              class="journaliste-avatar"
              loading="lazy" decoding="async"
              width="42" height="42"
            >
            <div class="journaliste-info">
              <div class="journaliste-nom"><?= e($j['nom'] ?? '') ?></div>
              <div class="journaliste-depuis">Depuis <?= date('Y', strtotime($j['date_embauche'])) ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Carte schématique -->
      <div class="widget">
        <div class="widget-titre">Zone du conflit</div>
        <div class="carte-iran">
          <svg viewBox="0 0 260 200" xmlns="http://www.w3.org/2000/svg" aria-label="Carte schématique de l'Iran avec zones de conflit">
            <defs>
              <filter id="glow">
                <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                <feMerge><feMergeNode in="coloredBlur"/><feMergeNode in="SourceGraphic"/></feMerge>
              </filter>
            </defs>
            <path d="M45 40 L80 25 L130 20 L170 30 L210 45 L220 70 L215 95 L200 120 L185 150 L160 170 L135 175 L105 165 L80 150 L55 130 L35 105 L30 80 Z"
                  fill="rgba(201,168,76,.12)" stroke="rgba(201,168,76,.5)" stroke-width="1.5"/>
            <circle cx="90" cy="55" r="5" fill="#c0392b" filter="url(#glow)" opacity=".9">
              <animate attributeName="r" values="5;8;5" dur="2s" repeatCount="indefinite"/>
              <animate attributeName="opacity" values=".9;.5;.9" dur="2s" repeatCount="indefinite"/>
            </circle>
            <circle cx="155" cy="75" r="4" fill="#c0392b" filter="url(#glow)" opacity=".8">
              <animate attributeName="r" values="4;7;4" dur="2.5s" repeatCount="indefinite"/>
            </circle>
            <circle cx="130" cy="120" r="6" fill="#c0392b" filter="url(#glow)" opacity=".7">
              <animate attributeName="r" values="6;9;6" dur="1.8s" repeatCount="indefinite"/>
            </circle>
            <circle cx="118" cy="80" r="4" fill="var(--or)" opacity=".9"/>
            <text x="124" y="84" fill="rgba(255,255,255,.7)" font-size="9" font-family="monospace">Téhéran</text>
            <circle cx="30" cy="185" r="4" fill="#c0392b"/>
            <text x="38" y="189" fill="rgba(255,255,255,.5)" font-size="8" font-family="monospace">Zones actives</text>
            <circle cx="130" cy="185" r="4" fill="var(--or)"/>
            <text x="138" y="189" fill="rgba(255,255,255,.5)" font-size="8" font-family="monospace">Capitale</text>
          </svg>
        </div>
        <p style="font-size:11px;color:var(--gris-moyen);font-family:var(--fonte-mono)">
          ● Points de tension actifs au <?= date('j F Y') ?>
        </p>
      </div>

      <!-- Newsletter -->
      <div class="widget">
        <div class="widget-titre">Alertes & Newsletter</div>
        <p class="newsletter-texte" style="margin-bottom:16px">
          Recevez les dernières actualités directement dans votre boîte mail, sans publicité.
        </p>
        <div class="newsletter-form">
          <label for="newsletter-email" class="sr-only">Adresse e-mail</label>
          <input id="newsletter-email" type="email" class="newsletter-input" placeholder="votre@email.com" autocomplete="email">
          <button class="newsletter-btn" type="button">S'abonner aux alertes →</button>
        </div>
        <p class="newsletter-texte" style="margin-top:12px">
          Fréquence : 1 à 2 envois par jour · Désinscription en un clic
        </p>
      </div>

    </aside>
  </div>
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
        <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
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

<!-- Accessible skip-link helper (sr-only) -->
<style>.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0}</style>

</body>
</html>
