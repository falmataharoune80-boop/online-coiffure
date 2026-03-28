<?php
session_start();
if (!isset($_SESSION['admin_connecte'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';

// Confirmer un RDV
if (isset($_GET['confirmer'])) {
    $pdo->prepare("UPDATE rendez_vous SET statut='confirmé' WHERE id=?")->execute([(int)$_GET['confirmer']]);
    header('Location: rendez_vous.php'); exit;
}

// Annuler un RDV
if (isset($_GET['annuler'])) {
    $pdo->prepare("UPDATE rendez_vous SET statut='annulé' WHERE id=?")->execute([(int)$_GET['annuler']]);
    header('Location: rendez_vous.php'); exit;
}

// Supprimer un RDV
if (isset($_GET['supprimer'])) {
    $pdo->prepare("DELETE FROM rendez_vous WHERE id=?")->execute([(int)$_GET['supprimer']]);
    header('Location: rendez_vous.php'); exit;
}

$rdvs = $pdo->query("
    SELECT r.*, c.nom, c.prenom, c.telephone, c.email, s.nom AS service_nom, s.prix
    FROM rendez_vous r
    JOIN clients c ON r.client_id = c.id
    JOIN services s ON r.service_id = s.id
    ORDER BY r.date_rdv DESC, r.heure_rdv ASC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rendez-vous — Admin GloriHair</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body{background:#f0f2f5;}
        .admin-navbar{background:#1a1a2e;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;}
        .admin-navbar .logo{color:#e91e8c;font-size:1.5rem;font-weight:700;}
        .admin-navbar nav a{color:#ccc;margin-left:20px;font-size:0.95rem;}
        .admin-navbar nav a:hover{color:#e91e8c;}
        .admin-content{padding:30px;max-width:1200px;margin:0 auto;}
        .card{background:#fff;border-radius:15px;padding:25px;box-shadow:0 2px 15px rgba(0,0,0,0.07);}
        table{width:100%;border-collapse:collapse;}
        th{background:#f9f9f9;padding:12px 15px;text-align:left;color:#555;font-size:0.85rem;text-transform:uppercase;}
        td{padding:12px 15px;border-bottom:1px solid #f0f0f0;font-size:0.9rem;}
        tr:hover td{background:#fef9fc;}
        .badge{padding:4px 12px;border-radius:20px;font-size:0.8rem;font-weight:600;}
        .badge-attente{background:#fff3cd;color:#856404;}
        .badge-confirme{background:#d1e7dd;color:#0f5132;}
        .badge-annule{background:#f8d7da;color:#842029;}
    </style>
</head>
<body>
<div class="admin-navbar">
    <div class="logo">✂ GloriHair Admin</div>
    <nav>
        <a href="index.php">🏠 Dashboard</a>
        <a href="rendez_vous.php">📅 Rendez-vous</a>
        <a href="clients.php">👥 Clients</a>
        <a href="services.php">✂ Services</a>
        <a href="galerie.php">🖼 Galerie</a>
        <a href="logout.php">🚪 Déconnexion</a>
    </nav>
</div>

<div class="admin-content">
    <h2 style="color:#1a1a2e;margin-bottom:25px;">📅 Tous les rendez-vous</h2>
    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Client</th><th>Contact</th>
                    <th>Service</th><th>Prix</th>
                    <th>Date</th><th>Heure</th>
                    <th>Statut</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($rdvs as $r): ?>
            <tr>
                <td><?= $r['id'] ?></td>
                <td><strong><?= htmlspecialchars($r['prenom'].' '.$r['nom']) ?></strong></td>
                <td><?= htmlspecialchars($r['telephone']) ?><br>
                    <small style="color:#888;"><?= htmlspecialchars($r['email']) ?></small></td>
                <td><?= htmlspecialchars($r['service_nom']) ?></td>
                <td style="color:#e91e8c;font-weight:600;"><?= number_format($r['prix'],0,',',' ') ?> F</td>
                <td><?= date('d/m/Y', strtotime($r['date_rdv'])) ?></td>
                <td><?= substr($r['heure_rdv'],0,5) ?></td>
                <td>
                    <?php
                    $badges=['en_attente'=>'badge-attente','confirmé'=>'badge-confirme','annulé'=>'badge-annule'];
                    echo '<span class="badge '.($badges[$r['statut']]??'badge-attente').'">'.ucfirst($r['statut']).'</span>';
                    ?>
                </td>
                <td>
                    <a href="?confirmer=<?= $r['id'] ?>" style="color:#0f5132;">✅</a> &nbsp;
                    <a href="?annuler=<?= $r['id'] ?>"   style="color:#856404;">⏸</a> &nbsp;
                    <a href="?supprimer=<?= $r['id'] ?>" style="color:#842029;"
                       onclick="return confirm('Supprimer ce RDV ?')">🗑</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>