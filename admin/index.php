<?php
session_start();
if (!isset($_SESSION['admin_connecte'])) {
    header('Location: login.php');
    exit;
}
require_once '../includes/db.php';

// Statistiques
$nb_clients    = $pdo->query("SELECT COUNT(*) FROM clients")->fetchColumn();
$nb_rdv        = $pdo->query("SELECT COUNT(*) FROM rendez_vous")->fetchColumn();
$nb_rdv_today  = $pdo->query("SELECT COUNT(*) FROM rendez_vous WHERE date_rdv = CURDATE()")->fetchColumn();
$nb_services   = $pdo->query("SELECT COUNT(*) FROM services")->fetchColumn();

// Derniers rendez-vous
$rdvs = $pdo->query("
    SELECT r.*, c.nom, c.prenom, c.telephone, s.nom AS service_nom
    FROM rendez_vous r
    JOIN clients c ON r.client_id = c.id
    JOIN services s ON r.service_id = s.id
    ORDER BY r.date_rdv DESC, r.heure_rdv ASC
    LIMIT 10
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord — Admin GloriHair</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { background: #f0f2f5; }
        .admin-navbar {
            background: #1a1a2e;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-navbar .logo { color: #e91e8c; font-size:1.5rem; font-weight:700; }
        .admin-navbar nav a {
            color: #ccc; margin-left: 20px;
            font-size: 0.95rem; transition: color 0.3s;
        }
        .admin-navbar nav a:hover { color: #e91e8c; }
        .admin-content { padding: 30px; max-width: 1200px; margin: 0 auto; }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 35px;
        }
        .stat-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 2px 15px rgba(0,0,0,0.07);
            border-left: 5px solid #e91e8c;
        }
        .stat-card .number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #e91e8c;
        }
        .stat-card .label { color: #666; font-size: 0.95rem; margin-top: 5px; }
        .card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.07);
            margin-bottom: 25px;
        }
        .card h3 { color: #1a1a2e; margin-bottom: 20px; font-size: 1.2rem; }
        table { width: 100%; border-collapse: collapse; }
        th {
            background: #f9f9f9; padding: 12px 15px;
            text-align: left; color: #555;
            font-size: 0.85rem; text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td { padding: 12px 15px; border-bottom: 1px solid #f0f0f0; font-size:0.95rem; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fef9fc; }
        .badge {
            padding: 4px 12px; border-radius: 20px;
            font-size: 0.8rem; font-weight: 600;
        }
        .badge-attente  { background: #fff3cd; color: #856404; }
        .badge-confirme { background: #d1e7dd; color: #0f5132; }
        .badge-annule   { background: #f8d7da; color: #842029; }
        .menu-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        .menu-link {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            color: #1a1a2e;
            font-weight: 600;
            font-size: 0.95rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .menu-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(233,30,140,0.15);
            color: #e91e8c;
        }
        .menu-link .icon { font-size: 1.8rem; display: block; margin-bottom: 8px; }
    </style>
</head>
<body>

<!-- NAVBAR ADMIN -->
<div class="admin-navbar">
    <div class="logo">✂ Ahlam-Coiffure Admin</div>
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

    <!-- Bonjour -->
    <h2 style="color:#1a1a2e; margin-bottom:25px;">
        👋 Bonjour, <?= htmlspecialchars($_SESSION['admin_nom']) ?> !
    </h2>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="number"><?= $nb_clients ?></div>
            <div class="label">👥 Clients enregistrés</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $nb_rdv ?></div>
            <div class="label">📅 Total rendez-vous</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $nb_rdv_today ?></div>
            <div class="label">🔔 RDV aujourd'hui</div>
        </div>
        <div class="stat-card">
            <div class="number"><?= $nb_services ?></div>
            <div class="label">✂ Services proposés</div>
        </div>
    </div>

    <!-- Menu rapide -->
    <div class="menu-links">
        <a href="rendez_vous.php" class="menu-link">
            <span class="icon">📅</span>Gérer RDV
        </a>
        <a href="clients.php" class="menu-link">
            <span class="icon">👥</span>Voir clients
        </a>
        <a href="services.php" class="menu-link">
            <span class="icon">✂</span>Gérer services
        </a>
        <a href="galerie.php" class="menu-link">
            <span class="icon">🖼</span>Gérer galerie
        </a>
        <a href="../index.php" class="menu-link" target="_blank">
            <span class="icon">🌐</span>Voir le site
        </a>
    </div>

    <!-- Derniers rendez-vous -->
    <div class="card">
        <h3>📅 Derniers rendez-vous</h3>
        <?php if (count($rdvs) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rdvs as $r): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($r['prenom'].' '.$r['nom']) ?></strong></td>
                    <td><?= htmlspecialchars($r['telephone']) ?></td>
                    <td><?= htmlspecialchars($r['service_nom']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['date_rdv'])) ?></td>
                    <td><?= substr($r['heure_rdv'], 0, 5) ?></td>
                    <td>
                        <?php
                        $badges = [
                            'en_attente' => 'badge-attente',
                            'confirmé'   => 'badge-confirme',
                            'annulé'     => 'badge-annule'
                        ];
                        $classe = $badges[$r['statut']] ?? 'badge-attente';
                        ?>
                        <span class="badge <?= $classe ?>"><?= ucfirst($r['statut']) ?></span>
                    </td>
                    <td>
                        <a href="rendez_vous.php?confirmer=<?= $r['id'] ?>"
                           style="color:#0f5132; font-size:0.85rem;">✅ Confirmer</a> |
                        <a href="rendez_vous.php?annuler=<?= $r['id'] ?>"
                           style="color:#842029; font-size:0.85rem;">❌ Annuler</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
            <p style="color:#888; text-align:center; padding:30px;">Aucun rendez-vous pour le moment.</p>
        <?php endif; ?>
    </div>

</div>
</body>
</html>