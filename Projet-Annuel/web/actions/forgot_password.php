<?php
session_start();
require_once '../config/config.php';
require_once '../utils/database.php';
require_once '../utils/helpers.php';
require_once '../utils/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');

    $errors = [];
    
    if (empty($email)) {
        $errors[] = 'L\'adresse email est requise.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Adresse email invalide.';
    }
   
    if (empty($errors)) {
        $db = Database::getInstance();
        
        $user = $db->query(
            "SELECT id, first_name, last_name, email FROM users WHERE email = ? LIMIT 1",
            [$email],
            true
        );
        
        if ($user) {
            
            $token = generateResetToken($user['id']);
            
            
            $resetLink = APP_URL . '/index.php?page=reset_password&token=' . $token;
            $subject = 'Réinitialisation de votre mot de passe';
            $message = '
                <h1>Réinitialisation de votre mot de passe</h1>
                <p>Bonjour ' . $user['first_name'] . ' ' . $user['last_name'] . ',</p>
                <p>Vous avez demandé une réinitialisation de votre mot de passe. Cliquez sur le lien ci-dessous pour procéder :</p>
                <p><a href="' . $resetLink . '">Réinitialiser mon mot de passe</a></p>
                <p>Si vous n\'avez pas demandé cette réinitialisation, veuillez ignorer cet email.</p>
                <p>Ce lien expirera dans 24 heures.</p>
                <p>Cordialement,<br>L\'équipe Business Care</p>
            ';
            
            
            logAction('password_reset_request', 'Demande de réinitialisation de mot de passe', $user['id']);
            
           
            setAlert('Un email de réinitialisation a été envoyé à votre adresse email.', 'success');
        } else {
            
            setAlert('Si cette adresse email existe dans notre système, un email de réinitialisation sera envoyé.', 'info');
        }
        
        
        redirect('../index.php?page=login');
    } else {
        
        setAlert(implode('<br>', $errors), 'danger');
        $_SESSION['forgot_password_form_data'] = ['email' => $email];
        redirect('../index.php?page=forgot_password');
    }
} else {
  
    redirect('../index.php?page=forgot_password');
}