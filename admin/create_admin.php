<?php
// create_admin.php

// Inclure la connexion à la base de données
require_once '../backend/db_connect.php';

// Données de l'administrateur
$username = 'kouadio'; // Nom d'utilisateur de l'admin
$password = 'MATHias255'; // Mot de passe en clair (à changer)

// Hacher le mot de passe
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    // Insérer l'administrateur dans la base de données
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashedPassword]);

    echo "Administrateur créé avec succès !";
} catch (PDOException $e) {
    die("Erreur lors de la création de l'administrateur : " . $e->getMessage());
}