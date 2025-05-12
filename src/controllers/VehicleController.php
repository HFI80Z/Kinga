<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Page publique : liste des véhicules
     */
    public function index(): void
    {
        $vehicles = (new Vehicle())->getAll();
        $this->view('vehicles/index', ['vehicles' => $vehicles]);
    }

    /**
     * Panneau admin : liste + formulaire d’ajout
     */
    public function adminPanel(): void
    {
        if (! Auth::check()) {
            $this->redirect('/');
        }

        $vehicles = (new Vehicle())->getAll();
        $this->view('vehicles/admin', ['vehicles' => $vehicles]);
    }

    /**
     * Formulaire de création
     */
    public function create(): void
    {
        if (! Auth::check()) {
            $this->redirect('/');
        }

        // pour créer
        $this->view('vehicles/form');
    }

    /**
     * Traitement du formulaire d’ajout
     */
    public function store(): void
    {
        if (! Auth::check()) {
            $this->redirect('/');
        }

        $data = [
            'immatriculation' => trim($_POST['immatriculation'] ?? ''),
            'type'            => trim($_POST['type']            ?? ''),
            'fabricant'       => trim($_POST['fabricant']       ?? ''),
            'modele'          => trim($_POST['modele']          ?? ''),
            'couleur'         => trim($_POST['couleur']         ?? ''),
            'nb_sieges'       => (int) ($_POST['nb_sieges']      ?? 0),
            'km'              => (int) ($_POST['km']             ?? 0),
        ];

        (new Vehicle())->create($data);
        $this->redirect('/admin');
    }

    /**
     * Edition d’un véhicule existant
     */
    public function edit(): void
    {
        if (! Auth::check()) {
            $this->redirect('/');
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            $this->redirect('/admin');
        }

        $vehicle = (new Vehicle())->find($id);
        if (! $vehicle) {
            $this->redirect('/admin');
        }

        // pour modifier
        $this->view('vehicles/form', ['vehicle' => $vehicle]);
    }

    /**
     * Mise à jour après édition
     */
    public function update(): void
    {
        if (! Auth::check()) {
            $this->redirect('/');
        }

        $data = [
            'id'              => (int) ($_POST['id']             ?? 0),
            'immatriculation' => trim($_POST['immatriculation'] ?? ''),
            'type'            => trim($_POST['type']            ?? ''),
            'fabricant'       => trim($_POST['fabricant']       ?? ''),
            'modele'          => trim($_POST['modele']          ?? ''),
            'couleur'         => trim($_POST['couleur']         ?? ''),
            'nb_sieges'       => (int) ($_POST['nb_sieges']      ?? 0),
            'km'              => (int) ($_POST['km']             ?? 0),
        ];

        if ($data['id'] > 0) {
            (new Vehicle())->update($data);
        }

        $this->redirect('/admin');
    }

    /**
     * Suppression d’un véhicule
     */
    public function delete(): void
    {
        if (! Auth::check()) {
            $this->redirect('/');
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id > 0) {
            (new Vehicle())->delete($id);
        }

        $this->redirect('/admin');
    }
}
