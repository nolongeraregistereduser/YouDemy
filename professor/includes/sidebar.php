<?php
// Add this at the top of the file
$pendingCount = 0;
if (isset($teacher)) {
    $pendingCount = $teacher->getPendingEnrollmentsCount();
}
?>

<aside class="sidebar">
    <div class="brand" style="text-align: center; padding: 15px 0;">
        <h2 style="margin: 0 auto;">Youdemy</h2>
    </div>

    <div class="profile" style="display: flex; align-items: center; padding: 10px; gap: 10px; border-bottom: 1px solid #eee;">
        <span class="user-emoji" style="font-size: 2rem;">👨‍🏫</span>
        <div class="profile-info">
            <h3 style="margin: 0; font-size: 0.9rem;"><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h3>
            <p style="margin: 0; font-size: 0.8rem; color: #666;"><?php echo ucfirst($user['role']); ?></p>
        </div>
    </div>

    <nav class="nav-menu">
        <a href="index.php" class="nav-item <?php echo $page === 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-th-large"></i>
            <span>Dashboard</span>
        </a>
        <a href="my_courses.php" class="nav-item <?php echo $page === 'courses' ? 'active' : ''; ?>">
            <i class="fas fa-book"></i>
            <span>My Courses</span>
        </a>
        <a href="add_course.php" class="nav-item">
            <i class="fas fa-plus-circle"></i>
            <span>Ajouter un cours</span>
        </a>

        <a href="analytics.php" class="nav-item <?php echo $page === 'analytics' ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i>
            <span>Analytics</span>
        </a>

        <a href="enrollments.php" class="nav-item <?php echo $page === 'enrollments' ? 'active' : ''; ?>">
            <i class="fas fa-user-check"></i>
            <span>Inscriptions</span>
            <?php if ($pendingCount > 0): ?>
                <span class="badge"><?php echo $pendingCount; ?></span>
            <?php endif; ?>
        </a>
        <a href="../logout.php" class="nav-item logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>
</aside>