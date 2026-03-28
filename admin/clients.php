<?php
session_start();
if (!isset($_SESSION['admin_connecte'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';

// Supprimer un client
if (isset($_GET['supprimer'])) {
    $id = (int)$_GET['supprimer'];
    $pdo->prepare("DELETE FROM rendez_vous WHERE client_id=?")->execute([$id]);
    $pdo->prepare("DELETE FROM clients WHERE id=?")->execute([$id]);
    header('Location: clients.php'); exit;
}

$clients = $pdo->query("
    SELECT c.*, COUNT(r.id) AS nb_rdv
    FROM clients c
    LEFT JOIN rendez_vous r ON c.id = r.client_id
    GROUP BY c.id
    ORDER BY c.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Clients — Admin GloriHair</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body{background:#f0f2f5;}
        .admin-navbar{background:#1a1a2e;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;}
        .admin-navbar .logo{color:#e91e8c;font-size:1.5rem;font-weight:700;}
        .admin-navbar nav a{color:#ccc;margin-left:20px;font-size:0.95rem;}
        .admin-navbar nav a:hover{color:#e91e8c;}
        .admin-content{padding:30px;max-width:1200px;margin:0 auto;}
        .card{background:#fff;border-radius:15px;padding:25px;box-shadow:0 2px 15px rgba(0,0,0,0.07);margin-bottom:25px;}
        .card h3{color:#1a1a2e;margin-bottom:20px;font-size:1.2rem;}
        table{width:100%;border-collapse:collapse;}
        th{background:#f9f9f9;padding:12px 15px;text-align:left;color:#555;font-size:0.85rem;text-transform:uppercase;letter-spacing:0.5px;}
        td{padding:12px 15px;border-bottom:1px solid #f0f0f0;font-size:0.9rem;}
        tr:last-child td{border-bottom:none;}
        tr:hover td{background:#fef9fc;}
        .avatar{width:38px;height:38px;border-radius:50%;background:#e91e8c;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1rem;}
        .badge-rdv{background:#fce4f3;color:#c2185b;padding:3px 10px;border-radius:20px;font-size:0.8rem;font-weight:600;}
        .search-bar{width:100%;padding:12px 15px;border:2px solid #eee;border-radius:10px;font-size:1rem;margin-bottom:20px;font-family:inherit;}
        .search-bar:focus{outline:none;border-color:#e91e8c;}
        .stat-mini{display:inline-block;background:#f9f9f9;border-radius:10px;padding:10px 20px;margin-right:15px;margin-bottom:20px;font-size:0.9rem;color:#555;}
        .stat-mini strong{color:#e91e8c;font-size:1.3rem;display:block;}
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
    <h2 style="color:#1a1a2e;margin-bottom:20px;">👥 Gestion des Clients</h2>

    <!-- Stats rapides -->
    <div>
        <div class="stat-mini">
            <strong><?= count($clients) ?></strong>
            Clients au total
        </div>
        <div class="stat-mini">
            <strong><?= $pdo->query("SELECT COUNT(*) FROM clients WHERE DATE(created_at)=CURDATE()")->fetchColumn() ?></strong>
            Nouveaux aujourd'hui
        </div>
    </div>

    <div class="card">
        <h3>📋 Liste des clients</h3>

        <!-- Recherche -->
        <input type="text" class="search-bar" id="recherche"
               placeholder="🔍 Rechercher par nom, email ou téléphone...">

        <table id="table-clients">
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Rendez-vous</th>
                    <th>Inscrit le</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($clients as $c): ?>
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div class="avatar"><?= strtoupper(substr($c['prenom'],0,1)) ?></div>
                        <div>
                            <strong><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></strong>
                        </div>
                    </div>
                </td>
                <td><?= htmlspecialchars($c['email']) ?></td>
                <td><?= htmlspecialchars($c['telephone'] ?: '—') ?></td>
                <td><span class="badge-rdv"><?= $c['nb_rdv'] ?> RDV</span></td>
                <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                <td>
                    <a href="?supprimer=<?= $c['id'] ?>"
                       onclick="return confirm('Supprimer ce client et ses RDV ?')"
                       style="color:#842029;font-size:0.85rem;">🗑 Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if (count($clients) === 0): ?>
            <p style="color:#888;text-align:center;padding:30px;">Aucun client enregistré.</p>
        <?php endif; ?>
    </div>
</div>

<script>
// Recherche en temps réel
document.getElementById('recherche').addEventListener('keyup', function() {
    const val = this.value.toLowerCase();
    document.querySelectorAll('#table-clients tbody tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(val) ? '' : 'none';
    });
});
</script>

</body>
</html>