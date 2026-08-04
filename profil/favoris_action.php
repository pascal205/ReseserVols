<?php
require_once "../form/config.php";
require_once "../form/database.php";

$userId = $_SESSION['user_id'] ?? null;
$idReserv = isset($_GET['id']) ? (int)$_GET['id'] : null;
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';

if (!$userId) {
    redirect('form/login.php');
    exit;
}

if (!$idReserv) {
    redirect('profil/reservation.php');
    exit;
}

$favstmt = $pdo->prepare("SELECT * FROM favoris WHERE id_user = :id_user AND id_reserv = :id_reserv LIMIT 1");
$favstmt->execute([
    ':id_user' => $userId,
    ':id_reserv' => $idReserv
]);
$favExists = $favstmt->fetchColumn();

if ($favExists) {
    $favorisstmt = $pdo->prepare("DELETE FROM favoris WHERE id_user = :id_user AND id_reserv = :id_reserv");
    $favorisstmt->execute([
        ':id_user' => $userId,
        ':id_reserv' => $idReserv,
    ]);
} else {
    $favorisstmt = $pdo->prepare("INSERT INTO favoris(id_user, id_reserv) VALUES(:id_user, :id_reserv)");
    $favorisstmt->execute([
        ':id_user' => $userId,
        ':id_reserv' => $idReserv
    ]);
}
if ($redirect) {
    redirect('profil/favoris.php');
}
redirect('profil/reservation.php');
exit;
