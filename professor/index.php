<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Teacher Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    

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