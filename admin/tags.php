<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';
require_once '../Class/Tag.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../auth.php');
    exit();
}

$db = new Database();
$pdo = $db->getConnection();
$tag = new Tag($pdo);

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                if ($tag->create($_POST['name'])) {
                    $message = "Tag ajouté avec succès";
                } else {
                    $error = "Erreur lors de l'ajout du tag";
                }
                break;

            case 'bulk_create':
                $tags = explode(',', $_POST['tags']);
                if ($tag->bulkCreate($tags)) {
                    $message = "Tags ajoutés avec succès";
                } else {
                    $error = "Erreur lors de l'ajout des tags";
                }
                break;
                
            case 'update':
                if ($tag->update($_POST['id'], $_POST['name'])) {
                    $message = "Tag mis à jour avec succès";
                } else {
                    $error = "Erreur lors de la mise à jour";
                }
                break;
                
            case 'delete':
                if ($tag->delete($_POST['id'])) {
                    $message = "Tag supprimé avec succès";
                } else {
                    $error = "Erreur lors de la suppression";
                }
                break;
        }
    }
}

$tags = $tag->getAll();

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <h1>Gestion des Tags</h1>
    
    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-6">
            <button id="showAddForm" class="btn btn-primary">Ajouter un tag</button>
        </div>
        <div class="col-md-6">
            <button id="showBulkForm" class="btn btn-secondary">Ajout en masse</button>
        </div>
    </div>

    <!-- Modal d'ajout -->
    <div class="modal" id="addModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajouter un tag</h5>
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
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal d'ajout en masse -->
    <div class="modal" id="bulkModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ajout en masse des tags</h5>
                    <button type="button" class="close" onclick="closeBulkModal()">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="action" value="bulk_create">
                        <div class="form-group">
                            <label>Tags (séparés par des virgules)</label>
                            <textarea name="tags" class="form-control" rows="4" required 
                                    placeholder="PHP, JavaScript, HTML, CSS"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Ajouter en masse</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des tags -->
    <div class="card">
        <div class="card-header">
            <h2>Liste des tags</h2>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tags as $t): ?>
                    <tr>
                        <td><?php echo $t['id']; ?></td>
                        <td><?php echo htmlspecialchars($t['name']); ?></td>
                        <td><?php echo $t['created_at']; ?></td>
                        <td>
                            <button class="btn btn-sm btn-primary" 
                                    onclick="editTag(<?php echo htmlspecialchars(json_encode($t)); ?>)">
                                Modifier
                            </button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $t['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce tag ?')">
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
    <div class="modal" id="editModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le tag</h5>
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
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
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

document.getElementById('showBulkForm').addEventListener('click', function() {
    document.getElementById('bulkModal').style.display = 'block';
});

function closeAddModal() {
    document.getElementById('addModal').style.display = 'none';
}

function closeBulkModal() {
    document.getElementById('bulkModal').style.display = 'none';
}

function editTag(tag) {
    const modal = document.getElementById('editModal');
    document.getElementById('edit_id').value = tag.id;
    document.getElementById('edit_name').value = tag.name;
    modal.style.display = 'block';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

window.onclick = function(event) {
    if (event.target.classList.contains('modal')) {
        event.target.style.display = 'none';
    }
}
</script>

<?php require_once 'includes/footer.php'; ?> 