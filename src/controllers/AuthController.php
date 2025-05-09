<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Config\Database;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);

        $this->view('auth/login', ['error' => $error]);
    }

    public function login()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $plain = isset($_POST['password']) ? $_POST['password'] : '';

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            "SELECT id, user_name, user_firstname, email, password, role
             FROM users
             WHERE email = :email
             LIMIT 1"
        );
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user && password_verify($plain, $user['password'])) {
            unset($user['password']);  // on ne stocke pas le hash en session
            $_SESSION['user'] = $user;
            $this->redirect('/');
        }

        $_SESSION['error'] = 'Email ou mot de passe incorrect.';
        $this->redirect('/login');
    }

    public function showRegister()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $error = isset($_SESSION['error']) ? $_SESSION['error'] : null;
        unset($_SESSION['error']);

        $this->view('auth/register', ['error' => $error]);
    }

    public function register()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $name  = isset($_POST['user_name'])      ? trim($_POST['user_name'])      : '';
        $first = isset($_POST['user_firstname']) ? trim($_POST['user_firstname']) : '';
        $email = isset($_POST['email'])          ? trim($_POST['email'])          : '';
        $plain = isset($_POST['password'])       ? $_POST['password']             : '';
        $role  = 'user';

        $hash = password_hash($plain, PASSWORD_BCRYPT);

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare("
                INSERT INTO users (user_name, user_firstname, email, password, role)
                VALUES (:name, :first, :email, :pass, :role)
            ");
            $stmt->execute([
                ':name'  => $name,
                ':first' => $first,
                ':email' => $email,
                ':pass'  => $hash,
                ':role'  => $role,
            ]);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Impossible de s’inscrire : ' . $e->getMessage();
            $this->redirect('/register');
        }

        // On récupère l’utilisateur pour la session
        $stmt = $pdo->prepare("
            SELECT id, user_name, user_firstname, email, role
            FROM users
            WHERE email = :email
            LIMIT 1
        ");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        $_SESSION['user'] = $user;
        $this->redirect('/');
    }

    public function logout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_destroy();
        $this->redirect('/');
    }
}
