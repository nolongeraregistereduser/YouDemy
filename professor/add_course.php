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

    // Traitement upload video
    $video_url = '';
    if (isset($_FILES['video']) && $_FILES['video']['error'] === 0) {
        $allowed = ['mp4', 'webm'];
        $filename = $_FILES['video']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed)) {
            $video_url = 'uploads/' . uniqid() . '.' . $filetype;
            move_uploaded_file($_FILES['video']['tmp_name'], '../' . $video_url);
        }
    }

    try {
        $courseData = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'content' => $_POST['content'],
            'image_url' => $image_url,
            'video_url' => $video_url,
            'teacher_id' => $_SESSION['user']['id'],
            'category_id' => $_POST['category_id'],
            'tags' => isset($_POST['tags']) ? $_POST['tags'] : []
        ];

        if ($course->create($courseData)) {
            $message = "Cours ajouté avec succès";
        }
    } catch(PDOException $e) {
        $error = "Erreur lors de l'ajout du cours: " . $e->getMessage();
    }
}

require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="container">
        <h1>Ajouter un nouveau cours</h1>

        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Titre</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Contenu</label>
                        <textarea name="content" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Image du cours</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-group">
                        <label>Vidéo du cours</label>
                        <input type="file" name="video" class="form-control" accept="video/*">
                    </div>

                    <div class="form-group">
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

                    <div class="form-group">
                        <label>Tags</label>
                        <select name="tags[]" class="form-control" multiple>
                            <?php foreach ($tags as $t): ?>
                                <option value="<?php echo $t['id']; ?>">
                                    <?php echo htmlspecialchars($t['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus-circle"></i>
                        Ajouter le cours
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 