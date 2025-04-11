<?php
// Connexion à la base de données
include 'backend/db_connect.php';

// Récupérer les plats depuis la base de données
$query = "SELECT * FROM plats";
$result = $pdo->query($query);
$plats = $result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - ManFOOD</title>
    <meta name="description" content="Découvrez notre menu varié et commandez vos plats préférés en ligne.">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Général */
body {
    font-family: 'Poppins', sans-serif;
    margin: 0;
    padding: 0;
    color: #333;
    line-height: 1.6;
}

.container {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
}

/* Header */
header {
    background-color: #333;
    color: #fff;
    padding: 20px 0;
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 2.5rem;
}

nav ul {
    list-style: none;
    padding: 0;
    margin: 10px 0 0;
}

nav ul li {
    display: inline;
    margin: 0 15px;
}

nav ul li a {
    color: #fff;
    text-decoration: none;
    font-weight: 600;
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


/* Section Menu */
.menu {
    padding: 60px 0;
    text-align: center;
}

.menu h2 {
    font-size: 2rem;
    margin-bottom: 40px;
}

.plats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
}

.plat-card {
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.plat-card:hover {
    transform: translateY(-10px);
}

.plat-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.plat-card h3 {
    font-size: 1.5rem;
    margin: 15px 0 10px;
}

.plat-card p {
    padding: 0 15px;
    font-size: 0.9rem;
    color: #666;
}

.plat-card .prix {
    font-size: 1.2rem;
    font-weight: 600;
    color: #ff6b6b;
    margin: 10px 0;
}

.plat-card .btn {
    display: block;
    background-color: #ff6b6b;
    color: #fff;
    padding: 10px;
    text-decoration: none;
    font-weight: 600;
    margin: 15px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.plat-card .btn:hover {
    background-color: #e65a5a;
}

/* Footer */
footer {
    background-color: #333;
    color: #fff;
    padding: 40px 0;
}

.footer-content {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.footer-section {
    width: 30%;
    margin-bottom: 20px;
}

.footer-section h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
}

.footer-section p, .footer-section a {
    color: #fff;
    text-decoration: none;
    margin-bottom: 10px;
    display: block;
}

.footer-section a:hover {
    color: #ff6b6b;
}

.footer-section form {
    display: flex;
}

.footer-section input {
    padding: 10px;
    border: none;
    border-radius: 5px 0 0 5px;
    flex: 1;
}

.footer-section button {
    padding: 10px 20px;
    background-color: #ff6b6b;
    border: none;
    color: #fff;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
}

.footer-bottom {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid #444;
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.menu {
    animation: fadeIn 1s ease-in-out;
}
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <h1>Apperitif Man</h1>
            
        </div>
    </header>
    
    <!-- bouton de retour -->
    <button onclick="goBack()">⬅</button>

    <!-- Section Menu -->
    <section class="menu">
        <div class="container">
            <h2>Notre Menu</h2>
            <div class="plats-grid">
                <?php foreach ($plats as $plat): ?>
                    <div class="plat-card">
                        <img src="assets/images/<?= htmlspecialchars($plat['image']) ?>" alt="<?= htmlspecialchars($plat['nom']) ?>">
                        <h3><?= htmlspecialchars($plat['nom']) ?></h3>
                        <p><?= htmlspecialchars($plat['description']) ?></p>
                        <p class="prix"><?= htmlspecialchars($plat['prix']) ?> FCFA</p>
                        <a href="commander.php?plat=<?= $plat['id'] ?>" class="btn">Commander</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Contactez-nous</h3>
                    <p><i class="fas fa-phone"></i> +225 0708817956</p>
                    <p><i class="fas fa-envelope"></i> contact@ManFood.com</p>
                    <p><i class="fas fa-map-marker-alt"></i> Man, Côte d'Ivoire</p>
                </div>
                <div class="footer-section">
                    <h3>Suivez-nous</h3>
                    <a href="https://web.facebook.com/?_rdc=1&_rdr#" target="_blanck"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/accounts/emailsignup/"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
                <div class="footer-section">
                    <h3>Newsletter</h3>
                    <form action="#" method="post">
                        <input type="email" placeholder="Votre email" required>
                        <button type="submit">S'inscrire</button>
                    </form>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 ManFOOD. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Script JavaScript -->
    <script >

        
        //bouton de retour
        function goBack() {
            window.history.back(); // Retourne à la page précédente
        }


        // Ajouter des animations supplémentaires ou des interactions
document.addEventListener("DOMContentLoaded", function () {
    // Exemple : Ajouter un effet de survol dynamique
    const platCards = document.querySelectorAll(".plat-card");

    platCards.forEach((card) => {
        card.addEventListener("mouseenter", () => {
            card.style.transform = "scale(1.05)";
        });

        card.addEventListener("mouseleave", () => {
            card.style.transform = "scale(1)";
        });
    });
});
    </script>
</body>
</html>