
<?php
session_start();
require_once '../backend/db_connect.php';

// Vérification de l'authentification
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

// Récupération des données
$plats = $pdo->query("SELECT * FROM plats")->fetchAll();
$commandes = $pdo->query("SELECT commandes.*, plats.nom AS plat_nom FROM commandes JOIN plats ON commandes.plat_id = plats.id")->fetchAll();
$contacts = $pdo->query("SELECT * FROM contacts")->fetchAll();
$newsletters = $pdo->query("SELECT * FROM newsletter")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Styles CSS ici */
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Navigation -->
            <nav class="col-md-2 dashboard-nav">
                <h3 class="text-white mb-4">Administration</h3>
                <div class="nav flex-column">
                    <a class="nav-link active" href="#plats">Plats</a>
                    <a class="nav-link" href="#commandes">Commandes</a>
                    <a class="nav-link" href="#contacts">Contacts</a>
                    <a class="nav-link" href="#newsletter">Newsletter</a>
                    <a class="nav-link text-danger" href="admin_logout.php">Déconnexion</a>
                </div>
            </nav>

            <!-- Contenu principal -->
            <main class="col-md-10 main-content">
                <!-- Section Plats -->
                <section id="plats" class="mb-5">
                    <!-- Code pour les plats -->
                </section>

                <!-- Section Commandes -->
                <section id="commandes" class="mb-5">
                    <!-- Code pour les commandes -->
                     
                    <section id="commandes" class="mb-5">
    <h2>Gestion des Commandes</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover" id="commandesTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Plat</th>
                        <th>Quantité</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commandes as $commande): ?>
                    <tr>
                        <td><?= $commande['id'] ?></td>
                        <td><?= htmlspecialchars($commande['nom_client']) ?></td>
                        <td><?= htmlspecialchars($commande['plat_nom']) ?></td>
                        <td><?= $commande['quantite'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($commande['date_commande'])) ?></td>
                        <td>
                            <span class="badge bg-<?= $commande['statut'] === 'livré' ? 'success' : 'warning' ?>">
                                <?= ucfirst($commande['statut']) ?>
                            </span>
                        </td>
                        <td>
                            <a href="admin_edit_commande.php?id=<?= $commande['id'] ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="admin_process.php?action=delete_commande&id=<?= $commande['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Confirmer la suppression ?')">
                               <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
                </section>

                <!-- Section Contacts -->
                <section id="contacts" class="mb-5">
                    <!-- Code pour les contacts -->
                    <section id="contacts" class="mb-5">
    <h2>Messages des Contacts</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover" id="contactsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Sujet</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?= $contact['id'] ?></td>
                        <td><?= htmlspecialchars($contact['nom']) ?></td>
                        <td><?= htmlspecialchars($contact['email']) ?></td>
                        <td><?= htmlspecialchars($contact['sujet']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($contact['date_envoi'])) ?></td>
                        <td>
                            <a href="admin_view_contact.php?id=<?= $contact['id'] ?>" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="admin_process.php?action=delete_contact&id=<?= $contact['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Confirmer la suppression ?')">
                               <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
                </section>

                <!-- Section Newsletter -->
                <section id="newsletter" class="mb-5">
                    <!-- Code pour la newsletter -->
                    <section id="newsletter" class="mb-5">
    <h2>Abonnés à la Newsletter</h2>
    <div class="card">
        <div class="card-body">
            <table class="table table-hover" id="newsletterTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($newsletters as $subscriber): ?>
                    <tr>
                        <td><?= $subscriber['id'] ?></td>
                        <td><?= htmlspecialchars($subscriber['email']) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($subscriber['date_inscription'])) ?></td>
                        <td>
                            <a href="admin_process.php?action=delete_newsletter&id=<?= $subscriber['id'] ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Confirmer la suppression ?')">
                               <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
                </section>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialisation des DataTables
            $('#platsTable, #commandesTable, #contactsTable, #newsletterTable').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
                }
            });
        });

    $(document).ready(function() {
        $('#platsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            }
        });

        $('#commandesTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            }
        });

        $('#contactsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            }
        });

        $('#newsletterTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json'
            }
        });
    });
</script>
</body>
</html>