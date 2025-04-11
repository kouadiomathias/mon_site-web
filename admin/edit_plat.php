<?php
session_start();
include '../backend/db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if (isset($_GET['id'])) {
    $plat = $pdo->prepare("SELECT * FROM plats WHERE id = ?")->execute([$_GET['id']])->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => $_POST['nom'],
        'description' => $_POST['description'],
        'prix' => $_POST['prix']
    ];

    try {
        if (isset($_POST['id'])) {
            // Mise à jour
            $pdo->prepare("UPDATE plats SET nom=?, description=?, prix=? WHERE id=?")
               ->execute([$data['nom'], $data['description'], $data['prix'], $_POST['id']]);
        } else {
            // Création
            $pdo->prepare("INSERT INTO plats (nom, description, prix) VALUES (?, ?, ?)")
               ->execute([$data['nom'], $data['description'], $data['prix']]);
        }
        $_SESSION['message'] = "Plat enregistré avec succès";
        header("Location: tableau_bord.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<body>
<div class="container mt-5">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-utensils"></i> modifier un plat</h4>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Nom du plat</label>
                        <input type="text" name="nom" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prix (FCFA)</label>
                        <input type="number" name="prix" class="form-control" min="1000" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Image du plat</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-save"></i> Enregistrer
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
<html>
<!-- Formulaire d'édition avec les champs pré-remplis -->