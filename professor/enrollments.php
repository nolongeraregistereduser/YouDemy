<?php
session_start();
require_once '../config/database.php';
require_once '../Class/Course.php';
require_once '../Class/Enrollment.php';
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

// Handle enrollment actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve']) && isset($_POST['enrollment_id'])) {
        if ($teacher->approveEnrollment($_POST['enrollment_id'])) {
            $success_message = "Inscription approuvée avec succès.";
        } else {
            $error_message = "Erreur lors de l'approbation.";
        }
    } elseif (isset($_POST['reject']) && isset($_POST['enrollment_id'])) {
        if ($teacher->rejectEnrollment($_POST['enrollment_id'])) {
            $success_message = "Inscription rejetée.";
        } else {
            $error_message = "Erreur lors du rejet.";
        }
    }
}

$pendingEnrollments = $teacher->getPendingEnrollments();
$user = $_SESSION['user'];
$page = 'enrollments';

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <div class="welcome">
            <h2>Demandes d'inscription</h2>
            <p>Gérez les demandes d'inscription à vos cours</p>
        </div>
    </div>

    <?php if (isset($success_message)): ?>
        <div class="alert alert-success">
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($error_message)): ?>
        <div class="alert alert-danger">
            <?php echo $error_message; ?>
        </div>
    <?php endif; ?>

    <div class="enrollments-container">
        <?php if (!empty($pendingEnrollments)): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Étudiant</th>
                            <th>Email</th>
                            <th>Cours</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingEnrollments as $enrollment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($enrollment['firstname'] . ' ' . $enrollment['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['email']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['course_title']); ?></td>
                                <td class="actions">
                                    <form method="POST" class="action-buttons">
                                        <input type="hidden" name="enrollment_id" value="<?php echo $enrollment['enrollment_id']; ?>">
                                        <button type="submit" name="approve" class="btn-approve">
                                            <i class="fas fa-check"></i> Approuver
                                        </button>
                                        <button type="submit" name="reject" class="btn-reject">
                                            <i class="fas fa-times"></i> Rejeter
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="fas fa-clipboard-check"></i>
                <h3>Aucune demande en attente</h3>
                <p>Vous n'avez pas de nouvelles demandes d'inscription à traiter.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<style>
.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-approve, .btn-reject {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.btn-approve {
    background-color: #28a745;
    color: white;
}

.btn-approve:hover {
    background-color: #218838;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-reject {
    background-color: #dc3545;
    color: white;
}

.btn-reject:hover {
    background-color: #c82333;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.btn-approve i, .btn-reject i {
    font-size: 12px;
}

/* Table styling improvements */
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin: 20px 0;
}

.table th {
    background-color: #f8f9fa;
    color: #495057;
    font-weight: 600;
    padding: 12px 15px;
    text-align: left;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    padding: 12px 15px;
    border-bottom: 1px solid #dee2e6;
    vertical-align: middle;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Add some spacing between table cells */
.table td:not(:last-child),
.table th:not(:last-child) {
    padding-right: 20px;
}
</style> 