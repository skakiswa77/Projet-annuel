<?php
require_once '../config/config.php';  // Charger la configuration

// Connexion à la base de données
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Récupérer les informations des employés
$sql = "SELECT e.id, e.first_name, e.last_name, e.email, c.name AS company
        FROM employees e
        JOIN companies c ON e.company_id = c.id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Récupérer et afficher les informations des employés
    $employees = [];
    while ($row = $result->fetch_assoc()) {
        $employees[] = [
            'id' => $row['id'],
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'],
            'email' => $row['email'],
            'company' => $row['company']
        ];
    }

    echo json_encode([
        'success' => true,
        'employees' => $employees
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Aucun employé trouvé.'
    ]);
}

// Fermer la connexion à la base de données
$conn->close();
?>
