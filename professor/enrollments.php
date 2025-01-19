<?php
session_start();
require_once '../config/database.php';
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
                            <th>Date de demande</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingEnrollments as $enrollment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($enrollment['firstname'] . ' ' . $enrollment['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['email']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['course_title']); ?></td>
                                <td><?php echo htmlspecialchars($enrollment['created_at'] ?? 'N/A'); ?></td>
                                <td class="actions">
                                    <form method="POST" class="d-inline">
                                        <input type="hidden" name="enrollment_id" value="<?php echo $enrollment['enrollment_id']; ?>">
                                        <button type="submit" name="approve" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Approuver
                                        </button>
                                        <button type="submit" name="reject" class="btn btn-danger btn-sm">
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