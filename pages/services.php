<?php
require_once '../includes/db.php';
$stmt = $pdo->query("SELECT * FROM services");
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Services — Ahlam-Coiffure</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="navbar">
    <div class="logo">✂ Ahlam-Coiffure</div>
    <ul>
        <li><a href="../index.php">Accueil</a></li>
        <li><a href="services.php">Services</a></li>
        <li><a href="galerie.php">Galerie</a></li>
        <li><a href="reservation.php">Réservation</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
</nav>

<section style="background:#f9f9f9; padding-top:60px;">
    <h2 class="section-title">Tous Nos Services</h2>
    <p class="section-subtitle">Choisissez le soin qu'il vous faut</p>
    <div class="services-grid">
        <?php foreach ($services as $s): ?>
        <div class="service-card">
            <div class="icon">💇‍♀️</div>
            <h3><?= htmlspecialchars($s['nom']) ?></h3>
            <p><?= htmlspecialchars($s['description']) ?></p>
            <p style="color:#888; font-size:0.85rem;">⏱ <?= $s['duree'] ?> minutes</p>
            <div class="prix"><?= number_format($s['prix'], 0, ',', ' ') ?> FCFA</div>
            <br>
            <a href="reservation.php" class="btn" style="font-size:0.9rem; padding:10px 25px;">Réserver</a>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<footer>
    <p>© 2026 <span>Ahlam-Coiffure</span> — Tous droits réservés | Ngaoundere, Cameroun</p>
</footer>

</body>
</html>