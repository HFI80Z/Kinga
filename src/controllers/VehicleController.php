<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index(): void
    {
        // Lire les filtres
        $f = [
            'type'      => trim($_GET['type']      ?? ''),
            'fabricant' => trim($_GET['fabricant'] ?? ''),
            'model'     => trim($_GET['model']     ?? ''),
            'color'     => trim($_GET['color']     ?? ''),
            'seats'     => (int) ($_GET['seats']   ?? 0),
            'km_max'    => (int) ($_GET['km_max']  ?? 0),
        ];

        $vModel   = new Vehicle();
        $vehicles = $vModel->search(
            $f['type'],
            $f['fabricant'],
            $f['model'],
            $f['color'],
            $f['seats'],
            $f['km_max']
        );

        $this->view('vehicles/index', array_merge(['vehicles'=>$vehicles], $f));
    }

    public function adminPanel(): void
    {
        if (!Auth::check()) $this->redirect('/');
        $this->view('vehicles/admin', ['vehicles'=>(new Vehicle())->getAll()]);
    }

    public function create(): void
    {
        Auth::check() ?: $this->redirect('/');
        $this->view('vehicles/form');
    }

    public function store(): void
    {
        if (!Auth::check()) $this->redirect('/');
        (new Vehicle())->create($_POST);
        $this->redirect('/admin');
    }

    public function edit(): void
    {
        if (!Auth::check()) $this->redirect('/');
        $id      = (int)($_GET['id'] ?? 0);
        $vehicle = (new Vehicle())->find($id) ?: $this->redirect('/admin');
        $this->view('vehicles/form', ['vehicle'=>$vehicle]);
    }

    public function update(): void
    {
        if (!Auth::check()) $this->redirect('/');
        $data = array_merge($_POST, ['id'=>(int)($_POST['id'] ?? 0)]);
        (new Vehicle())->update($data);
        $this->redirect('/admin');
    }

    public function delete(): void
    {
        if (!Auth::check()) $this->redirect('/');
        if ($id = (int)($_GET['id'] ?? 0)) {
            (new Vehicle())->delete($id);
        }
        $this->redirect('/admin');
    }
}
