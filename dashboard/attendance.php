<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Attendance specific styles */
        .attendance-controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .attendance-table th {
            background: rgba(74, 111, 165, 0.1);
            text-align: left;
            padding: 12px 15px;
        }
        
        .attendance-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .attendance-table tr:hover td {
            background: rgba(74, 111, 165, 0.05);
        }
        
        .member-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 2px solid var(--accent-color);
        }
        
        .attendance-status {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .present {
            background: rgba(79, 195, 161, 0.2);
            color: #2e8b6d;
        }
        
        .absent {
            background: rgba(255, 99, 71, 0.2);
            color: #cc4a37;
        }
        
        .attendance-actions {
            display: flex;
            gap: 5px;
        }
        
        .attendance-actions .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <!-- Header (Same as dashboard) -->
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
        <!-- Sidebar Navigation (Same as dashboard) -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <div class="nav-item">
                    <a href="index" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="attendance" class="nav-link active">
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
                    <a href="members" class="nav-link">
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
                <h1 class="page-title">Attendance Management</h1>
                <div class="actions">
                    <button class="btn btn-accent"><i class="fas fa-calendar"></i> Select Date</button>
                    <button class="btn"><i class="fas fa-download"></i> Export</button>
                </div>
            </div>
            
            <div class="glass-card">
                <div class="attendance-controls">
                    <div class="search-filter">
                        <input type="text" class="form-control" placeholder="Search members...">
                    </div>
                    <div>
                        <button class="btn btn-sm" style="margin-right: 10px;">Mark All Present</button>
                        <button class="btn btn-sm btn-accent">Save Attendance</button>
                    </div>
                </div>
                
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Ministry</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar" style="background-image: url('images/user1.jpeg');"></div>
                                    <div>
                                        <strong>James Okello</strong>
                                        <div style="font-size: 0.8rem; color: #666;">+256 123 456 789</div>
                                    </div>
                                </div>
                            </td>
                            <td>Men's Fellowship</td>
                            <td><span class="attendance-status present">Present</span></td>
                            <td>
                                <div class="attendance-actions">
                                    <button class="btn btn-sm">Absent</button>
                                    <button class="btn btn-sm btn-accent">Present</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar" style="background-image: url('images/user2.jpeg');"></div>
                                    <div>
                                        <strong>Sarah Nalwoga</strong>
                                        <div style="font-size: 0.8rem; color: #666;">+256 987 654 321</div>
                                    </div>
                                </div>
                            </td>
                            <td>Women's Ministry</td>
                            <td><span class="attendance-status absent">Absent</span></td>
                            <td>
                                <div class="attendance-actions">
                                    <button class="btn btn-sm">Absent</button>
                                    <button class="btn btn-sm btn-accent">Present</button>
                                </div>
                            </td>
                        </tr>
                        <!-- More attendance rows -->
                    </tbody>
                </table>
                
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <div>Showing 1-10 of 245 members</div>
                    <div>
                        <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                        <button class="btn btn-sm btn-accent">Next</button>
                    </div>
                </div>
            </div>
            
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">Attendance Statistics</h2>
                    <select class="form-control" style="width: auto; display: inline-block;">
                        <option>Last 7 Days</option>
                        <option>Last 30 Days</option>
                        <option>Last 3 Months</option>
                    </select>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; text-align: center;">
                    <div>
                        <h3 style="margin: 0; color: var(--primary-color);">75%</h3>
                        <p style="margin: 5px 0; color: #666;">Weekly Average</p>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: var(--accent-color);">215</h3>
                        <p style="margin: 5px 0; color: #666;">Present This Week</p>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #ff6347;">42</h3>
                        <p style="margin: 5px 0; color: #666;">Absent This Week</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
    
    <!-- Footer (Same as dashboard) -->
    <footer class="footer">
        <!-- Same footer content as dashboard.html -->
    </footer>
    
    <script src="js/scripts.js"></script>
    <script>
        // Attendance specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle attendance status
            document.querySelectorAll('.attendance-actions .btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const row = this.closest('tr');
                    const statusCell = row.querySelector('.attendance-status');
                    
                    if(this.textContent.trim() === 'Present') {
                        statusCell.textContent = 'Present';
                        statusCell.className = 'attendance-status present';
                    } else {
                        statusCell.textContent = 'Absent';
                        statusCell.className = 'attendance-status absent';
                    }
                });
            });
        });
    </script>
</body>
</html>