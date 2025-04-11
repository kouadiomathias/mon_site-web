<?php
session_start();
require_once '../backend/db_connect.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement du formulaire
    require_once 'admin_process.php';
}
?>

<!DOCTYPE html>
<html>
<head>
    <!-- Header similaire au dashboard -->
    <meta charset="UTF-8">
    <title>Tableau de bord Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <style>
        .dashboard-nav {
            background: #2c3e50;
            min-height: 100vh;
            padding: 20px;
        }
        .nav-link {
            color: #ecf0f1 !important;
            transition: all 0.3s;
        }
        .nav-link:hover {
            background: #34495e;
        }
        .main-content {
            padding: 30px;
            background: #f8f9fa;
        }
        .card {
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
</head>
<body>
    <div class="container mt-5">
        <div class="card mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-utensils"></i> Ajouter un nouveau plat</h4>
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
</html>