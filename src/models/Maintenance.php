<?php
declare(strict_types=1);
namespace App\Models;

use App\Core\Model;
use PDO;

class Maintenance extends Model
{
    /**
     * Récupère toutes les maintenances en cours (is_active = TRUE).
     * On joint la table vehicles et repairers pour avoir plus d’infos.
     */
    public function getAllActive(): array
    {
        $sql = '
            SELECT m.id AS maintenance_id,
                   m.vehicle_id,
                   v.immatriculation,
                   v.type,
                   v.fabricant,
                   v.modele,
                   r.id   AS repairer_id,
                   r.name AS repairer_name,
                   m.reason,
                   m.created_at
            FROM maintenance m
            JOIN vehicles v ON v.id = m.vehicle_id
            JOIN repairers r ON r.id = m.repairer_id
            WHERE m.is_active = TRUE
            ORDER BY m.created_at DESC
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère l’historique complet des maintenances (actives ou non).
     */
    public function getHistoryByVehicle(int $vehicleId): array
    {
        $sql = '
            SELECT m.id AS maintenance_id,
                   r.name AS repairer_name,
                   m.reason,
                   m.is_active,
                   m.created_at
            FROM maintenance m
            JOIN repairers r ON r.id = m.repairer_id
            WHERE m.vehicle_id = :vid
            ORDER BY m.created_at DESC
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':vid' => $vehicleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Trouve une maintenance par son ID (même si inactive).
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM maintenance WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Crée une nouvelle entrée de maintenance (active par défaut).
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO maintenance 
               (vehicle_id, repairer_id, reason, is_active)
             VALUES 
               (:vid, :rid, :reason, TRUE)
             RETURNING id'
        );
        $stmt->execute([
            ':vid'    => (int)$data['vehicle_id'],
            ':rid'    => (int)$data['repairer_id'],
            ':reason' => trim($data['reason']),
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Termine une maintenance (passe is_active à FALSE).
     */
    public function close(int $id): void
    {
        $stmt = $this->db->prepare(
            'UPDATE maintenance
             SET is_active = FALSE
             WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
    }
}
