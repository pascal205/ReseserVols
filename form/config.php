<?php
define('SITE_URL', 'http://localhost/rservVols');
session_start();

function redirect($url): void {
    header("Location: " . SITE_URL . "/" . $url);
    exit;
    // header("Location: " . APP_URL . "/" . ltrim($url, '/'));
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}
?>