<div class="sidebar">
    <h2>Admin Dashboard</h2>
    <nav>
        <ul>
            <li><a href="index.php" <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'class="active"' : ''; ?>>Dashboard</a></li>
            <li><a href="teachers.php" <?php echo basename($_SERVER['PHP_SELF']) === 'teachers.php' ? 'class="active"' : ''; ?>>Manage Teachers</a></li>
            <li><a href="users.php" <?php echo basename($_SERVER['PHP_SELF']) === 'users.php' ? 'class="active"' : ''; ?>>Manage Users</a></li>
            <li><a href="courses.php" <?php echo basename($_SERVER['PHP_SELF']) === 'courses.php' ? 'class="active"' : ''; ?>>Manage Courses</a></li>
            <li><a href="categories.php" <?php echo basename($_SERVER['PHP_SELF']) === 'categories.php' ? 'class="active"' : ''; ?>>Categories & Tags</a></li>
            <li><a href="statistics.php" <?php echo basename($_SERVER['PHP_SELF']) === 'statistics.php' ? 'class="active"' : ''; ?>>Statistics</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
</div> 