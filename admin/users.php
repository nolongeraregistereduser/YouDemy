<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';

// Modifier la vérification de session pour utiliser les bonnes clés
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth.php');
    exit();
}

$db = new Database();
$pdo = $db->getConnection();

// Gérer les actions (suspend/delete)
if(isset($_POST['action']) && isset($_POST['user_id'])) {
    $action = $_POST['action'];
    $userId = $_POST['user_id'];
    
    try {
        $pdo->beginTransaction();
        
        switch($action) {
            case 'suspend':
                $stmt = $pdo->prepare("UPDATE users SET status = 'blocked' WHERE id = ? AND role != 'admin'");
                $message = "L'utilisateur a été suspendu avec succès.";
                break;
            case 'activate':
                $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ? AND role != 'admin'");
                $message = "L'utilisateur a été activé avec succès.";
                break;
            case 'delete':
                // Supprimer les inscriptions si c'est un étudiant
                $stmt = $pdo->prepare("DELETE FROM enrollments WHERE student_id = ?");
                $stmt->execute([$userId]);
                
                // Supprimer les cours si c'est un professeur
                $stmt = $pdo->prepare("DELETE FROM courses WHERE teacher_id = ?");
                $stmt->execute([$userId]);
                
                // Supprimer l'utilisateur
                $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
                $message = "L'utilisateur a été supprimé avec succès.";
                break;
        }
        
        $stmt->execute([$userId]);
        $pdo->commit();
        $_SESSION['success'] = $message;
        
    } catch(Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erreur: " . $e->getMessage();
    }
    
    header('Location: users.php');
    exit();
}

// Récupérer la liste des utilisateurs
$query = "SELECT * FROM users WHERE role != 'admin' ORDER BY role, created_at DESC";
$stmt = $pdo->query($query);
$users = $stmt->fetchAll();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="page-header">
        <h1>Gestion des Utilisateurs</h1>
        <p>Gérer tous les comptes utilisateurs</p>
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

    <!-- Liste des utilisateurs -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Date d'inscription</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($users as $user): ?>
                    <tr class="user-row <?php echo $user['role']; ?>">
                        <td><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="role-badge <?php echo $user['role']; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-badge <?php echo $user['status']; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($user['created_at'])); ?></td>
                        <td class="actions">
                            <?php if($user['status'] === 'active'): ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <input type="hidden" name="action" value="suspend">
                                    <button type="submit" class="btn btn-warning" onclick="return confirm('Êtes-vous sûr de vouloir suspendre cet utilisateur ?')">
                                        <i class="fas fa-ban"></i> Suspendre
                                    </button>
                                </form>
                            <?php else: ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <input type="hidden" name="action" value="activate">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> Activer
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <input type="hidden" name="action" value="delete">
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
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

<?php require_once 'includes/footer.php'; ?> 