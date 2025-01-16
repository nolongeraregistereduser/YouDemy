<?php
session_start();
require_once '../config/database.php';
require_once '../Class/User.php';
require_once '../Class/Teacher.php';
require_once '../Class/Course.php';
require_once '../Class/Tag.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
    header('Location: ../auth.php');
    exit();
}

$db = new Database();
$pdo = $db->getConnection();

$teacher = new Teacher($pdo);
$teacher->setId($_SESSION['user']['id']);

$course = new Course($pdo);
$tag = new Tag($pdo);

// Get course ID from URL
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get course details
$courseDetails = $course->getById($courseId);

// Verify if course exists and belongs to current teacher
if (!$courseDetails || $courseDetails['teacher_id'] != $_SESSION['user']['id']) {
    header('Location: my_courses.php');
    exit();
}

// Get all available tags
$allTags = $tag->getAll();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'content' => $_POST['content'],
        'category_id' => $_POST['category_id'],
        'status' => $_POST['status'],
        'teacher_id' => $_SESSION['user']['id']
    ];

    // Handle image upload if new image is provided
    if (!empty($_FILES['image']['name'])) {
        $uploadDir = '../uploads/courses/';
        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $uploadFile = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $data['image_url'] = 'uploads/courses/' . $fileName;
        }
    } else {
        $data['image_url'] = $courseDetails['image_url'];
    }

    // Handle tags
    $data['tags'] = isset($_POST['tags']) ? $_POST['tags'] : [];

    if ($course->update($courseId, $data)) {
        $_SESSION['success_message'] = "Le cours a été modifié avec succès.";
        header('Location: my_courses.php');
        exit();
    } else {
        $error_message = "Une erreur est survenue lors de la modification du cours.";
    }
}

$page = 'my_courses';
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <div class="top-bar">
        <div class="welcome">
            <h2>Modifier le Cours</h2>
            <p>Modifiez les informations de votre cours</p>
        </div>
    </div>

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
                       value="<?php echo htmlspecialchars($courseDetails['title']); ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4" required>
                    <?php echo htmlspecialchars($courseDetails['description']); ?>
                </textarea>
            </div>

            <div class="form-group">
                <label for="content">Contenu</label>
                <textarea id="content" name="content" class="form-control" rows="8" required>
                    <?php echo htmlspecialchars($courseDetails['content']); ?>
                </textarea>
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

<?php require_once 'includes/footer.php'; ?> 