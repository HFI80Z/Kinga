<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;
use PDOException;

class Vehicle extends Model
{
    /**
     * Récupère tous les véhicules
     *
     * @return array
     */
    public function getAll(): array
    {
        try {
            $stmt = $this->db->query('SELECT * FROM vehicles');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // logger($e->getMessage());
            return [];
        }
    }

    /**
     * Récupère un véhicule par son ID
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM vehicles WHERE id = :id LIMIT 1');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Crée un nouveau véhicule et renvoie son ID
     */
    public function create(array $data): int
    {
        $sql = '
            INSERT INTO vehicles 
              (immatriculation, type, fabricant, modele, couleur, nb_sieges, km)
            VALUES 
              (:immatriculation, :type, :fabricant, :modele, :couleur, :nb_sieges, :km)
            RETURNING id
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':immatriculation' => $data['immatriculation'],
            ':type'            => $data['type'],
            ':fabricant'       => $data['fabricant'],
            ':modele'          => $data['modele'],
            ':couleur'         => $data['couleur'],
            ':nb_sieges'       => $data['nb_sieges'],
            ':km'              => $data['km'],
        ]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Met à jour un véhicule existant
     */
    public function update(array $data): void
    {
        $sql = '
            UPDATE vehicles SET
              immatriculation = :immatriculation,
              type            = :type,
              fabricant       = :fabricant,
              modele          = :modele,
              couleur         = :couleur,
              nb_sieges       = :nb_sieges,
              km              = :km
            WHERE id = :id
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':immatriculation' => $data['immatriculation'],
            ':type'            => $data['type'],
            ':fabricant'       => $data['fabricant'],
            ':modele'          => $data['modele'],
            ':couleur'         => $data['couleur'],
            ':nb_sieges'       => $data['nb_sieges'],
            ':km'              => $data['km'],
            ':id'              => $data['id'],
        ]);
    }

    /**
     * Supprime définitivement un véhicule
     */
    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM vehicles WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
