<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Config\Database;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        $this->view('auth/login', ['error' => $error]);
    }

    public function login(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $email = $_POST['email']    ?? '';
        $plain = $_POST['password'] ?? '';

        $pdo = Database::getConnection();
        $stmt = $pdo->prepare(
            "SELECT id, email, role
             FROM users
             WHERE email = :email
               AND password = crypt(:plain, password)"
        );
        $stmt->execute([
            ':email' => $email,
            ':plain' => $plain,
        ]);

        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($user) {
            // on ne stocke pas le mot de passe
            $_SESSION['user'] = $user;
            $this->redirect('/');
        }

        $_SESSION['error'] = 'Email ou mot de passe incorrect.';
        $this->redirect('/login');
    }

    public function showRegister(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        $this->view('auth/register', ['error' => $error]);
    }

    public function register(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $email    = $_POST['email']    ?? '';
        $plain    = $_POST['password'] ?? '';
        $role     = 'user';

        // hasher le mot de passe en PHP
        $hash = password_hash($plain, PASSWORD_BCRYPT);

        try {
            $pdo = Database::getConnection();
            $stmt = $pdo->prepare(
                "INSERT INTO users (email, password, role)
                 VALUES (:email, :pass, :role)"
            );
            $stmt->execute([
                ':email' => $email,
                ':pass'  => $hash,
                ':role'  => $role,
            ]);
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Impossible de s’inscrire : ' . $e->getMessage();
            $this->redirect('/register');
        }

        // récupération de l’utilisateur pour la session
        $stmt = $pdo->prepare("SELECT id, email, role FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        $_SESSION['user'] = $user;
        $this->redirect('/');
    }

    public function logout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_destroy();
        $this->redirect('/');
    }
}
