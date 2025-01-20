<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/Class/Course.php';
require_once __DIR__ . '/Class/Enrollment.php';

// Verify student authentication
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header('Location: auth.php');
    exit();
}

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Get student's enrolled courses
$query = "SELECT c.*, u.firstname as teacher_firstname, u.lastname as teacher_lastname,
          e.status as enrollment_status, e.enrollment_date,
          (SELECT COUNT(*) FROM enrollments WHERE course_id = c.id AND status = 'approved') as enrollment_count
          FROM courses c
          JOIN enrollments e ON c.id = e.course_id
          JOIN users u ON c.teacher_id = u.id
          WHERE e.student_id = :student_id
          AND e.status = 'approved'
          ORDER BY e.enrollment_date DESC";

$stmt = $db->prepare($query);
$stmt->execute(['student_id' => $_SESSION['user']['id']]);
$enrolledCourses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mes cours | YouDemy</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styleStudent.css">
    <link rel="stylesheet" href="assets/css/course-cards.css">
    <style>
        .my-learning-header {
            background-color: #f8f9fa;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        .my-learning-header h1 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .my-learning-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        .course-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            padding: 1rem 0;
        }

        .course-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 400px;
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .course-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background-color: #f8f9fa;
        }

        .course-content {
            padding: 1.5rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .course-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            height: 2.8em;
        }

        .course-author {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 1rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            height: 1.2em;
        }

        .enrollment-date {
            color: #888;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            height: 1em;
        }

        .course-actions {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 40px;
        }

        .continue-btn {
            background-color: #3498db;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
            display: inline-block;
            white-space: nowrap;
        }

        .continue-btn:hover {
            background-color: #2980b9;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
        }

        .empty-state i {
            font-size: 3rem;
            color: #95a5a6;
            margin-bottom: 1rem;
        }

        .empty-state h2 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #7f8c8d;
            margin-bottom: 1.5rem;
        }

        .browse-courses-btn {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .browse-courses-btn:hover {
            background-color: #2980b9;
        }

        .course-image.missing {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8f9fa;
        }

        .course-image.missing::after {
            content: '\f03e';
            font-family: 'Font Awesome 5 Free';
            font-size: 3rem;
            color: #cbd3da;
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="my-learning-header">
        <div class="my-learning-container">
            <h1>Mes cours</h1>
            <p>Continuez votre apprentissage là où vous vous êtes arrêté</p>
        </div>
    </div>

    <div class="my-learning-container">
        <?php if (empty($enrolledCourses)): ?>
            <div class="empty-state">
                <i class="fas fa-graduation-cap"></i>
                <h2>Vous n'êtes inscrit à aucun cours</h2>
                <p>Explorez notre catalogue de cours et commencez votre parcours d'apprentissage</p>
                <a href="courses.php" class="browse-courses-btn">Parcourir les cours</a>
            </div>
        <?php else: ?>
            <div class="course-grid">
                <?php foreach($enrolledCourses as $course): ?>
                    <div class="course-card">
                        <img src="<?php echo !empty($course['image_url']) ? htmlspecialchars($course['image_url']) : '#'; ?>" 
                             alt="<?php echo htmlspecialchars($course['title']); ?>" 
                             class="course-image <?php echo empty($course['image_url']) ? 'missing' : ''; ?>"
                             onerror="this.classList.add('missing'); this.src='data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';">
                        <div class="course-content">
                            <div class="course-title">
                                <?php echo htmlspecialchars($course['title']); ?>
                            </div>
                            <div class="course-author">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <?php echo htmlspecialchars($course['teacher_firstname'] . ' ' . $course['teacher_lastname']); ?>
                            </div>
                            <div class="enrollment-date">
                                <i class="fas fa-clock"></i>
                                Inscrit le <?php echo date('d/m/Y', strtotime($course['enrollment_date'])); ?>
                            </div>
                            <div class="course-actions">
                                <a href="course-details.php?id=<?php echo $course['id']; ?>" class="continue-btn">
                                    Continuer <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 