<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';
require_once '../Class/Teacher.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header('Location: ../auth.php');
    exit();
}

$db = new Database();
$pdo = $db->getConnection();

$teacher = new Teacher($pdo);
$teacher->setId($_SESSION['user']['id']);

$dashboardData = $teacher->getDashboardData();

// Initialiser les valeurs par défaut si getDashboardData retourne false
if ($dashboardData === false) {
    $dashboardData = [
        'total_courses' => 0,
        'total_students' => 0,
        'recent_courses' => [],
        'course_status' => [],
        'active_courses' => 0
    ];
}

$user = $_SESSION['user'];
$page = 'dashboard';

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <div class="welcome">
            <h2>Bonjour, <?php echo htmlspecialchars($user['firstname']); ?>!</h2>
            <p>Voici un aperçu de vos activités</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-details">
                <h3>Total Cours</h3>
                <p class="stat-number"><?php echo $dashboardData['total_courses']; ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-details">
                <h3>Total Étudiants</h3>
                <p class="stat-number"><?php echo $dashboardData['total_students']; ?></p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-details">
                <h3>Cours Actifs</h3>
                <p class="stat-number"><?php echo $dashboardData['active_courses']; ?></p>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="quick-links">
        <h3>Actions Rapides</h3>
        <div class="links-grid">
            <a href="add_course.php" class="quick-link-card">
                <i class="fas fa-plus-circle"></i>
                <span>Ajouter un Cours</span>
            </a>
            <a href="my_courses.php" class="quick-link-card">
                <i class="fas fa-book"></i>
                <span>Mes Cours</span>
            </a>
            <a href="students.php" class="quick-link-card">
                <i class="fas fa-users"></i>
                <span>Mes Étudiants</span>
            </a>
            <a href="analytics.php" class="quick-link-card">
                <i class="fas fa-chart-bar"></i>
                <span>Statistiques</span>
            </a>
        </div>
    </div>

    <!-- Recent Courses -->
    <?php if (!empty($dashboardData['recent_courses'])): ?>
    <div class="recent-courses">
        <h3>Cours Récents</h3>
        <div class="courses-grid">
            <?php foreach ($dashboardData['recent_courses'] as $course): ?>
            <div class="course-card">
                <div class="course-image">
                    <img src="<?php echo !empty($course['image_url']) ? '../' . $course['image_url'] : '../assets/images/course-placeholder.jpg'; ?>" 
                         alt="<?php echo htmlspecialchars($course['title']); ?>">
                </div>
                <div class="course-info">
                    <h4><?php echo htmlspecialchars($course['title']); ?></h4>
                    <p><?php echo htmlspecialchars(substr($course['description'], 0, 100)) . '...'; ?></p>
                    <span class="status-badge <?php echo $course['status']; ?>">
                        <?php echo ucfirst($course['status']); ?>
                    </span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>