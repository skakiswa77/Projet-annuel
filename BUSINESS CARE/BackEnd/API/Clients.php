<?php
require_once '../config/config.php';  // Charger la configuration
require_once '../vendor/autoload.php';  // Inclure la librairie Stripe

// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Exemple : Récupérer les informations d'un client depuis la base de données
$sql = "SELECT u.id, u.username, u.email, r.name AS role, c.name AS company, c.subscription_plan 
        FROM users u
        JOIN roles r ON u.role_id = r.id
        JOIN companies c ON u.company_id = c.id
        WHERE u.id = ?";  // Utilise un paramètre dynamique pour la sécurité
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_GET['client_id']);  // Récupère l'ID du client depuis l'URL
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Récupérer les données du client
    $row = $result->fetch_assoc();
    $client_data = [
        'id' => $row['id'],
        'username' => $row['username'],
        'email' => $row['email'],
        'role' => $row['role'],
        'company' => $row['company'],
        'subscription_plan' => $row['subscription_plan']
    ];

    // Optionnel : récupérer les informations du client depuis Stripe (si tu as un client Stripe)
    \Stripe\Stripe::setApiKey(STRIPE_API_SECRET);
    try {
        $stripeCustomer = \Stripe\Customer::retrieve($row['email']);  // Récupérer le client Stripe par email

        if ($stripeCustomer) {
            $client_data['stripe_id'] = $stripeCustomer->id;
            $client_data['balance'] = $stripeCustomer->balance / 100;  // Balance en euros
        }
    } catch (\Stripe\Exception\ApiErrorException $e) {
        // Gérer l'erreur Stripe si le client n'existe pas
        $client_data['stripe_error'] = 'Erreur Stripe: ' . $e->getMessage();
    }

    // Retourner les informations du client en JSON
    echo json_encode([
        'success' => true,
        'client' => $client_data
    ]);
} else {
    // Si aucun client n'est trouvé
    echo json_encode([
        'success' => false,
        'message' => 'Client non trouvé.'
    ]);
}

// Fermer la connexion à la base de données
$conn->close();
?>
