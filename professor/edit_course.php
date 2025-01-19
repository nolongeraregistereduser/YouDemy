<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';
require_once '../Class/Teacher.php';
require_once '../Class/Course.php';
require_once '../Class/Tag.php';
require_once '../Class/Category.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header('Location: ../auth.php');
    exit();
}

// Set user variable for sidebar/navbar
$user = $_SESSION['user'];
$page = 'my_courses';

$db = new Database();
$pdo = $db->getConnection();

$teacher = new Teacher($pdo);
$teacher->setId($_SESSION['user']['id']);

$course = new Course($pdo);
$tag = new Tag($pdo);
$category = new Category($pdo);

// Get course ID from URL
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get course details
$courseDetails = $course->getById($courseId);

// Verify if course exists and belongs to current teacher
if (!$courseDetails || $courseDetails['teacher_id'] != $_SESSION['user']['id']) {
    header('Location: my_courses.php');
    exit();
}

// Get all categories
$categories = $category->getAll();

// Get all available tags
$allTags = $tag->getAll();

// Get current course tags
$courseTags = !empty($courseDetails['tag_ids']) ? explode(',', $courseDetails['tag_ids']) : [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Initialize variables
    $content_url = $courseDetails['content_url'];
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

    // Handle image upload if new image is provided
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../uploads/';
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $data['image_url'] = 'uploads/' . $fileName;
        }
    } else {
        $data['image_url'] = $courseDetails['image_url'];
    }

    $data = [
        'title' => $_POST['title'],
        'content' => trim($_POST['content'] ?? ''),
        'content_type' => $content_type,
        'content_url' => $content_url,
        'image_url' => $data['image_url'],
        'video_url' => null,  // Since we're using content_url for videos
        'category_id' => $_POST['category_id'],
        'status' => $_POST['status'],
        'teacher_id' => $_SESSION['user']['id'],
        'tags' => isset($_POST['tags']) ? $_POST['tags'] : []
    ];

    if ($course->update($courseId, $data)) {
        $_SESSION['success_message'] = "Le cours a été modifié avec succès.";
        header('Location: my_courses.php');
        exit();
    } else {
        $error_message = "Une erreur est survenue lors de la modification du cours.";
    }
}

require_once 'includes/header.php';
?>

<div class="dashboard">
    <?php require_once 'includes/sidebar.php'; ?>
    
    <div class="main-content">
        <?php require_once 'includes/navbar.php'; ?>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="edit-course-form">
            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Titre du cours</label>
                    <input type="text" id="title" name="title" class="form-control" 
                           value="<?php echo htmlspecialchars($courseDetails['title'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="content">Contenu</label>
                    <textarea id="content" name="content" class="form-control" rows="4" required>
                        <?php echo htmlspecialchars($courseDetails['content'] ?? ''); ?>
                    </textarea>
                </div>

                <div class="form-group">
                    <label>Type de contenu</label>
                    <select name="content_type" id="content_type" class="form-control" required onchange="toggleContentUpload()">
                        <option value="video" <?php echo ($courseDetails['content_type'] ?? '') === 'video' ? 'selected' : ''; ?>>Vidéo</option>
                        <option value="document" <?php echo ($courseDetails['content_type'] ?? '') === 'document' ? 'selected' : ''; ?>>Document</option>
                    </select>
                </div>

                <div class="form-group" id="video_upload" style="display: none;">
                    <label>Vidéo du cours</label>
                    <input type="file" name="video" class="form-control" accept="video/*">
                    <?php if (!empty($courseDetails['content_url']) && $courseDetails['content_type'] === 'video'): ?>
                        <p>Vidéo actuelle: <?php echo basename($courseDetails['content_url']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group" id="document_upload" style="display: none;">
                    <label>Document du cours</label>
                    <input type="file" name="document" class="form-control" accept=".pdf,.doc,.docx">
                    <?php if (!empty($courseDetails['content_url']) && $courseDetails['content_type'] === 'document'): ?>
                        <p>Document actuel: <?php echo basename($courseDetails['content_url']); ?></p>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="image">Image du cours</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <?php if (!empty($courseDetails['image_url'])): ?>
                        <div class="current-image">
                            <img src="<?php echo '../' . $courseDetails['image_url']; ?>" alt="Current course image">
                            <p>Image actuelle</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="category_id">Catégorie</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                    <?php echo $category['id'] == $courseDetails['category_id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="tags">Tags</label>
                    <select id="tags" name="tags[]" class="form-control" multiple>
                        <?php foreach ($allTags as $tag): ?>
                            <option value="<?php echo $tag['id']; ?>"
                                    <?php echo in_array($tag['id'], $courseTags) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tag['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status">Statut</label>
                    <select id="status" name="status" class="form-control" required>
                        <option value="draft" <?php echo $courseDetails['status'] == 'draft' ? 'selected' : ''; ?>>Brouillon</option>
                        <option value="published" <?php echo $courseDetails['status'] == 'published' ? 'selected' : ''; ?>>Publié</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="my_courses.php" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 