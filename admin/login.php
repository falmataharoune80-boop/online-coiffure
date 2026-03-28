<?php
session_start();
require_once '../includes/db.php';

$erreur = '';

// Identifiants administrateur
define('ADMIN_USERNAME', 'falmata');
define('ADMIN_PASSWORD', 'ahlam.33');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_connecte'] = true;
        $_SESSION['admin_nom']      = $username;
        header('Location: index.php');
        exit;
    } else {
        $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin — Ahlam Coiffure</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background: #1a1a2e;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-box {
            background: #fff;
            padding: 50px 40px;
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
        }
        .login-box .logo {
            font-size: 2rem;
            color: #e91e8c;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .login-box p {
            color: #888;
            margin-bottom: 30px;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>

<div class="login-box">
    <div class="logo">✂ Ahlam Coiffure</div>
    <p>Espace Administrateur</p>

    <?php if ($erreur): ?>
        <div class="alert alert-error"><?= $erreur ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group" style="text-align:left;">
            <label>Nom d'utilisateur</label>
            <input type="text" name="username" placeholder="falmata" required>
        </div>
        <div class="form-group" style="text-align:left;">
            <label>Mot de passe</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn" style="width:100%; margin-top:10px;">
            🔐 Se connecter
        </button>
    </form>
    <br>
    <a href="../index.php" style="color:#888; font-size:0.9rem;">← Retour au site</a>
</div>

</body>
</html>