<?php
namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        // Démarre la session seulement si ce n’est pas déjà fait
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        return isset($_SESSION['user'])
            && ($_SESSION['user']['role'] ?? '') === 'ADMIN';
    }
}
