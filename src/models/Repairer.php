<?php
declare(strict_types=1);
namespace App\Models;

use App\Core\Model;
use PDO;

class Repairer extends Model
{
    /**
     * Récupère tous les réparateurs (par défaut sans pagination).
     */
    public function getAll(): array
    {
        $stmt = $this->db->query('SELECT * FROM repairers ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Trouve un réparateur par son ID.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM repairers WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Crée un nouveau réparateur.
     */
    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO repairers (name, contact) 
             VALUES (:name, :contact)
             RETURNING id'
        );
        $stmt->execute([
            ':name'    => trim($data['name']),
            ':contact' => trim($data['contact']),
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Met à jour un réparateur existant.
     */
    public function update(array $data): void
    {
        $stmt = $this->db->prepare(
            'UPDATE repairers SET
               name    = :name,
               contact = :contact
             WHERE id = :id'
        );
        $stmt->execute([
            ':name'    => trim($data['name']),
            ':contact' => trim($data['contact']),
            ':id'      => (int)$data['id'],
        ]);
    }

    /**
     * Supprime un réparateur.
     */
    public function delete(int $id): void
    {
        $this->db
            ->prepare('DELETE FROM repairers WHERE id = :id')
            ->execute([':id' => $id]);
    }
}
