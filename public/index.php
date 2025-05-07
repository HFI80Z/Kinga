<?php
declare(strict_types=1);

// Autoload
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\VehicleController;
use App\Controllers\AuthController;

// Démarrage de la session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$router = new Router();

// --- Routes Véhicules ---
$router->get('/',                    [VehicleController::class, 'index']);
$router->get('/vehicle/create',      [VehicleController::class, 'create']);
$router->post('/vehicle/store',      [VehicleController::class, 'store']);
$router->get('/vehicle/edit',        [VehicleController::class, 'edit']);
$router->post('/vehicle/update',     [VehicleController::class, 'update']);
$router->get('/vehicle/delete',      [VehicleController::class, 'delete']);

// --- Routes Authentification ---
$router->get('/login',               [AuthController::class, 'showLogin']);
$router->post('/login',              [AuthController::class, 'login']);
$router->get('/register',            [AuthController::class, 'showRegister']);
$router->post('/register',           [AuthController::class, 'register']);
$router->get('/logout',              [AuthController::class, 'logout']);

// Lancer le routeur
$router->run();
