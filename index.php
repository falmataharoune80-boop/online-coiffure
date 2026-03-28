<?php
require_once 'includes/db.php';

// Récupérer les services depuis la base de données
$stmt = $pdo->query("SELECT * FROM services LIMIT 4");
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salon de Coiffure — Beauté & Élégance</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">✂ Ahlam-Coiffure</div>
    <ul>
        <li><a href="index.php">Accueil</a></li>
        <li><a href="pages/services.php">Services</a></li>
        <li><a href="pages/galerie.php">Galerie</a></li>
        <li><a href="pages/reservation.php">Réservation</a></li>
        <li><a href="pages/contact.php">Contact</a></li>
    </ul>
</nav>

<!-- HERO -->
<section class="hero">
    <h1>✨ Sublimez Votre Beauté avec Ahlam</h1>
    <p>Bienvenue chez Ahlam-Coiffure — le salon de coiffure dédié à la femme africaine moderne. Tresses, défrisage, coloration et bien plus.</p>
    <div>
        <a href="pages/reservation.php" class="btn">Prendre RDV</a>
        <a href="pages/services.php" class="btn btn-outline">Nos Services</a>
    </div>
</section>

<!-- SERVICES -->
<section style="background: #f9f9f9;">
    <h2 class="section-title">Nos Services</h2>
    <p class="section-subtitle">Des soins adaptés à tous les types de cheveux</p>
    <div class="services-grid">
        <?php foreach ($services as $service): ?>
        <div class="service-card">
            <div class="icon">💇‍♀️</div>
            <h3><?= htmlspecialchars($service['nom']) ?></h3>
            <p><?= htmlspecialchars($service['description']) ?></p>
            <div class="prix"><?= number_format($service['prix'], 0, ',', ' ') ?> FCFA</div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- POURQUOI NOUS -->
<section>
    <h2 class="section-title">Pourquoi Nous Choisir ?</h2>
    <p class="section-subtitle">Une expérience unique à chaque visite</p>
    <div class="services-grid">
        <div class="service-card">
            <div class="icon">🏆</div>
            <h3>Expérience</h3>
            <p>Plus de 10 ans dans la coiffure africaine et moderne.</p>
        </div>
        <div class="service-card">
            <div class="icon">🌿</div>
            <h3>Produits naturels</h3>
            <p>Nous utilisons uniquement des produits sains pour vos cheveux.</p>
        </div>
        <div class="service-card">
            <div class="icon">📅</div>
            <h3>Réservation facile</h3>
            <p>Prenez rendez-vous en ligne en 2 minutes.</p>
        </div>
        <div class="service-card">
            <div class="icon">💖</div>
            <h3>Clientèle satisfaite</h3>
            <p>Plus de 500 clientes fidèles nous font confiance.</p>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <p>© 2026 <span>Ahlam-Coiffure</span> — Tous droits réservés | Ngaoundere, Cameroun</p>
</footer>

</body>
</html>