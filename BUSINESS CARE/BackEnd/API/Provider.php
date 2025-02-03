<?php
require_once '../config/config.php';  // Charger la configuration

// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Récupérer les informations des prestataires
$sql = "SELECT id, name, email, phone_number, service_type, hourly_rate FROM providers";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Récupérer et afficher les informations des prestataires
    $providers = [];
    while ($row = $result->fetch_assoc()) {
        $providers[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'email' => $row['email'],
            'phone_number' => $row['phone_number'],
            'service_type' => $row['service_type'],
            'hourly_rate' => $row['hourly_rate']
        ];
    }

    echo json_encode([
        'success' => true,
        'providers' => $providers
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Aucun prestataire trouvé.'
    ]);
}

// Fermer la connexion à la base de données
$conn->close();
?>
