<?php
declare(strict_types=1);

namespace App\Config;

use PDO;
use RuntimeException;

class Database
{
    private static ?PDO $instance = null;

    /**
     * Retourne l’instance PDO unique pour la BDD.
     *
     * Cherche les paramètres dans, par ordre :
     * 1) le fichier .env à la racine du projet
     * 2) les variables d’environnement système
     * 3) les valeurs par défaut codées en dur
     *
     * @return PDO
     * @throws RuntimeException si la connexion échoue
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            // Chemin vers .env (deux niveaux au-dessus de src/Config)
            $envFile = dirname(__DIR__, 2) . '/.env';

            // Parse .env si présent
            $params = [];
            if (file_exists($envFile) && is_readable($envFile)) {
                $params = parse_ini_file($envFile, false, INI_SCANNER_TYPED) ?: [];
            }

            // Priorité : .env → getenv() → valeur par défaut
            $host = $params['DB_HOST'] ?? getenv('DB_HOST') ?: 'db';
            $port = $params['DB_PORT'] ?? getenv('DB_PORT') ?: '5432';
            $name = $params['DB_NAME'] ?? getenv('DB_NAME') ?: 'vehicles_db';
            $user = $params['DB_USER'] ?? getenv('DB_USER') ?: 'user';
            $pass = $params['DB_PASS'] ?? getenv('DB_PASS') ?: 'password';

            // Construction du DSN PostgreSQL
            $dsn = sprintf('pgsql:host=%s;port=%s;dbname=%s', $host, $port, $name);

            try {
                self::$instance = new PDO($dsn, $user, $pass);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {
                throw new RuntimeException('Connexion à la base de données impossible : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
