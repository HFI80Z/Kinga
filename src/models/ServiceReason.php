<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class ServiceReason extends Model
{
    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM service_reasons ORDER BY label");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
