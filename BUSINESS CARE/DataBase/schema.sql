-- Creation de la base de donnees
CREATE DATABASE BusinessCare;
USE BusinessCare;

-- Table des roles
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nom du role (admin, client, prestataire, etc.)'
);

-- Table des utilisateurs
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE COMMENT 'Nom d'utilisateur',
    password VARCHAR(255) NOT NULL COMMENT 'Mot de passe hashé',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email de l'utilisateur',
    role_id INT NOT NULL COMMENT 'Role de l utilisateur',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE
);

-- Table des societes clientes
CREATE TABLE companies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE COMMENT 'Nom de la société cliente',
    address VARCHAR(255) COMMENT 'Adresse de la société',
    phone_number VARCHAR(20) COMMENT 'Numéro de téléphone',
    email VARCHAR(100) UNIQUE COMMENT 'Email de contact',
    subscription_plan ENUM('Starter', 'Basic', 'Premium') NOT NULL COMMENT 'Plan d abonnement',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table des salariés
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL COMMENT 'Prénom du salarié',
    last_name VARCHAR(50) NOT NULL COMMENT 'Nom du salarié',
    email VARCHAR(100) NOT NULL UNIQUE COMMENT 'Email du salarié',
    company_id INT NOT NULL COMMENT 'Référence à la société cliente',
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE,
    CONSTRAINT unique_employee_email UNIQUE (email)
);

-- Table des prestataires
CREATE TABLE providers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'Nom du prestataire',
    email VARCHAR(100) UNIQUE COMMENT 'Email du prestataire',
    phone_number VARCHAR(20) COMMENT 'Numéro de téléphone',
    service_type VARCHAR(100) NOT NULL COMMENT 'Type de prestation (yoga, coaching, etc.)',
    hourly_rate DECIMAL(10, 2) NOT NULL COMMENT 'Tarif horaire',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT unique_provider_email UNIQUE (email)
);

-- Table des services
CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'Nom du service',
    description TEXT COMMENT 'Description du service',
    price DECIMAL(10, 2) NOT NULL COMMENT 'Prix du service',
    provider_id INT NOT NULL COMMENT 'Référence au prestataire',
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE
);

-- Table des contrats
CREATE TABLE contracts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_id INT NOT NULL COMMENT 'Référence à la société cliente',
    start_date DATE NOT NULL COMMENT 'Date de début du contrat',
    end_date DATE COMMENT 'Date de fin du contrat',
    total_price DECIMAL(10, 2) NOT NULL COMMENT 'Prix total du contrat',
    payment_status ENUM('Pending', 'Paid', 'Cancelled') DEFAULT 'Pending' COMMENT 'Statut du paiement',
    FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
);

-- Table des factures
CREATE TABLE invoices (
    id INT AUTO_INCREMENT PRIMARY KEY,
    contract_id INT NOT NULL COMMENT 'Référence au contrat',
    amount DECIMAL(10, 2) NOT NULL COMMENT 'Montant de la facture',
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Date d émission',
    FOREIGN KEY (contract_id) REFERENCES contracts(id) ON DELETE CASCADE
);

-- Table des événements
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL COMMENT 'Nom de l événement',
    description TEXT COMMENT 'Description de l événement',
    date DATE NOT NULL COMMENT 'Date de l événement',
    location VARCHAR(255) COMMENT 'Lieu de l événement',
    created_by INT NOT NULL COMMENT 'Créateur de l événement',
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
);

-- Table des réservations
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id INT NOT NULL COMMENT 'Référence au salarié',
    event_id INT NOT NULL COMMENT 'Référence à l événement',
    reserved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de réservation',
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Table des évaluations
CREATE TABLE evaluations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    provider_id INT NOT NULL COMMENT 'Référence au prestataire',
    rating INT NOT NULL COMMENT 'Note sur 5',
    feedback TEXT COMMENT 'Commentaire',
    evaluated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Date de l évaluation',
    FOREIGN KEY (provider_id) REFERENCES providers(id) ON DELETE CASCADE
);

-- Contraintes additionnelles pour l'intégrité des données
ALTER TABLE companies
ADD CONSTRAINT unique_company_email UNIQUE (email);

ALTER TABLE services
ADD CONSTRAINT unique_service_name UNIQUE (name);

ALTER TABLE events
ADD CONSTRAINT unique_event_name_date UNIQUE (name, date);
