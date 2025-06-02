<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Vehicle;
use PDO;

class VehicleController extends Controller
{
    /**
     * Page publique : liste des véhicules disponibles (hors maintenance),
     * avec possibilité de cocher plusieurs valeurs de Type / Fabricant / Couleur.
     */
    public function index(): void
    {
        // 1) On récupère les filtres envoyés en GET
        //    type, fabricant et color sont des tableaux (cases à cocher), model reste une chaîne,
        //    seats et km_max restent des entiers.
        $fType      = $_GET['type']      ?? [];
        $fFabricant = $_GET['fabricant'] ?? [];
        $fModel     = trim($_GET['model'] ?? '');
        $fColor     = $_GET['color']     ?? [];
        $fSeats     = (int)($_GET['seats']   ?? 0);
        $fKmMax     = (int)($_GET['km_max'] ?? 0);

        // S’assurer que ce sont bien des tableaux
        if (!is_array($fType)) {
            $fType = $fType !== '' ? [$fType] : [];
        }
        if (!is_array($fFabricant)) {
            $fFabricant = $fFabricant !== '' ? [$fFabricant] : [];
        }
        if (!is_array($fColor)) {
            $fColor = $fColor !== '' ? [$fColor] : [];
        }

        // 2) Pagination
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 5;

        // 3) On appelle la méthode qui recherche hors maintenance active
        $vModel = new Vehicle();
        $all    = $vModel->searchExcludingMaintenance(
            $fType,
            $fFabricant,
            $fModel,
            $fColor,
            $fSeats,
            $fKmMax
        );

        // 4) Pagination manuelle
        $total      = count($all);
        $totalPages = (int)ceil($total / $limit);
        $offset     = ($page - 1) * $limit;
        $vehicles   = array_slice($all, $offset, $limit, true);

        // 5) On passe tout à la vue
        $this->view('vehicles/index', [
            'vehicles'   => $vehicles,
            'page'       => $page,
            'totalPages' => $totalPages,
            'offset'     => $offset,
            // pour préremplir les cases cochées dans index.php
            'type'       => $fType,
            'fabricant'  => $fFabricant,
            'model'      => $fModel,
            'color'      => $fColor,
            'seats'      => $fSeats,
            'km_max'     => $fKmMax,
        ]);
    }

    /**
     * Panneau admin : liste TOTALE des véhicules, avec overlay “En réparation”
     * pour ceux ayant maintenance active.
     */
    public function adminPanel(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        // Pagination
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 5;

        // 1) On récupère tous les véhicules
        $allVehicles = (new Vehicle())->getAll();

        // 2) On récupère les IDs en maintenance active
        $pdo = (new \App\Config\Database())->getConnection();
        $stmt = $pdo->query("
            SELECT vehicle_id
            FROM maintenance
            WHERE is_active = TRUE
        ");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $inMaintenance = [];
        foreach ($rows as $r) {
            $inMaintenance[(int)$r['vehicle_id']] = true;
        }

        // 3) Pagination manuelle
        $total      = count($allVehicles);
        $totalPages = (int)ceil($total / $limit);
        $offset     = ($page - 1) * $limit;
        $vehicles   = array_slice($allVehicles, $offset, $limit, true);

        // 4) On transmet à la vue
        $this->view('vehicles/admin', [
            'vehicles'      => $vehicles,
            'page'          => $page,
            'totalPages'    => $totalPages,
            'offset'        => $offset,
            'inMaintenance' => $inMaintenance
        ]);
    }

    /**
     * Affiche le formulaire de création d’un véhicule.
     */
    public function create(): void
    {
        Auth::check() ?: $this->redirect('/');
        $this->view('vehicles/form');
    }

    /**
     * Stocke un nouveau véhicule.
     */
    public function store(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        (new Vehicle())->create($_POST);
        $this->redirect('/admin');
    }

    /**
     * Affiche le formulaire d’édition.
     */
    public function edit(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        $id      = (int)($_GET['id'] ?? 0);
        $vehicle = (new Vehicle())->find($id) ?: $this->redirect('/admin');
        $this->view('vehicles/form', ['vehicle' => $vehicle]);
    }

    /**
     * Met à jour le véhicule.
     */
    public function update(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        $data = array_merge($_POST, ['id' => (int)($_POST['id'] ?? 0)]);
        (new Vehicle())->update($data);
        $this->redirect('/admin');
    }

    /**
     * Supprime le véhicule (et cascade supprime les maintenances liées).
     */
    public function delete(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        if ($id = (int)($_GET['id'] ?? 0)) {
            (new Vehicle())->delete($id);
        }
        $this->redirect('/admin');
    }
}
