<div class="sidebar">
    <div class="brand">
        <img src="../assets/images/logo.png" alt="Youdemy" class="logo">
        <h2>Youdemy Admin</h2>
    </div>
    <nav>
        <ul>
            <li>
                <a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="teachers.php" <?php echo basename($_SERVER['PHP_SELF']) === 'teachers.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Teachers</span>
                </a>
            </li>
            <li>
                <a href="users.php" <?php echo basename($_SERVER['PHP_SELF']) === 'users.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="courses.php" <?php echo basename($_SERVER['PHP_SELF']) === 'courses.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-book"></i>
                    <span>Courses</span>
                </a>
            </li>
            <li>
                <a href="categories.php" <?php echo basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'class="active"' : ''; ?>>
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="tags.php" class="nav-link">
                    <i class="fas fa-tags"></i>
                    <span>Gestion des Tags</span>
                </a>
            </li>
            <li>
                <a href="../logout.php">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

<!-- Mobile Header -->
<div class="mobile-header">
    <button class="menu-toggle">
        <i class="fas fa-bars"></i>
    </button>
    <div class="brand">
        <img src="../assets/images/logo.png" alt="Youdemy" class="logo">
        <h2>Youdemy Admin</h2>
    </div>
</div> 