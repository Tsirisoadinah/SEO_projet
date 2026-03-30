<?php
// ============================================================
// DONNÉES STATIQUES — Structurées selon le schéma init.sql
// Remplacer par des requêtes SQL plus tard sera très simple
// ============================================================

// --- Table: Categorie ---
$categories = [
    1 => ["Id_Categorie" => 1, "libelle" => "Breaking"],
    2 => ["Id_Categorie" => 2, "libelle" => "Diplomatie"],
    3 => ["Id_Categorie" => 3, "libelle" => "Humanitaire"],
    4 => ["Id_Categorie" => 4, "libelle" => "Économie"],
    5 => ["Id_Categorie" => 5, "libelle" => "Société"],
    6 => ["Id_Categorie" => 6, "libelle" => "Analyse"],
    7 => ["Id_Categorie" => 7, "libelle" => "Chronologie"],
    8 => ["Id_Categorie" => 8, "libelle" => "Reportage"],
];

// --- Table: Journaliste ---
$journalistes = [
    1 => [
        "Id_Journaliste" => 1,
        "Nom" => "Rédaction internationale",
        "date_embauche" => "2022-01-15",
        "Actif" => true,
        "image" => "https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&q=80"
    ],
    2 => [
        "Id_Journaliste" => 2,
        "Nom" => "Pierre Dumont",
        "date_embauche" => "2021-06-01",
        "Actif" => true,
        "image" => "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80"
    ],
    3 => [
        "Id_Journaliste" => 3,
        "Nom" => "Marie Fontaine",
        "date_embauche" => "2023-03-10",
        "Actif" => true,
        "image" => "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80"
    ],
    4 => [
        "Id_Journaliste" => 4,
        "Nom" => "Ahmed Benali",
        "date_embauche" => "2020-09-20",
        "Actif" => true,
        "image" => "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80"
    ],
    5 => [
        "Id_Journaliste" => 5,
        "Nom" => "Sophie Laurent",
        "date_embauche" => "2024-01-05",
        "Actif" => true,
        "image" => "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80"
    ],
];

// --- Table: articles ---
$articles = [
    1 => [
        "Id_articles" => 1,
        "Titre" => "Derniers développements sur le front : situation critique dans le nord",
        "Introduction" => "Les observateurs internationaux font état de mouvements significatifs.",
        "Contenu" => "Les observateurs internationaux font état de mouvements de troupes significatifs près de la frontière nord. L'ONU appelle à une désescalade immédiate. Les forces en présence se sont repositionnées au cours des dernières 48 heures, suscitant l'inquiétude de la communauté internationale.",
        "image" => "https://images.unsplash.com/photo-1589998059171-988d887df646?w=800&q=80",
        "alt" => "Situation sur le front nord du conflit",
        "creation" => "2026-03-30 09:14:00",
        "Id_Categorie" => 1,
    ],
    2 => [
        "Id_articles" => 2,
        "Titre" => "Les négociations à Genève reprennent après une semaine de suspension",
        "Introduction" => "Les délégations se retrouvent sous médiation des Nations Unies.",
        "Contenu" => "Les délégations se retrouvent autour de la table sous médiation des Nations Unies. Les positions restent éloignées mais le dialogue est rétabli. Le secrétaire général a salué cette reprise des pourparlers comme un signe encourageant.",
        "image" => "https://images.unsplash.com/photo-1616469829167-0bd76a80c913?w=800&q=80",
        "alt" => "Négociations diplomatiques à Genève",
        "creation" => "2026-03-30 07:50:00",
        "Id_Categorie" => 2,
    ],
    3 => [
        "Id_articles" => 3,
        "Titre" => "Crise humanitaire : plus de 2 millions de déplacés internes selon le HCR",
        "Introduction" => "Les camps de réfugiés débordent de capacité.",
        "Contenu" => "Les camps de réfugiés débordent de capacité. Les ONG sur le terrain alertent sur le manque de ressources médicales et alimentaires. La situation sanitaire se détériore rapidement dans les zones les plus touchées.",
        "image" => "https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=800&q=80",
        "alt" => "Camp de réfugiés déplacés internes",
        "creation" => "2026-03-29 18:30:00",
        "Id_Categorie" => 3,
    ],
    4 => [
        "Id_articles" => 4,
        "Titre" => "Sanctions économiques : l'impact sur la population civile iranienne",
        "Introduction" => "L'économie locale subit de plein fouet les restrictions internationales.",
        "Contenu" => "Les sanctions économiques imposées par les puissances occidentales ont un impact direct sur la vie quotidienne des civils. L'inflation galopante rend les produits de première nécessité inaccessibles pour une grande partie de la population.",
        "image" => "https://images.unsplash.com/photo-1526304640581-d334cdbbf45e?w=800&q=80",
        "alt" => "Impact économique des sanctions sur la population",
        "creation" => "2026-03-29 14:00:00",
        "Id_Categorie" => 4,
    ],
    5 => [
        "Id_articles" => 5,
        "Titre" => "Téhéran : la vie quotidienne sous tension permanente",
        "Introduction" => "Les habitants de la capitale tentent de maintenir un semblant de normalité.",
        "Contenu" => "Malgré les alertes et les tensions croissantes, les habitants de Téhéran s'efforcent de poursuivre leur quotidien. Les marchés restent ouverts, mais l'ambiance est pesante et les files d'attente s'allongent.",
        "image" => "https://images.unsplash.com/photo-1565711561500-49678a10a63f?w=800&q=80",
        "alt" => "Vie quotidienne à Téhéran",
        "creation" => "2026-03-29 10:00:00",
        "Id_Categorie" => 5,
    ],
    6 => [
        "Id_articles" => 6,
        "Titre" => "Analyse : les enjeux géopolitiques régionaux en jeu",
        "Introduction" => "Le conflit redessine les alliances au Moyen-Orient.",
        "Contenu" => "Les experts géopolitiques s'accordent à dire que ce conflit dépasse largement les frontières iraniennes. Les alliances régionales sont en pleine recomposition, avec des implications à long terme pour la stabilité de toute la région.",
        "image" => "https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=800&q=80",
        "alt" => "Carte géopolitique du Moyen-Orient",
        "creation" => "2026-03-28 16:00:00",
        "Id_Categorie" => 6,
    ],
    7 => [
        "Id_articles" => 7,
        "Titre" => "Chronologie complète : comment le conflit a éclaté",
        "Introduction" => "Retour sur les événements clés depuis septembre 2025.",
        "Contenu" => "Du premier incident diplomatique aux premières frappes, retour détaillé sur l'enchaînement des événements qui ont mené à la situation actuelle. Une timeline illustrée pour comprendre les origines du conflit.",
        "image" => "https://images.unsplash.com/photo-1504711434969-e33886168d5c?w=800&q=80",
        "alt" => "Frise chronologique du conflit",
        "creation" => "2026-03-28 12:00:00",
        "Id_Categorie" => 7,
    ],
    8 => [
        "Id_articles" => 8,
        "Titre" => "Témoignages : des civils racontent leur quotidien",
        "Introduction" => "Paroles recueillies auprès de familles touchées par le conflit.",
        "Contenu" => "Nos correspondants ont recueilli les témoignages poignants de familles vivant dans les zones les plus affectées. Entre peur, résilience et espoir, ces voix racontent une réalité que les chiffres seuls ne peuvent exprimer.",
        "image" => "https://images.unsplash.com/photo-1532375810709-75b1da00537c?w=800&q=80",
        "alt" => "Témoignage de civils",
        "creation" => "2026-03-27 09:00:00",
        "Id_Categorie" => 8,
    ],
];

// --- Table: journaliste_article (liaison Many-to-Many) ---
$journaliste_article = [
    ["Id_articles" => 1, "Id_Journaliste" => 1],
    ["Id_articles" => 2, "Id_Journaliste" => 2],
    ["Id_articles" => 3, "Id_Journaliste" => 3],
    ["Id_articles" => 4, "Id_Journaliste" => 4],
    ["Id_articles" => 5, "Id_Journaliste" => 2],
    ["Id_articles" => 6, "Id_Journaliste" => 5],
    ["Id_articles" => 7, "Id_Journaliste" => 1],
    ["Id_articles" => 8, "Id_Journaliste" => 3],
];

// ============================================================
// FONCTIONS UTILITAIRES — Simulent les jointures SQL
// Ces fonctions seront remplacées par des requêtes SQL
// ============================================================

/**
 * Récupère le nom du journaliste d'un article via la table de liaison
 * Equivalent SQL: SELECT j.Nom FROM Journaliste j
 *   JOIN journaliste_article ja ON j.Id_Journaliste = ja.Id_Journaliste
 *   WHERE ja.Id_articles = ?
 */
function getJournalisteNom($id_article, $journaliste_article, $journalistes) {
    foreach ($journaliste_article as $liaison) {
        if ($liaison['Id_articles'] == $id_article) {
            return $journalistes[$liaison['Id_Journaliste']]['Nom'] ?? 'Inconnu';
        }
    }
    return 'Inconnu';
}

/**
 * Récupère le libellé de la catégorie d'un article
 * Equivalent SQL: SELECT c.libelle FROM Categorie c WHERE c.Id_Categorie = ?
 */
function getCategorieLibelle($id_categorie, $categories) {
    return $categories[$id_categorie]['libelle'] ?? 'Non classé';
}

/**
 * Formate une date de création pour l'affichage
 */
function formaterDate($datetime) {
    setlocale(LC_TIME, 'fr_FR.UTF-8', 'fr_FR', 'French');
    $timestamp = strtotime($datetime);
    $jours = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'];
    $mois = ['','janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
    return (int)date('j', $timestamp) . ' ' . $mois[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp) . ' · ' . date('H\hi', $timestamp);
}

// ============================================================
// PRÉPARATION DES DONNÉES POUR L'AFFICHAGE
// ============================================================
$site_nom = "IRAN CRISIS";
$site_slogan = "Actualités · Analyses · Terrain";
$date_mise_a_jour = date('d/m/Y à H:i');

// Article principal (le plus récent) — SQL: ORDER BY creation DESC LIMIT 1
$article_principal = $articles[1];
$article_principal_journaliste = getJournalisteNom($article_principal['Id_articles'], $journaliste_article, $journalistes);
$article_principal_categorie = getCategorieLibelle($article_principal['Id_Categorie'], $categories);

// Articles secondaires (les 2 suivants) — SQL: ORDER BY creation DESC LIMIT 2 OFFSET 1
$articles_secondaires = [$articles[2], $articles[3]];

// Articles liste "À lire également" — SQL: ORDER BY creation DESC LIMIT 5 OFFSET 3
$articles_liste = [$articles[4], $articles[5], $articles[6], $articles[7], $articles[8]];
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
        <a href="accueil.php" class="actif">Accueil</a>
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
        <h1 class="hero-titre"><?= htmlspecialchars($article_principal['Titre']) ?></h1>
        <p class="hero-resume"><?= htmlspecialchars($article_principal['Contenu']) ?></p>
        <div class="hero-meta">
            <span class="hero-auteur"><?= htmlspecialchars($article_principal_journaliste) ?></span>
            <span class="hero-date"><?= formaterDate($article_principal['creation']) ?></span>
            <span class="hero-lecture">Lecture : 4 min</span>
        </div>
        <a href="#" class="btn-lire">Lire l'article complet →</a>
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
                    $cat_label = getCategorieLibelle($article['Id_Categorie'], $categories);
                    $journaliste_nom = getJournalisteNom($article['Id_articles'], $journaliste_article, $journalistes);
                ?>
                <article class="article-card">
                    <img src="<?= htmlspecialchars($article['image']) ?>" alt="<?= htmlspecialchars($article['alt']) ?>" class="article-card-image">
                    <div class="article-card-cat"><?= htmlspecialchars(strtoupper($cat_label)) ?></div>
                    <h2 class="article-card-titre"><?= htmlspecialchars($article['Titre']) ?></h2>
                    <p class="article-card-resume"><?= htmlspecialchars($article['Contenu']) ?></p>
                    <div class="article-card-meta">
                        <span><?= htmlspecialchars($journaliste_nom) ?></span>
                        <span>·</span>
                        <span><?= formaterDate($article['creation']) ?></span>
                        <span>·</span>
                        <span>5 min de lecture</span>
                    </div>
                </article>
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
                    $cat_label = getCategorieLibelle($item['Id_Categorie'], $categories);
                ?>
                <div class="liste-item">
                    <div class="liste-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></div>
                    <div>
                        <div class="liste-cat"><?= htmlspecialchars(strtoupper($cat_label)) ?></div>
                        <div class="liste-titre"><?= htmlspecialchars($item['Titre']) ?></div>
                        <div class="liste-date"><?= formaterDate($item['creation']) ?></div>
                    </div>
                </div>
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
                    <?php if ($journaliste['Actif']): ?>
                    <div class="journaliste-item">
                        <img src="<?= htmlspecialchars($journaliste['image']) ?>" alt="<?= htmlspecialchars($journaliste['Nom']) ?>" class="journaliste-avatar">
                        <div class="journaliste-info">
                            <div class="journaliste-nom"><?= htmlspecialchars($journaliste['Nom']) ?></div>
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
