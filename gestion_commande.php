<?php
session_start();
require_once 'auth.php'; // Fichier d'authentification

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['restaurateur_id'])) {
    header("Location: login.php");
    exit();
}

// Connexion à la base de données
try {
    $pdo = new PDO("mysql:host=localhost;dbname=mon_site_de_repas", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Mettre à jour le statut d'une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $commande_id = $_POST['commande_id'];
    $nouveau_statut = $_POST['nouveau_statut'];
    
    $stmt = $pdo->prepare("UPDATE commandes SET status = ? WHERE id = ?");
    $stmt->execute([$nouveau_statut, $commande_id]);
    
    $_SESSION['message'] = "Statut de la commande #$commande_id mis à jour !";
    header("Location: gestion_commandes.php");
    exit();
}

// Récupérer les commandes
$stmt = $pdo->prepare("
    SELECT c.*, p.nom AS plat_nom 
    FROM commandes c
    JOIN plats p ON c.plat_id = p.id
    WHERE c.restaurateur_id = ?
    ORDER BY c.date_commande DESC
");
$stmt->execute([$_SESSION['restaurateur_id']]);
$commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - Mon Site de Repas</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .commandes-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .commande-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .statut {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .pending { background: #ffd700; color: #000; }
        .preparing { background: #2196F3; color: white; }
        .ready { background: #4CAF50; color: white; }
        .delivered { background: #9E9E9E; color: white; }

        .btn-group {
            margin-top: 15px;
            display: flex;
            gap: 10px;
        }

        select, button {
            padding: 8px 15px;
            border-radius: 5px;
            border: 1px solid #ddd;
            cursor: pointer;
        }

        button {
            background: #075E54;
            color: white;
            border: none;
        }

        .message {
            padding: 15px;
            background: #d4edda;
            color: #155724;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Gestion des Commandes</h1>
        
        <?php if(isset($_SESSION['message'])): ?>
            <div class="message"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <div class="commandes-grid">
            <?php foreach ($commandes as $commande): ?>
                <div class="commande-card">
                    <h3>Commande #<?= htmlspecialchars($commande['id']) ?></h3>
                    <p><strong>Client:</strong> <?= htmlspecialchars($commande['nom_client']) ?></p>
                    <p><strong>Plat:</strong> <?= htmlspecialchars($commande['plat_nom']) ?> (x<?= htmlspecialchars($commande['quantite']) ?>)</p>
                    <p><strong>Total:</strong> <?= number_format($commande['quantite'] * $commande['prix'], 0, ',', ' ') ?> XOF</p>
                    <p><strong>Livraison:</strong> <?= date('d/m/Y H:i', strtotime($commande['date_livraison'] . ' ' . $commande['heure_livraison'])) ?></p>
                    
                    <form method="POST">
                        <input type="hidden" name="commande_id" value="<?= $commande['id'] ?>">
                        
                        <div class="statut <?= $commande['status'] ?>">
                            <?= strtoupper($commande['status']) ?>
                        </div>

                        <div class="btn-group">
                            <select name="nouveau_statut">
                                <option value="pending" <?= $commande['status'] === 'pending' ? 'selected' : '' ?>>En attente</option>
                                <option value="preparing" <?= $commande['status'] === 'preparing' ? 'selected' : '' ?>>En préparation</option>
                                <option value="ready" <?= $commande['status'] === 'ready' ? 'selected' : '' ?>>Prêt</option>
                                <option value="delivered" <?= $commande['status'] === 'delivered' ? 'selected' : '' ?>>Livré</option>
                            </select>
                            <button type="submit" name="update_status">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>