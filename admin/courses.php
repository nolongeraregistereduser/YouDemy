<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';
require_once '../Class/Course.php';

// Vérification admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth.php');
    exit();
}

$db = new Database();
$pdo = $db->getConnection();

// Traitement de la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    try {
        $courseId = $_POST['course_id'];
        
        $pdo->beginTransaction();
        
        // Supprimer les inscriptions liées au cours
        $stmt = $pdo->prepare("DELETE FROM enrollments WHERE course_id = ?");
        $stmt->execute([$courseId]);
        
        // Supprimer le cours
        $stmt = $pdo->prepare("DELETE FROM courses WHERE id = ?");
        $stmt->execute([$courseId]);
        
        $pdo->commit();
        $_SESSION['success'] = "Le cours a été supprimé avec succès.";
        
    } catch(Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur lors de la suppression du cours: " . $e->getMessage();
    }
    
    header('Location: courses.php');
    exit();
}

// Récupération des cours avec les informations du professeur
$query = "SELECT c.*, u.firstname as teacher_firstname, u.lastname as teacher_lastname, 
          (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id) as enrollment_count
          FROM courses c 
          LEFT JOIN users u ON c.teacher_id = u.id 
          ORDER BY c.created_at DESC";
$stmt = $pdo->query($query);
$courses = $stmt->fetchAll();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="page-header">
        <h1>Gestion des Cours</h1>
        <p>Superviser et gérer les cours de la plateforme</p>
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

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <!-- Liste des cours -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Professeur</th>
                    <th>Inscrits</th>
                    <th>Statut</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($courses as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['title']); ?></td>
                        <td><?php echo htmlspecialchars($course['teacher_firstname'] . ' ' . $course['teacher_lastname']); ?></td>
                        <td><?php echo $course['enrollment_count']; ?> étudiants</td>
                        <td>
                            <span class="status-badge <?php echo $course['status']; ?>">
                                <?php echo ucfirst($course['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($course['created_at'])); ?></td>
                        <td class="actions">
                            <a href="../course-details.php?id=<?php echo $course['id']; ?>" 
                               class="btn btn-info" target="_blank">
                                <i class="fas fa-eye"></i> Voir
                            </a>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                <button type="submit" class="btn btn-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ? Cette action est irréversible.')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.85em;
    font-weight: 500;
}

.status-badge.published {
    background-color: #4CAF50;
    color: white;
}

.status-badge.draft {
    background-color: #FFC107;
    color: black;
}

.actions {
    display: flex;
    gap: 8px;
}

.btn {
    padding: 6px 12px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-size: 0.9em;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-info {
    background-color: #2196F3;
    color: white;
}

.btn-danger {
    background-color: #f44336;
    color: white;
}

.btn i {
    font-size: 0.9em;
}

/* Responsive table */
@media (max-width: 768px) {
    .table-container {
        overflow-x: auto;
    }
    
    table {
        min-width: 800px;
    }
}
</style>

<?php require_once 'includes/footer.php'; ?> 