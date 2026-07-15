<?php
require_once('form/config.php');
require_once("form/database.php");


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['ville'])) {
    $ville = $_GET['ville'];
    
    if (empty(trim($ville))) {
        echo json_encode([]);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id_aeroport, nom, code_aeroport FROM aeroport WHERE ville LIKE :ville ORDER BY nom");
        $stmt->execute([':ville' => '%' . $ville . '%']);
        $aeroports = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode($aeroports);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur de base de données']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Paramètre "ville" manquant']);
}
?>
