<?php
include('../config/db.php');

// Récupération des catégories
$stmt = $pdo->query("SELECT id_categorie, libelle FROM categorie ORDER BY libelle");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des journalistes actifs
$stmt = $pdo->query("SELECT id_journaliste, nom FROM journaliste WHERE actif = TRUE ORDER BY nom");
$journalistes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$errors = [];

$categorieId   = null;
$titre         = '';
$introduction  = '';
$contenu       = '';
$alt           = '';
$creationInput = '';
$journalisteId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $categorieId   = $_POST['categorie_id'] ?? null;
  $titre         = $_POST['titre'] ?? '';
  $introduction  = $_POST['introduction'] ?? '';
  $contenu       = $_POST['contenu'] ?? '';
  $alt           = $_POST['alt'] ?? '';
  $creationInput = $_POST['creation'] ?? '';
  $journalisteId = $_POST['journaliste_id'] ?? null;
  $imagePath     = null;

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
        // Conversion du format datetime-local (YYYY-MM-DDTHH:MM) vers un format SQL
        $creation = str_replace('T', ' ', $creationInput) . ':00';
    } else {
        $creation = date('Y-m-d H:i:s');
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

      // La colonne image est VARCHAR(100) en base : on tronque si nécessaire
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
        // Insertion de l'article
        $stmt = $pdo->prepare(
            "INSERT INTO articles (titre, introduction, contenu, image, alt, creation, id_categorie)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $titre,
            $introduction,
            $contenu,
          $imagePath,
            $alt,
            $creation,
            $categorieId,
        ]);

        // Récupération de l'ID de l'article inséré
        $articleId = $pdo->lastInsertId('articles_id_articles_seq');

        // Liaison avec le journaliste choisi
        if (!empty($journalisteId)) {
            $stmt = $pdo->prepare("INSERT INTO journaliste_article (id_articles, id_journaliste) VALUES (?, ?)");
            $stmt->execute([$articleId, $journalisteId]);
        }

        header('Location: dashboard.html');
        exit;
    }
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$canonical = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/backoffice/ajouter_article.html');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter un article - Backoffice</title>
  <meta name="description" content="Formulaire du backoffice pour créer un nouvel article avec titre, introduction, contenu, image et journaliste.">
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
      <h1 class="form-title">Ajouter un article</h1>

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
              <option value="<?= (int)$c['id_categorie'] ?>" <?= (isset($categorieId) && (int)$categorieId === (int)$c['id_categorie']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['libelle'], ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-group">
          <label for="titre">Titre</label>
          <input type="text" id="titre" name="titre" placeholder="Titre" value="<?= htmlspecialchars($titre ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
          <label for="introduction">Introduction</label>
          <input type="text" id="introduction" name="introduction" placeholder="Introduction" maxlength="100" value="<?= htmlspecialchars($introduction ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
          <label for="contenu">Contenu</label>
          <textarea id="contenu" name="contenu"><?= htmlspecialchars($contenu ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>

        <div class="form-group">
          <label for="image">Image</label>
          <input type="file" id="image" name="image" accept="image/*">
        </div>

        <div class="form-group">
          <label for="alt">Description de l'image (alt)</label>
          <input type="text" id="alt" name="alt" placeholder="Texte alternatif de l'image" value="<?= htmlspecialchars($alt ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
          <label for="creation">Date/heure de création</label>
          <input type="datetime-local" id="creation" name="creation" value="<?= htmlspecialchars($creationInput ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
          <label for="journaliste_id">Journaliste</label>
          <select name="journaliste_id" id="journaliste_id">
            <option value="">-- Choisir un journaliste --</option>
            <?php foreach ($journalistes as $j): ?>
              <option value="<?= (int)$j['id_journaliste'] ?>" <?= (isset($journalisteId) && (int)$journalisteId === (int)$j['id_journaliste']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($j['nom'], ENT_QUOTES, 'UTF-8') ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit">Ajouter</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>