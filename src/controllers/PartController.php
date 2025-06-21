<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Part;

class PartController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) $this->redirect('/');
        $parts = (new Part())->getAll();
        $this->view('parts/index', ['parts' => $parts]);
    }

    public function updateStock(): void
    {
        if (!Auth::check()) $this->redirect('/');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $stock = (int)($_POST['stock'] ?? 0);
            if ($id > 0) {
                (new Part())->updateStock($id, $stock);
            }
        }
        $this->redirect('/admin/parts');
    }

    public function inventory(): void
{
    if (!\App\Core\Auth::check()) {
        $this->redirect('/');
    }

    $partModel = new \App\Models\Part();
    $parts = $partModel->getAll();

    $this->view('parts/inventory', [
        'parts' => $parts
    ]);
}

}
