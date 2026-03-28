<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact — GloriHair</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .contact-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            max-width: 1000px;
            margin: 0 auto;
        }
        .contact-info h3 { color: #1a1a2e; margin-bottom: 25px; font-size:1.4rem; }
        .contact-item {
            display: flex; align-items: flex-start;
            gap: 15px; margin-bottom: 25px;
        }
        .contact-item .icon {
            font-size: 1.5rem; width: 45px; height: 45px;
            background: #fce4f3; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .contact-item p { color: #444; line-height: 1.6; }
        .contact-item strong { color: #1a1a2e; display: block; margin-bottom: 4px; }
        @media(max-width:768px){ .contact-grid{ grid-template-columns:1fr; } }
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
    <h2 class="section-title">Contactez-Nous</h2>
    <p class="section-subtitle">Nous sommes disponibles pour vous</p>

    <div class="contact-grid">
        <!-- Infos contact -->
        <div class="contact-info">
            <h3>Nos coordonnées</h3>
            <div class="contact-item">
                <div class="icon">📍</div>
                <p><strong>Adresse</strong>Ngaoundere, Cameroun<br>Quartier Beka-Bocom</p>
            </div>
            <div class="contact-item">
                <div class="icon">📞</div>
                <p><strong>Téléphone</strong>+237 692 773 799<br>+237 659 690 790</p>
            </div>
            <div class="contact-item">
                <div class="icon">📧</div>
                <p><strong>Email</strong>falmataharoune80@gmail.com</p>
            </div>
            <div class="contact-item">
                <div class="icon">🕐</div>
                <p><strong>Horaires d'ouverture</strong>
                Lundi – Samedi : 8h00 – 18h00<br>
                Dimanche : 10h00 – 15h00</p>
            </div>
        </div>

        <!-- Formulaire contact -->
        <div class="form-container" style="box-shadow:none; padding:0;">
            <h3 style="color:#1a1a2e; margin-bottom:25px; font-size:1.4rem;">Envoyez un message</h3>
            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" placeholder="Votre nom">
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="votre@email.com">
            </div>
            <div class="form-group">
                <label>Sujet</label>
                <input type="text" placeholder="Objet de votre message">
            </div>
            <div class="form-group">
                <label>Message</label>
                <textarea rows="5" placeholder="Votre message..."></textarea>
            </div>
            <button class="btn" style="width:100%;">📨 Envoyer le message</button>
        </div>
    </div>
</section>

<footer>
    <p>© 2026 <span>Ahlam-Coiffure</span> — Tous droits réservés | Ngaoundere, Cameroun</p>
</footer>

</body>
</html>