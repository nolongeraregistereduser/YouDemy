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

// Pagination settings
$coursesPerPage = 6;
$totalCourses = count($courses);
$totalPages = ceil($totalCourses / $coursesPerPage);
$currentPage = isset($_GET['page']) ? max(1, min((int)$_GET['page'], $totalPages)) : 1;
$startIndex = ($currentPage - 1) * $coursesPerPage;
$paginatedCourses = array_slice($courses, $startIndex, $coursesPerPage);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tous les cours | YouDemy</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styleStudent.css">
    <link rel="stylesheet" href="assets/css/course-cards.css">
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
                <?php foreach($paginatedCourses as $course): ?>
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
                                <?php echo htmlspecialchars($course['teacher_name']); ?>
                            </div>
                            
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
                            
                            <div class="course-actions">
                                <a class="enroll-btn" href="course-details.php?id=<?php echo $course['id']; ?>">
                                    En savoir plus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if($totalPages > 1): ?>
                <div class="pagination">
                    <?php if($currentPage > 1): ?>
                        <a href="?page=<?php echo $currentPage - 1; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="page-link">
                            <i class="fas fa-chevron-left"></i> Précédent
                        </a>
                    <?php endif; ?>

                    <div class="page-numbers">
                        <?php for($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="?page=<?php echo $i; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" 
                               class="page-link <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>
                    </div>

                    <?php if($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="page-link">
                            Suivant <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <?php include 'includes/footer.php'; ?>

    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 2rem 0;
            gap: 1rem;
        }

        .page-numbers {
            display: flex;
            gap: 0.5rem;
        }

        .page-link {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .page-link:hover {
            background-color: #f5f5f5;
        }

        .page-link.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .page-link i {
            font-size: 0.8rem;
        }
    </style>
</body>
</html> 