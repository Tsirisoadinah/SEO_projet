<?php
require('backend/Article.php');
require('backend/Categorie.php');
require('backend/Journaliste.php');
require('backend/JournalisteArticle.php');

function formaterDate($datetime) {
    setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'French');
    $timestamp = strtotime($datetime);
    $jours = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];
    $mois = ['','janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
    return (int)date('j', $timestamp) . ' ' . $mois[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp) . ' · ' . date('H\hi', $timestamp);
}

$articleModel = new Article();
$categorieModel = new Categorie();
$journalisteModel = new Journaliste();
$journaliste_article = new JournalisteArticle();
// 🔥 DATA
$article_principal = $articleModel->getLatestArticle();
$articles_secondaires = $articleModel->getSecondaryArticles();
$articles_liste = $articleModel->getListArticles();
$journalistes = $journalisteModel->getActifs();
$categories = $categorieModel->getNavCategories();
$site_slogan = "Actualités · Analyses · Terrain";
$date_mise_a_jour = date('d/m/Y à H:i');
$article_principal_categorie = $categorieModel->getCategorieLibelle($article_principal['id_categorie']);
$article_principal_journaliste = $journalisteModel->getJournalisteNom($article_principal['id_articles'], $journaliste_article->getRelations(), $journalistes);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_nom ?> — <?= $site_slogan ?></title>
    <meta name="description" content="Couverture complète du conflit en Iran : actualités, analyses géopolitiques, situation humanitaire.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Source+Serif+4:opsz,wght@8..60,300;8..60,400;8..60,600&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">

   <link rel="stylesheet" href="assets/css/accueil.css">
</head>
<body>

<!-- BARRE D'ALERTE EN TEMPS RÉEL -->
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
            <div class="logo-slogan"><?= $site_slogan ?></div>
        </div>
        <div class="entete-meta">
            <div class="entete-date">Lundi <?= date('j F Y') ?></div>
            <div class="entete-mise-a-jour">Mis à jour · <?= $date_mise_a_jour ?></div>
        </div>
    </div>
    <nav class="nav">
        <a href="#" class="actif">Accueil</a>
        <?php
        // Navigation dynamique basée sur les catégories
        // SQL: SELECT libelle FROM Categorie ORDER BY Id_Categorie
        $nav_categories = [2, 3, 6, 7]; // Diplomatie, Humanitaire, Analyse, Chronologie
        foreach ($nav_categories as $cat_id): ?>
            <a href="#"><?= htmlspecialchars($categories[$cat_id]['libelle']) ?></a>
        <?php endforeach; ?>
        <a href="#">Carte du conflit</a>
        <div class="nav-recherche">
            <input type="text" placeholder="Rechercher...">
            <button class="btn-recherche">→</button>
        </div>
    </nav>
</header>

<!-- ARTICLE PRINCIPAL (UNE) -->
<!-- SQL: SELECT a.*, c.libelle, j.Nom FROM articles a
     JOIN Categorie c ON a.Id_Categorie = c.Id_Categorie
     JOIN journaliste_article ja ON a.Id_articles = ja.Id_articles
     JOIN Journaliste j ON ja.Id_Journaliste = j.Id_Journaliste
     ORDER BY a.creation DESC LIMIT 1 -->
<section class="hero">
    <img src="<?= htmlspecialchars($article_principal['image']) ?>" alt="<?= htmlspecialchars($article_principal['alt']) ?>" class="hero-image">
    <div class="hero-overlay"></div>
    <div class="hero-contenu">
        <span class="hero-categorie"><?= htmlspecialchars(strtoupper($article_principal_categorie)) ?></span>
        <h1 class="hero-titre"><?= htmlspecialchars($article_principal['titre']) ?></h1>
        <p class="hero-resume"><?= htmlspecialchars($article_principal['contenu']) ?></p>
        <div class="hero-meta">
            <span class="hero-auteur"><?= htmlspecialchars($article_principal_journaliste) ?></span>
            <span class="hero-date"><?= formaterDate($article_principal['creation']) ?></span>
            <span class="hero-lecture">Lecture : 4 min</span>
        </div>
        <a href="article.php?id=<?= $article_principal['id_articles'] ?>" class="btn-lire">Lire l'article complet →</a>
    </div>
</section>

<!-- CONTENU PRINCIPAL -->
<main class="container">

    <div class="grille-principale">

        <!-- COLONNE ARTICLES -->
        <div>

            <!-- Articles secondaires en grille -->
            <div class="section-titre">
                <h2>Dernières dépêches</h2>
            </div>

            <!-- SQL: SELECT a.*, c.libelle, j.Nom FROM articles a
                 JOIN Categorie c ON ... JOIN journaliste_article ja ON ...
                 JOIN Journaliste j ON ... ORDER BY a.creation DESC LIMIT 2 OFFSET 1 -->
            <div class="articles-grille">
                <?php foreach ($articles_secondaires as $article):
                    $cat_label = $categorieModel->getCategorieLibelle($article['id_categorie'], $categories);
                    $journaliste_nom = $journalisteModel->getJournalisteNom($article['id_articles'], $journaliste_article->getRelations(), $journalistes);
                ?>
                <a href="article.php?id=<?= $article['id_articles'] ?>" class="article-card-link">
                    <article class="article-card">
                        <img src="<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['alt']) ?>" class="article-card-image">
                        <div class="article-card-cat"><?= htmlspecialchars(strtoupper($cat_label)) ?></div>
                        <h2 class="article-card-titre"><?= htmlspecialchars($article['titre']) ?></h2>
                        <p class="article-card-resume"><?= htmlspecialchars($article['contenu']) ?></p>
                        <div class="article-card-meta">
                            <span><?= htmlspecialchars($journaliste_nom) ?></span>
                            <span>·</span>
                            <span><?= formaterDate($article['creation']) ?></span>
                            <span>·</span>
                            <span>5 min de lecture</span>
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
                        "Nous ne dormons plus. Chaque nuit, les sirènes résonnent sur la ville. Les enfants ont appris à courir vers les abris sans que nous ayons besoin de les appeler."
                    </blockquote>
                    <div class="temoin-source">— Nadia K., résidente de Téhéran · recueilli le 28 mars 2026</div>
                </div>
            </div>

            <!-- À lire aussi -->
            <div class="section-titre">
                <h2>À lire également</h2>
            </div>

            <!-- SQL: SELECT a.*, c.libelle FROM articles a
                 JOIN Categorie c ON a.Id_Categorie = c.Id_Categorie
                 ORDER BY a.creation DESC LIMIT 5 OFFSET 3 -->
            <div class="liste-articles">
                <?php foreach ($articles_liste as $i => $item):
                    $cat_label = $categorieModel->getCategorieLibelle($item['id_categorie'], $categories);
                ?>
                <a href="article.php?id=<?= $item['id_articles'] ?>" class="liste-item-link">
                    <div class="liste-item">
                        <div class="liste-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
                        <div>
                            <div class="liste-cat"><?= htmlspecialchars(strtoupper($cat_label)) ?></div>
                            <div class="liste-titre"><?= htmlspecialchars($item['titre']) ?></div>
                            <div class="liste-date"><?= formaterDate($item['creation']) ?></div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

        </div>

        <!-- BARRE LATÉRALE -->
        <aside class="sidebar">

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
                        <span class="chiffre-valeur"><?= floor((time() - strtotime('2025-09-01')) / 86400) ?></span>
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

            <!-- Journalistes actifs -->
            <!-- SQL: SELECT * FROM Journaliste WHERE Actif = true -->
            <div class="widget">
                <div class="widget-titre">Notre équipe</div>
                <div class="journalistes-liste">
                    <?php foreach ($journalistes as $journaliste): ?>
                    <?php if ($journaliste['actif']): ?>
                    <div class="journaliste-item">
                        <img src="<?= htmlspecialchars($journaliste['image']) ?>" alt="<?= htmlspecialchars($journaliste['Nom']) ?>" class="journaliste-avatar">
                        <div class="journaliste-info">
                            <div class="journaliste-nom"><?= htmlspecialchars($journaliste['nom']) ?></div>
                            <div class="journaliste-depuis">Depuis <?= date('Y', strtotime($journaliste['date_embauche'])) ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Carte schématique -->
            <div class="widget">
                <div class="widget-titre">Zone du conflit</div>
                <div class="carte-iran">
                    <svg viewBox="0 0 260 200" xmlns="http://www.w3.org/2000/svg">
                        <defs>
                            <filter id="glow">
                                <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                                <feMerge>
                                    <feMergeNode in="coloredBlur"/>
                                    <feMergeNode in="SourceGraphic"/>
                                </feMerge>
                            </filter>
                        </defs>
                        <path d="M 45 40 L 80 25 L 130 20 L 170 30 L 210 45 L 220 70 L 215 95 L 200 120 L 185 150 L 160 170 L 135 175 L 105 165 L 80 150 L 55 130 L 35 105 L 30 80 Z"
                              fill="rgba(201,168,76,0.12)" stroke="rgba(201,168,76,0.5)" stroke-width="1.5"/>
                        <circle cx="90" cy="55" r="5" fill="#c0392b" filter="url(#glow)" opacity="0.9">
                            <animate attributeName="r" values="5;8;5" dur="2s" repeatCount="indefinite"/>
                            <animate attributeName="opacity" values="0.9;0.5;0.9" dur="2s" repeatCount="indefinite"/>
                        </circle>
                        <circle cx="155" cy="75" r="4" fill="#c0392b" filter="url(#glow)" opacity="0.8">
                            <animate attributeName="r" values="4;7;4" dur="2.5s" repeatCount="indefinite"/>
                        </circle>
                        <circle cx="130" cy="120" r="6" fill="#c0392b" filter="url(#glow)" opacity="0.7">
                            <animate attributeName="r" values="6;9;6" dur="1.8s" repeatCount="indefinite"/>
                        </circle>
                        <circle cx="118" cy="80" r="4" fill="var(--or)" opacity="0.9"/>
                        <text x="124" y="84" fill="rgba(255,255,255,0.7)" font-size="9" font-family="monospace">Téhéran</text>
                        <circle cx="30" cy="185" r="4" fill="#c0392b"/>
                        <text x="38" y="189" fill="rgba(255,255,255,0.5)" font-size="8" font-family="monospace">Zones actives</text>
                        <circle cx="130" cy="185" r="4" fill="var(--or)"/>
                        <text x="138" y="189" fill="rgba(255,255,255,0.5)" font-size="8" font-family="monospace">Capitale</text>
                    </svg>
                </div>
                <p style="font-size: 11px; color: var(--gris-moyen); font-family: var(--fonte-mono);">
                    ● Points de tension actifs au 30 mars 2026
                </p>
            </div>

            <!-- Newsletter -->
            <div class="widget">
                <div class="widget-titre">Alertes & Newsletter</div>
                <p class="newsletter-texte" style="margin-bottom: 16px;">
                    Recevez les dernières actualités directement dans votre boîte mail, sans publicité.
                </p>
                <div class="newsletter-form">
                    <input type="email" class="newsletter-input" placeholder="votre@email.com">
                    <button class="newsletter-btn">S'abonner aux alertes →</button>
                </div>
                <p class="newsletter-texte" style="margin-top: 12px;">
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
                Site d'information indépendant dédié à la couverture du conflit iranien. Nos correspondants sur le terrain et nos analystes vous offrent une information rigoureuse, vérifiée et sans parti pris.
            </p>
        </div>
        <div>
            <div class="footer-col-titre">Rubriques</div>
            <ul class="footer-liens">
                <!-- SQL: SELECT libelle FROM Categorie LIMIT 5 -->
                <?php foreach (array_slice($categories, 0, 5) as $cat): ?>
                <li><a href="#"><?= htmlspecialchars($cat['libelle']) ?></a></li>
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
