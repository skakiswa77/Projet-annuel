<?php
// Chargement des dépendances avec Composer
require_once '../vendor/autoload.php';

// Inclure la configuration de la base de données et de Stripe
require_once '../config/db.php';

// Vérification de la clé API Stripe est définie
if (!defined('STRIPE_SECRET_KEY')) {
    die("Erreur : Clé API Stripe non définie.");
}

// Configurer Stripe avec la clé secrète
\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

/**
 * Fonction pour créer un PaymentIntent Stripe
 *
 * @param int 
 * @param string 
 * @param string|null 
 * @return string|null 
 */
function createPaymentIntent($amount, $currency = 'eur', $receipt_email = null) {
    try {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => $currency,
            'receipt_email' => $receipt_email,
        ]);

        return $paymentIntent->client_secret;
    } catch (\Stripe\Exception\ApiErrorException $e) {
        error_log('Erreur Stripe: ' . $e->getMessage()); // Enregistre l'erreur dans les logs
        return null;
    }
}

// Récupérer un utilisateur depuis la base de données
$sql = "SELECT id, name, email FROM users LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_email = $user['email'];
} else {
    die("Erreur : Aucun utilisateur trouvé.");
}


$clientSecret = createPaymentIntent(5000, 'eur', $user_email);

if ($clientSecret) {
    echo json_encode([
        "success" => true,
        "message" => "PaymentIntent créé avec succès",
        "clientSecret" => $clientSecret
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de la création du PaymentIntent"
    ]);
}

// Fermer la connexion à la base de données
$conn->close();
?>
