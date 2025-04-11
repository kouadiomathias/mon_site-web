<?php
session_start(); // Démarrer la session pour stocker les messages

// Connexion à la base de données
include 'db_connect.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $sujet = htmlspecialchars($_POST['sujet']);
    $message = htmlspecialchars($_POST['message']);

    // Valider les données
    if (!$nom || !$email || !$sujet || !$message) {
        $_SESSION['error'] = "Veuillez remplir tous les champs du formulaire.";
        header("Location: ../contact.html");
        exit();
    }

    try {
        // Insérer le message dans la table contacts
        $stmt = $pdo->prepare("INSERT INTO contacts (nom, email, sujet, message) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $email, $sujet, $message]);

        // Rediriger vers une page de confirmation
        $_SESSION['success'] = "Votre message a été envoyé avec succès !";
        header("Location: ../contact_confirmation.html");
        exit();

    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur lors de l'envoi du message : " . $e->getMessage();
        header("Location: ../contact.html");
        exit();
    }
} else {
    $_SESSION['error'] = "Méthode de requête non autorisée.";
header("Location: ../contact_confirmation.html");
exit();
}
?>