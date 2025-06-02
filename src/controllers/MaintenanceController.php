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
     * Affiche la liste paginée des maintenances en cours (is_active = TRUE),
     * avec les pièces nécessaires pour chacune.
     */
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        $mModel  = new Maintenance();
        $mpModel = new MaintenancePart();

        // Récupérer toutes les maintenances actives
        $allActive = $mModel->getAllActive();  // tableau complet

        // Pagination
        $page       = max(1, (int)($_GET['page'] ?? 1));
        $perPage    = 5;
        $total      = count($allActive);
        $totalPages = (int)ceil($total / $perPage);
        $offset     = ($page - 1) * $perPage;

        // Extraire la sous-liste (slice) des éléments à afficher
        $activePage = array_slice($allActive, $offset, $perPage, true);

        // Pour chaque maintenance de la page, récupérer les pièces associées
        foreach ($activePage as &$m) {
            $m['parts'] = $mpModel->getByMaintenance((int)$m['maintenance_id']);
        }
        unset($m);

        // Appeler la vue avec les données paginées
        $this->view('maintenance/index', [
            'maintenances' => $activePage,
            'page'         => $page,
            'totalPages'   => $totalPages
        ]);
    }

    /**
     * Formulaire pour créer une nouvelle maintenance.
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

        // Récupérer tous les réparateurs
        $repairers = (new Repairer())->getAll();

        // Si aucun véhicule pré-sélectionné, charger ceux disponibles pour maintenance
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
     * Stocke une nouvelle maintenance (et ses pièces associées).
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

        // 2) Enregistrer les pièces (part_name[] et quantity[])
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
     * Termine (clôture) une maintenance active :
     *   - passe is_active à FALSE
     *   - remplit closed_at avec NOW()
     */
    public function close(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            (new Maintenance())->close((int)$id);
        }
        $this->redirect('/admin/maintenance');
    }

    /**
     * Affiche l’historique complet de toutes les maintenances
     * (actives ou clôturées), avec pagination et durée entre début et fin.
     */
    public function history(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        $mModel     = new Maintenance();
        $allHistory = $mModel->getAllHistory(); // tableau complet

        // Pagination
        $page       = max(1, (int)($_GET['page'] ?? 1));
        $perPage    = 5;
        $total      = count($allHistory);
        $totalPages = (int)ceil($total / $perPage);
        $offset     = ($page - 1) * $perPage;

        // Extraire la page courante
        $historyPage = array_slice($allHistory, $offset, $perPage, true);

        // Calculer la durée pour chaque entrée
        foreach ($historyPage as &$h) {
            if ($h['closed_at'] !== null) {
                $start    = new \DateTime($h['created_at']);
                $end      = new \DateTime($h['closed_at']);
                $interval = $start->diff($end);
                $h['duration'] = sprintf(
                    '%d jours %02d:%02d:%02d',
                    $interval->d,
                    $interval->h,
                    $interval->i,
                    $interval->s
                );
            } else {
                $h['duration'] = '—';
            }
        }
        unset($h);

        $this->view('maintenance/history', [
            'history'    => $historyPage,
            'page'       => $page,
            'totalPages' => $totalPages
        ]);
    }

    /**
     * Supprime entièrement l’historique (toutes les maintenances & pièces),
     * qu’elles soient en cours ou déjà fermées.
     */
    public function clearHistory(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        (new Maintenance())->clearAll();
        $this->redirect('/admin/maintenance/history');
    }
}
