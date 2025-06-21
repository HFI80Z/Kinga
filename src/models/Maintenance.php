<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class Maintenance extends Model
{
    public function getAllActiveWithReasonLabel(): array
    {
        $sql = "
            SELECT
              m.id,
              m.vehicle_id,
              v.immatriculation,
              v.type,
              r.name AS repairer_name,
              sr.label AS reason_label,
              m.created_at
            FROM maintenance m
            JOIN vehicles v ON v.id = m.vehicle_id
            LEFT JOIN repairers r ON r.id = m.repairer_id
            LEFT JOIN service_reasons sr ON sr.id = m.reason_id
            WHERE m.is_active = TRUE
            ORDER BY m.created_at DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO maintenance (vehicle_id, repairer_id, service_id, reason_id, is_active)
            VALUES (:vid, :rid, :sid, :reason_id, TRUE)
            RETURNING id
        ");
        $stmt->execute([
            ':vid'       => $data['vehicle_id'],
            ':rid'       => $data['repairer_id'],
            ':sid'       => $data['service_id'],
            ':reason_id' => $data['reason_id']
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function close(int $id): void
    {
        $stmt = $this->db->prepare("
            UPDATE maintenance
            SET is_active = FALSE,
                closed_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([':id' => $id]);
    }

    public function getAllHistoryWithReasonLabel(): array
    {
        $sql = "
            SELECT
              m.id,
              v.immatriculation,
              v.type,
              r.name AS repairer_name,
              sr.label AS reason_label,
              m.created_at,
              m.closed_at
            FROM maintenance m
            JOIN vehicles v ON v.id = m.vehicle_id
            LEFT JOIN repairers r ON r.id = m.repairer_id
            LEFT JOIN service_reasons sr ON sr.id = m.reason_id
            ORDER BY m.created_at DESC
        ";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearAll(): void
    {
        $this->db->exec("DELETE FROM maintenance_parts");
        $this->db->exec("DELETE FROM maintenance");
    }
}
