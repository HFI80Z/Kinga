<?php
namespace App\Core;

class Auth
{
    public static function check(): bool
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // on compare en lowercase pour être sûr
        $role = strtolower($_SESSION['user']['role'] ?? '');
        return isset($_SESSION['user']) && $role === 'admin';
    }
}
