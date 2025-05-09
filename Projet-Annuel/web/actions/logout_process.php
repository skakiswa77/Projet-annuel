<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/helpers.php';


if (isset($_SESSION['user_id'])) {

    if (function_exists('logAction')) {
        $userId = $_SESSION['user_id'];
        logAction('logout', 'Déconnexion réussie', $userId);
    }
    
    if (isset($_COOKIE['remember_token'])) {
        if (class_exists('Database')) {
            $db = Database::getInstance();
            
            try {
                $db->delete('remember_tokens', 'user_id = ?', [$_SESSION['user_id']]);
            } catch (Exception $e) {
            }
        }
        
        setcookie('remember_token', '', time() - 3600, '/', '', false, true);
    }
    
    if (function_exists('setAlert')) {
        setAlert('Vous avez été déconnecté avec succès.', 'success');
    }
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();


if (defined('APP_URL')) {
    header("Location: " . APP_URL . "/index.php?page=login");
} else {
 
    header("Location: ../index.php?page=login");
}
exit;