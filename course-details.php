<?php
session_start();
require_once 'config/database.php';
require_once 'Class/Course.php';
require_once 'Class/Enrollment.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Course object
$courseObj = new Course($db);
$enrollmentObj = new Enrollment($db);

// Get course ID from URL
$courseId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$courseId) {
    header('Location: /Youdemy/index.php');
    exit();
}

// Check if user is logged in and is a student
$isStudent = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'student';
$isEnrolled = false;

if ($isStudent) {
    $studentId = $_SESSION['user']['id'];
    $isEnrolled = $enrollmentObj->isEnrolled($studentId, $courseId);
}

// Get course preview details
$courseDetails = $courseObj->getPreviewContent($courseId);

if (!$courseDetails) {
    header('Location: /Youdemy/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($courseDetails['title'] ?? ''); ?> | YouDemy</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/Youdemy/assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="course-details-container">
        <div class="course-header">
            <h1><?php echo htmlspecialchars($courseDetails['title'] ?? ''); ?></h1>
            <div class="course-meta">
                <span class="category">
                    <i class="fas fa-folder"></i>
                    <?php echo htmlspecialchars($courseDetails['category_name'] ?? ''); ?>
                </span>
                <span class="instructor">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <?php 
                        $teacherName = trim(($courseDetails['teacher_firstname'] ?? '') . ' ' . ($courseDetails['teacher_lastname'] ?? ''));
                        echo htmlspecialchars($teacherName);
                    ?>
                </span>
            </div>
        </div>

        <div class="course-content">
            <div class="main-content">
                <div class="description">
                    <h2>Description du cours</h2>
                    <p><?php echo nl2br(htmlspecialchars($courseDetails['description'] ?? '')); ?></p>
                </div>

                <?php if (!$isEnrolled): ?>
                    <div class="course-preview">
                        <h2>Aperçu du cours</h2>
                        <?php if (!empty($courseDetails['image_url'])): ?>
                            <div class="preview-image">
                                <img src="<?php echo htmlspecialchars($courseDetails['image_url']); ?>" 
                                     alt="Aperçu du cours">
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="sidebar">
                <?php if (!$isEnrolled && $isStudent): ?>
                    <div class="enrollment-card">
                        <form action="enroll.php" method="POST">
                            <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                            <button type="submit" name="enroll" class="enroll-button">
                                Demander l'inscription
                            </button>
                        </form>
                    </div>
                <?php elseif (!$isStudent): ?>
                    <div class="login-prompt">
                        <p>Connectez-vous en tant qu'étudiant pour vous inscrire à ce cours</p>
                        <a href="/Youdemy/auth.php" class="login-button">Se connecter</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 