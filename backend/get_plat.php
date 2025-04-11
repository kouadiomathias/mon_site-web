<?php
include 'db_connect.php';

header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM plats WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $plat = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($plat) {
        echo json_encode($plat);
    } else {
        echo json_encode(['error' => 'Plat non trouvé']);
    }
} else {
    echo json_encode(['error' => 'ID non fourni']);
}