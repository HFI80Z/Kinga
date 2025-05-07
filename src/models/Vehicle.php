<?php
namespace App\Models;

use App\Core\Model;
use PDO;
use PDOException;

class Vehicle extends Model
{
    /**
     * Récupère tous les véhicules non supprimés
     *
     * @return array
     */
    public function getAll(): array
    {
        $sql = 'SELECT * FROM vehicles WHERE deleted_at IS NULL';
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // logger($e->getMessage());
            return [];
        }
    }

    /**
     * Supprime définitivement le véhicule d'ID donné
     *
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM vehicles WHERE id = ?');
        $stmt->execute([$id]);
    }

    // TODO : ajouter create(), update(), etc.
}
