<aside class="sidebar">
    <div class="logo">
        <h1>Youdemy</h1>
    </div>

    <div class="profile">
        <img src="<?php echo htmlspecialchars($user['avatar'] ?? 'https://via.placeholder.com/150'); ?>" alt="Profile" class="avatar">
        <div class="profile-info">
            <h3><?php echo htmlspecialchars($user['firstname'] . ' ' . $user['lastname']); ?></h3>
            <p><?php echo ucfirst($user['role']); ?></p>
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
        <a href="students.php" class="nav-item <?php echo $page === 'students' ? 'active' : ''; ?>">
            <i class="fas fa-users"></i>
            <span>Students</span>
        </a>
        <a href="analytics.php" class="nav-item <?php echo $page === 'analytics' ? 'active' : ''; ?>">
            <i class="fas fa-chart-bar"></i>
            <span>Analytics</span>
        </a>
        <a href="settings.php" class="nav-item <?php echo $page === 'settings' ? 'active' : ''; ?>">
            <i class="fas fa-cog"></i>
            <span>Settings</span>
        </a>
        <a href="logout.php" class="nav-item logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Logout</span>
        </a>
    </nav>
</aside>