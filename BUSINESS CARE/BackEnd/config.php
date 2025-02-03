<?php

// Informations sur le projet
define('PROJECT_NAME', 'PROJET');   
define('BASE_URL', 'http://localhost/PROJET'); 

// Configuration de la base de données
define('DB_HOST', 'localhost');   
define('DB_NAME', 'PROJET');  
define('DB_USER', 'root');    
define('DB_PASS', '');    
define('DB_CHARSET', 'utf8mb4');    

// Configuration de l'authentification (JWT)
define('JWT_SECRET_KEY', 'sk_test_51QoCXc4TCWJlKcN4g2RtVJFVzW3K41msFYAUkolqOgtSusB33gd2fDe78TbOTfgoqrSYT9OKfnCKGLZLe8a7GaK500983RpnDH');    // La clé secrète pour signer les tokens JWT
define('JWT_ALGORITHM', 'HS256');    // L'algorithme utilisé pour signer les JWT

// Configuration Stripe
define('STRIPE_API_KEY', 'pk_test_51QoCXc4TCWJlKcN4nWvOkwpIVuksQWOjmRDnBZSeEzcMqxzFxnP5otJe7bsjJfWPLzNAYAw1rzGVXCBaMSsFglAJ0009bqICex');    // Clé publique pour le front-end (à utiliser côté client)
define('STRIPE_API_SECRET', 'sk_test_51QoCXc4TCWJlKcN4g2RtVJFVzW3K41msFYAUkolqOgtSusB33gd2fDe78TbOTfgoqrSYT9OKfnCKGLZLe8a7GaK500983RpnDH');    // Clé secrète pour le back-end (à utiliser côté serveur)

// Autres configurations 
define('ADMIN_EMAIL', 'admin@tonprojet.com');   // Adresse email de l'administrateur
?>
