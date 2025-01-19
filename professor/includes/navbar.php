<header class="top-bar">
    <div class="welcome">
        <h2>Welcome back, <?php echo htmlspecialchars($user['firstname']); ?>!</h2>
        <p>Here's what's happening with your courses today.</p>
    </div>
    <div class="top-actions">
        <button class="icon-btn">
            <i class="fas fa-bell"></i>
            <span class="notification-badge">3</span>
        </button>
        <div class="user-menu">
            <span class="user-emoji">👨‍🏫</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</header>