<?php
session_start();

include('../config/db.php');

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Recherche de l'utilisateur en base de données
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && $user['mdp'] === $password) {
        // Connexion réussie
        $_SESSION['user'] = [
            'id' => $user['id_utilisateur'] ?? null,
            'nom' => $user['nom'] ?? null,
            'email' => $user['email'] ?? null,
        ];

        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$canonical = $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ($_SERVER['REQUEST_URI'] ?? '/backoffice/login.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion backoffice - SEO Journal</title>
  <meta name="description" content="Page de connexion sécurisée au backoffice du journal pour gérer les articles et le contenu.">
  <!-- <meta name="robots" content="noindex, nofollow"> -->
  <link rel="canonical" href="<?= htmlspecialchars($canonical, ENT_QUOTES, 'UTF-8') ?>">
  <link rel="stylesheet" href="backoffice.css">
</head>
<body>
  <div class="page page--narrow login-page">
    <div class="login-card form-wrapper">
      <h1 class="form-title">Connexion au backoffice</h1>

      <?php if ($error): ?>
        <p class="error-message"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" id="email" name="email" placeholder="Email" value="<?= htmlspecialchars($email ?? 'admin@gmail.com', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-group">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Mot de passe" value="<?= htmlspecialchars($password ?? 'admin123', ENT_QUOTES, 'UTF-8') ?>">
        </div>

        <div class="form-actions">
          <button type="submit">Login</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>