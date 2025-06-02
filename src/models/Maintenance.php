<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class Maintenance extends Model
{
    /**
     * Renvoie toutes les maintenances actives (is_active = TRUE),
     * avec véhicule (immatriculation, type, fabricant, modele) et réparateur (nom).
     */
    public function getAllActive(): array
    {
        $sql = "
            SELECT
              m.id                 AS maintenance_id,
              m.vehicle_id         AS vehicle_id,
              v.immatriculation    AS immatriculation,
              v.type               AS type,
              v.fabricant          AS fabricant,
              v.modele             AS modele,
              m.repairer_id        AS repairer_id,
              r.name               AS repairer_name,
              m.reason             AS reason,
              m.created_at         AS created_at
            FROM maintenance m
            JOIN vehicles v
              ON v.id = m.vehicle_id
            LEFT JOIN repairers r
              ON r.id = m.repairer_id
            WHERE m.is_active = TRUE
            ORDER BY m.created_at DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle maintenance (avec is_active = TRUE, closed_at = NULL).
     * Retourne l'ID créé.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO maintenance
              (vehicle_id, repairer_id, reason, is_active)
            VALUES
              (:vid, :rid, :reason, TRUE)
            RETURNING id
        ");
        $stmt->execute([
            ':vid'    => $data['vehicle_id'],
            ':rid'    => $data['repairer_id'],
            ':reason' => $data['reason'],
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Clôture la maintenance d'ID $id : passe is_active=FALSE et closed_at = NOW().
     */
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

    /**
     * Renvoie toutes les maintenances (actives ou fermées), avec
     * véhicule (immatriculation, type, fabricant, modele),
     * réparateur (nom), raison, created_at, closed_at.
     */
    public function getAllHistory(): array
    {
        $sql = "
            SELECT
              m.id              AS maintenance_id,
              v.immatriculation AS immatriculation,
              v.type            AS type,
              v.fabricant       AS fabricant,
              v.modele          AS modele,
              r.name            AS repairer_name,
              m.reason          AS reason,
              m.created_at      AS created_at,
              m.closed_at       AS closed_at
            FROM maintenance m
            JOIN vehicles v
              ON v.id = m.vehicle_id
            LEFT JOIN repairers r
              ON r.id = m.repairer_id
            ORDER BY m.created_at DESC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime toutes les maintenances fermées (closed_at IS NOT NULL).
     */
    public function clearClosed(): void
    {
        $this->db->exec("
            DELETE FROM maintenance
            WHERE closed_at IS NOT NULL
        ");
    }
}
