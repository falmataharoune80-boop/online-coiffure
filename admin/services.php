<?php
session_start();
if (!isset($_SESSION['admin_connecte'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';

$message = '';
$type    = '';

// Ajouter un service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter') {
    $nom         = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix        = (float)$_POST['prix'];
    $duree       = (int)$_POST['duree'];

    if ($nom && $prix > 0) {
        $pdo->prepare("INSERT INTO services (nom, description, prix, duree) VALUES (?,?,?,?)")
            ->execute([$nom, $description, $prix, $duree]);
        $message = "✅ Service ajouté avec succès !";
        $type    = 'success';
    } else {
        $message = "❌ Veuillez remplir le nom et le prix.";
        $type    = 'error';
    }
}

// Modifier un service
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifier') {
    $id          = (int)$_POST['id'];
    $nom         = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix        = (float)$_POST['prix'];
    $duree       = (int)$_POST['duree'];

    $pdo->prepare("UPDATE services SET nom=?, description=?, prix=?, duree=? WHERE id=?")
        ->execute([$nom, $description, $prix, $duree, $id]);
    $message = "✅ Service modifié avec succès !";
    $type    = 'success';
}

// Supprimer un service
if (isset($_GET['supprimer'])) {
    $id = (int)$_GET['supprimer'];
    $pdo->prepare("DELETE FROM services WHERE id=?")->execute([$id]);
    header('Location: services.php'); exit;
}

// Charger service à modifier
$service_edit = null;
if (isset($_GET['modifier'])) {
    $stmt = $pdo->prepare("SELECT * FROM services WHERE id=?");
    $stmt->execute([(int)$_GET['modifier']]);
    $service_edit = $stmt->fetch();
}

$services = $pdo->query("SELECT * FROM services ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Services — Admin GloriHair</title>
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
        .prix-badge{color:#e91e8c;font-weight:700;font-size:1rem;}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:15px;}
        .form-edit{background:#fff8fc;border:2px solid #e91e8c;border-radius:15px;padding:25px;margin-bottom:25px;}
        .form-edit h3{color:#e91e8c;margin-bottom:20px;}
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
    <h2 style="color:#1a1a2e;margin-bottom:25px;">✂ Gestion des Services</h2>

    <?php if ($message): ?>
        <div class="alert alert-<?= $type ?>"><?= $message ?></div>
    <?php endif; ?>

    <!-- Formulaire modifier (si actif) -->
    <?php if ($service_edit): ?>
    <div class="form-edit">
        <h3>✏️ Modifier le service : <?= htmlspecialchars($service_edit['nom']) ?></h3>
        <form method="POST">
            <input type="hidden" name="action" value="modifier">
            <input type="hidden" name="id" value="<?= $service_edit['id'] ?>">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nom du service *</label>
                    <input type="text" name="nom"
                           value="<?= htmlspecialchars($service_edit['nom']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Prix (FCFA) *</label>
                    <input type="number" name="prix"
                           value="<?= $service_edit['prix'] ?>" required>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="2"><?= htmlspecialchars($service_edit['description']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Durée (minutes)</label>
                <input type="number" name="duree" value="<?= $service_edit['duree'] ?>">
            </div>
            <div style="display:flex;gap:10px;">
                <button type="submit" class="btn">💾 Enregistrer</button>
                <a href="services.php" class="btn btn-outline">Annuler</a>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Formulaire ajouter -->
    <div class="card">
        <h3>➕ Ajouter un nouveau service</h3>
        <form method="POST">
            <input type="hidden" name="action" value="ajouter">
            <div class="form-grid">
                <div class="form-group">
                    <label>Nom du service *</label>
                    <input type="text" name="nom" placeholder="Ex: Tresse nattes" required>
                </div>
                <div class="form-group">
                    <label>Prix (FCFA) *</label>
                    <input type="number" name="prix" placeholder="Ex: 8000" required>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="2"
                          placeholder="Décrivez brièvement le service..."></textarea>
            </div>
            <div class="form-group">
                <label>Durée (minutes)</label>
                <input type="number" name="duree" placeholder="Ex: 90">
            </div>
            <button type="submit" class="btn">➕ Ajouter le service</button>
        </form>
    </div>

    <!-- Liste des services -->
    <div class="card">
        <h3>📋 Services existants (<?= count($services) ?>)</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nom du service</th>
                    <th>Description</th>
                    <th>Prix</th>
                    <th>Durée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($services as $s): ?>
            <tr>
                <td><?= $s['id'] ?></td>
                <td><strong><?= htmlspecialchars($s['nom']) ?></strong></td>
                <td style="color:#666;max-width:250px;">
                    <?= htmlspecialchars(substr($s['description'], 0, 60)) ?>
                    <?= strlen($s['description']) > 60 ? '...' : '' ?>
                </td>
                <td class="prix-badge"><?= number_format($s['prix'],0,',',' ') ?> FCFA</td>
                <td>⏱ <?= $s['duree'] ?> min</td>
                <td>
                    <a href="?modifier=<?= $s['id'] ?>"
                       style="color:#0f5132;font-size:0.85rem;">✏️ Modifier</a> &nbsp;|&nbsp;
                    <a href="?supprimer=<?= $s['id'] ?>"
                       onclick="return confirm('Supprimer ce service ?')"
                       style="color:#842029;font-size:0.85rem;">🗑 Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>