<?php
session_start();
require_once 'config/database.php';
require_once 'Class/Course.php';
require_once 'Class/Enrollment.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize objects
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
$enrollmentStatus = null;

if ($isStudent) {
    $studentId = $_SESSION['user']['id'];
    $enrollmentStatus = $enrollmentObj->getEnrollmentStatus($studentId, $courseId);
}

// Get course details
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

        .course-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .main-content {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .course-header {
            margin-bottom: 20px;
        }

        .course-header h1 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #1c1d1f;
            font-weight: 600;
        }

        .course-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 15px;
        }

        .course-meta span {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1c1d1f;
            font-size: 14px;
        }

        .course-meta i {
            color: #5624d0;
        }

        .document-type {
            background: #e3e6f0;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }

        .course-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .tag {
            background: #f1f1f1;
            color: #666;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 500;
        }

        .tag:hover {
            background: #e3e3e3;
            color: #333;
        }

        .course-preview {
            background: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
        }

        .preview-image {
            width: 100%;
            height: 300px;
            overflow: hidden;
            border-radius: 8px;
            margin-top: 10px;
        }

        .preview-image img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 8px;
        }

        .description {
            padding: 20px;
        }

        .description h2 {
            font-size: 18px;
            margin-bottom: 12px;
            color: #1c1d1f;
            font-weight: 600;
        }

        .description p {
            font-size: 14px;
            line-height: 1.6;
            color: #1c1d1f;
        }

        .enrollment-status {
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            background-color: #fff3cd;
            border: 1px solid #ffd700;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .enrollment-status i {
            font-size: 24px;
            color: #856404;
            margin-bottom: 8px;
        }

        .enrollment-status h3 {
            font-size: 16px;
            color: #856404;
            margin-bottom: 8px;
        }

        .enrollment-status p {
            font-size: 14px;
            color: #333;
        }

        .enroll-button {
            width: 100%;
            padding: 12px;
            background: #a435f0;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .enroll-button:hover {
            background: #8710d8;
        }

        /* Course Materials Styles */
        .course-materials {
            margin-top: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .materials-header {
            padding: 20px;
            border-bottom: 1px solid #e3e6f0;
        }

        .materials-header h2 {
            font-size: 20px;
            color: #1c1d1f;
            margin-bottom: 15px;
        }

        .progress-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: #e3e6f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress {
            height: 100%;
            background: #5624d0;
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .content-sections {
            padding: 20px;
        }

        .video-player {
            width: 100%;
            margin: 20px 0;
            background: #000;
            border-radius: 8px;
            overflow: hidden;
        }

        .video-player video {
            width: 100%;
            height: 400px;
            display: block;
        }

        .document-viewer {
            width: 100%;
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .document-container {
            width: 100%;
            min-height: 600px;
            padding: 20px;
            background: #f8f9fa;
        }

        .doc-fallback {
            text-align: center;
            padding: 40px 20px;
        }

        .download-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 24px;
            background: #a435f0;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }

        .download-btn:hover {
            background: #8710d8;
        }

        .download-btn i {
            margin-right: 8px;
        }

        .course-resources {
            padding: 20px;
            background: #f8f9fa;
            border-top: 1px solid #e3e6f0;
        }

        .course-resources h3 {
            font-size: 16px;
            color: #1c1d1f;
            margin-bottom: 15px;
        }

        .resources-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .resource-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: white;
            border-radius: 6px;
            color: #1c1d1f;
            text-decoration: none;
            transition: all 0.2s;
        }

        .resource-item:hover {
            background: #f1f1f1;
        }

        .resource-item i {
            color: #5624d0;
        }

        .no-resources {
            color: #6a6f73;
            font-style: italic;
        }

        .no-content {
            padding: 20px;
            text-align: center;
            color: #6a6f73;
            font-style: italic;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="course-details-container">
        <div class="course-content">
            <div class="main-content">
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

                <?php if ($enrollmentStatus === 'approved'): ?>
                    <div class="course-materials">
                        <?php if (!empty($courseDetails['content_type']) && !empty($courseDetails['content_url'])): 
                            if ($courseDetails['content_type'] === 'video'): ?>
                                <div class="video-player">
                                    <video width="100%" height="400" controls>
                                        <source src="/Youdemy/<?php echo htmlspecialchars($courseDetails['content_url']); ?>" 
                                                type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            <?php elseif ($courseDetails['content_type'] === 'document'): ?>
                                <div class="document-viewer">
                                    <?php 
                                    $fileExtension = pathinfo($courseDetails['content_url'], PATHINFO_EXTENSION);
                                    $fullUrl = 'http://' . $_SERVER['HTTP_HOST'] . '/Youdemy/' . $courseDetails['content_url'];
                                    ?>
                                    <div class="document-container">
                                        <?php if ($fileExtension === 'pdf'): ?>
                                            <object
                                                data="<?php echo htmlspecialchars($fullUrl); ?>"
                                                type="application/pdf"
                                                width="100%"
                                                height="800px">
                                                <p>Impossible d'afficher le PDF. <a href="<?php echo htmlspecialchars($fullUrl); ?>" target="_blank">Cliquez ici pour le télécharger</a>.</p>
                                            </object>
                                        <?php else: ?>
                                            <div class="doc-fallback">
                                                <p>Pour voir le document, vous pouvez:</p>
                                                <a href="<?php echo htmlspecialchars($fullUrl); ?>" class="download-btn" target="_blank">
                                                    <i class="fas fa-download"></i> Télécharger le document
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php if (!empty($courseDetails['image_url'])): ?>
                        <div class="preview-image">
                            <img src="<?php echo htmlspecialchars($courseDetails['image_url']); ?>" 
                                 alt="Aperçu du cours">
                        </div>
                    <?php endif; ?>
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
                        <div class="enrollment-status">
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