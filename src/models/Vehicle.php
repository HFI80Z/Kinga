<?php
declare(strict_types=1);
namespace App\Models;

use App\Core\Model;
use PDO;

class Vehicle extends Model
{
    public function search(
        string $type = '',
        string $fabricant = '',
        string $model = '',
        string $color = '',
        int    $seats = 0,
        int    $kmMax = 0
    ): array {
        $sql = 'SELECT * FROM vehicles WHERE 1=1';
        $p   = [];

        if ($type)      { $sql .= ' AND type LIKE :type';           $p[':type']      = "%$type%"; }
        if ($fabricant) { $sql .= ' AND fabricant LIKE :fab';      $p[':fab']       = "%$fabricant%"; }
        if ($model)     { $sql .= ' AND modele LIKE :model';       $p[':model']     = "%$model%"; }
        if ($color)     { $sql .= ' AND couleur LIKE :color';      $p[':color']     = "%$color%"; }
        if ($seats > 0) { $sql .= ' AND nb_sieges = :seats';       $p[':seats']     = $seats; }
        if ($kmMax > 0) { $sql .= ' AND km <= :kmMax';            $p[':kmMax']     = $kmMax; }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($p);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll(): array
    {
        return $this->db
            ->query('SELECT * FROM vehicles')
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM vehicles WHERE id=:id');
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $d): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO vehicles
               (immatriculation,type,fabricant,modele,couleur,nb_sieges,km)
             VALUES
               (:imm,:type,:fab,:mod,:col,:seats,:km)
             RETURNING id'
        );
        $stmt->execute([
            ':imm'   => $d['immatriculation'],
            ':type'  => $d['type'],
            ':fab'   => $d['fabricant'],
            ':mod'   => $d['modele'],
            ':col'   => $d['couleur'],
            ':seats' => $d['nb_sieges'],
            ':km'    => $d['km'],
        ]);
        return (int)$stmt->fetchColumn();
    }

    public function update(array $d): void
    {
        $stmt = $this->db->prepare(
            'UPDATE vehicles SET
               immatriculation=:imm,
               type          =:type,
               fabricant     =:fab,
               modele        =:mod,
               couleur       =:col,
               nb_sieges     =:seats,
               km            =:km
             WHERE id=:id'
        );
        $stmt->execute([
            ':imm'   => $d['immatriculation'],
            ':type'  => $d['type'],
            ':fab'   => $d['fabricant'],
            ':mod'   => $d['modele'],
            ':col'   => $d['couleur'],
            ':seats' => $d['nb_sieges'],
            ':km'    => $d['km'],
            ':id'    => $d['id'],
        ]);
    }

    public function delete(int $id): void
    {
        $this->db
            ->prepare('DELETE FROM vehicles WHERE id=:id')
            ->execute([':id'=>$id]);
    }
}
