-- Insertion ou mise à jour des rôles
INSERT INTO roles (name) VALUES 
('Admin'),
('Client'),
('Prestataire')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Nettoyer la table des sociétés avant d'insérer
DELETE FROM companies;

-- Insertion des sociétés clientes
INSERT INTO companies (name, address, phone_number, email, subscription_plan) VALUES 
('TechCorp', '123 Rue de Paris', '0123456789', 'contact@techcorp.com', 'Premium'),
('HealthPlus', '45 Avenue de Lyon', '0145789632', 'info@healthplus.com', 'Basic');

-- Nettoyer la table des sociétés avant d'insérer
DELETE FROM users;

-- Insertion des utilisateurs
INSERT INTO users (username, password, email, role_id) VALUES 
('admin', 'hashed_password', 'admin@businesscare.com', 1), -- Admin
('client1', 'hashed_password', 'client1@techcorp.com', 2), -- Client de TechCorp
('client2', 'hashed_password', 'client2@healthplus.com', 2); -- Client de HealthPlus

-- Nettoyer la table des sociétés avant d'insérer
DELETE FROM employees;

-- Insertion des salariés
INSERT INTO employees (first_name, last_name, email, company_id) VALUES 
('John', 'Doe', 'john.doe@techcorp.com', 1), 
('Jane', 'Smith', 'jane.smith@healthplus.com', 2);

-- Nettoyer la table des sociétés avant d'insérer
DELETE FROM providers;

-- Insertion des prestataires
INSERT INTO providers (name, email, phone_number, service_type, hourly_rate) VALUES 
('YogaMaster', 'contact@yogamaster.com', '0654321987', 'Yoga', 50.00),
('CoachX', 'info@coachx.com', '0621345678', 'Coaching', 75.00);

-- Nettoyer la table des sociétés avant d'insérer
DELETE FROM services;

-- Insertion des services
INSERT INTO services (name, description, price, provider_id) VALUES 
('Morning Yoga', 'Relaxing yoga session', 100.00, 1),
('Leadership Training', 'Boost leadership skills', 200.00, 2);

-- Nettoyer la table des sociétés avant d'insérer
DELETE FROM contracts;

-- Insertion des contrats
INSERT INTO contracts (company_id, start_date, end_date, total_price, payment_status) VALUES 
(1, '2025-01-01', '2025-12-31', 5000.00, 'Paid'),
(2, '2025-01-01', '2025-06-30', 2500.00, 'Pending');

-- Nettoyer la table des sociétés avant d'insérer
DELETE FROM events;

-- Insertion des événements
INSERT INTO events (name, description, date, location, created_by) VALUES 
('Yoga Session', 'A relaxing yoga session for employees', '2025-02-01', 'Paris', 1),
('Team Building', 'Fun activities to boost team spirit', '2025-03-01', 'Lyon', 1);

-- Insertion des réservations
INSERT INTO reservations (employee_id, event_id, reserved_at) VALUES 
(1, 1, NOW()),
(2, 2, NOW());

-- Insertion des évaluations
INSERT INTO evaluations (provider_id, rating, feedback, evaluated_at) VALUES 
(1, 5, 'Excellent session!', NOW()),
(2, 4, 'Very helpful training.', NOW());
