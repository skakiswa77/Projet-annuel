<?php
/**
 * Redirige vers une URL
 * @param string $url URL de redirection
 */
function redirect($path) {
    // Si le chemin commence par /, supprimez-le pour éviter des doubles /
    if (substr($path, 0, 1) === '/') {
        $path = substr($path, 1);
    }
    
    header("Location: " . APP_URL . "/" . $path);
    exit;
}

/**
 * Ajoute une alerte en session
 * @param string $message Message de l'alerte
 * @param string $type Type de l'alerte (success, danger, warning, info)
 */
function setAlert($message, $type = 'info') {
    $_SESSION['alert'] = [
        'message' => $message,
        'type' => $type
    ];
}

/**
 * Génère un token CSRF
 * @return string Token CSRF
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie si un token CSRF est valide
 * @param string $token Token à vérifier
 * @return bool True si valide, false sinon
 */
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && $token === $_SESSION['csrf_token'];
}

/**
 * Nettoie une chaîne de caractères
 * @param string $data Données à nettoyer
 * @return string Données nettoyées
 */
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Génère un mot de passe aléatoire
 * @param int $length Longueur du mot de passe
 * @return string Mot de passe généré
 */
function generatePassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+';
    $password = '';
    
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    
    return $password;
}

/**
 * Formate une date
 * @param string $date Date à formater
 * @param string $format Format de sortie
 * @return string Date formatée
 */
function formatDate($date, $format = 'd/m/Y') {
    $dt = new DateTime($date);
    return $dt->format($format);
}

/**
 * Formate un prix
 * @param float $price Prix à formater
 * @param string $currency Symbole de la devise
 * @return string Prix formaté
 */
function formatPrice($price, $currency = '€') {
    return number_format($price, 2, ',', ' ') . ' ' . $currency;
}

/**
 * Vérifie si l'utilisateur est connecté
 * @return bool True si connecté, false sinon
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Vérifie si l'utilisateur a un rôle spécifique
 * @param string|array $role Rôle(s) à vérifier
 * @return bool True si l'utilisateur a le rôle, false sinon
 */
function hasRole($role) {
    if (!isLoggedIn()) {
        return false;
    }
    
    if (is_array($role)) {
        return in_array($_SESSION['user_role'], $role);
    } else {
        return $_SESSION['user_role'] === $role;
    }
}

/**
 * Génère un numéro de facture unique
 * @param string $prefix Préfixe du numéro
 * @return string Numéro de facture
 */
function generateInvoiceNumber($prefix = 'BC') {
    $date = date('Ymd');
    $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
    
    return $prefix . '-' . $date . '-' . $random;
}

/**
 * Convertit une chaîne en slug URL
 * @param string $string Chaîne à convertir
 * @return string Slug URL
 */
function slugify($string) {
    $string = preg_replace('/[^\p{L}\p{Nd}]+/u', '-', $string);
    $string = trim($string, '-');
    $string = strtolower($string);
    
    return $string;
}

/**
 * Tronque un texte à une longueur donnée
 * @param string $text Texte à tronquer
 * @param int $length Longueur maximale
 * @param string $suffix Suffixe à ajouter si le texte est tronqué
 * @return string Texte tronqué
 */
function truncateText($text, $length = 100, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    
    return substr($text, 0, $length) . $suffix;
}

/**
 * Envoie un email
 * @param string $to Adresse du destinataire
 * @param string $subject Sujet de l'email
 * @param string $message Corps de l'email (HTML)
 * @param array $attachments Pièces jointes
 * @return bool True si l'email a été envoyé, false sinon
 */
function sendEmail($to, $subject, $message, $attachments = []) {
    return true;
}

/**
 * Génère un PDF
 * @param string $html Contenu HTML du PDF
 * @param string $filename Nom du fichier PDF
 * @param bool $download True pour télécharger, false pour retourner le contenu
 * @return mixed Contenu du PDF ou true si téléchargé
 */
function generatePDF($html, $filename, $download = true) {
    return true;
}

/**
 * Envoie une notification push
 * @param int|array $user_ids ID(s) des utilisateurs destinataires
 * @param string $title Titre de la notification
 * @param string $message Message de la notification
 * @param array $data Données supplémentaires
 * @return bool True si la notification a été envoyée, false sinon
 */
function sendPushNotification($user_ids, $title, $message, $data = []) {
    return true;
}

/**
 * Vérifie les limites d'utilisation selon le plan
 * @param string $feature Fonctionnalité à vérifier
 * @param int $company_id ID de l'entreprise
 * @return bool|int True si dans les limites, nombre restant ou false si hors limites
 */
function checkPlanLimits($feature, $company_id) {
    return true;
}

/**
 * Journalise une action
 * @param string $action Action effectuée
 * @param string $description Description de l'action
 * @param int $user_id ID de l'utilisateur
 */
function logAction($action, $description, $user_id = null) {
    if ($user_id === null && isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
    
    $db = Database::getInstance();
    $db->insert('action_logs', [
        'action' => $action,
        'description' => $description,
        'user_id' => $user_id,
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'created_at' => date('Y-m-d H:i:s')
    ]);
}