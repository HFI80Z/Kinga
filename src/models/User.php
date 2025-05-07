<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class User extends Model
{
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = :e LIMIT 1');
        $stmt->execute(['e' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('
            INSERT INTO users 
              (user_name, user_firstname, email, user_password, role) 
            VALUES 
              (:name, :first, :email, :pass, :role)
            RETURNING id
        ');
        $stmt->execute([
            'name'  => $data['user_name'],
            'first' => $data['user_firstname'],
            'email' => $data['email'],
            'pass'  => password_hash($data['password'], PASSWORD_DEFAULT),
            'role'  => 'USER',
        ]);
        return (int)$stmt->fetchColumn();
    }
}
