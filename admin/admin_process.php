<?php
session_start();
require_once '../backend/db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ajout de plat
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $prix = (int)$_POST['prix'];
    $image = '';

    // Gestion de l'upload d'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../assets/images/";
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetFile = $targetDir . $fileName;
        
        // Vérification du type de fichier
        $allowedTypes = ['jpg', 'jpeg', 'png', 'webp'];
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        
        if (in_array($fileType, $allowedTypes)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
            $image = $fileName;
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO plats (nom, description, prix, image) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nom, $description, $prix, $image]);
        $_SESSION['success'] = "Plat ajouté avec succès";
        header("Location: admin_dashbord.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
        header("Location: admin_add_plat.php");
        exit();
    }
}

// Gestion des autres actions (suppression, etc.)
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete_plat':
            $id = (int)$_GET['id'];
            $pdo->prepare("DELETE FROM plats WHERE id = ?")->execute([$id]);
            $_SESSION['success'] = "Plat supprimé avec succès";
            break;
        
        // Ajouter d'autres cas pour les autres tables
    }
    header("Location: admin_dashbord.php");
    exit();
}