<?php
declare(strict_types=1);
namespace App\Models;

use App\Core\Model;
use PDO;

class MaintenancePart extends Model
{
    /**
     * Récupère toutes les pièces associées à une maintenance donnée.
     */
    public function getByMaintenance(int $maintenanceId): array
    {
        $stmt = $this->db->prepare(
            'SELECT * 
             FROM maintenance_parts 
             WHERE maintenance_id = :mid'
        );
        $stmt->execute([':mid' => $maintenanceId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Insère une nouvelle pièce pour une maintenance donnée.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO maintenance_parts (maintenance_id, part_name, quantity)
             VALUES (:mid, :pname, :qty)
             RETURNING id'
        );
        $stmt->execute([
            ':mid'    => (int)$data['maintenance_id'],
            ':pname'  => trim($data['part_name']),
            ':qty'    => (int)$data['quantity'],
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Supprime toutes les pièces d’une maintenance (utile si on supprime la maintenance).
     */
    public function deleteByMaintenance(int $maintenanceId): void
    {
        $this->db
            ->prepare('DELETE FROM maintenance_parts WHERE maintenance_id = :mid')
            ->execute([':mid' => $maintenanceId]);
    }
}
