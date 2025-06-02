<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

class Vehicle extends Model
{
    /**
     * Recherche simple avec filtres (non utilisé depuis passage en checkbox,
     * mais conservé si besoin) :
     */
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

        if ($type)      { $sql .= ' AND type LIKE :type';      $p[':type']      = "%$type%"; }
        if ($fabricant) { $sql .= ' AND fabricant LIKE :fab';   $p[':fab']       = "%$fabricant%"; }
        if ($model)     { $sql .= ' AND modele LIKE :model';    $p[':model']     = "%$model%"; }
        if ($color)     { $sql .= ' AND couleur LIKE :color';   $p[':color']     = "%$color%"; }
        if ($seats > 0) { $sql .= ' AND nb_sieges = :seats';    $p[':seats']     = $seats; }
        if ($kmMax > 0) { $sql .= ' AND km <= :kmMax';         $p[':kmMax']     = $kmMax; }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($p);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère tous les véhicules (sans filtrage).
     */
    public function getAll(): array
    {
        return $this->db
            ->query('SELECT * FROM vehicles ORDER BY immatriculation')
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Trouve un véhicule par son ID.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM vehicles WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Crée un nouveau véhicule.
     */
    public function create(array $d): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO vehicles
               (immatriculation, type, fabricant, modele, couleur, nb_sieges, km)
             VALUES
               (:imm, :type, :fab, :mod, :col, :seats, :km)
             RETURNING id'
        );
        $stmt->execute([
            ':imm'   => trim($d['immatriculation']),
            ':type'  => trim($d['type']),
            ':fab'   => trim($d['fabricant']),
            ':mod'   => trim($d['modele']),
            ':col'   => trim($d['couleur']),
            ':seats' => (int)$d['nb_sieges'],
            ':km'    => (int)$d['km'],
        ]);
        return (int)$stmt->fetchColumn();
    }

    /**
     * Met à jour un véhicule existant.
     */
    public function update(array $d): void
    {
        $stmt = $this->db->prepare(
            'UPDATE vehicles SET
               immatriculation = :imm,
               type            = :type,
               fabricant       = :fab,
               modele          = :mod,
               couleur         = :col,
               nb_sieges       = :seats,
               km              = :km
             WHERE id = :id'
        );
        $stmt->execute([
            ':imm'   => trim($d['immatriculation']),
            ':type'  => trim($d['type']),
            ':fab'   => trim($d['fabricant']),
            ':mod'   => trim($d['modele']),
            ':col'   => trim($d['couleur']),
            ':seats' => (int)$d['nb_sieges'],
            ':km'    => (int)$d['km'],
            ':id'    => (int)$d['id'],
        ]);
    }

    /**
     * Supprime un véhicule.
     */
    public function delete(int $id): void
    {
        $this->db
            ->prepare('DELETE FROM vehicles WHERE id = :id')
            ->execute([':id' => $id]);
    }

    /**
     * Récupère tous les véhicules pour sélection en maintenance.
     */
    public function getAvailableForMaintenance(): array
    {
        $sql = '
            SELECT v.*
            FROM vehicles v
            LEFT JOIN maintenance m
              ON v.id = m.vehicle_id
             AND m.is_active = TRUE
            WHERE m.id IS NULL
            ORDER BY v.immatriculation
        ';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recherche + exclusion des véhicules en maintenance active,
     * avec possibilité de filtrer sur :
     *   - $types (tableau de valeurs, p. ex. ['Moto','Berline']),
     *   - $fabricants (tableau, p. ex. ['Honda','TVS']),
     *   - $model (string),
     *   - $colors (tableau de couleurs),
     *   - $seats (int),
     *   - $kmMax (int).
     */
    public function searchExcludingMaintenance(
        array  $types = [],
        array  $fabricants = [],
        string $model = '',
        array  $colors = [],
        int    $seats = 0,
        int    $kmMax = 0
    ): array {
        $where   = 'WHERE 1=1';
        $params  = [];

        // 1) FILTRE TYPE (tableau)
        if (!empty($types)) {
            $placeholders = [];
            foreach ($types as $i => $t) {
                $ph = ":t{$i}";
                $placeholders[] = $ph;
                $params[$ph] = $t;
            }
            $inClause = implode(', ', $placeholders);
            $where .= " AND v.type IN ($inClause)";
        }

        // 2) FILTRE FABRICANT (tableau)
        if (!empty($fabricants)) {
            $placeholders = [];
            foreach ($fabricants as $i => $f) {
                $ph = ":f{$i}";
                $placeholders[] = $ph;
                $params[$ph] = $f;
            }
            $inClause = implode(', ', $placeholders);
            $where .= " AND v.fabricant IN ($inClause)";
        }

        // 3) FILTRE MODEL (texte libre / LIKE)
        if ($model !== '') {
            $where .= ' AND v.modele LIKE :model';
            $params[':model'] = "%$model%";
        }

        // 4) FILTRE COULEUR (tableau)
        if (!empty($colors)) {
            $placeholders = [];
            foreach ($colors as $i => $c) {
                $ph = ":c{$i}";
                $placeholders[] = $ph;
                $params[$ph] = $c;
            }
            $inClause = implode(', ', $placeholders);
            $where .= " AND v.couleur IN ($inClause)";
        }

        // 5) FILTRE NB SIEGES (égalité)
        if ($seats > 0) {
            $where .= ' AND v.nb_sieges = :seats';
            $params[':seats'] = $seats;
        }

        // 6) FILTRE KM MAX (≤)
        if ($kmMax > 0) {
            $where .= ' AND v.km <= :kmMax';
            $params[':kmMax'] = $kmMax;
        }

        // Requête finale : on fait un LEFT JOIN sur maintenance pour exclure les véhicules en cours de maintenance
        $sql = "
            SELECT v.*
            FROM vehicles v
            LEFT JOIN maintenance m
              ON v.id = m.vehicle_id
             AND m.is_active = TRUE
            $where
            AND m.id IS NULL
            ORDER BY v.immatriculation
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
