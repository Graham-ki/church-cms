<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Dashboard specific styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
        }
        
        .stat-icon {
            font-size: 2.5rem;
        }
        
        .stat-info {
            flex: 1;
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 5px 0;
        }
        
        .stat-change {
            font-size: 0.9rem;
        }
        
        .positive {
            color: var(--accent-color);
        }
        
        .negative {
            color: #ff6347;
        }
        
        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
        }
        
        .action-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            text-decoration: none;
            color: var(--dark-color);
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            background: rgba(74, 111, 165, 0.2);
            transform: translateY(-3px);
        }
        
        .action-icon {
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 10px;
        }
        
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        .activity-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        
        .activity-item:hover {
            background: rgba(74, 111, 165, 0.05);
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .event-card {
            display: flex;
            gap: 15px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        
        .event-date {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 8px;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="logo">
            <img src="../public/images/logo.png" alt="Church Logo">
            <div class="logo-text">
                <h1>St. Stephen C.O.U</h1>
                <p>Church Management System</p>
            </div>
        </div>
        
        <div class="header-search">
            <input type="text" placeholder="Search...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
        
        <div class="user-actions">
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
                <span class="notification-badge">3</span>
            </div>
            <div class="user-profile">
                <div class="user-avatar" style="background-image: url('../public/images/user7.png');"></div>
                <span class="user-name">Admin User</span>
                <div class="user-dropdown">
                    <a href="#"><i class="fas fa-user"></i> My Profile</a>
                    <a href="#"><i class="fas fa-cog"></i> Settings</a>
                    <a href="#" id="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <div class="main-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="index" class="nav-link active">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="attendance" class="nav-link">
                        <i class="fas fa-user-check"></i>
                        <span class="nav-text">Attendance</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="communications" class="nav-link">
                        <i class="fas fa-comments"></i>
                        <span class="nav-text">Communications</span>
                    </a>
                </div>
                 <div class="nav-item">
                    <a href="sacraments" class="nav-link">
                        <i class="fas fa-bible"></i>
                        <span class="nav-text">Sacraments</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="events" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="nav-text">Events</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="contributions" class="nav-link">
                        <i class="fas fa-donate"></i>
                        <span class="nav-text">Contributions</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="members.html" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Members</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="reports" class="nav-link">
                        <i class="fas fa-chart-bar"></i>
                        <span class="nav-text">Reports</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="settings" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                </div>
            </nav>
        </aside>
        
        <!-- Page Content -->
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Dashboard Overview</h1>
                <div class="actions">
                    <button class="btn btn-accent"><i class="fas fa-plus"></i> Quick Add</button>
                </div>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="glass-card stat-card">
                    <div class="stat-icon" style="color: var(--primary-color);">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Total Members</h3>
                        <p class="stat-value">428</p>
                        <p class="stat-change positive"><i class="fas fa-arrow-up"></i> 12 this month</p>
                    </div>
                </div>
                
                <div class="glass-card stat-card">
                    <div class="stat-icon" style="color: var(--accent-color);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Last Attendance</h3>
                        <p class="stat-value">287</p>
                        <p class="stat-change negative"><i class="fas fa-arrow-down"></i> 5% from last</p>
                    </div>
                </div>
                
                <div class="glass-card stat-card">
                    <div class="stat-icon" style="color: #4fc3a1;">
                        <i class="fas fa-donate"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Monthly Contributions</h3>
                        <p class="stat-value">3.45M UGX</p>
                        <p class="stat-change positive"><i class="fas fa-arrow-up"></i> 8% increase</p>
                    </div>
                </div>
                
                <div class="glass-card stat-card">
                    <div class="stat-icon" style="color: #ffa500;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Upcoming Events</h3>
                        <p class="stat-value">5</p>
                        <a href="events.html" class="stat-link">View all</a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="glass-card quick-actions-card">
                <div class="card-header">
                    <h2 class="card-title">Quick Actions</h2>
                </div>
                <div class="actions-grid">
                    <a href="attendance.html" class="action-btn">
                        <div class="action-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <span>Take Attendance</span>
                    </a>
                    <a href="contributions.html" class="action-btn">
                        <div class="action-icon">
                            <i class="fas fa-donate"></i>
                        </div>
                        <span>Record Offering</span>
                    </a>
                    <a href="communications.html" class="action-btn">
                        <div class="action-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <span>Send Announcement</span>
                    </a>
                    <a href="events.html" class="action-btn">
                        <div class="action-icon">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <span>Add Event</span>
                    </a>
                    <a href="members.html" class="action-btn">
                        <div class="action-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <span>Add Member</span>
                    </a>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">Recent Activity</h2>
                    <a href="#" class="btn btn-sm">View All</a>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-avatar" style="background-image: url('../public/images/user1.jpeg');"></div>
                        <div class="activity-details">
                            <p><strong>James Okello</strong> recorded a tithe contribution of <strong>50,000 UGX</strong></p>
                            <small>Today, 10:15 AM</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background-image: url('../public/images/user2.jpeg');"></div>
                        <div class="activity-details">
                            <p><strong>Sarah Nalwoga</strong> was marked <strong>present</strong> for Sunday service</p>
                            <small>Yesterday, 2:30 PM</small>
                        </div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-avatar" style="background-image: url('../public/images/user3.jpeg');"></div>
                        <div class="activity-details">
                            <p><strong>David Opio</strong> registered for <strong>Youth Camp</strong> event</p>
                            <small>Yesterday, 11:45 AM</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Upcoming Events -->
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">Upcoming Events</h2>
                    <a href="events.html" class="btn btn-sm">View All</a>
                </div>
                <div class="events-grid">
                    <div class="event-card">
                        <div class="event-date" style="background: var(--primary-color);">
                            <span>15</span>
                            <span>JUN</span>
                        </div>
                        <div class="event-info">
                            <h3>Annual Church Conference</h3>
                            <p><i class="fas fa-clock"></i> 9:00 AM - 4:00 PM</p>
                            <p><i class="fas fa-map-marker-alt"></i> Church Main Hall</p>
                            <div class="event-stats">
                                <span><i class="fas fa-users"></i> 120 attending</span>
                                <a href="#" class="btn btn-sm">Details</a>
                            </div>
                        </div>
                    </div>
                    <div class="event-card">
                        <div class="event-date" style="background: var(--accent-color);">
                            <span>22</span>
                            <span>JUN</span>
                        </div>
                        <div class="event-info">
                            <h3>Youth Camp</h3>
                            <p><i class="fas fa-clock"></i> All day</p>
                            <p><i class="fas fa-map-marker-alt"></i> Lake View Resort</p>
                            <div class="event-stats">
                                <span><i class="fas fa-users"></i> 45 attending</span>
                                <a href="#" class="btn btn-sm">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="footer-links">
            <a href="#">About</a>
            <a href="#">Help</a>
            <a href="#">Privacy</a>
            <a href="#">Terms</a>
            <a href="#">Contact</a>
        </div>
        <div class="copyright">
            &copy; 2025 St. Stephen C.O.U Church Management System. All rights reserved.
        </div>
    </footer>
    
    <script src="js/scripts.js"></script>
    <script>
        // Dashboard specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Logout button functionality
            document.getElementById('logout-btn').addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('Are you sure you want to logout?')) {
                    // Redirect to login page
                    window.location.href = 'login.html';
                }
            });
            
            // Other dashboard specific scripts
        });
    </script>
</body>
</html>