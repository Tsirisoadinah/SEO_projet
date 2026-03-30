<?php
include('../config/db.php');

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupération des catégories
$stmt = $pdo->query("SELECT id_categorie, libelle FROM categorie ORDER BY libelle");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des journalistes actifs
$stmt = $pdo->query("SELECT id_journaliste, nom FROM journaliste WHERE actif = TRUE ORDER BY nom");
$journalistes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération de l'article et du journaliste associé (s'il existe)
$stmt = $pdo->prepare(
    "SELECT a.*, ja.id_journaliste
     FROM articles a
     LEFT JOIN journaliste_article ja ON a.id_articles = ja.id_articles
     WHERE a.id_articles = ?
     LIMIT 1"
);
$stmt->execute([$id]);
$a = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$a) {
    die('Article introuvable');
}

$errors = [];

$categorieId   = $a['id_categorie'] ?? null;
$titre         = $a['titre'] ?? '';
$introduction  = $a['introduction'] ?? '';
$contenu       = $a['contenu'] ?? '';
$alt           = $a['alt'] ?? '';
$creationInput = '';
$journalisteId = $a['id_journaliste'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $categorieId   = $_POST['categorie_id'] ?? null;
  $titre         = $_POST['titre'] ?? '';
  $introduction  = $_POST['introduction'] ?? '';
  $contenu       = $_POST['contenu'] ?? '';
  $alt           = $_POST['alt'] ?? '';
  $creationInput = $_POST['creation'] ?? '';
  $journalisteId = $_POST['journaliste_id'] ?? null;
  $imagePath     = $a['image'];

    if ($titre === '') {
        $errors[] = 'Le titre est obligatoire.';
    }
    if ($contenu === '') {
        $errors[] = 'Le contenu est obligatoire.';
    }
    if (empty($categorieId)) {
        $errors[] = 'La catégorie est obligatoire.';
    }


    // Gestion de la date/heure de création
    if ($creationInput !== '') {
        $creation = str_replace('T', ' ', $creationInput) . ':00';
    } else {
        $creation = $a['creation'];
    }

    // Gestion de l'upload d'image
    $imageFileName = null;
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $originalName = basename($_FILES['image']['name']);
        $safeName = preg_replace('/[^A-Za-z0-9._-]/', '_', $originalName);
        $imageFileName = uniqid('img_', true) . '_' . $safeName;

        // Respect de VARCHAR(100) pour image (chemin complet)
        $prefix = '../uploads/';
        $maxColumnLength = 100;
        $maxImageLength = $maxColumnLength - strlen($prefix);
        if ($maxImageLength < 1) {
          $maxImageLength = $maxColumnLength;
        }

        if (strlen($imageFileName) > $maxImageLength) {
          $ext = pathinfo($imageFileName, PATHINFO_EXTENSION);
          $base = pathinfo($imageFileName, PATHINFO_FILENAME);

          $allowedBaseLength = $maxImageLength - ($ext ? strlen($ext) + 1 : 0);
          if ($allowedBaseLength < 1) {
            $allowedBaseLength = $maxImageLength;
            $ext = '';
          }

          $base = substr($base, 0, $allowedBaseLength);
          $imageFileName = $ext ? ($base . '.' . $ext) : $base;
        }

        $targetPath = $uploadDir . $imageFileName;
        $imagePath = $prefix . $imageFileName;

        if (!is_uploaded_file($_FILES['image']['tmp_name']) || !move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
            $errors[] = "Erreur lors de l'upload de l'image.";
        }
    }

    if (empty($errors)) {
        // Mise à jour de l'article
        $stmt = $pdo->prepare(
            "UPDATE articles
             SET titre = ?, introduction = ?, contenu = ?, image = ?, alt = ?, creation = ?, id_categorie = ?
             WHERE id_articles = ?"
        );

        $stmt->execute([
            $titre,
            $introduction,
            $contenu,
            $imagePath,
            $alt,
            $creation,
            $categorieId,
            $id,
        ]);

        // Mise à jour du lien journaliste_article
        $stmt = $pdo->prepare("DELETE FROM journaliste_article WHERE id_articles = ?");
        $stmt->execute([$id]);

        if (!empty($journalisteId)) {
            $stmt = $pdo->prepare("INSERT INTO journaliste_article (id_articles, id_journaliste) VALUES (?, ?)");
            $stmt->execute([$id, $journalisteId]);
        }

        header('Location: dashboard.html');
        exit;
    }
}

// Préparation des valeurs par défaut pour le formulaire
$currentCategorieId = $a['id_categorie'] ?? null;
$currentJournalisteId = $a['id_journaliste'] ?? null;
$creationValue = $a['creation'] ? date('Y-m-d\TH:i', strtotime($a['creation'])) : '';

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$canonical = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/backoffice/edit_article.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier un article - Backoffice</title>
  <meta name="description" content="Formulaire du backoffice pour modifier un article existant : titre, introduction, contenu, image, alt et journaliste.">
  <!-- <meta name="robots" content="noindex, nofollow"> -->
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="stylesheet" href="backoffice.css">
</head>
<body>
  <div class="page page--narrow">
    <div class="top-bar">
      <a href="dashboard.html">← Retour au dashboard</a>
      <a href="logout.php">Déconnexion</a>
    </div>

    <div class="form-wrapper">
      <h1 class="form-title">Modifier un article</h1>

      <?php if (!empty($errors)): ?>
        <ul class="error-list">
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e, ENT_QUOTES, 'UTF-8') ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <label for="categorie_id">Catégorie</label>
          <select name="categorie_id" id="categorie_id">
            <option value="">-- Choisir une catégorie --</option>
            <?php foreach ($categories as $c): ?>
              <option value="<?= (int)$c['id_categorie'] ?>" <?= ($currentCategorieId == $c['id_categorie']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['libelle'], ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="titre">Titre</label>
          <input type="text" id="titre" name="titre" value="<?= htmlspecialchars($titre ?? $a['titre'], ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
          <label for="introduction">Introduction</label>
          <input type="text" id="introduction" name="introduction" value="<?= htmlspecialchars($introduction ?? ($a['introduction'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
          <label for="contenu">Contenu</label>
          <textarea id="contenu" name="contenu"><?= htmlspecialchars($contenu ?? ($a['contenu'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-group">
          <label>Image actuelle</label>
          <?php if (!empty($a['image'])): ?>
            <div>
              <img src="<?= htmlspecialchars($a['image'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($a['alt'] ?? '', ENT_QUOTES, 'UTF-8') ?>" width="150">
            </div>
          <?php else: ?>
            <div>Aucune image</div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label for="image">Nouvelle image (optionnel)</label>
          <input type="file" id="image" name="image" accept="image/*">
        </div>

        <div class="form-group">
          <label for="alt">Description de l'image (alt)</label>
          <input type="text" id="alt" name="alt" value="<?= htmlspecialchars($alt ?? ($a['alt'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
          <label for="creation">Date/heure de création</label>
          <input type="datetime-local" id="creation" name="creation" value="<?= htmlspecialchars($creationInput !== '' ? $creationInput : $creationValue, ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
          <label for="journaliste_id">Journaliste</label>
          <select name="journaliste_id" id="journaliste_id">
            <option value="">-- Choisir un journaliste --</option>
            <?php foreach ($journalistes as $j): ?>
              <option value="<?= (int)$j['id_journaliste'] ?>" <?= ($currentJournalisteId == $j['id_journaliste']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($j['nom'], ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit">Modifier</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>