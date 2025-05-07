<?php
declare(strict_types=1);

namespace App\Core;

class Controller
{
    /**
     * Charge une vue et lui passe des données.
     *
     * @param string $path  Chemin relatif sous src/views sans l’extension .php
     * @param array  $data  Variables à injecter dans la vue
     */
    public function view(string $path, array $data = []): void
    {
        // protection contre l’écrasement de variables internes
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/' . $path . '.php';
    }

    /**
     * Redirige vers une URL, même si les headers ont déjà été envoyés.
     *
     * @param string $url
     */
    public function redirect(string $url): void
    {
        if (!headers_sent()) {
            header('Location: ' . $url);
            exit;
        }

        // fallback JavaScript en cas de headers déjà envoyés
        echo "<script>window.location.replace('{$url}');</script>";
        exit;
    }
}
