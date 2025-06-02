<?php
declare(strict_types=1);

// Autoload
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\VehicleController;
use App\Controllers\AuthController;
use App\Controllers\RepairerController;
use App\Controllers\MaintenanceController;

// Démarrage de la session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$router = new Router();

// --- Routes Véhicules (public + admin) ---
$router->get('/',                    [VehicleController::class, 'index']);
$router->get('/vehicle/create',      [VehicleController::class, 'create']);
$router->post('/vehicle/store',      [VehicleController::class, 'store']);
$router->get('/vehicle/edit',        [VehicleController::class, 'edit']);
$router->post('/vehicle/update',     [VehicleController::class, 'update']);
$router->get('/vehicle/delete',      [VehicleController::class, 'delete']);

// Route pour accéder au panel admin (liste des véhicules)
$router->get('/admin', [VehicleController::class, 'adminPanel']);

// --- Routes Authentification ---
$router->get('/login',               [AuthController::class, 'showLogin']);
$router->post('/login',              [AuthController::class, 'login']);
$router->get('/register',            [AuthController::class, 'showRegister']);
$router->post('/register',           [AuthController::class, 'register']);
$router->get('/logout',              [AuthController::class, 'logout']);

// --- Routes pour les réparateurs (panel admin) ---
$router->get('/admin/repairers',      [RepairerController::class, 'index']);
$router->get('/admin/repairers/form', [RepairerController::class, 'form']);
$router->post('/repairer/store',      [RepairerController::class, 'store']);
$router->post('/repairer/update',     [RepairerController::class, 'update']);
$router->get('/repairer/delete',      [RepairerController::class, 'delete']);

// --- Routes pour les maintenances (panel admin) ---
$router->get('/admin/maintenance',       [MaintenanceController::class, 'index']);
$router->get('/admin/maintenance/form',  [MaintenanceController::class, 'form']);
$router->post('/maintenance/store',      [MaintenanceController::class, 'store']);
$router->get('/maintenance/close',       [MaintenanceController::class, 'close']);

// Lancer le routeur
$router->run();
