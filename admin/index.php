<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
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

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Youdemy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="admin-container">
        <!-- La sidebar est maintenant incluse via includes/sidebar.php -->
        
        <!-- Main Content -->
        <div class="main-content">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
            
            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Courses</h3>
                    <p><?php echo $stats['total_courses']; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <p><?php echo $stats['total_students']; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Total Teachers</h3>
                    <p><?php echo $stats['total_teachers']; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Pending Teachers</h3>
                    <p><?php echo $stats['pending_teachers']; ?></p>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="recent-activities">
                <h2>Recent Activities</h2>
                <!-- Add recent activities here -->
            </div>

            <!-- Quick Actions -->
            <div class="quick-actions">
                <h2>Quick Actions</h2>
                <div class="action-buttons">
                    <a href="teachers.php?status=pending" class="btn">Review Pending Teachers</a>
                    <a href="courses.php?action=new" class="btn">Add New Course</a>
                    <a href="categories.php?action=new" class="btn">Manage Categories</a>
                    <a href="tags.php" class="btn">Manage Tags</a>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/admin.js"></script>

</body>
</html>
