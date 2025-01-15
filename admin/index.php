<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';
require_once '../Class/Notification.php';

// Modifier cette vérification pour utiliser les bonnes clés de session
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth.php');
    exit();
}

$db = new Database();
$pdo = $db->getConnection();

// Get statistics
$stats = [
    'total_courses' => 0,
    'total_students' => 0,
    'total_teachers' => 0,
    'pending_teachers' => 0
];

try {
    // Get counts
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM courses");
    $stats['total_courses'] = $stmt->fetch()['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'student'");
    $stats['total_students'] = $stmt->fetch()['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'teacher'");
    $stats['total_teachers'] = $stmt->fetch()['count'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'teacher' AND status = 'pending'");
    $stats['pending_teachers'] = $stmt->fetch()['count'];
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Include header
require_once 'includes/header.php';
// Include sidebar
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="dashboard-welcome">
        <h1>Welcome, Admin System</h1>
        <p>Here's what's happening with your platform today</p>
    </div>
    
    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Courses</h3>
            <p><?php echo $stats['total_courses']; ?></p>
            <i class="fas fa-graduation-cap"></i>
        </div>
        
        <div class="stat-card">
            <h3>Total Students</h3>
            <p><?php echo $stats['total_students']; ?></p>
            <i class="fas fa-users"></i>
        </div>
        
        <div class="stat-card">
            <h3>Total Teachers</h3>
            <p><?php echo $stats['total_teachers']; ?></p>
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        
        <div class="stat-card">
            <h3>Pending Teachers</h3>
            <p><?php echo $stats['pending_teachers']; ?></p>
            <i class="fas fa-user-clock"></i>
        </div>
    </div>

    <div class="dashboard-sections">
        <!-- Activités récentes -->
        <div class="recent-activities">
            <h2>Recent Activities</h2>
            <div class="activities-list">
                <!-- Vous pouvez ajouter ici vos activités récentes -->
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="teachers.php?status=pending" class="btn btn-primary">
                    <i class="fas fa-user-check"></i>
                    Review Pending Teachers
                </a>
                <a href="courses.php?action=new" class="btn btn-secondary">
                    <i class="fas fa-plus"></i>
                    Add New Course
                </a>
                <a href="categories.php" class="btn btn-secondary">
                    <i class="fas fa-tags"></i>
                    Manage Categories
                </a>
                <a href="tags.php" class="btn btn-secondary">
                    <i class="fas fa-tag"></i>
                    Manage Tags
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
