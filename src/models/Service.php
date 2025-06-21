<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class Service extends Model
{
    public function getAllWithReasons(): array
    {
        $sql = "SELECT s.id as service_id, s.name as service_name, r.id as reason_id, r.label
                FROM services s
                LEFT JOIN service_reasons r ON s.id = r.service_id
                ORDER BY s.name, r.label";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $grouped = [];
        foreach ($rows as $row) {
            $sid = $row['service_id'];
            if (!isset($grouped[$sid])) {
                $grouped[$sid] = [
                    'id' => $sid,
                    'name' => $row['service_name'],
                    'reasons' => []
                ];
            }
            if ($row['reason_id']) {
                $grouped[$sid]['reasons'][] = [
                    'id' => $row['reason_id'],
                    'label' => $row['label']
                ];
            }
        }

        return array_values($grouped);
    }
}
