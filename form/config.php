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
function nav_item(string $titre, string $link, string $classli = ' ', string $class=' ', string $activepage = ' '): string{
    $url = SITE_URL . '/' . $link;
    $class .= ' nav-link px-0 me-3';
    $classli .= ' nav-item fw-bold';
    if ($activepage === $titre) {
        $class .= ' link-active';
    }
        // $class .= ' link-active';
    
    return <<<HTML
        <li class="$classli"><a href="$url" class="$class">$titre</a></li>
    HTML;
}