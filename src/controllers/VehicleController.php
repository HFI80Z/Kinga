<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Page publique : liste des véhicules disponibles (hors maintenance).
     */
    public function index(): void
    {
        // Récupération des filtres éventuels
        $f = [
            'type'      => trim($_GET['type']      ?? ''),
            'fabricant' => trim($_GET['fabricant'] ?? ''),
            'model'     => trim($_GET['model']     ?? ''),
            'color'     => trim($_GET['color']     ?? ''),
            'seats'     => (int)($_GET['seats']   ?? 0),
            'km_max'    => (int)($_GET['km_max'] ?? 0),
        ];

        // Pagination
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $limit  = 5;
        $vModel = new Vehicle();

        // On utilise la méthode searchExcludingMaintenance() pour ne plus afficher
        // les véhicules en maintenance active.
        $all    = $vModel->searchExcludingMaintenance(
            $f['type'],
            $f['fabricant'],
            $f['model'],
            $f['color'],
            $f['seats'],
            $f['km_max']
        );

        $total      = count($all);
        $totalPages = (int)ceil($total / $limit);
        $offset     = ($page - 1) * $limit;
        $vehicles   = array_slice($all, $offset, $limit, true);

        $this->view('vehicles/index', array_merge(
            [
                'vehicles'   => $vehicles,
                'page'       => $page,
                'totalPages' => $totalPages,
                'offset'     => $offset
            ],
            $f
        ));
    }

    /**
     * Panneau admin : liste TOTALE des véhicules, avec overlay “En réparation”
     * pour ceux qui ont une maintenance active.
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
        $allVehicles = (new Vehicle())->getAll(); // renvoie tous les enregistrements de vehicles

        // 2) On récupère le tableau des vehicle_id qui sont en maintenance active
        $pdo = (new \App\Config\Database())->getConnection();
        $stmt = $pdo->query("
            SELECT vehicle_id
            FROM maintenance
            WHERE is_active = TRUE
        ");
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $inMaintenance = [];
        foreach ($rows as $r) {
            $inMaintenance[(int)$r['vehicle_id']] = true;
        }

        // 3) Pagination manuelle
        $total      = count($allVehicles);
        $totalPages = (int)ceil($total / $limit);
        $offset     = ($page - 1) * $limit;
        $vehicles   = array_slice($allVehicles, $offset, $limit, true);

        // 4) On passe $inMaintenance à la vue pour qu’elle affiche l’overlay sur chaque ligne
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
