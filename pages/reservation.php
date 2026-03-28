<?php
require_once '../includes/db.php';

$message = '';
$type    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = trim($_POST['nom']);
    $prenom     = trim($_POST['prenom']);
    $email      = trim($_POST['email']);
    $telephone  = trim($_POST['telephone']);
    $service_id = (int)$_POST['service_id'];
    $date_rdv   = $_POST['date_rdv'];
    $heure_rdv  = $_POST['heure_rdv'];
    $msg        = trim($_POST['message']);

    if ($nom && $prenom && $email && $service_id && $date_rdv && $heure_rdv) {
        // Vérifier si le client existe déjà
        $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        $client = $stmt->fetch();

        if (!$client) {
            // Créer le client avec mot de passe temporaire
            $mdp = password_hash('client123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO clients (nom, prenom, email, telephone, mot_de_passe) VALUES (?,?,?,?,?)");
            $stmt->execute([$nom, $prenom, $email, $telephone, $mdp]);
            $client_id = $pdo->lastInsertId();
        } else {
            $client_id = $client['id'];
        }

        // Enregistrer le rendez-vous
        $stmt = $pdo->prepare("INSERT INTO rendez_vous (client_id, service_id, date_rdv, heure_rdv, message) VALUES (?,?,?,?,?)");
        $stmt->execute([$client_id, $service_id, $date_rdv, $heure_rdv, $msg]);

        $message = "✅ Votre rendez-vous a été enregistré avec succès ! Nous vous contacterons pour confirmation.";
        $type    = 'success';
    } else {
        $message = "❌ Veuillez remplir tous les champs obligatoires.";
        $type    = 'error';
    }
}

$services = $pdo->query("SELECT * FROM services")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation — Ahlam-Coiffurer</title>
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

<section>
    <h2 class="section-title">Prendre Rendez-vous</h2>
    <p class="section-subtitle">Remplissez le formulaire ci-dessous</p>

    <div class="form-container">
        <?php if ($message): ?>
            <div class="alert alert-<?= $type ?>"><?= $message ?></div>
        <?php endif; ?>

        <form method="POST">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label>Nom *</label>
                    <input type="text" name="nom" placeholder="Votre nom" required>
                </div>
                <div class="form-group">
                    <label>Prénom *</label>
                    <input type="text" name="prenom" placeholder="Votre prénom" required>
                </div>
            </div>
            <div class="form-group">
                <label>Email *</label>
                <input type="email" name="email" placeholder="votre@email.com" required>
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="tel" name="telephone" placeholder="6XX XXX XXX">
            </div>
            <div class="form-group">
                <label>Service souhaité *</label>
                <select name="service_id" required>
                    <option value="">-- Choisir un service --</option>
                    <?php foreach ($services as $s): ?>
                    <option value="<?= $s['id'] ?>">
                        <?= htmlspecialchars($s['nom']) ?> — <?= number_format($s['prix'],0,',',' ') ?> FCFA
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                <div class="form-group">
                    <label>Date *</label>
                    <input type="date" name="date_rdv"
                           min="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="form-group">
                    <label>Heure *</label>
                    <input type="time" name="heure_rdv"
                           min="08:00" max="18:00" required>
                </div>
            </div>
            <div class="form-group">
                <label>Message (optionnel)</label>
                <textarea name="message" rows="3"
                          placeholder="Précisions sur votre coiffure souhaitée..."></textarea>
            </div>
            <button type="submit" class="btn" style="width:100%; font-size:1rem;">
                📅 Confirmer le rendez-vous
            </button>
        </form>
    </div>
</section>

<footer>
    <p>© 2026 <span>Ahlam-Coiffure</span> — Tous droits réservés | Ngaoundere, Cameroun</p>
</footer>

</body>
</html>