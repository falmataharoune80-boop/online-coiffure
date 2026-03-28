<?php
require_once '../includes/db.php';
$stmt = $pdo->query("SELECT * FROM galerie ORDER BY created_at DESC");
$photos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galerie — GloriHair</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .galerie-item { position: relative; overflow: hidden; border-radius: 12px; }
        .galerie-item img { width:100%; height:360px; object-fit:cover; transition: transform 0.4s; }
        .galerie-item:hover img { transform: scale(1.08); }
        .galerie-item .overlay {
            position: absolute; bottom: 0; left: 0; right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            color: #fff; padding: 15px; opacity: 0; transition: opacity 0.3s;
        }
        .galerie-item:hover .overlay { opacity: 1; }
        .empty-galerie {
            text-align: center; padding: 60px 20px;
            color: #888; grid-column: 1/-1;
        }
        .empty-galerie .icon { font-size: 4rem; margin-bottom: 20px; }
    </style>
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

<section>
    <h2 class="section-title">Notre Galerie</h2>
    <p class="section-subtitle">Nos plus belles réalisations</p>
    <div class="galerie-grid">
        <?php if (count($photos) > 0): ?>
            <?php foreach ($photos as $photo): ?>
            <div class="galerie-item">
                <img src="../images/<?= htmlspecialchars($photo['image']) ?>"
                     alt="<?= htmlspecialchars($photo['titre']) ?>">
                <div class="overlay">
                    <strong><?= htmlspecialchars($photo['titre']) ?></strong><br>
                    <small><?= htmlspecialchars($photo['categorie']) ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty-galerie">
                <div class="icon">🖼️</div>
                <h3>Galerie en cours de constitution</h3>
                <p>Revenez bientôt pour voir nos réalisations !</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<footer>
    <p>© 2026 <span>Ahlam-Coiffure</span> — Tous droits réservés | Ngaoundere, Cameroun</p>
</footer>

</body>
</html>