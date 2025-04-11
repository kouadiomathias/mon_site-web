<?php
// Inclusion de la connexion à la base de données
include 'backend/db_connect.php';

// Récupérer tous les plats pour la liste déroulante
try {
    $plats = $pdo->query("SELECT * FROM plats")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de base de données : " . $e->getMessage());
}

// Récupérer le plat sélectionné si l'ID est passé
$selected_plat = null;
if (isset($_GET['plat']) && is_numeric($_GET['plat'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
        $stmt->execute([$_GET['plat']]);
        $selected_plat = $stmt->fetch();
    } catch (PDOException $e) {
        die("Erreur de base de données : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander - ManFOOD</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Variables CSS */
        :root {
            --primary-color: #ff6b6b;
            --secondary-color: #4ecdc4;
            --text-dark: #2c3e50;
            --text-light: #ffffff;
            --bg-light: #f8f9fa;
        }

        /* Styles de base */
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header */
        header {
            background: var(--primary-color);
            padding: 1.5rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        header h1 {
            color: var(--text-light);
            text-align: center;
            margin: 0;
            font-size: 2rem;
        }

        /* Bouton de retour */
        .back-button {
            position: absolute;
            left: 20px;
            top: 20px;
            background: var(--text-light);
            color: var(--primary-color);
            padding: 0.8rem 1.2rem;
            border-radius: 30px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            transform: translateX(-5px);
        }

        /* Formulaire */
        .commander {
            background: var(--text-light);
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            margin: 2rem auto;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .input-group {
            margin-bottom: 1.5rem;
        }

        .input-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        .input-group input,
        .input-group select,
        .input-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .input-group input:focus,
        .input-group select:focus,
        .input-group textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 8px rgba(255, 107, 107, 0.2);
        }

        /* Détails du plat */
        .plat-details {
            background: var(--bg-light);
            padding: 1.5rem;
            border-radius: 10px;
            margin: 1.5rem 0;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }

        /* Bouton de soumission */
        .btn-submit {
            background: var(--primary-color);
            color: var(--text-light);
            padding: 1rem 2rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-submit:hover {
            background: #e55a5a;
            transform: translateY(-2px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .commander {
                padding: 1.5rem;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <button class="back-button" onclick="history.back()">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
        <h1><i class="fas fa-shopping-cart"></i> Commander</h1>
    </header>

    <!-- Contenu principal -->
    <main class="container">
        <section class="commander">
            <div class="form-header">
                <h2><i class="fas fa-concierge-bell"></i> Passer votre commande</h2>
                <p>Remplissez le formulaire pour finaliser votre commande</p>
            </div>

            <form id="commandeForm" action="backend/process_commande.php" method="POST">
                <div class="form-grid">
                    <!-- Colonne gauche -->
                    <div class="form-column">
                        <div class="input-group">
                            <label for="nom"><i class="fas fa-user"></i> Nom complet</label>
                            <input type="text" id="nom" name="nom" required>
                        </div>

                        <div class="input-group">
                            <label for="email"><i class="fas fa-envelope"></i> Adresse email</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="input-group">
                            <label for="telephone"><i class="fas fa-phone"></i> Téléphone</label>
                            <input type="tel" id="telephone" name="telephone" pattern="[0-9]{10}" required>
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="form-column">
                        <div class="input-group">
                            <label for="adresse"><i class="fas fa-map-marker-alt"></i> Adresse de livraison</label>
                            <textarea id="adresse" name="adresse" rows="3" required></textarea>
                        </div>

                        <div class="input-group">
                            <label for="date_livraison"><i class="fas fa-calendar-day"></i> Date de livraison</label>
                            <input type="date" id="date_livraison" name="date_livraison" min="<?= date('Y-m-d') ?>" required>
                        </div>

                        <div class="input-group">
                            <label for="heure_livraison"><i class="fas fa-clock"></i> Heure de livraison</label>
                            <input type="time" id="heure_livraison" name="heure_livraison" min="08:00" max="20:00" required>
                        </div>
                    </div>
                </div>

                <!-- Section Plat -->
                <div class="plat-selection">
                    <div class="input-group">
                        <label for="plat_id"><i class="fas fa-utensils"></i> Choix du plat</label>
                        <select id="plat_id" name="plat_id" required>
                            <option value="">Sélectionnez un plat</option>
                            <?php foreach ($plats as $plat): ?>
                                <option value="<?= $plat['id'] ?>" 
                                    <?= ($selected_plat && $selected_plat['id'] == $plat['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($plat['nom']) ?> - <?= number_format($plat['prix'], 0, ',', ' ') ?> FCFA
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="plat-details" id="platDetails">
                        <?php if ($selected_plat): ?>
                            <div class="detail-item">
                                <span class="detail-label">Description:</span>
                                <p><?= htmlspecialchars($selected_plat['description']) ?></p>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Prix unitaire:</span>
                                <span class="prix"><?= number_format($selected_plat['prix'], 0, ',', ' ') ?> FCFA</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="input-group">
                        <label for="quantite"><i class="fas fa-calculator"></i> Quantité</label>
                        <input type="number" id="quantite" name="quantite" min="1" value="1" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> Confirmer la commande
                </button>
            </form>
        </section>
    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const platSelect = document.getElementById('plat_id');
        const platDetails = document.getElementById('platDetails');

        // Chargement dynamique des détails du plat
        platSelect.addEventListener('change', function() {
            const platId = this.value;
            if (!platId) {
                platDetails.innerHTML = '';
                return;
            }

            fetch(`backend/get_plat.php?id=${platId}`)
                .then(response => response.json())
                .then(data => {
                    platDetails.innerHTML = `
                        <div class="detail-item">
                            <span class="detail-label">Description:</span>
                            <p>${data.description}</p>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Prix unitaire:</span>
                            <span>${new Intl.NumberFormat().format(data.prix)} FCFA</span>
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error:', error);
                    platDetails.innerHTML = '<p class="error">Impossible de charger les détails du plat</p>';
                });
        });

        // Validation de l'heure
        document.getElementById('heure_livraison').addEventListener('change', function() {
            const [hours] = this.value.split(':');
            if (hours < 8 || hours > 20) {
                alert('Les livraisons sont possibles entre 08h et 20h');
                this.value = '';
            }
        });
    });
    </script>
</body>
</html>