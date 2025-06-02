<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Maintenance;
use App\Models\MaintenancePart;
use App\Models\Repairer;
use App\Models\Vehicle as VehicleModel;

class MaintenanceController extends Controller
{
    /**
     * Affiche la liste des maintenances en cours (is_active = TRUE),
     * ainsi que les pièces nécessaires pour chaque maintenance.
     */
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        $mModel   = new Maintenance();
        $mpModel  = new MaintenancePart();

        // Récupérer toutes les maintenances actives
        $activeMaintenances = $mModel->getAllActive();
        // On ajoute pour chaque maintenance la liste des pièces associées
        foreach ($activeMaintenances as &$m) {
            $m['parts'] = $mpModel->getByMaintenance((int)$m['maintenance_id']);
        }
        unset($m);

        // On passe le tableau enrichi à la vue
        $this->view('maintenance/index', ['maintenances' => $activeMaintenances]);
    }

    /**
     * Formulaire pour créer une nouvelle maintenance.
     * Si ?vehicle_id=... est fourni, on pré-remplit le véhicule.
     */
    public function form(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        $vehicleId = isset($_GET['vehicle_id']) ? (int)$_GET['vehicle_id'] : 0;
        $vehicle   = null;
        if ($vehicleId > 0) {
            $vehicle = (new VehicleModel())->find($vehicleId);
            if (!$vehicle) {
                $this->redirect('/admin/maintenance');
            }
        }

        $repairers = (new Repairer())->getAll();
        $availableVehicles = [];
        if (!$vehicle) {
            $availableVehicles = (new VehicleModel())->getAvailableForMaintenance();
        }

        $this->view('maintenance/form', [
            'vehicle'           => $vehicle,
            'repairers'         => $repairers,
            'availableVehicles' => $availableVehicles
        ]);
    }

    /**
     * Crée une nouvelle maintenance (et ses pièces associées).
     */
    public function store(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        // 1) Enregistrer la maintenance
        $mData = [
            'vehicle_id'  => (int)($_POST['vehicle_id']  ?? 0),
            'repairer_id' => (int)($_POST['repairer_id'] ?? 0),
            'reason'      => trim($_POST['reason'] ?? '')
        ];
        $mModel = new Maintenance();
        $maintenanceId = $mModel->create($mData);

        // 2) Enregistrer les pièces (si fournies)
        $mpModel = new MaintenancePart();
        if (isset($_POST['part_name']) && is_array($_POST['part_name'])) {
            foreach ($_POST['part_name'] as $index => $pname) {
                $qty = (int)($_POST['quantity'][$index] ?? 0);
                if (trim($pname) !== '' && $qty > 0) {
                    $mpModel->create([
                        'maintenance_id' => $maintenanceId,
                        'part_name'      => trim($pname),
                        'quantity'       => $qty
                    ]);
                }
            }
        }

        $this->redirect('/admin/maintenance');
    }

    /**
     * Termine une maintenance (passe is_active à FALSE).
     */
    public function close(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            (new Maintenance())->close($id);
        }
        $this->redirect('/admin/maintenance');
    }
}
