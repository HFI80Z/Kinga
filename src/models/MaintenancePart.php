<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class MaintenancePart extends Model
{
    /**
     * Crée une pièce (part_name, quantity) liée à une maintenance.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO maintenance_parts (maintenance_id, part_name, quantity)
            VALUES (:mid, :pname, :qty)
            RETURNING id
        ");
        $stmt->execute([
            ':mid'   => $data['maintenance_id'],
            ':pname' => $data['part_name'],
            ':qty'   => $data['quantity']
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Renvoie toutes les pièces (part_name, quantity) pour une maintenance donnée.
     */
    public function getByMaintenance(int $maintenanceId): array
    {
        $stmt = $this->db->prepare("
            SELECT part_name, quantity
            FROM maintenance_parts
            WHERE maintenance_id = :mid
        ");
        $stmt->execute([':mid' => $maintenanceId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
