<?php
session_start();
require_once '../config/database.php';
require_once '../Class/Course.php';
require_once '../Class/Category.php';
require_once '../Class/Tag.php';

// Vérification teacher
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header('Location: ../auth.php');
    exit();
}

$db = new Database();
$pdo = $db->getConnection();

$course = new Course($pdo);
$category = new Category($pdo);
$tag = new Tag($pdo);

$categories = $category->getAll();
$tags = $tag->getAll();

$message = '';
$error = '';

// Pour le sidebar actif
$page = 'add_course';

// Pour les informations de l'utilisateur dans le sidebar
$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Traitement upload image
    $image_url = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['image']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $image_url = 'uploads/' . uniqid() . '.' . $filetype;
            move_uploaded_file($_FILES['image']['tmp_name'], '../' . $image_url);
        }
    }

    // Initialize content URL
    $content_url = '';
    $content_type = $_POST['content_type'];

    // Handle content upload based on type
    if ($content_type === 'video' && isset($_FILES['video']) && $_FILES['video']['error'] === 0) {
        $allowed = ['mp4', 'webm'];
        $filename = $_FILES['video']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $content_url = 'uploads/' . uniqid() . '.' . $filetype;
            move_uploaded_file($_FILES['video']['tmp_name'], '../' . $content_url);
        }
    } elseif ($content_type === 'document' && isset($_FILES['document']) && $_FILES['document']['error'] === 0) {
        $allowed = ['pdf', 'doc', 'docx'];
        $filename = $_FILES['document']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $content_url = 'uploads/' . uniqid() . '.' . $filetype;
            move_uploaded_file($_FILES['document']['tmp_name'], '../' . $content_url);
        }
    }

    try {
        $courseData = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'content' => $_POST['content'],
            'image_url' => $image_url,
            'content_url' => $content_url,
            'content_type' => $content_type,
            'teacher_id' => $_SESSION['user']['id'],
            'category_id' => $_POST['category_id'],
            'tags' => isset($_POST['tags']) ? $_POST['tags'] : []
        ];

        if ($course->create($courseData)) {
            $message = "Cours ajouté avec succès";
            header('Location: index.php');
            exit();
        }
    } catch(PDOException $e) {
        $error = "Erreur lors de l'ajout du cours: " . $e->getMessage();
    }
}

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <div class="welcome">
            <h2>Ajouter un nouveau cours</h2>
            <p>Créez et publiez un nouveau cours pour vos étudiants</p>
        </div>
    </div>

    <div class="content-wrapper">
        <?php if ($message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data" class="course-form">
                    <div class="form-group">
                        <label>Titre du cours</label>
                        <input type="text" name="title" class="form-control" required 
                               placeholder="Entrez le titre du cours">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" required
                                  placeholder="Décrivez brièvement votre cours"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Type de contenu</label>
                        <select name="content_type" id="content_type" class="form-control" required onchange="toggleContentUpload()">
                            <option value="">Sélectionner le type de contenu</option>
                            <option value="video">Vidéo</option>
                            <option value="document">Document (PDF)</option>
                        </select>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Image du cours</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="form-text">Format recommandé: JPG, PNG (max 2MB)</small>
                        </div>

                        <div class="form-group col-md-6" id="video_upload" style="display: none;">
                            <label>Vidéo du cours</label>
                            <input type="file" name="video" class="form-control" accept="video/*">
                            <small class="form-text">Format recommandé: MP4, WEBM (max 100MB)</small>
                        </div>

                        <div class="form-group col-md-6" id="document_upload" style="display: none;">
                            <label>Document du cours</label>
                            <input type="file" name="document" class="form-control" accept=".pdf,.doc,.docx">
                            <small class="form-text">Format recommandé: PDF, DOC (max 10MB)</small>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Catégorie</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Sélectionner une catégorie</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?php echo $cat['id']; ?>">
                                        <?php echo htmlspecialchars($cat['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Tags</label>
                            <select name="tags[]" class="form-control" multiple>
                                <?php foreach ($tags as $t): ?>
                                    <option value="<?php echo $t['id']; ?>">
                                        <?php echo htmlspecialchars($t['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text">Maintenez Ctrl pour sélectionner plusieurs tags</small>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i>
                            Ajouter le cours
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i>
                            Réinitialiser
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function toggleContentUpload() {
    const contentType = document.getElementById('content_type').value;
    const videoUpload = document.getElementById('video_upload');
    const documentUpload = document.getElementById('document_upload');

    if (contentType === 'video') {
        videoUpload.style.display = 'block';
        documentUpload.style.display = 'none';
    } else if (contentType === 'document') {
        videoUpload.style.display = 'none';
        documentUpload.style.display = 'block';
    } else {
        videoUpload.style.display = 'none';
        documentUpload.style.display = 'none';
    }
}
</script>

<?php require_once 'includes/footer.php'; ?> 