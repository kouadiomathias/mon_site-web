




<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'site_repas';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les commandes avec les détails des plats
$query = "
    SELECT c.*, p.nom AS plat_nom, p.prix AS plat_prix
    FROM commandes c
    LEFT JOIN plats p ON c.plat_id = p.id
    ORDER BY c.date_commande DESC
";
$stmt = $pdo->query($query);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Simuler les statuts (à remplacer par une logique métier réelle)
$statusOptions = ['pending', 'preparing', 'ready', 'delivered'];
foreach ($orders as &$order) {
    $order['status'] = $statusOptions[array_rand($statusOptions)]; // Exemple aléatoire
}

// Mettre à jour le statut (simulé en session)
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $_POST['new_status'];

    // Simuler la mise à jour (à remplacer par une logique réelle)
    $_SESSION['order_status'][$orderId] = $newStatus;

    // Rediriger pour éviter la resoumission du formulaire
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Appéritif Man</title>
    <style>
        /* [Votre CSS reste inchangé] */
        /* Reset CSS */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f8f9fa; color: #333; }

        /* En-tête */
        header {
            background: #075E54;
            color: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        header h1 { font-size: 1.8rem; }

        /* Conteneur principal */
        .dashboard {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Carte de commande */
        .order-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .order-card:hover { transform: translateY(-5px); }

        .order-card h3 { color: #075E54; margin-bottom: 10px; }
        .order-card p { margin: 5px 0; }
        .order-card .status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
        }
        .status.pending { background: #ffeb3b; color: #333; }
        .status.preparing { background: #2196f3; color: white; }
        .status.ready { background: #4caf50; color: white; }
        .status.delivered { background: #9e9e9e; color: white; }

        /* Boutons */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
            margin-top: 10px;
        }
        .btn.accept { background: #4caf50; color: white; }
        .btn.decline { background: #f44336; color: white; }
        .btn:disabled { background: #ccc; cursor: not-allowed; }

        /* Responsive */
        @media (min-width: 768px) {
            .dashboard { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .dashboard { grid-template-columns: repeat(3, 1fr); }
        }
    </style>
</head>
<body>
    <header>
        <h1>Tableau de Bord - Appéritif Man</h1>
        <p>Suivez et gérez vos commandes en temps réel</p>
    </header>

    <div class="dashboard">
        <?php foreach ($orders as $order): ?>
            <?php
            // Récupérer le statut simulé
            $orderId = $order['id'];
            $status = $_SESSION['order_status'][$orderId] ?? 'pending';
            ?>
            <div class="order-card">
                <h3>Commande #<?= htmlspecialchars($order['id']) ?></h3>
                <p><strong>Client :</strong> <?= htmlspecialchars($order['nom_client']) ?></p>
                <p><strong>Email :</strong> <?= htmlspecialchars($order['email_client']) ?></p>
                <p><strong>Téléphone :</strong> <?= htmlspecialchars($order['telephone']) ?></p>
                <p><strong>Adresse :</strong> <?= htmlspecialchars($order['adresse']) ?></p>
                <p><strong>Plat :</strong> <?= htmlspecialchars($order['plat_nom']) ?> (x<?= htmlspecialchars($order['quantite']) ?>)</p>
                <p><strong>Total :</strong> <?= htmlspecialchars($order['quantite'] * $order['plat_prix']) ?> FCFA</p>
                <p><strong>Date commande :</strong> <?= date('d/m/Y H:i', strtotime($order['date_commande'])) ?></p>
                <p><strong>Livraison :</strong> <?= htmlspecialchars($order['date_livraison']) ?> à <?= htmlspecialchars($order['heure_livraison']) ?></p>
                <div class="status <?= htmlspecialchars($status) ?>">
                    <?= getStatusText($status) ?>
                </div>
                <form method="POST" action="" style="display: inline;">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <input type="hidden" name="new_status" value="preparing">
                    <button type="submit" name="update_order" class="btn accept" <?= $status !== 'pending' ? 'disabled' : '' ?>>Accepter</button>
                </form>
                <form method="POST" action="" style="display: inline;">
                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                    <input type="hidden" name="new_status" value="declined">
                    <button type="submit" name="update_order" class="btn decline" <?= $status !== 'pending' ? 'disabled' : '' ?>>Refuser</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <?php
    // Fonction pour traduire le statut
    function getStatusText($status) {
        switch ($status) {
            case 'pending': return 'En attente';
            case 'preparing': return 'En préparation';
            case 'ready': return 'Prêt';
            case 'delivered': return 'Livré';
            default: return 'Statut inconnu';
        }
    }
    ?>
</body>
</html>