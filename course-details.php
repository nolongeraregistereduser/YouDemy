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

// Get enrollment status if student
$enrollmentStatus = null;
if ($isStudent) {
    $studentId = $_SESSION['user']['id'];
    $enrollmentStatus = $enrollmentObj->getEnrollmentStatus($studentId, $courseId);
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="assets/css/styleStudent.css">
    <style>
        .course-details-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }

        .course-header {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .course-header h1 {
            font-size: 28px;
            margin-bottom: 15px;
            color: #1c1d1f;
        }

        .course-meta {
            display: flex;
            gap: 15px;
        }

        .course-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1c1d1f;
        }

        .course-meta i {
            color: #5624d0;
        }

        .document-type {
            background: #e3e6f0;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .course-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .main-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
        }

        .description h2 {
            font-size: 20px;
            margin-bottom: 15px;
            color: #1c1d1f;
        }

        .preview-image {
            margin-top: 15px;
        }

        .preview-image img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .enrollment-status {
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .enrollment-status.pending {
            background-color: #fff3cd;
            border: 2px solid #ffd700;
        }

        .enrollment-status.pending i {
            font-size: 2em;
            color: #856404;
            margin-bottom: 10px;
        }

        .enrollment-status.pending h3 {
            color: #856404;
            margin-bottom: 10px;
        }

        .enrollment-status.pending p {
            color: #333;
        }

        .enroll-button {
            width: 100%;
            padding: 16px;
            background: #a435f0;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .enroll-button:hover {
            background: #8710d8;
        }
    </style>
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
                <span class="document-type">
                    <i class="fas fa-file-alt"></i>
                    <?php echo htmlspecialchars($courseDetails['document_type'] ?? 'Cours'); ?>
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
                <?php if ($isStudent): ?>
                    <?php if (!$enrollmentStatus): ?>
                        <div class="enrollment-card">
                            <form action="enroll.php" method="POST">
                                <input type="hidden" name="course_id" value="<?php echo $courseId; ?>">
                                <button type="submit" name="enroll" class="enroll-button">
                                    Demander l'inscription
                                </button>
                            </form>
                        </div>
                    <?php elseif ($enrollmentStatus === 'pending'): ?>
                        <div class="enrollment-status pending">
                            <i class="fas fa-clock"></i>
                            <h3>En attente d'approbation</h3>
                            <p>Votre demande d'inscription est en cours d'examen.</p>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
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