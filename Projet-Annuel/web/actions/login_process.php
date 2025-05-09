<?php
session_start();
require_once '../config/config.php';
require_once '../utils/database.php';
require_once '../utils/helpers.php';
require_once '../utils/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    
 
    $errors = [];
    
    if (empty($email)) {
        $errors[] = 'L\'email est requis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email invalide.';
    }
    
    if (empty($password)) {
        $errors[] = 'Le mot de passe est requis.';
    }
    
   
    if (empty($errors)) {
        $db = Database::getInstance();
        
       
        $user = $db->query(
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            [$email],
            true
        );
        
        if ($user && password_verify($password, $user['password'])) {
            
            if ($user['is_active'] != 1) {
                setAlert('Votre compte n\'est pas actif. Veuillez contacter l\'administrateur.', 'danger');
                redirect('../index.php?page=login');
                exit;
            }
            
           
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
            
            
            $db->update('users', 
                ['last_login' => date('Y-m-d H:i:s')], 
                'id = ?', 
                [$user['id']]
            );
            
           
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
                
               
                $db->delete('remember_tokens', 'user_id = ?', [$user['id']]);
                
                
                $db->insert('remember_tokens', [
                    'user_id' => $user['id'],
                    'token' => $token,
                    'expires_at' => $expires,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                
                setcookie('remember_token', $token, strtotime('+30 days'), '/', '', false, true);
            }
            
            
            logAction('login', 'Connexion réussie', $user['id']);
            
            
            switch ($user['role']) {
                case 'admin':
                    echo '<script>window.location.href = "../../web/back_office/dashboard.php";</script>';
                    exit;
                case 'company_admin':
                    echo '<script>window.location.href = "../../web/client_espace/dashboard.php";</script>';
                    exit;
                case 'employee':
                    echo '<script>window.location.href = "../../web/employee_espace/dashboard.php";</script>';
                    exit;
                case 'provider':
                    echo '<script>window.location.href = "../../web/provider_espace/dashboard.php";</script>';
                    exit;
                default:
                    echo '<script>window.location.href = "../../web/index.php";</script>';
                    exit;
            }
        } else {
            
            $errors[] = 'Email ou mot de passe incorrect.';
            
           
            logAction('login_failed', 'Tentative de connexion échouée pour: ' . $email);
        }
    }
    
    
    if (!empty($errors)) {
        setAlert(implode('<br>', $errors), 'danger');
        $_SESSION['login_email'] = $email;
        redirect('../index.php?page=login');
        exit;
    }
} else {
   
    redirect('../index.php?page=login');
    exit;
}