<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Repairer;

class RepairerController extends Controller
{
    /**
     * Affiche la liste des réparateurs dans le panel admin.
     */
    public function index(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        $repairers = (new Repairer())->getAll();
        $this->view('repairers/admin', ['repairers' => $repairers]);
    }

    /**
     * Formulaire de création (ou édition si $id est défini).
     */
    public function form(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $repairer = null;
        if ($id > 0) {
            $repairer = (new Repairer())->find($id);
            if (!$repairer) {
                $this->redirect('/admin/repairers');
            }
        }
        $this->view('repairers/form', ['repairer' => $repairer]);
    }

    /**
     * Stocke un nouveau réparateur.
     */
    public function store(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        $data = [
            'name'    => $_POST['name'] ?? '',
            'contact' => $_POST['contact'] ?? '',
        ];
        (new Repairer())->create($data);
        $this->redirect('/admin/repairers');
    }

    /**
     * Met à jour un réparateur existant.
     */
    public function update(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        $data = [
            'id'      => (int)($_POST['id'] ?? 0),
            'name'    => $_POST['name'] ?? '',
            'contact' => $_POST['contact'] ?? '',
        ];
        (new Repairer())->update($data);
        $this->redirect('/admin/repairers');
    }

    /**
     * Supprime un réparateur (confirmation JS côté vue).
     */
    public function delete(): void
    {
        if (!Auth::check()) {
            $this->redirect('/');
        }
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id > 0) {
            (new Repairer())->delete($id);
        }
        $this->redirect('/admin/repairers');
    }
}
