<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php'; 
require_once __DIR__ . '/../utils/auth.php';

// Vérification de l'authentification et du rôle (à adapter selon l'espace)
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'company_admin') { // Remplacer ROLE_ICI par 'company_admin', 'employee' ou 'provider'
    setAlert('Accès non autorisé.', 'danger');
    redirect(APP_URL . '/index.php?page=login');
    exit;
}

$db = Database::getInstance();
// Récupération des données de l'utilisateur connecté
try {
    $user = $db->query(
        "SELECT * FROM users WHERE id = ? LIMIT 1",
        [$_SESSION['user_id']],
        true
    );
} catch (Exception $e) {
    $user = [
        'id' => $_SESSION['user_id'],
        'first_name' => $_SESSION['user_name'] ?? 'Utilisateur',
        'last_name' => '',
        'profile_picture' => ''
    ];
}

// Récupération des données spécifiques à chaque dashboard (à adapter)
$dashboardStats = [
    'stat1' => ['value' => 0, 'label' => 'Élément 1', 'icon' => 'calendar-check'],
    'stat2' => ['value' => 0, 'label' => 'Élément 2', 'icon' => 'users'],
    'stat3' => ['value' => 0, 'label' => 'Élément 3', 'icon' => 'chart-line'],
    'stat4' => ['value' => 0, 'label' => 'Élément 4', 'icon' => 'clipboard-list']
];

// Variables pour le titre de la page et le nom de l'espace (à adapter)
$pageTitle = "Tableau de bord";
$spaceName = "Espace Entreprise"; // À remplacer par "Espace Entreprise", "Espace Employé" ou "Espace Prestataire"
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?> | Business Care</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --info-color: #36b9cc;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-color: #f8f9fc;
            --dark-color: #5a5c69;
        }
        
        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary-color) 0%, #224abe 100%);
            color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            width: 250px;
            transition: all 0.3s;
        }
        
        .sidebar-brand {
            padding: 1.5rem 1rem;
            text-align: center;
            font-size: 1.25rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu {
            padding: 1rem 0;
        }
        
        .sidebar-menu a {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .sidebar-menu a:hover, .sidebar-menu a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-menu i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 1.5rem;
            transition: all 0.3s;
        }
        
        .topbar {
            background-color: white;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            padding: 1rem 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .topbar-user {
            display: flex;
            align-items: center;
        }
        
        .topbar-user img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
            margin-right: 0.75rem;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 2rem 0 rgba(58, 59, 69, 0.15);
        }
        
        .stat-icon {
            font-size: 2rem;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-bottom: 1rem;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }
        
        .stat-label {
            color: var(--secondary-color);
            font-size: 0.875rem;
        }
        
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            font-weight: 700;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .hamburger-menu {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--primary-color);
            cursor: pointer;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            
            .sidebar.active {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .main-content.active {
                margin-left: 250px;
            }
            
            .hamburger-menu {
                display: block;
            }
        }
        
        .footer {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            padding: 1rem;
            text-align: center;
            color: var(--secondary-color);
            font-size: 0.875rem;
            margin-top: 1.5rem;
        }
        
        .bg-primary-soft {
            background-color: rgba(78, 115, 223, 0.1);
            color: var(--primary-color);
        }
        
        .bg-success-soft {
            background-color: rgba(28, 200, 138, 0.1);
            color: var(--success-color);
        }
        
        .bg-info-soft {
            background-color: rgba(54, 185, 204, 0.1);
            color: var(--info-color);
        }
        
        .bg-warning-soft {
            background-color: rgba(246, 194, 62, 0.1);
            color: var(--warning-color);
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-heartbeat me-2"></i> Business Care
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="active">
                <i class="fas fa-tachometer-alt"></i> Tableau de bord
            </a>
            <!-- Menu pour l'espace Entreprise (à afficher seulement si l'utilisateur est company_admin) -->
            <?php if ($_SESSION['user_role'] === 'company_admin'): ?>
            <a href="employees.php">
                <i class="fas fa-users"></i> Employés
            </a>
            <a href="subscriptions.php">
                <i class="fas fa-credit-card"></i> Abonnements
            </a>
            <a href="reports.php">
                <i class="fas fa-chart-bar"></i> Rapports
            </a>
            <?php endif; ?>
            
            
            <!-- Menu commun à tous les rôles -->
            <a href="profile.php">
                <i class="fas fa-user"></i> Profil
            </a>
            <a href="notifications.php">
                <i class="fas fa-bell"></i> Notifications
            </a>
            <a href="messages.php">
                <i class="fas fa-envelope"></i> Messages
            </a>
            <a href="<?= APP_URL ?>/actions/logout_process.php">
                <i class="fas fa-sign-out-alt"></i> Déconnexion
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="topbar">
            <div>
                <button class="hamburger-menu" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="d-inline-block mb-0"><?= $spaceName ?></h4>
            </div>
            <div class="topbar-user">
                <img src="<?= $user['profile_picture'] ?: 'https://via.placeholder.com/40' ?>" alt="<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>">
                <div>
                    <div class="fw-bold"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></div>
                    <small class="text-muted"><?= $_SESSION['user_role'] ?></small>
                </div>
            </div>
        </div>

        <!-- Welcome Card -->
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="card-title">Bienvenue, <?= htmlspecialchars($user['first_name']) ?> !</h2>
                <p class="card-text">Voici un aperçu de vos informations et activités récentes.</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon bg-primary-soft mx-auto">
                        <i class="fas fa-<?= $dashboardStats['stat1']['icon'] ?>"></i>
                    </div>
                    <div class="stat-value"><?= $dashboardStats['stat1']['value'] ?></div>
                    <div class="stat-label"><?= $dashboardStats['stat1']['label'] ?></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon bg-success-soft mx-auto">
                        <i class="fas fa-<?= $dashboardStats['stat2']['icon'] ?>"></i>
                    </div>
                    <div class="stat-value"><?= $dashboardStats['stat2']['value'] ?></div>
                    <div class="stat-label"><?= $dashboardStats['stat2']['label'] ?></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon bg-info-soft mx-auto">
                        <i class="fas fa-<?= $dashboardStats['stat3']['icon'] ?>"></i>
                    </div>
                    <div class="stat-value"><?= $dashboardStats['stat3']['value'] ?></div>
                    <div class="stat-label"><?= $dashboardStats['stat3']['label'] ?></div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="stat-card text-center">
                    <div class="stat-icon bg-warning-soft mx-auto">
                        <i class="fas fa-<?= $dashboardStats['stat4']['icon'] ?>"></i>
                    </div>
                    <div class="stat-value"><?= $dashboardStats['stat4']['value'] ?></div>
                    <div class="stat-label"><?= $dashboardStats['stat4']['label'] ?></div>
                </div>
            </div>
        </div>

        <!-- Main Content - Adapt this section based on the specific dashboard -->
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Recent Activities Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Activités récentes</h5>
                        <a href="#" class="btn btn-sm btn-primary">Voir tout</a>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <!-- Replace with actual data from your database -->
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Activité 1</h6>
                                        <small class="text-muted">Description de l'activité 1</small>
                                    </div>
                                    <small class="text-muted">Il y a 2 heures</small>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Activité 2</h6>
                                        <small class="text-muted">Description de l'activité 2</small>
                                    </div>
                                    <small class="text-muted">Hier</small>
                                </div>
                            </div>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Activité 3</h6>
                                        <small class="text-muted">Description de l'activité 3</small>
                                    </div>
                                    <small class="text-muted">Il y a 3 jours</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Calendar Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Calendrier</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            Cette section affichera un calendrier avec vos événements à venir.
                        </div>
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-alt fa-4x text-muted mb-3"></i>
                            <p>Fonctionnalité en cours de développement</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Notifications Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Notifications</h5>
                        <span class="badge bg-primary rounded-pill">3</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <!-- Replace with actual notifications from your database -->
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar bg-primary-soft text-primary rounded-circle p-2">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1">Notification 1</p>
                                        <small class="text-muted">Il y a 1 heure</small>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar bg-success-soft text-success rounded-circle p-2">
                                            <i class="fas fa-check"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1">Notification 2</p>
                                        <small class="text-muted">Il y a 3 heures</small>
                                    </div>
                                </div>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 me-3">
                                        <div class="avatar bg-warning-soft text-warning rounded-circle p-2">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-1">Notification 3</p>
                                        <small class="text-muted">Il y a 1 jour</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="notifications.php" class="text-decoration-none">Voir toutes les notifications</a>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Actions rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <?php if ($_SESSION['user_role'] === 'company_admin'): ?>
                            <a href="employees.php?action=new" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Ajouter un employé
                            </a>
                            <a href="reports.php?action=new" class="btn btn-outline-primary">
                                <i class="fas fa-file-medical me-2"></i>Générer un rapport
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($_SESSION['user_role'] === 'employee'): ?>
                            <a href="appointments.php?action=new" class="btn btn-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Prendre rendez-vous
                            </a>
                            <a href="events.php?action=register" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-day me-2"></i>S'inscrire à un événement
                            </a>
                            <?php endif; ?>
                            
                            <?php if ($_SESSION['user_role'] === 'provider'): ?>
                            <a href="appointments.php?action=manage" class="btn btn-primary">
                                <i class="fas fa-calendar-check me-2"></i>Gérer les rendez-vous
                            </a>
                            <a href="events.php?action=new" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Créer un événement
                            </a>
                            <a href="invoices.php?action=new" class="btn btn-outline-primary">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Créer une facture
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="mb-0">&copy; <?= date('Y') ?> Business Care. Tous droits réservés.</p>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JavaScript -->
    <script>
        // Toggle Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });
        
        // Display alerts if any
        <?php if (isset($_SESSION['alert'])): ?>
        window.addEventListener('DOMContentLoaded', function() {
            // Create a Bootstrap alert
            var alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show';
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                <?= $_SESSION['alert']['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
            // Insert at the top of the main content
            var mainContent = document.querySelector('.main-content');
            mainContent.insertBefore(alertDiv, mainContent.firstChild);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000);
        });
        <?php 
        // Clear the alert from session
        unset($_SESSION['alert']);
        endif; 
        ?>
    </script>
</body>
</html>