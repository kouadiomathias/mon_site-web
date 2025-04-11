<?php
// Connexion à la base de données
include 'db_connect.php';

// Récupérer l'ID de la commande depuis l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de commande invalide.");
}
$commande_id = (int)$_GET['id'];

// Récupérer les détails de la commande
$stmt = $pdo->prepare("
    SELECT commandes.*, plats.nom AS plat_nom, plats.prix AS plat_prix 
    FROM commandes 
    JOIN plats ON commandes.plat_id = plats.id 
    WHERE commandes.id = ?
");
$stmt->execute([$commande_id]);
$commande = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$commande) {
    die("Commande introuvable.");
}

// Calculer le total
$total = $commande['plat_prix'] * $commande['quantite'] + 500; // Ajouter les frais de livraison
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande - ManFOOD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .confirmation-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 40px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: fadeIn 1s ease-in;
        }

        .confirmation-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .checkmark-circle {
            width: 100px;
            height: 100px;
            background: #28a745;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .checkmark {
            color: white;
            font-size: 50px;
        }

        .order-details {
            margin: 30px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 10px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .next-steps {
            margin-top: 40px;
            text-align: center;
        }

        .timeline {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }

        .timeline-step {
            text-align: center;
            flex: 1;
            position: relative;
        }

        .timeline-step i {
            font-size: 25px;
            background: #28a745;
            color: white;
            padding: 15px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <div class="confirmation-header">
            <div class="checkmark-circle">
                <i class="fas fa-check checkmark"></i>
            </div>
            <h1 class="mb-3">Merci pour votre commande !</h1>
            <p class="lead">Votre commande a été confirmée avec succès</p>
        </div>

        <div class="order-details">
            <h4 class="mb-4"><i class="fas fa-receipt"></i> Récapitulatif de la commande</h4>
            
            <div class="detail-item">
                <span>Numéro de commande :</span>
                <strong>#CMD-<?= str_pad($commande['id'], 5, '0', STR_PAD_LEFT) ?></strong>
            </div>
            
            <div class="detail-item">
                <span>Date de livraison :</span>
                <strong><?= date('d M Y', strtotime($commande['date_livraison'])) ?> - <?= $commande['heure_livraison'] ?></strong>
            </div>
            
            <div class="detail-item">
                <span>Adresse de livraison :</span>
                <strong><?= htmlspecialchars($commande['adresse']) ?></strong>
            </div>

            <h5 class="mt-4 mb-3">Détails des plats :</h5>
            <div class="detail-item">
                <span><?= htmlspecialchars($commande['plat_nom']) ?> (x<?= $commande['quantite'] ?>)</span>
                <strong><?= number_format($commande['plat_prix'] * $commande['quantite'], 0, ',', ' ') ?> FCFA</strong>
            </div>
            <div class="detail-item">
                <span>Frais de livraison :</span>
                <strong>5 00 FCFA</strong>
            </div>
            
            <div class="detail-item" style="border-bottom: none;">
                <span class="h5">Total :</span>
                <span class="h5 text-success"><?= number_format($total, 0, ',', ' ') ?> FCFA</span>
            </div>
        </div>

        <div class="next-steps">
            <h4 class="mb-4"><i class="fas fa-clock"></i> Prochaines étapes</h4>
            
            <div class="timeline">
                <div class="timeline-step">
                    <i class="fas fa-utensils"></i>
                    <p>Préparation en cours</p>
                </div>
                <div class="timeline-step">
                    <i class="fas fa-motorcycle"></i>
                    <p>Livraison en route</p>
                </div>
                <div class="timeline-step">
                    <i class="fas fa-home"></i>
                    <p>Livré !</p>
                </div>
            </div>

            <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle"></i> Vous recevrez une notification SMS lorsque votre livreur sera en route.
            </div>

            <div class="mt-4">
                <a href="../commander.php" class="btn btn-success btn-lg">
                    <i class="fas fa-utensils"></i> Commander à nouveau
                </a>
                <a href="../contact.html" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-headset"></i> Support client
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>