<?php
session_start();

require_once 'config/config.php';
require_once 'utils/database.php';
require_once 'utils/helpers.php';


$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include_once 'includes/header.php';


switch ($page) {
    case 'home':
        include_once 'pages/home.php';
        break;
    case 'login':
        include_once 'pages/login.php';
        break;
    case 'register':
        include_once 'pages/register.php';
        break;
    case 'services':
        include_once 'pages/services.php';
        break;
    case 'about':
        include_once 'pages/about.php';
        break;
    case 'contact':
        include_once 'pages/contact.php';
        break;
    case 'quote':
            include_once 'pages/quote.php';
            break;


    case 'dashboard':

        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }


        if ($_SESSION['user_role'] === 'admin') {
            include_once 'back_office/dashboard.php';
        } elseif ($_SESSION['user_role'] === 'company_admin') {
            include_once 'client_espace/dashboard.php';
        } elseif ($_SESSION['user_role'] === 'employee') {
            include_once 'employee_espace/dashboard.php';
        } elseif ($_SESSION['user_role'] === 'provider') {
            include_once 'provider_espace/dashboard.php';
        }
        break;
    default:
        include_once 'pages/404.php';
        break;
}


include_once 'includes/footer.php';
?>