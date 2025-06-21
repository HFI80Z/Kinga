<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Part extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM parts ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(string $name, int $stock): void
    {
        $stmt = $this->db->prepare("INSERT INTO parts (name, stock) VALUES (:name, :stock)");
        $stmt->execute([':name' => $name, ':stock' => $stock]);
    }

    public function updateStock(int $id, int $stock): void
    {
        $stmt = $this->db->prepare("UPDATE parts SET stock = :stock WHERE id = :id");
        $stmt->execute([':stock' => $stock, ':id' => $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare("DELETE FROM parts WHERE id = :id");
        $stmt->execute([':id' => $id]);
    }
}
