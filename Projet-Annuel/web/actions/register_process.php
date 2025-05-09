<?php
session_start();
require_once '../config/config.php';
require_once '../utils/database.php';
require_once '../utils/helpers.php';
require_once '../utils/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $companyName = trim($_POST['company_name'] ?? '');
    $companyEmail = trim($_POST['company_email'] ?? '');
    $companyPhone = trim($_POST['company_phone'] ?? '');
    $companyAddress = trim($_POST['company_address'] ?? '');
    
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName = trim($_POST['last_name'] ?? '');
    $adminEmail = trim($_POST['admin_email'] ?? '');
    $adminPhone = trim($_POST['admin_phone'] ?? '');
    $gender = trim($_POST['gender'] ?? '');
    $birthdate = trim($_POST['birthdate'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $postalCode = trim($_POST['postal_code'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $role = trim($_POST['role'] ?? 'company_admin');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $requestAdminCode = isset($_POST['request_admin_code']); 
    
    $terms = isset($_POST['terms']);
    
    
    $errors = [];
    
    
    if ($role === 'company_admin') {
       
        if (empty($companyName)) {
            $errors[] = 'Le nom de l\'entreprise est requis.';
        }
        
        if (empty($companyEmail)) {
            $errors[] = 'L\'email de l\'entreprise est requis.';
        } elseif (!filter_var($companyEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email de l\'entreprise invalide.';
        }
    } elseif ($role === 'admin') {
       
        if (!$requestAdminCode) {
            $errors[] = 'Vous devez confirmer votre demande d\'accès administrateur.';
        }
    }
    
    
    if (empty($firstName)) {
        $errors[] = 'Le prénom est requis.';
    }
    
    if (empty($lastName)) {
        $errors[] = 'Le nom est requis.';
    }
    
    if (empty($adminEmail)) {
        $errors[] = 'L\'email est requis.';
    } elseif (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email invalide.';
    }
    
    if (empty($password)) {
        $errors[] = 'Le mot de passe est requis.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Le mot de passe doit contenir au moins 8 caractères.';
    }
    
    if ($password !== $confirmPassword) {
        $errors[] = 'Les mots de passe ne correspondent pas.';
    }
    
    if (!$terms) {
        $errors[] = 'Vous devez accepter les conditions d\'utilisation.';
    }
    
    
    if (empty($errors)) {
        $db = Database::getInstance();
        
        
        $existingUser = $db->query(
            "SELECT * FROM users WHERE email = ? LIMIT 1",
            [$adminEmail],
            true
        );
        
        if ($existingUser) {
            $errors[] = 'Cette adresse email est déjà utilisée.';
        } else {
            try {
                
                $db->beginTransaction();
                
                $companyId = null;
                
                
                if ($role === 'company_admin') {
                   
                    $existingCompany = $db->query(
                        "SELECT * FROM companies WHERE email = ? LIMIT 1",
                        [$companyEmail],
                        true
                    );
                    
                    if ($existingCompany) {
                        throw new Exception('Cette adresse email d\'entreprise est déjà utilisée.');
                    }
                    
                   
                    $companyId = $db->insert('companies', [
                        'name' => $companyName,
                        'email' => $companyEmail,
                        'phone' => $companyPhone,
                        'address' => $companyAddress,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    
                   
                    $db->insert('subscriptions', [
                        'company_id' => $companyId,
                        'plan_id' => 1, 
                        'start_date' => date('Y-m-d'),
                        'end_date' => date('Y-m-d', strtotime('+1 year')),
                        'is_active' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }
                
                
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                
             
                if ($role === 'admin') {
                    $isActive = 0; 
                } else {
                    $isActive = 1; 
                }
                
               
                $userId = $db->insert('users', [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $adminEmail,
                    'password' => $passwordHash,
                    'role' => $role,
                    'company_id' => $companyId,
                    'phone' => $adminPhone,
                    'gender' => $gender,
                    'birthdate' => $birthdate,
                    'address' => $address,
                    'postal_code' => $postalCode,
                    'city' => $city,
                    'is_active' => $isActive,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                if ($role === 'admin' && $requestAdminCode) {
                    
                    $db->insert('admin_requests', [
                        'user_id' => $userId,
                        'request_reason' => 'Inscription en tant qu\'administrateur',
                        'status' => 'pending',
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                    
               
                    $admins = $db->query(
                        "SELECT * FROM users WHERE role = 'admin' AND is_active = 1"
                    );
                    
                    if (!empty($admins)) {
                        foreach ($admins as $admin) {
                        
                        }
                    }
                } elseif ($role === 'provider') {
                 
                    $db->insert('provider_profiles', [
                        'user_id' => $userId,
                        'specialization_id' => 1, 
                        'hourly_rate' => 0, 
                        'is_verified' => 0, 
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                } elseif ($role === 'employee') {

                    if (empty($companyId)) {
                        throw new Exception('Aucune entreprise n\'a été spécifiée pour ce salarié.');
                    }
 
                    $cardUid = 'BC-' . strtoupper(bin2hex(random_bytes(6)));
                    $db->insert('nfc_cards', [
                        'user_id' => $userId,
                        'card_uid' => $cardUid,
                        'is_active' => 1,
                        'issued_date' => date('Y-m-d'),
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }

                $db->insert('notification_preferences', [
                    'user_id' => $userId,
                    'email_events' => 1,
                    'email_appointments' => 1,
                    'email_community' => 1,
                    'email_marketing' => 0,
                    'push_events' => 1,
                    'push_appointments' => 1,
                    'push_community' => 1,
                    'push_marketing' => 0,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                

                logAction('register', 'Inscription réussie en tant que ' . $role, $userId);

                $db->commit();
                

                if ($role === 'admin') {
                    setAlert('Votre demande d\'accès administrateur a été enregistrée. Elle est en attente d\'approbation. Vous recevrez un email avec un code de vérification une fois votre demande approuvée.', 'success');
                } else {
                    setAlert('Inscription réussie ! Vous pouvez maintenant vous connecter.', 'success');
                }
                

                $subject = 'Bienvenue sur Business Care';
                $message = '
                    <h1>Bienvenue sur Business Care, ' . htmlspecialchars($firstName) . ' !</h1>
                    <p>Merci de vous être inscrit sur notre plateforme. Votre compte a été créé avec succès.</p>
                ';
                
                if ($role === 'admin') {
                    $message .= '<p>Votre demande d\'accès administrateur est en cours d\'examen. Vous recevrez un email avec un code de vérification une fois votre demande approuvée.</p>';
                } else {
                    $message .= '<p>Vous pouvez dès maintenant vous connecter à votre espace.</p>';
                }
                
                $message .= '
                    <p>Cordialement,<br>L\'équipe Business Care</p>
                ';
                redirect('../index.php?page=login');
                exit;
            } catch (Exception $e) {
 
                $db->rollback();
                $errors[] = 'Erreur lors de l\'inscription: ' . $e->getMessage();
            }
        }
    }
    

    if (!empty($errors)) {
        setAlert(implode('<br>', $errors), 'danger');
        

        $_SESSION['register_form_data'] = [
            'company_name' => $companyName,
            'company_email' => $companyEmail,
            'company_phone' => $companyPhone,
            'company_address' => $companyAddress,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'admin_email' => $adminEmail,
            'admin_phone' => $adminPhone,
            'gender' => $gender,
            'birthdate' => $birthdate,
            'address' => $address,
            'postal_code' => $postalCode,
            'city' => $city,
            'role' => $role
        ];
        
        redirect('../index.php?page=register');
        exit;
    }
} else {

    redirect('../index.php?page=register');
    exit;
}

