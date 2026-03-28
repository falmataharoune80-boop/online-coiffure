<?php
session_start();
if (!isset($_SESSION['admin_connecte'])) { header('Location: login.php'); exit; }
require_once '../includes/db.php';

$message = '';

// Upload photo
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $titre     = trim($_POST['titre']);
    $categorie = trim($_POST['categorie']);
    $file      = $_FILES['image'];

    $extensions_ok = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $extensions_ok) && $file['size'] < 5000000) {
        $nouveau_nom = uniqid('photo_').'.'.$ext;
        $destination = '../images/'.$nouveau_nom;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $pdo->prepare("INSERT INTO galerie (titre, image, categorie) VALUES (?,?,?)")
                ->execute([$titre, $nouveau_nom, $categorie]);
            $message = "✅ Photo ajoutée avec succès !";
        }
    } else {
        $message = "❌ Fichier invalide. JPG/PNG/WEBP, max 5MB.";
    }
}

// Supprimer photo
if (isset($_GET['supprimer'])) {
    $id    = (int)$_GET['supprimer'];
    $photo = $pdo->prepare("SELECT image FROM galerie WHERE id=?");
    $photo->execute([$id]);
    $p = $photo->fetch();
    if ($p) {
        @unlink('../images/'.$p['image']);
        $pdo->prepare("DELETE FROM galerie WHERE id=?")->execute([$id]);
    }
    header('Location: galerie.php'); exit;
}

$photos = $pdo->query("SELECT * FROM galerie ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Galerie — Admin GloriHair</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body{background:#f0f2f5;}
        .admin-navbar{background:#1a1a2e;padding:15px 30px;display:flex;justify-content:space-between;align-items:center;}
        .admin-navbar .logo{color:#e91e8c;font-size:1.5rem;font-weight:700;}
        .admin-navbar nav a{color:#ccc;margin-left:20px;font-size:0.95rem;}
        .admin-navbar nav a:hover{color:#e91e8c;}
        .admin-content{padding:30px;max-width:1200px;margin:0 auto;}
        .card{background:#fff;border-radius:15px;padding:25px;box-shadow:0 2px 15px rgba(0,0,0,0.07);margin-bottom:25px;}
        .photos-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:20px;margin-top:20px;}
        .photo-item{position:relative;border-radius:10px;overflow:hidden;box-shadow:0 2px 10px rgba(0,0,0,0.1);}
        .photo-item img{width:100%;height:180px;object-fit:cover;display:block;}
        .photo-item .info{padding:10px;background:#fff;}
        .photo-item .info strong{font-size:0.9rem;color:#1a1a2e;}
        .photo-item .info small{color:#888;display:block;}
        .photo-item .suppr{
            position:absolute;top:8px;right:8px;
            background:rgba(220,53,69,0.85);color:#fff;
            border:none;border-radius:50%;width:28px;height:28px;
            cursor:pointer;font-size:0.8rem;
        }
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
    <h2 style="color:#1a1a2e;margin-bottom:25px;">🖼 Gérer la Galerie</h2>

    <!-- Formulaire upload -->
    <div class="card">
        <h3 style="margin-bottom:20px;">➕ Ajouter une photo</h3>
        <?php if ($message): ?>
            <div class="alert alert-<?= str_contains($message,'✅')?'success':'error' ?>"><?= $message ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:15px;align-items:end;">
                <div class="form-group" style="margin:0;">
                    <label>Titre</label>
                    <input type="text" name="titre" placeholder="Ex: Tresse nattes" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Catégorie</label>
                    <select name="categorie">
                        <option>Tresses</option>
                        <option>Défrisage</option>
                        <option>Coloration</option>
                        <option>Coupe</option>
                        <option>Soin</option>
                        <option>Autre</option>
                    </select>
                </div>
                <div class="form-group" style="margin:0;">
                    <label>Photo (JPG/PNG, max 5MB)</label>
                    <input type="file" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="btn" style="height:46px;">📤 Uploader</button>
            </div>
        </form>
    </div>

    <!-- Grille photos -->
    <div class="card">
        <h3>📸 Photos publiées (<?= count($photos) ?>)</h3>
        <?php if (count($photos) > 0): ?>
        <div class="photos-grid">
            <?php foreach ($photos as $p): ?>
            <div class="photo-item">
                <img src="../images/<?= htmlspecialchars($p['image']) ?>"
                     alt="<?= htmlspecialchars($p['titre']) ?>">
                <a href="?supprimer=<?= $p['id'] ?>"
                   onclick="return confirm('Supprimer cette photo ?')">
                    <button class="suppr">✕</button>
                </a>
                <div class="info">
                    <strong><?= htmlspecialchars($p['titre']) ?></strong>
                    <small><?= htmlspecialchars($p['categorie']) ?></small>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p style="color:#888;text-align:center;padding:30px;">Aucune photo pour le moment.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>