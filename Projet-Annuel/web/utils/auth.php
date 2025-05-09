<?php
/**
 * Connecte un utilisateur
 * @param int $user_id ID de l'utilisateur
 * @param string $user_name Nom de l'utilisateur
 * @param string $user_role Rôle de l'utilisateur
 * @param int $company_id ID de l'entreprise (optionnel)
 */
function login($user_id, $user_name, $user_role, $company_id = null) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = $user_name;
    $_SESSION['user_role'] = $user_role;
    if ($company_id !== null) {
        $_SESSION['company_id'] = $company_id;
    }
    

    logAction('login', 'Connexion réussie', $user_id);
    

    redirect('index.php?page=dashboard');
}

function logout() {

    if (isset($_SESSION['user_id'])) {
        logAction('logout', 'Déconnexion', $_SESSION['user_id']);
    }
    

    session_unset();
    session_destroy();
    

    redirect('index.php');
}

/**
 * Vérifie si un utilisateur existe
 * @param string $email Email de l'utilisateur
 * @return array|false Données de l'utilisateur ou false s'il n'existe pas
 */
function getUserByEmail($email) {
    $db = Database::getInstance();
    return $db->query(
        "SELECT * FROM users WHERE email = ? LIMIT 1",
        [$email],
        true
    );
}

/**
 * Vérifie si un mot de passe est correct
 * @param string $password Mot de passe en clair
 * @param string $hash Hash du mot de passe
 * @return bool True si le mot de passe est correct, false sinon
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Hache un mot de passe
 * @param string $password Mot de passe en clair
 * @return string Hash du mot de passe
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * Génère un token de réinitialisation de mot de passe
 * @param int $user_id ID de l'utilisateur
 * @return string Token généré
 */
function generateResetToken($user_id) {
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+24 hours'));
    
    $db = Database::getInstance();
    $db->insert('password_resets', [
        'user_id' => $user_id,
        'token' => $token,
        'expires_at' => $expiry
    ]);
    
    return $token;
}

/**
 * Vérifie si un token de réinitialisation est valide
 * @param string $token Token à vérifier
 * @return int|false ID de l'utilisateur si valide, false sinon
 */
function validateResetToken($token) {
    $db = Database::getInstance();
    $reset = $db->query(
        "SELECT user_id FROM password_resets WHERE token = ? AND expires_at > NOW() LIMIT 1",
        [$token],
        true
    );
    
    return $reset ? $reset['user_id'] : false;
}

/**
 * Réinitialise le mot de passe d'un utilisateur
 * @param int $user_id ID de l'utilisateur
 * @param string $password Nouveau mot de passe
 * @return bool True si la réinitialisation a réussi, false sinon
 */
function resetPassword($user_id, $password) {
    $db = Database::getInstance();
    $hash = hashPassword($password);
    
    // Mise à jour du mot de passe
    $result = $db->update('users', ['password' => $hash], 'id = ?', [$user_id]);
    
    // Suppression des tokens de réinitialisation
    $db->delete('password_resets', 'user_id = ?', [$user_id]);
    
    // Journalisation
    if ($result) {
        logAction('password_reset', 'Réinitialisation du mot de passe', $user_id);
    }
    
    return $result > 0;
}

/**
 * Vérifie si l'utilisateur a le droit d'accéder à une ressource
 * @param string $resource Resource à vérifier
 * @param string $action Action à effectuer (read, write, delete)
 * @return bool True si l'utilisateur a le droit, false sinon
 */
function hasPermission($resource, $action) {
    // À implémenter selon les règles métier
    // Cette fonction est un placeholder
    return true;
}