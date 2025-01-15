<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Teacher Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>/* Base Styles */
:root {
    --primary: #4f46e5;
    --primary-light: #6366f1;
    --success: #22c55e;
    --danger: #ef4444;
    --warning: #f59e0b;
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background-color: var(--gray-50);
    color: var(--gray-800);
    line-height: 1.5;
}

/* Layout */
.dashboard {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.sidebar {
    width: 280px;
    background-color: white;
    border-right: 1px solid var(--gray-200);
    padding: 2rem;
    position: fixed;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

.logo h1 {
    color: var(--primary);
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
}

.profile {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--gray-200);
    margin-bottom: 1.5rem;
}

.avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
}

.profile-info h3 {
    font-size: 0.9rem;
    font-weight: 600;
}

.profile-info p {
    font-size: 0.8rem;
    color: var(--gray-500);
}

/* Navigation */
.nav-menu {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    flex-grow: 1;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1rem;
    color: var(--gray-600);
    text-decoration: none;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
}

.nav-item:hover {
    background-color: var(--gray-50);
    color: var(--primary);
}

.nav-item.active {
    background-color: var(--primary);
    color: white;
}

.nav-item i {
    font-size: 1.1rem;
}

.logout {
    margin-top: auto;
    color: var(--gray-500);
}

/* Main Content */
.main-content {
    margin-left: 280px;
    padding: 2rem;
    flex-grow: 1;
}

/* Top Bar */
.top-bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.welcome h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
}

.welcome p {
    color: var(--gray-500);
    margin-top: 0.25rem;
}

.top-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.icon-btn {
    background: none;
    border: none;
    padding: 0.5rem;
    cursor: pointer;
    position: relative;
    color: var(--gray-600);
    border-radius: 50%;
    transition: all 0.2s ease;
}

.icon-btn:hover {
    background-color: var(--gray-100);
    color: var(--primary);
}

.notification-badge {
    position: absolute;
    top: 0;
    right: 0;
    background-color: var(--danger);
    color: white;
    font-size: 0.7rem;
    padding: 0.1rem 0.4rem;
    border-radius: 1rem;
}

.user-menu {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.avatar-small {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background-color: white;
    padding: 1.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-card h4 {
    color: var(--gray-500);
    font-size: 0.875rem;
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.stat-value {
    display: flex;
    align-items: baseline;
    gap: 0.75rem;
}

.stat-value span:first-child {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
}

.trend {
    font-size: 0.875rem;
    font-weight: 500;
}

.trend.positive {
    color: var(--success);
}

/* Course Management */
.courses-section {
    background-color: white;
    border-radius: 0.75rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
}

.btn-primary {
    background-color: var(--primary);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-primary:hover {
    background-color: var(--primary-light);
}

.search-filters {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.search-bar {
    flex-grow: 1;
    position: relative;
}

.search-bar i {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
}

.search-bar input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid var(--gray-200);
    border-radius: 0.5rem;
    font-size: 0.875rem;
}

.search-bar input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.btn-secondary {
    padding: 0.75rem 1.5rem;
    border: 1px solid var(--gray-200);
    background-color: white;
    color: var(--gray-700);
    border-radius: 0.5rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-secondary:hover {
    border-color: var(--gray-300);
    background-color: var(--gray-50);
}

/* Table Styles */
.courses-table {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    text-align: left;
    padding: 1rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--gray-600);
    border-bottom: 1px solid var(--gray-200);
}

td {
    padding: 1rem;
    border-bottom: 1px solid var(--gray-200);
    color: var(--gray-700);
}

.course-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.course-info img {
    width: 48px;
    height: 48px;
    border-radius: 0.5rem;
    object-fit: cover;
}

.course-info span {
    font-weight: 500;
}

.status {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.status.published {
    background-color: #dcfce7;
    color: #166534;
}

.status.draft {
    background-color: #fef3c7;
    color: #92400e;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
}

/* Toast Notifications */
.toast {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    background-color: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    animation: slideIn 0.3s ease;
}

.toast.success {
    border-left: 4px solid var(--success);
}

.toast.success i {
    color: var(--success);
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    align-items: center;
    justify-content: center;
}

.modal.active {
    display: flex;
}

.modal-content {
    background-color: white;
    padding: 2rem;
    border-radius: 0.75rem;
    max-width: 400px;
    width: 90%;
}

.modal-content h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
}

.modal-content p {
    color: var(--gray-600);
    margin-bottom: 1.5rem;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.btn-danger {
    background-color: var(--danger);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease;
}

.btn-danger:hover {
    background-color: #dc2626;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .sidebar {
        width: 80px;
        padding: 1rem;
    }

    .logo h1,
    .profile-info,
    .nav-item span {
        display: none;
    }

    .main-content {
        margin-left: 80px;
    }

    .nav-item {
        justify-content: center;
        padding: 0.75rem;
    }

    .nav-item i {
        margin: 0;
    }
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .courses-table {
        font-size: 0.875rem;
    }

    .action-buttons {
        flex-direction: column;
    }
}

@media (max-width: 640px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }

    .search-filters {
        flex-direction: column;
    }

    .top-bar {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }

    .courses-table {
        display: block;
    }

    td, th {
        padding: 0.75rem 0.5rem;
    }

    .course-info img {
        display: none;
    }
}

/*
SAMARA
    


*/
</style>
</head>
<body>
    <div class="dashboard">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="logo">
                <h1>Youdemy</h1>
            </div>

            <div class="profile">
                <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=crop&w=256&q=80" alt="Sarah Anderson" class="avatar">
                <div class="profile-info">
                    <h3>Sarah Anderson</h3>
                    <p>Senior Instructor</p>
                </div>
            </div>

            <nav class="nav-menu">
                <a href="#" class="nav-item active">
                    <i class="fas fa-th-large"></i>
                    Dashboard
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-book"></i>
                    My Courses
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-plus-circle"></i>
                    Create Course
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-users"></i>
                    Students
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-chart-bar"></i>
                    Analytics
                </a>
                <a href="#" class="nav-item">
                    <i class="fas fa-cog"></i>
                    Settings
                </a>
                <a href="#" class="nav-item logout">
                    <i class="fas fa-sign-out-alt"></i>
                    Logout
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <header class="top-bar">
                <div class="welcome">
                    <h2>Welcome back, Sarah!</h2>
                    <p>Here's what's happening with your courses today.</p>
                </div>
                <div class="top-actions">
                    <button class="icon-btn">
                        <i class="fas fa-bell"></i>
                        <span class="notification-badge">3</span>
                    </button>
                    <div class="user-menu">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&auto=format&fit=crop&w=256&q=80" alt="Sarah Anderson" class="avatar-small">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
            </header>

            <!-- Statistics Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h4>Total Students</h4>
                    <div class="stat-value">
                        <span>2,845</span>
                        <span class="trend positive">+12%</span>
                    </div>
                </div>
                <div class="stat-card">
                    <h4>Active Courses</h4>
                    <div class="stat-value">
                        <span>24</span>
                        <span class="trend positive">+3</span>
                    </div>
                </div>
                <div class="stat-card">
                    <h4>Total Revenue</h4>
                    <div class="stat-value">
                        <span>$45,290</span>
                        <span class="trend positive">+8%</span>
                    </div>
                </div>
                <div class="stat-card">
                    <h4>Average Rating</h4>
                    <div class="stat-value">
                        <span>4.8</span>
                        <span class="trend positive">+0.2</span>
                    </div>
                </div>
            </div>

            <!-- Course Management -->
            <section class="courses-section">
                <div class="section-header">
                    <h3>Your Courses</h3>
                    <button class="btn-primary">
                        <i class="fas fa-plus"></i>
                        New Course
                    </button>
                </div>

                <div class="search-filters">
                    <div class="search-bar">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search courses...">
                    </div>
                    <button class="btn-secondary">
                        <i class="fas fa-filter"></i>
                        Filters
                    </button>
                </div>

                <div class="courses-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Course</th>
                                <th>Status</th>
                                <th>Students</th>
                                <th>Revenue</th>
                                <th>Last Updated</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="course-info">
                                        <img src="https://images.unsplash.com/photo-1516116216624-53e697fedbea?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80" alt="Course thumbnail">
                                        <span>Advanced Web Development</span>
                                    </div>
                                </td>
                                <td><span class="status published">Published</span></td>
                                <td>456</td>
                                <td>$12,400</td>
                                <td>2024-02-28</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="icon-btn" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="icon-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="course-info">
                                        <img src="https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?ixlib=rb-1.2.1&auto=format&fit=crop&w=400&q=80" alt="Course thumbnail">
                                        <span>UX Design Fundamentals</span>
                                    </div>
                                </td>
                                <td><span class="status draft">Draft</span></td>
                                <td>0</td>
                                <td>$0</td>
                                <td>2024-03-01</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="icon-btn" title="Preview">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="icon-btn" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Toast Notifications -->
    <div class="toast success">
        <i class="fas fa-check-circle"></i>
        <span>Course successfully published!</span>
    </div>

    <!-- Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-content">
            <h3>Delete Course</h3>
            <p>Are you sure you want to delete this course? This action cannot be undone.</p>
            <div class="modal-actions">
                <button class="btn-secondary">Cancel</button>
                <button class="btn-danger">Delete</button>
            </div>
        </div>
    </div>
</body>
</html>