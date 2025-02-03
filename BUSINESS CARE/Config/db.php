<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "PROJET";

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Récupération d'un utilisateur
$sql = "SELECT id, name, email FROM users LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_email = $row["email"];
} else {
    die("Aucun utilisateur trouvé.");
}

// Fermeture de la connexion à la base de données
$conn->close();

// Vérification que Stripe est bien installé
require_once 'vendor/autoload.php';

// Configuration de Stripe
\Stripe\Stripe::setApiKey('sk_test_51QoCXc4TCWJlKcN4g2RtVJFVzW3K41msFYAUkolqOgtSusB33gd2fDe78TbOTfgoqrSYT9OKfnCKGLZLe8a7GaK500983RpnDH');

try {
    // Création du PaymentIntent
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => 5000,  
        'currency' => 'eur',
        'receipt_email' => $user_email,  
    ]);

    // Affichage du client secret
    echo "Client Secret: " . $paymentIntent->client_secret;

} catch (\Stripe\Exception\ApiErrorException $e) {
    // Gestion des erreurs Stripe
    echo "Erreur Stripe: " . $e->getMessage();
}
?>
