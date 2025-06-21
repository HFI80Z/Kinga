<?php  
declare(strict_types=1);

// Autoload
require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\VehicleController;
use App\Controllers\AuthController;
use App\Controllers\RepairerController;
use App\Controllers\MaintenanceController;
use App\Controllers\PartController;

// Démarrage de la session
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$router = new Router();

// --- ROUTES PUBLIC / ADMIN ---
// Véhicules
$router->get('/',                    [VehicleController::class, 'index']);
$router->get('/vehicle/create',      [VehicleController::class, 'create']);
$router->post('/vehicle/store',      [VehicleController::class, 'store']);
$router->get('/vehicle/edit',        [VehicleController::class, 'edit']);
$router->post('/vehicle/update',     [VehicleController::class, 'update']);
$router->get('/vehicle/delete',      [VehicleController::class, 'delete']);
$router->get('/admin',               [VehicleController::class, 'adminPanel']);

// Auth
$router->get('/login',               [AuthController::class, 'showLogin']);
$router->post('/login',              [AuthController::class, 'login']);
$router->get('/register',            [AuthController::class, 'showRegister']);
$router->post('/register',           [AuthController::class, 'register']);
$router->get('/logout',              [AuthController::class, 'logout']);

// Réparateurs
$router->get('/admin/repairers',      [RepairerController::class, 'index']);
$router->get('/admin/repairers/form', [RepairerController::class, 'form']);
$router->post('/repairer/store',      [RepairerController::class, 'store']);
$router->post('/repairer/update',     [RepairerController::class, 'update']);
$router->get('/repairer/delete',      [RepairerController::class, 'delete']);

// Maintenances
$router->get('/admin/maintenance',                [MaintenanceController::class, 'index']);
$router->get('/admin/maintenance/form',           [MaintenanceController::class, 'form']);
$router->post('/maintenance/store',               [MaintenanceController::class, 'store']);
$router->get('/maintenance/close',                [MaintenanceController::class, 'close']);
$router->get('/admin/maintenance/history',        [MaintenanceController::class, 'history']);
$router->post('/admin/maintenance/history/clear', [MaintenanceController::class, 'clearHistory']);

// Pièces (Inventaire)
$router->get('/admin/parts',                      [PartController::class, 'index']);
$router->post('/admin/parts/update-stock',        [PartController::class, 'updateStock']);
$router->get('/admin/parts/inventory',            [PartController::class, 'inventory']); // ✅ INVENTAIRE

// Démarrage du routeur
$router->run();
