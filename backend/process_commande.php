<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $telephone = htmlspecialchars($_POST['telephone']);
    $adresse = htmlspecialchars($_POST['adresse']);
    $plat_id = (int)$_POST['plat_id'];
    $quantite = (int)$_POST['quantite'];
    $date_livraison = $_POST['date_livraison'];
    $heure_livraison = $_POST['heure_livraison'];

    // Validation des données
    if (!$email || $plat_id <= 0 || $quantite <= 0) {
        die("Données invalides");
    }

    try {
        // Insertion de la commande
        $stmt = $pdo->prepare("INSERT INTO commandes 
            (nom_client, email_client, telephone, adresse, plat_id, quantite, date_livraison, heure_livraison) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $nom,
            $email,
            $telephone,
            $adresse,
            $plat_id,
            $quantite,
            $date_livraison,
            $heure_livraison
        ]);

        // Récupérer l'ID de la commande insérée
        $commande_id = $pdo->lastInsertId();

        // Rediriger vers la page de confirmation
        header("Location: confirmation.php?id=" . $commande_id);
        exit();

    } catch (PDOException $e) {
        die("Erreur de base de données: " . $e->getMessage());
    }
}
?>