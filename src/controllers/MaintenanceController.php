<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Maintenance;
use App\Models\MaintenancePart;
use App\Models\Repairer;
use App\Models\Vehicle as VehicleModel;
use App\Models\Service;
use App\Models\Part;

class MaintenanceController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        $mModel  = new Maintenance();
        $mpModel = new MaintenancePart();

        $allActive = $mModel->getAllActiveWithReasonLabel();

        $page       = max(1, (int)($_GET['page'] ?? 1));
        $perPage    = 5;
        $total      = count($allActive);
        $totalPages = (int)ceil($total / $perPage);
        $offset     = ($page - 1) * $perPage;

        $activePage = array_slice($allActive, $offset, $perPage, true);

        foreach ($activePage as &$m) {
            $m['parts'] = $mpModel->getByMaintenance((int)$m['id']);
        }
        unset($m);

        $this->view('maintenance/index', [
            'maintenances' => $activePage,
            'page'         => $page,
            'totalPages'   => $totalPages
        ]);
    }

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
        $availableVehicles = $vehicle ? [] : (new VehicleModel())->getAvailableForMaintenance();
        $services = (new Service())->getAllWithReasons();
        $parts = (new Part())->getAll();

        $this->view('maintenance/form', [
            'vehicle'           => $vehicle,
            'repairers'         => $repairers,
            'availableVehicles' => $availableVehicles,
            'services'          => $services,
            'parts'             => $parts
        ]);
    }

    public function store(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        $mData = [
            'vehicle_id'  => (int)($_POST['vehicle_id']  ?? 0),
            'repairer_id' => (int)($_POST['repairer_id'] ?? 0),
            'service_id'  => (int)($_POST['service_id']  ?? 0),
            'reason_id'   => (int)($_POST['reason_id']   ?? 0)
        ];

        $mModel = new Maintenance();
        $maintenanceId = $mModel->create($mData);

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

    public function history(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        $mModel     = new Maintenance();
        $allHistory = $mModel->getAllHistoryWithReasonLabel();
        $page       = max(1, (int)($_GET['page'] ?? 1));
        $perPage    = 5;
        $total      = count($allHistory);
        $totalPages = (int)ceil($total / $perPage);
        $offset     = ($page - 1) * $perPage;

        $historyPage = array_slice($allHistory, $offset, $perPage, true);

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

    public function clearHistory(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        (new Maintenance())->clearAll();
        $this->redirect('/admin/maintenance/history');
    }
}
