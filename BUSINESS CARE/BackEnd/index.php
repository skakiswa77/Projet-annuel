<?php

// Inclure la configuration générale 
require_once 'config.php';

// Vérifier que la méthode HTTP est bien utilisée pour router les requêtes
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    // Vérifier la route demandée par le client (ex: /client, /employe, etc.)
    if (isset($_GET['route'])) {
        $route = $_GET['route'];

        switch ($route) {
            case 'client':
                // Inclure le fichier de l'API client.php
                require_once 'API/Client.php';
                break;
                
            case 'employe':
                // Inclure le fichier de l'API employe.php
                require_once 'API/Employe.php';
                break;
                
            case 'provider':
                // Inclure le fichier de l'API provider.php
                require_once 'API/Provider.php';
                break;
                
            default:
                // Si la route n'existe pas, afficher une erreur
                echo json_encode(['error' => 'Route inconnue']);
                break;
        }
    } else {
        // Si aucune route n'est spécifiée, afficher une erreur
        echo json_encode(['error' => 'Aucune route spécifiée']);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Pour les requêtes POST on pourrais ajouter des vérifications ici
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'registerClient':
                // Inclure le fichier de traitement pour l'inscription du client
                require_once 'API/Client.php';
                break;
                
            case 'processPayment':
                // Inclure le fichier pour traiter un paiement
                require_once 'stripe.php';  // Par exemple pour intégrer Stripe
                break;
                
            default:
                // Si l'action est inconnue
                echo json_encode(['error' => 'Action inconnue']);
                break;
        }
    } else {
        echo json_encode(['error' => 'Aucune action spécifiée']);
    }

} else {
    // Si la méthode HTTP n'est ni GET ni POST, afficher une erreur
    echo json_encode(['error' => 'Méthode HTTP non supportée']);
}

?>
