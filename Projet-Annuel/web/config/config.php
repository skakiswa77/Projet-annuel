<?php

define('APP_NAME', 'Business Care');
define('APP_VERSION', '1.0.0');
define('APP_URL', 'http://localhost:8888/Projet-Annuel');

define('DB_HOST', 'localhost');
define('DB_NAME', 'business_care');
define('DB_USER', 'root');
define('DB_PASS', 'root');


define('API_URL', APP_URL . '/api');
define('API_KEY', 'votre_api_key_ici');


define('MAIL_HOST', 'smtp.example.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'businesscareasm@gmail.com');
define('MAIL_PASSWORD', 'mot_de_passe');
define('MAIL_FROM_NAME', 'Business Care');


define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024);
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx', 'xls', 'xlsx']);


define('STRIPE_PUBLIC_KEY', 'pk_test_votre_cle_publique');
define('STRIPE_SECRET_KEY', 'sk_test_votre_cle_secrete');
define('ONESIGNAL_APP_ID', 'votre_app_id_onesignal');
define('ONESIGNAL_API_KEY', 'votre_api_key_onesignal');


define('CHATBOT_QUESTIONS_LIMIT_STARTER', 6);
define('CHATBOT_QUESTIONS_LIMIT_BASIC', 20);
define('CHATBOT_QUESTIONS_LIMIT_PREMIUM', -1); 

$available_languages = [
    'fr' => 'Français',
    'en' => 'English',
    'es' => 'Español',
];


$default_language = 'fr';


if (isset($_GET['lang']) && array_key_exists($_GET['lang'], $available_languages)) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (!isset($_SESSION['lang'])) {
    $_SESSION['lang'] = $default_language;
}


$language = $_SESSION['lang'];
$lang = [];
$language_file = __DIR__ . '/../languages/' . $language . '.php';
if (file_exists($language_file)) {
    require_once $language_file;
}
