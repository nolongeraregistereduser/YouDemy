<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';
require_once '../Class/Notification.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth.php');
    exit();
}

$db = new Database();
$pdo = $db->getConnection();
$notification = new Notification($pdo);

// Handle teacher status updates
if(isset($_POST['action']) && isset($_POST['teacher_id'])) {
    $action = $_POST['action'];
    $teacherId = $_POST['teacher_id'];
    
    try {
        $pdo->beginTransaction();
        
        $status = ($action === 'approve') ? 'active' : 'blocked';
        
        // Update teacher status
        $stmt = $pdo->prepare("UPDATE users SET status = ? WHERE id = ? AND role = 'teacher'");
        $stmt->execute([$status, $teacherId]);
        
        // If approving teacher, send notification
        if($action === 'approve') {
            // Get teacher details with explicit column selection
            $stmt = $pdo->prepare("SELECT id, firstname, lastname, email, status FROM users WHERE id = ? AND role = 'teacher'");
            $stmt->execute([$teacherId]);
            $teacher = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$teacher) {
                throw new Exception("Teacher not found");
            }
            
            // Debug log
            error_log("Attempting to send email to teacher: " . json_encode($teacher));
            
            // Send approval email
            $emailResult = $notification->sendApprovalEmail($teacher);
            error_log("Email send result: " . ($emailResult ? "Success" : "Failed"));
            
            if($emailResult) {
                $_SESSION['success'] = "Teacher approved and notification sent successfully!";
            } else {
                // Roll back if email fails
                $pdo->rollBack();
                throw new Exception("Failed to send approval email");
            }
        } else {
            $_SESSION['success'] = "Teacher status updated successfully!";
        }
        
        $pdo->commit();
        
    } catch(Exception $e) {
        $pdo->rollBack();
        error_log("Error in teacher approval process: " . $e->getMessage());
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    
    header('Location: teachers.php');
    exit();
}

// Get teachers list
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$query = "SELECT * FROM users WHERE role = 'teacher'";
if($status !== 'all') {
    $query .= " AND status = ?";
}
$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
if($status !== 'all') {
    $stmt->execute([$status]);
} else {
    $stmt->execute();
}
$teachers = $stmt->fetchAll();

// Include header
require_once 'includes/header.php';
// Include sidebar
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="page-header">
        <h1>Manage Teachers</h1>
        <p>View and manage teacher accounts</p>
    </div>

    <!-- Notifications -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['warning'])): ?>
        <div class="alert alert-warning">
            <?php 
                echo $_SESSION['warning'];
                unset($_SESSION['warning']);
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Status Filter -->
    <div class="filter-section">
        <a href="teachers.php" class="btn <?php echo $status === 'all' ? 'active' : ''; ?>">All</a>
        <a href="teachers.php?status=pending" class="btn <?php echo $status === 'pending' ? 'active' : ''; ?>">Pending</a>
        <a href="teachers.php?status=active" class="btn <?php echo $status === 'active' ? 'active' : ''; ?>">Active</a>
        <a href="teachers.php?status=blocked" class="btn <?php echo $status === 'blocked' ? 'active' : ''; ?>">Blocked</a>
    </div>

    <!-- Teachers List -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Courses</th>
                    <th>Students</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($teachers as $teacher): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($teacher['firstname'] . ' ' . $teacher['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                        <td>
                            <span class="status-badge <?php echo $teacher['status']; ?>">
                                <?php echo ucfirst($teacher['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php
                                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM courses WHERE teacher_id = ?");
                                $stmt->execute([$teacher['id']]);
                                echo $stmt->fetch()['count'];
                            ?>
                        </td>
                        <td>
                            <?php
                                $stmt = $pdo->prepare("SELECT COUNT(DISTINCT student_id) as count FROM enrollments WHERE course_id IN (SELECT id FROM courses WHERE teacher_id = ?)");
                                $stmt->execute([$teacher['id']]);
                                echo $stmt->fetch()['count'];
                            ?>
                        </td>
                        <td><?php echo date('Y-m-d', strtotime($teacher['created_at'])); ?></td>
                        <td>
                            <?php if($teacher['status'] === 'pending'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="teacher_id" value="<?php echo $teacher['id']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-success">Approve</button>
                                </form>
                            <?php endif; ?>
                            
                            <?php if($teacher['status'] !== 'blocked'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="teacher_id" value="<?php echo $teacher['id']; ?>">
                                    <input type="hidden" name="action" value="block">
                                    <button type="submit" class="btn btn-danger">Block</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="teacher_id" value="<?php echo $teacher['id']; ?>">
                                    <input type="hidden" name="action" value="approve">
                                    <button type="submit" class="btn btn-success">Unblock</button>
                                </form>
                            <?php endif; ?>
                            
                            <a href="teacher_details.php?id=<?php echo $teacher['id']; ?>" class="btn">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 