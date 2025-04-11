<?php
include 'backend/db_connect.php';

// Récupérer tous les plats pour la liste déroulante
$plats = $pdo->query("SELECT * FROM plats")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le plat sélectionné si ID passé
$selected_plat = null;
if (isset($_GET['plat']) && is_numeric($_GET['plat'])) {
    $stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
    $stmt->execute([$_GET['plat']]);
    $selected_plat = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commander - Apperitif Man</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Animations */
@keyframes slide-down {
    0% { transform: translateY(-20px); opacity: 0; }
    100% { transform: translateY(0); opacity: 1; }
}

@keyframes fade-in {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

.animate-slide-down {
    animation: slide-down 0.5s ease-out;
}

.animate-fade-in {
    animation: fade-in 0.8s ease-in;
}
/*bouton de retour en arriere */
button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }


/* Formulaire amélioré */
.commander {
    background: #ffffff;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    margin: 2rem auto;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.input-group {
    margin-bottom: 1.5rem;
}

.input-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #2c3e50;
    font-weight: 600;
}

.input-group input,
.input-group select,
.input-group textarea {
    width: 100%;
    padding: 12px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.input-group input:focus,
.input-group select:focus,
.input-group textarea:focus {
    border-color: #ff6b6b;
    box-shadow: 0 0 8px rgba(255,107,107,0.2);
}

.plat-details {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin: 1rem 0;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
}

.detail-label {
    font-weight: 600;
    color: #2c3e50;
}

.btn-submit {
    background: #ff6b6b;
    color: white;
    padding: 15px 30px;
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
    box-shadow: 0 5px 15px rgba(255,107,107,0.3);
}
    </style>
</head>
<body>
    <header class="animate-slide-down">
        <div class="container">
            <h1><i class="fas fa-shopping-cart"></i> Commander</h1>
            <nav>
                <ul>
                    <li><a href="index.html"><i class="fas fa-home"></i> Accueil</a></li>
                    <li><a href="menu.php"><i class="fas fa-utensils"></i> Menu</a></li>
                    <li><a href="commander.php" class="active"><i class="fas fa-truck"></i> Commander</a></li>
                    <li><a href="contact.html"><i class="fas fa-envelope"></i> Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
     <!--bouton de retour-->
    <button onclick="goBack()">⬅</button>

    <section class="commander container animate-fade-in">
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
                        <input type="text" id="telephone" name="telephone" required>
                    </div>
                </div>

                <!-- Colonne droite -->
                <div class="form-column">
                    <div class="input-group">
                        <label for="adresse"><i class="fas fa-map-marker-alt"></i> Adresse de livraison</label>
                        <textarea id="adresse" name="adresse" required></textarea>
                    </div>

                    <div class="input-group">
                        <label for="date_livraison"><i class="fas fa-calendar-day"></i> Date de livraison</label>
                        <input type="date" id="date_livraison" name="date_livraison" min="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="input-group">
                        <label for="heure_livraison"><i class="fas fa-clock"></i> Heure de livraison</label>
                        <input type="time" id="heure_livraison" name="heure_livraison" min="10:00" max="20:00" required>
                    </div>
                </div>
            </div>

            <!-- Section Plat et Quantité -->
            <div class="plat-selection">
                <div class="input-group">
                    <label for="plat_id"><i class="fas fa-hamburger"></i> Choix du plat</label>
                    <select id="plat_id" name="plat_id" required>
                        <option value="">Sélectionnez un plat</option>
                        <?php foreach ($plats as $plat): ?>
                            <option value="<?= $plat['id'] ?>" 
                                <?= ($selected_plat && $selected_plat['id'] == $plat['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($plat['nom']) ?> - <?= $plat['prix'] ?> FCFA
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="plat-details animate-fade-in" id="platDetails">
                    <?php if ($selected_plat): ?>
                        <div class="detail-item">
                            <span class="detail-label">Description:</span>
                            <p><?= htmlspecialchars($selected_plat['description']) ?></p>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Prix unitaire:</span>
                            <span class="prix"><?= $selected_plat['prix'] ?> FCFA</span>
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

    <footer class="animate-slide-up">
        <!-- ... (même footer que précédemment) ... -->
    </footer>

    <script >
        
        //bouton de retour
        function goBack() {
            window.history.back(); // Retourne à la page précédente
        }


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
                        <span class="prix">${data.prix} FCFA</span>
                    </div>
                `;
                platDetails.classList.add('animate-fade-in');
            })
            .catch(error => console.error('Error:', error));
    });

    // Validation de la date et heure
    const dateInput = document.getElementById('date_livraison');
    const timeInput = document.getElementById('heure_livraison');
    
    dateInput.min = new Date().toISOString().split('T')[0];
    
    timeInput.addEventListener('change', function() {
        const selectedTime = this.value;
        const [hours] = selectedTime.split(':');
        if (hours < 10 || hours > 20) {
            alert('Les livraisons sont possibles entre 10h et 20h');
            this.value = '';
        }
    });
});
    </script>
</body>
</html>