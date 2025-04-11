<?php
session_start();
require_once '../backend/db_connect.php';

// Redirection si déjà connecté
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_dashbord.php");
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    try {
        // Récupérer l'administrateur
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        // Vérifier le mot de passe
        if ($admin && password_verify($password, $admin['password'])) {
            // Connexion réussie
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $admin['username'];
            
            // Mettre à jour la date de dernière connexion
            $pdo->prepare("UPDATE admins SET last_login = NOW() WHERE id = ?")
               ->execute([$admin['id']]);
            
            // Redirection vers le tableau de bord
            header("Location: admin_dashbord.php");
            exit();
        } else {
            $error = "Identifiants incorrects.";
        }
    } catch (PDOException $e) {
        $error = "Erreur de connexion : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
        }
        
        .login-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 400px;
            margin: 0 auto;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 20px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #764ba2;
            box-shadow: none;
        }

        .btn-login {
            background: #764ba2;
            color: white;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: #667eea;
            transform: translateY(-2px);
        }

        .alert {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="text-center mb-4">
            <i class="fas fa-lock fa-3x text-purple mb-3"></i>
            <h2 class="font-weight-bold">Connexion Admin</h2>
            <p class="text-muted">Accès réservé au personnel autorisé</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="admin_login.php">
            <div class="mb-3">
                <label class="form-label">Identifiant</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" 
                           name="username" 
                           class="form-control" 
                           required
                           autocomplete="off"
                           autocapitalize="off">
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Mot de passe</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-key"></i></span>
                    <input type="password" 
                           name="password" 
                           class="form-control" 
                           required
                           autocomplete="current-password">
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100">
                <i class="fas fa-sign-in-alt me-2"></i>Se connecter
            </button>
        </form>
    </div>
</body>
</html>