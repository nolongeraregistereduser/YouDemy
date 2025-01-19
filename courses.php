<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/Class/Course.php';
require_once __DIR__ . '/Class/Category.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Initialize Course and Category objects
$courseObj = new Course($db);
$categoryObj = new Category($db);

// Get all categories for the filter
$categories = $categoryObj->getAll();

// Get filter parameters and ensure categoryId is an integer or null
$categoryId = isset($_GET['category']) && !empty($_GET['category']) ? 
    (int)$_GET['category'] : null;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Get courses based on filters
$courses = $courseObj->getAllPublished($categoryId, $searchQuery);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tous les cours | YouDemy</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styleStudent.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="courses-header">
        <div class="courses-header-content">
            <h1>Explorez nos cours</h1>
            <p>Découvrez des milliers de cours dispensés par des experts</p>
            
            <!-- Search and Filter Section -->
            <div class="courses-filters">
                <form action="" method="GET" class="search-filter-form">
                    <div class="search-box">
                        <input type="text" 
                               name="search" 
                               placeholder="Rechercher un cours..." 
                               value="<?php echo htmlspecialchars($searchQuery); ?>">
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    
                    <select name="category" class="category-filter" onchange="this.form.submit()">
                        <option value="">Toutes les catégories</option>
                        <?php foreach($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                    <?php echo ($categoryId == $category['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <div class="courses-container">
        <?php if(empty($courses)): ?>
            <div class="no-courses">
                <i class="fas fa-book-open"></i>
                <h2>Aucun cours trouvé</h2>
                <p>Essayez de modifier vos filtres ou effectuez une nouvelle recherche.</p>
            </div>
        <?php else: ?>
            <div class="course-list">
                <?php foreach($courses as $course): ?>
                    <div class="course-card">
                        <img src="<?php echo htmlspecialchars($course['image_url']); ?>" 
                             alt="<?php echo htmlspecialchars($course['title']); ?>"/>
                        <div class="course-content">
                            <div class="course-title">
                                <?php echo htmlspecialchars($course['title']); ?>
                            </div>
                            <div class="course-author">
                                <i class="fas fa-chalkboard-teacher"></i>
                                <?php echo htmlspecialchars($course['teacher_name']); ?>
                            </div>
                            
                            <!-- Tags Section -->
                            <?php if(!empty($course['tags'])): ?>
                                <div class="course-tags">
                                    <?php foreach(explode(',', $course['tags']) as $tag): ?>
                                        <span class="tag"><?php echo htmlspecialchars(trim($tag)); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <div class="course-stats">
                                <span class="enrollment-count">
                                    <i class="fas fa-users"></i>
                                    <?php echo number_format($course['enrollment_count']); ?> étudiants
                                </span>
                            </div>
                            <?php if($course['enrollment_count'] > 1000): ?>
                                <div class="course-badge">
                                    Meilleure vente
                                </div>
                            <?php endif; ?>
                            <a class="enroll-btn" href="course-details.php?id=<?php echo $course['id']; ?>">
                                En savoir plus
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 