<?php
session_start();
require_once '../config/config.php';
require_once '../utils/database.php';
require_once '../utils/helpers.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    $privacy = isset($_POST['privacy']);
    

    $errors = [];
    
    if (empty($name)) {
        $errors[] = 'Le nom est requis.';
    }
    
    if (empty($email)) {
        $errors[] = 'L\'email est requis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email invalide.';
    }
    
    if (empty($subject)) {
        $errors[] = 'Le sujet est requis.';
    }
    
    if (empty($message)) {
        $errors[] = 'Le message est requis.';
    }
    
    if (!$privacy) {
        $errors[] = 'Vous devez accepter la politique de confidentialité.';
    }
    

    if (empty($errors)) {
        $db = Database::getInstance();
        
        try {
           
            $db->insert('contact_messages', [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company' => $company,
                'subject' => $subject,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            
           
            setAlert('Votre message a été envoyé avec succès. Nous vous répondrons dans les plus brefs délais.', 'success');
            
            redirect('../index.php?page=contact');
        } catch (Exception $e) {
            $errors[] = 'Une erreur est survenue lors de l\'envoi du message: ' . $e->getMessage();
        }
    }
    
   
    if (!empty($errors)) {
        setAlert(implode('<br>', $errors), 'danger');
        
        
        $_SESSION['contact_form_data'] = [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'company' => $company,
            'subject' => $subject,
            'message' => $message
        ];
        
        redirect('../index.php?page=contact');
    }
} else {
    
    redirect('../index.php?page=contact');
}