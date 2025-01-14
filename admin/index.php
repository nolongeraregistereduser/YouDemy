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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Youdemy</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Add your CSS framework here (Bootstrap, Tailwind, etc.) -->

    <style> 

/* Admin Dashboard Styles */
.admin-container {
    display: flex;
    min-height: 100vh;
}

.sidebar {
    width: 250px;
    background: #2c3e50;
    color: white;
    padding: 20px;
}

.sidebar nav ul {
    list-style: none;
    padding: 0;
}

.sidebar nav ul li a {
    color: white;
    text-decoration: none;
    padding: 10px 15px;
    display: block;
    margin: 5px 0;
    border-radius: 5px;
}

.sidebar nav ul li a:hover,
.sidebar nav ul li a.active {
    background: #34495e;
}

.main-content {
    flex: 1;
    padding: 20px;
    background: #f5f6fa;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin: 20px 0;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    background: #3498db;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    text-align: center;
}

.btn:hover {
    background: #2980b9;
}

    </style>
</head>
<body>
    <div class="admin-container">
        <!-- Sidebar/Navigation -->
        <div class="sidebar">
            <h2>Admin Dashboard</h2>
            <nav>
                <ul>
                    <li><a href="index.php" class="active">Dashboard</a></li>
                    <li><a href="teachers.php">Manage Teachers</a></li>
                    <li><a href="users.php">Manage Users</a></li>
                    <li><a href="courses.php">Manage Courses</a></li>
                    <li><a href="categories.php">Categories & Tags</a></li>
                    <li><a href="statistics.php">Statistics</a></li>
                    <li><a href="../logout.php">Logout</a></li>
                </ul>
            </nav>
        </div>

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
</body>
</html>
