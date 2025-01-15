<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';
require_once '../Class/Category.php';
require_once '../Class/Admin.php';

// Vérification admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth.php');
    exit();
}

$db = new Database();
$pdo = $db->getConnection();
$category = new Category($pdo);

// Traitement des actions CRUD
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                if ($category->create($_POST['name'], $_POST['description'])) {
                    $message = "Catégorie ajoutée avec succès";
                } else {
                    $error = "Erreur lors de l'ajout de la catégorie";
                }
                break;
                
            case 'update':
                if ($category->update($_POST['id'], $_POST['name'], $_POST['description'])) {
                    $message = "Catégorie mise à jour avec succès";
                } else {
                    $error = "Erreur lors de la mise à jour";
                }
                break;
                
            case 'delete':
                if ($category->delete($_POST['id'])) {
                    $message = "Catégorie supprimée avec succès";
                } else {
                    $error = "Erreur lors de la suppression";
                }
                break;
        }
    }
}

// Récupération des catégories
$categories = $category->getAll();

// Include header
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <h1>Gestion des Catégories</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Bouton pour afficher le form d'ajout -->
    <button id="showAddForm" class="btn btn-primary mb-3">Ajouter une nouvelle catégorie</button>

    <!-- Modal d'ajout -->
    <div class="modal" id="addModal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter une catégorie</h5>
                    <button type="button" class="close" onclick="closeAddModal()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="create">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des catégories -->
    <div class="card">
        <div class="card-header">
            <h2>Liste des catégories</h2>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?php echo $cat['id']; ?></td>
                        <td><?php echo htmlspecialchars($cat['name']); ?></td>
                        <td><?php echo htmlspecialchars($cat['description']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" onclick="editCategory(<?php echo htmlspecialchars(json_encode($cat)); ?>)">
                                Modifier
                            </button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de modification -->
    <div class="modal" id="editModal" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier la catégorie</h5>
                    <button type="button" class="close" onclick="closeEditModal()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editForm">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" id="edit_description" class="form-control"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('showAddForm').addEventListener('click', function() {
    document.getElementById('addModal').style.display = 'block';
});

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

function editCategory(category) {
    const modal = document.getElementById('editModal');
    document.getElementById('edit_id').value = category.id;
    document.getElementById('edit_name').value = category.name;
    document.getElementById('edit_description').value = category.description;
    modal.style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Fermer modal quand user click kharj modal
window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

<?php require_once 'includes/footer.php'; ?> 