<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../utils/database.php';
require_once __DIR__ . '/../utils/helpers.php'; 
require_once __DIR__ . '/../utils/auth.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'provider') {
    setAlert('Accès non autorisé.', 'danger');
    redirect(APP_URL . '/index.php?page=login');
    exit;
}

$db = Database::getInstance();

try {
    $user = $db->query(
        "SELECT u.*, pp.bio, pp.hourly_rate, pp.is_verified, pp.rating, ps.name as specialization
         FROM users u
         LEFT JOIN provider_profiles pp ON u.id = pp.user_id
         LEFT JOIN provider_specializations ps ON pp.specialization_id = ps.id
         WHERE u.id = ? LIMIT 1",
        [$_SESSION['user_id']],
        true
    );
    
    if (!$user) {
        throw new Exception("Utilisateur non trouvé");
    }
} catch (Exception $e) {
    $user = [
        'id' => $_SESSION['user_id'],
        'first_name' => $_SESSION['user_name'] ?? 'Prestataire',
        'last_name' => '',
        'profile_picture' => '',
        'rating' => 0,
        'specialization' => 'Non définie',
        'hourly_rate' => 0,
        'is_verified' => 0
    ];
}


try {

    $upcomingAppointmentsCount = $db->query(
        "SELECT COUNT(*) as count FROM medical_appointments 
         WHERE provider_id = ? AND appointment_datetime > NOW() AND status != 'cancelled'",
        [$_SESSION['user_id']],
        true
    );
    $appointmentsCount = $upcomingAppointmentsCount['count'] ?? 0;
    

    $upcomingEventsCount = $db->query(
        "SELECT COUNT(*) as count FROM events 
         WHERE provider_id = ? AND start_datetime > NOW()",
        [$_SESSION['user_id']],
        true
    );
    $eventsCount = $upcomingEventsCount['count'] ?? 0;
    

    $currentMonthInvoicesCount = $db->query(
        "SELECT COUNT(*) as count FROM provider_invoices 
         WHERE provider_id = ? AND MONTH(issue_date) = MONTH(CURRENT_DATE()) 
         AND YEAR(issue_date) = YEAR(CURRENT_DATE())",
        [$_SESSION['user_id']],
        true
    );
    $invoicesCount = $currentMonthInvoicesCount['count'] ?? 0;
    

    $reviewsCount = $db->query(
        "SELECT COUNT(*) as count FROM provider_reviews 
         WHERE provider_id = ?",
        [$_SESSION['user_id']],
        true
    );
    $reviewsTotal = $reviewsCount['count'] ?? 0;
    
} catch (Exception $e) {
    $appointmentsCount = 0;
    $eventsCount = 0;
    $invoicesCount = 0;
    $reviewsTotal = 0;
}


$dashboardStats = [
    'stat1' => ['value' => $appointmentsCount, 'label' => 'Rendez-vous à venir', 'icon' => 'calendar-check'],
    'stat2' => ['value' => $eventsCount, 'label' => 'Événements à venir', 'icon' => 'users'],
    'stat3' => ['value' => $invoicesCount, 'label' => 'Factures du mois', 'icon' => 'file-invoice-dollar'],
    'stat4' => ['value' => number_format($user['rating'], 1), 'label' => 'Note moyenne', 'icon' => 'star']
];


try {
    $upcomingAppointments = $db->query(
        "SELECT a.*, u.first_name as client_first_name, u.last_name as client_last_name, c.name as company_name
         FROM medical_appointments a
         JOIN users u ON a.user_id = u.id
         JOIN companies c ON u.company_id = c.id
         WHERE a.provider_id = ? AND a.appointment_datetime > NOW() AND a.status != 'cancelled'
         ORDER BY a.appointment_datetime ASC
         LIMIT 5",
        [$_SESSION['user_id']]
    );
} catch (Exception $e) {
    $upcomingAppointments = [];
}


try {
    $upcomingEvents = $db->query(
        "SELECT e.*, et.name as event_type_name
         FROM events e
         LEFT JOIN event_types et ON e.event_type_id = et.id
         WHERE e.provider_id = ? AND e.start_datetime > NOW()
         ORDER BY e.start_datetime ASC
         LIMIT 3",
        [$_SESSION['user_id']]
    );
} catch (Exception $e) {
    $upcomingEvents = [];
}


try {
    $latestInvoices = $db->query(
        "SELECT pi.*, c.name as company_name
         FROM provider_invoices pi
         JOIN companies c ON pi.company_id = c.id
         WHERE pi.provider_id = ?
         ORDER BY pi.issue_date DESC
         LIMIT 3",
        [$_SESSION['user_id']]
    );
} catch (Exception $e) {
    $latestInvoices = [];
}

$pageTitle = "Tableau de bord";
$spaceName = "Espace Prestataire";
?>

<!DOCTYPE html>
<html lang="fr">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Prestataire | Business Care</title>
    
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #4e73df;
            --sidebar-width: 240px;
            --sidebar-collapsed-width: 70px;
            --topbar-height: 60px;
        }

        body {
            background-color: #f8f9fc;
            font-family: 'Nunito', sans-serif;
            transition: all 0.3s;
        }


        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, #4e73df 0%, #3a5fc7 100%);
            color: white;
            z-index: 1000;
            transition: all 0.3s;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-brand {
            height: var(--topbar-height);
            display: flex;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand i {
            font-size: 1.2rem;
            margin-right: 0.75rem;
        }

        .sidebar.collapsed .sidebar-brand span,
        .sidebar.collapsed .sidebar-menu span {
            display: none;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.2rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar-menu i {
            width: 20px;
            margin-right: 1rem;
            text-align: center;
        }

        .sidebar.collapsed .sidebar-menu a {
            padding: 0.75rem;
            justify-content: center;
        }

        .sidebar.collapsed .sidebar-menu i {
            margin-right: 0;
            font-size: 1.2rem;
        }

 
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1rem;
            transition: all 0.3s;
        }

        .main-content.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

  
        .sidebar-toggle {
            position: fixed;
            bottom: 20px;
            left: calc(var(--sidebar-width) - 25px);
            z-index: 1001;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: white;
            color: var(--primary-color);
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .sidebar-toggle:hover {
            background-color: #f8f9fa;
        }

        .sidebar-toggle.collapsed {
            left: calc(var(--sidebar-collapsed-width) - 25px);
        }


        @media (max-width: 768px) {
            .sidebar {
                width: 0;
            }

            .sidebar.mobile-visible {
                width: var(--sidebar-width);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                left: 20px;
            }

            .sidebar-toggle.mobile-visible {
                left: calc(var(--sidebar-width) - 25px);
            }
        }
    </style>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-heartbeat"></i>
            <span>Business Care</span>
        </div>
        <div class="sidebar-menu">
            <a href="dashboard.php" class="active">
                <i class="fas fa-tachometer-alt"></i>
                <span>Tableau de bord</span>
            </a>
            <a href="appointments.php">
                <i class="fas fa-calendar-check"></i>
                <span>Rendez-vous</span>
            </a>
            <a href="events.php">
                <i class="fas fa-users"></i>
                <span>Événements</span>
            </a>
            <a href="invoices.php">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Factures</span>
            </a>
            <a href="reviews.php">
                <i class="fas fa-star"></i>
                <span>Avis</span>
            </a>
            <a href="profile.php">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </a>
            <a href="notifications.php">
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </a>
            <a href="messages.php">
                <i class="fas fa-envelope"></i>
                <span>Messages</span>
            </a>
            <a href="logout_process.php">
                <i class="fas fa-sign-out-alt"></i>
                <span>Déconnexion</span>
            </a>
        </div>
    </div>


    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-chevron-left" id="toggleIcon"></i>
    </button>


    <div class="main-content" id="mainContent">

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const toggleButton = document.getElementById('sidebarToggle');
            const toggleIcon = document.getElementById('toggleIcon');
            
  
            function isMobile() {
                return window.innerWidth <= 768;
            }
            
 
            function updateToggleIcon(collapsed) {
                if (collapsed) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                } else {
                    toggleIcon.classList.remove('fa-chevron-right');
                    toggleIcon.classList.add('fa-chevron-left');
                }
            }
            
 
            function setupResponsive() {
                if (isMobile()) {

                    sidebar.classList.remove('collapsed');
                    mainContent.classList.remove('expanded');
                    
                    if (!sidebar.classList.contains('mobile-visible')) {
                        sidebar.style.width = '0';
                        toggleButton.classList.remove('mobile-visible');
                        updateToggleIcon(true);
                    }
                } else {

                    sidebar.style.width = '';
                    if (sidebar.classList.contains('collapsed')) {
                        mainContent.classList.add('expanded');
                        updateToggleIcon(true);
                    } else {
                        mainContent.classList.remove('expanded');
                        updateToggleIcon(false);
                    }
                }
            }
            

            setupResponsive();
            

            toggleButton.addEventListener('click', function() {
                if (isMobile()) {
 
                    if (sidebar.classList.contains('mobile-visible')) {
                        sidebar.classList.remove('mobile-visible');
                        sidebar.style.width = '0';
                        toggleButton.classList.remove('mobile-visible');
                        updateToggleIcon(true);
                    } else {
                        sidebar.classList.add('mobile-visible');
                        sidebar.style.width = '';
                        toggleButton.classList.add('mobile-visible');
                        updateToggleIcon(false);
                    }
                } else {

                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    toggleButton.classList.toggle('collapsed');
                    updateToggleIcon(sidebar.classList.contains('collapsed'));
                }
            });
            
 
            window.addEventListener('resize', setupResponsive);
        });
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

    <div class="main-content">

        <div class="topbar">
            <div>
                <button class="hamburger-menu" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="d-inline-block mb-0"><?= $spaceName ?></h4>
            </div>
                    <div class="fw-bold"><?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?></div>
                    <small class="text-muted"><?= htmlspecialchars($user['specialization']) ?> <?= $user['is_verified'] ? '<span class="verification-badge"><i class="fas fa-check-circle"></i></span>' : '' ?></small>
                </div>
            </div>
        </div>


        <div class="card mb-4">
            <div class="card-body">
                        <h2 class="mb-0">Bienvenue, <?= htmlspecialchars($user['first_name']) ?> !</h2>
                        <p class="text-muted mb-0">
                            <?= htmlspecialchars($user['specialization']) ?> 
                            <?= $user['is_verified'] ? '<span class="text-success"><i class="fas fa-check-circle"></i> Vérifié</span>' : '<span class="text-warning"><i class="fas fa-exclamation-circle"></i> En attente de vérification</span>' ?>
                        </p>
                    </div>
                </div>
                <p>Gérez vos interventions, rendez-vous et factures depuis cet espace. Votre taux horaire actuel est de <?= number_format($user['hourly_rate'], 2) ?> €/h.</p>
            </div>
        </div>

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
                    <div class="stat-value"><?= $dashboardStats['stat4']['value'] ?>/5</div>
                    <div class="stat-label"><?= $dashboardStats['stat4']['label'] ?></div>
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= floor($user['rating'])): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif ($i - 0.5 <= $user['rating']): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-8">

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Prochains rendez-vous</h5>
                        <a href="appointments.php" class="btn btn-sm btn-primary">Voir tout</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($upcomingAppointments)): ?>
                            <div class="text-center py-3">
                                <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                <p>Vous n'avez aucun rendez-vous à venir pour le moment.</p>
                                <a href="availability.php" class="btn btn-sm btn-outline-primary">Définir mes disponibilités</a>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($upcomingAppointments as $appointment): ?>
                                    <div class="calendar-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="calendar-date">
                                                    <?= date('d/m/Y H:i', strtotime($appointment['appointment_datetime'])) ?>
                                                </div>
                                                <div class="calendar-title">
                                                    <?= htmlspecialchars($appointment['client_first_name'] . ' ' . $appointment['client_last_name']) ?>
                                                </div>
                                                <small class="text-muted"><?= htmlspecialchars($appointment['company_name']) ?></small>
                                            </div>
                                            <span class="badge bg-<?= $appointment['status'] === 'scheduled' ? 'primary' : ($appointment['status'] === 'confirmed' ? 'success' : 'warning') ?>">
                                                <?= $appointment['status'] === 'scheduled' ? 'Planifié' : ($appointment['status'] === 'confirmed' ? 'Confirmé' : 'En attente') ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Événements à venir</h5>
                        <a href="events.php" class="btn btn-sm btn-primary">Voir tout</a>
                    </div>
                    <div class="card-body">
                        <?php if (empty($upcomingEvents)): ?>
                            <div class="text-center py-3">
                                <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                <p>Vous n'avez aucun événement à venir pour le moment.</p>
                                <a href="events.php?action=new" class="btn btn-sm btn-outline-primary">Créer un événement</a>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($upcomingEvents as $event): ?>
                                    <div class="calendar-item">
                                        <div class="calendar-date">
                                            <?= date('d/m/Y', strtotime($event['start_datetime'])) ?> 
                                            <?= date('H:i', strtotime($event['start_datetime'])) ?> - <?= date('H:i', strtotime($event['end_datetime'])) ?>
                                        </div>
                                        <div class="calendar-title"><?= htmlspecialchars($event['title']) ?></div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted"><?= htmlspecialchars($event['event_type_name'] ?? 'Type non défini') ?></small>
                                            <small><?= htmlspecialchars($event['location']) ?></small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Revenus du mois</h5>
                    </div>
                    <div class="card-body">
                        <?php 

                        $monthlyEarnings = 0;
                        try {
                            $earnings = $db->query(
                                "SELECT SUM(total_amount) as total FROM provider_invoices 
                                 WHERE provider_id = ? AND MONTH(issue_date) = MONTH(CURRENT_DATE()) 
                                 AND YEAR(issue_date) = YEAR(CURRENT_DATE())",
                                [$_SESSION['user_id']],
                                true
                            );
                            $monthlyEarnings = $earnings['total'] ?? 0;
                        } catch (Exception $e) {
                            $monthlyEarnings = 0;
                        }
                        ?>
                        <div class="text-center mb-4">
                            <h2 class="display-4 fw-bold"><?= number_format($monthlyEarnings, 2) ?> €</h2>
                            <p class="text-muted"><?= date('F Y') ?></p>
                        </div>
                        
                        <div class="progress-wrapper">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>Objectif mensuel</span>
                                <span><?= round(($monthlyEarnings / 2000) * 100) ?>%</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: <?= min(100, ($monthlyEarnings / 2000) * 100) ?>%" aria-valuenow="<?= min(100, ($monthlyEarnings / 2000) * 100) ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="text-end mt-1">
                                <small class="text-muted">2000 €</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="invoices.php" class="text-decoration-none">Voir toutes les factures</a>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Dernières factures</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($latestInvoices)): ?>
                            <div class="text-center py-3">
                                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                <p>Aucune facture émise pour le moment.</p>
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($latestInvoices as $invoice): ?>
                                    <a href="invoices.php?id=<?= $invoice['id'] ?>" class="list-group-item list-group-item-action">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar bg-info-soft text-info rounded-circle p-2">
                                                    <i class="fas fa-file-invoice-dollar"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-1"><?= htmlspecialchars($invoice['invoice_number']) ?></p>
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted"><?= htmlspecialchars($invoice['company_name']) ?></small>
                                                    <span class="fw-bold"><?= number_format($invoice['total_amount'], 2) ?> €</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>


                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Actions rapides</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="availability.php" class="btn btn-primary">
                                <i class="fas fa-clock me-2"></i>Définir mes disponibilités
                            </a>
                            <a href="events.php?
                            <a href="availability.php" class="btn btn-primary">
                                <i class="fas fa-clock me-2"></i>Définir mes disponibilités
                            </a>
                            <a href="events.php?action=new" class="btn btn-outline-primary">
                                <i class="fas fa-calendar-plus me-2"></i>Créer un événement
                            </a>
                            <a href="invoices.php?action=new" class="btn btn-outline-primary">
                                <i class="fas fa-file-invoice-dollar me-2"></i>Créer une facture
                            </a>
                        </div>
                    </div>
                </div>


                <?php if ($user['is_verified'] != 1): ?>
                <div class="card mb-4 border-warning">
                    <div class="card-header bg-warning-soft">
                        <h5 class="mb-0 text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Vérification en attente</h5>
                    </div>
                    <div class="card-body">
                        <p>Votre compte est en cours de vérification par l'équipe Business Care. Une fois vérifié, vous pourrez recevoir plus de demandes de services.</p>
                        <p class="mb-0">Si vous n'avez pas encore soumis tous vos documents, veuillez compléter votre profil :</p>
                        <div class="d-grid mt-3">
                            <a href="profile.php?section=verification" class="btn btn-warning">Compléter mon profil</a>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>


        <div class="footer">
            <p class="mb-0">&copy; <?= date('Y') ?> Business Care. Tous droits réservés.</p>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.main-content').classList.toggle('active');
        });
        

        <?php if (isset($_SESSION['alert'])): ?>
        window.addEventListener('DOMContentLoaded', function() {
       
            var alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show';
            alertDiv.setAttribute('role', 'alert');
            alertDiv.innerHTML = `
                <?= $_SESSION['alert']['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            
      
            var mainContent = document.querySelector('.main-content');
            mainContent.insertBefore(alertDiv, mainContent.firstChild);
            
           
            setTimeout(function() {
                var bsAlert = new bootstrap.Alert(alertDiv);
                bsAlert.close();
            }, 5000);
        });
        <?php 

        unset($_SESSION['alert']);
        endif; 
        ?>
    </script>
</body>
</html>
               