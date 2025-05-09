<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Vehicle;
use App\Core\Auth;

class VehicleController extends Controller
{
    public function index(): void
    {
        $model    = new Vehicle();
        $vehicles = $model->getAll();
        $this->view('vehicles/index', ['vehicles' => $vehicles]);
    }

    // … create(), store(), edit(), update() laissés en TODO …

    public function delete(): void
    {
        // tu connais pour que seulement les admins peuvent supprimer
        if (! Auth::check()) {
            $this->redirect('/');
        }

        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            $model = new Vehicle();
            $model->delete($id);
        }

        $this->redirect('/');
    }
}
