<?php
require_once("../form/config.php");
require_once("../form/database.php");


$pagestyle = false;
$infolder = true;
$activepage = ' ';

$userId = isset($_GET['id']) ? (int)$_GET['id'] : null;

if ($userId === null || $userId <= 0) {
    $userId = $_SESSION['user_id'] ?? null;
}

if (!$userId) {
    header('Location:' . SITE_URL .'form/login.php');
    exit;
}

$_SESSION['user_id'] = $userId;

$stmt = $pdo->prepare("SELECT * FROM user WHERE id = :id");
$stmt->execute([':id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

$nbreservstmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE id_user = :id");
$nbreservstmt->execute([':id' => $userId]);
$nbreserv = $nbreservstmt->fetchColumn();

// $reservstmt = $pdo->prepare("SELECT id_reservation FROM reservations WHERE id_user = :id");
// $reservstmt->execute([':id' => $userId]);
// $reserv = $reservstmt->fetchAll();


if (!$user) {
    header('Location:' . SITE_URL . 'form/login.php');
    exit;
}

$_SESSION['prenom'] = $user['prenom'] ?? '';
$_SESSION['nom'] = $user['nom'] ?? '';
$_SESSION['email'] = $user['email'] ?? '';
$_SESSION['telephone'] = $user['telephone'] ?? '';
$_SESSION['type'] = $user['type'] ?? '';
$_SESSION['date_inscription'] = $user['date_inscription'] ?? '';
$_SESSION['last_conexion'] = $user['date_connect'] ?? '';

$error = "";
$succes = "";

if (isset($_POST['changeInfo'])) {
    $newNom = trim($_POST['nom'] ?? '');
    $newPrenom = trim($_POST['prenom'] ?? '');
    $newEmail = trim($_POST['email'] ?? '');
    $newTel = trim($_POST['telephone'] ?? '');

    $updateStmt = $pdo->prepare("UPDATE user SET nom = :nom, prenom = :prenom, email = :email, telephone = :telephone WHERE id = :id");
    $updated = $updateStmt->execute([
        ':nom' => $newNom,
        ':prenom' => $newPrenom,
        ':email' => $newEmail,
        ':telephone' => $newTel,
        ':id' => $userId
    ]);

    if ($updated) {
        $_SESSION['prenom'] = $newPrenom;
        $_SESSION['nom'] = $newNom;
        $_SESSION['email'] = $newEmail;
        $_SESSION['telephone'] = $newTel;

        $error = "Une erreur est survenue lors de l’enregistrement.";
    }
    $succes = "Modifications enregistrées avec succès.";

}
if (isset($_POST['changemdp'])) {
    $ancmdp = trim($_POST['ancmdp']);
    $newmdp = trim($_POST['newmdp']);
    $confmdp = trim($_POST['confmdp']);

    if (!password_verify($ancmdp, $user['mdp'])) {
        $error = "Ancien mot de passe incorrect";
    }
    if (!(($newmdp === $confmdp))) {
        $error = "les mots de passe ne correspondent pas";
    }
    
    if (empty($error)) {
        $hash = password_hash($newmdp, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE user SET mdp = :newmdp WHERE id = :id");
        $mdpudapted = $stmt->execute([
            ':newmdp' => $hash,
            ':id' => $userId
        ]);
        if ($mdpudapted) {
            $succes = "Mot de passe changé avec succès";
        }   
    }
}