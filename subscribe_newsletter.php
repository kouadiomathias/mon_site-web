<?php
// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: newsletter_form.html"); // Rediriger vers le formulaire
    exit();
}

// Connexion à la base de données
include 'backend/db_connect.php';

// Récupérer l'email depuis le formulaire
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);

// Valider l'email
if (!$email) {
    die("Adresse email invalide.");
}

try {
    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM newsletter WHERE email = ?");
    $stmt->execute([$email]);
    $existingEmail = $stmt->fetch();

    if ($existingEmail) {
        die("Vous êtes déjà inscrit à notre newsletter.");
    }

    // Insérer l'email dans la table newsletter
    $stmt = $pdo->prepare("INSERT INTO newsletter (email) VALUES (?)");
    $stmt->execute([$email]);

    // Rediriger vers une page de confirmation
    header("Location: subscribe_confirmation.html");
    exit();

} catch (PDOException $e) {
    die("Erreur lors de l'inscription : " . $e->getMessage());
}
?>