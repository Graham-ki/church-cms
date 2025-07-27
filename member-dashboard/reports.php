<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Reports specific styles */
        .report-tabs {
            display: flex;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .report-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .report-tab.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .report-tab:hover:not(.active) {
            border-bottom-color: rgba(74, 111, 165, 0.3);
        }
        
        .report-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 200px;
        }
        
        .filter-group label {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 20px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 1.2rem;
        }
        
        .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }
        
        .stat-trend {
            display: flex;
            align-items: center;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        
        .trend-up {
            color: #28a745;
        }
        
        .trend-down {
            color: #dc3545;
        }
        
        .chart-container {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .chart-title {
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .chart-actions {
            display: flex;
            gap: 10px;
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .report-table th {
            text-align: left;
            padding: 12px 15px;
            background: rgba(74, 111, 165, 0.1);
            font-weight: 500;
        }
        
        .report-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .report-table tr:hover {
            background: rgba(74, 111, 165, 0.05);
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .badge-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .badge-warning {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        
        .badge-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        
        .page-info {
            color: #666;
            font-size: 0.9rem;
        }
        
        .page-controls {
            display: flex;
            gap: 5px;
        }
        
        .export-options {
            display: flex;
            gap: 10px;
            margin-top: 20px;
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
            <input type="text" placeholder="Search reports...">
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
                    <a href="index" class="nav-link">
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
                    <a href="members" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Members</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="reports" class="nav-link active">
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
                <h1 class="page-title">Reports & Analytics</h1>
                <div class="actions">
                    <button class="btn btn-accent"><i class="fas fa-download"></i> Export All</button>
                    <button class="btn"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>
            
            <!-- Report Tabs -->
            <div class="glass-card">
                <div class="report-tabs">
                    <div class="report-tab active">Overview</div>
                    <div class="report-tab">Attendance</div>
                    <div class="report-tab">Contributions</div>
                    <div class="report-tab">Members</div>
                    <div class="report-tab">Sacraments</div>
                    <div class="report-tab">Events</div>
                </div>
                
                <!-- Filters -->
                <div class="report-filters">
                    <div class="filter-group">
                        <label>Time Period</label>
                        <select class="form-control">
                            <option>Last 7 days</option>
                            <option>Last 30 days</option>
                            <option selected>Last 90 days</option>
                            <option>This year</option>
                            <option>Last year</option>
                            <option>Custom range...</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Group By</label>
                        <select class="form-control">
                            <option>Daily</option>
                            <option selected>Weekly</option>
                            <option>Monthly</option>
                            <option>Quarterly</option>
                            <option>Yearly</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Department</label>
                        <select class="form-control">
                            <option>All Departments</option>
                            <option>Men's Fellowship</option>
                            <option>Women's Ministry</option>
                            <option>Youth Group</option>
                            <option>Children's Ministry</option>
                            <option>Choir</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Location</label>
                        <select class="form-control">
                            <option>All Locations</option>
                            <option>Main Sanctuary</option>
                            <option>Annex Building</option>
                            <option>Outdoor Chapel</option>
                        </select>
                    </div>
                    
                    <div class="filter-group" style="align-self: flex-end;">
                        <button class="btn btn-accent"><i class="fas fa-filter"></i> Apply Filters</button>
                    </div>
                </div>
                
                <!-- Overview Content -->
                <div id="overview-content">
                    <!-- Key Stats -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(40, 167, 69, 0.1); color: #28a745;">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="stat-value">1,248</div>
                            <div class="stat-label">Total Attendance</div>
                            <div class="stat-trend trend-up">
                                <i class="fas fa-arrow-up"></i> 12% from last period
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(13, 110, 253, 0.1); color: #0d6efd;">
                                <i class="fas fa-donate"></i>
                            </div>
                            <div class="stat-value">UGX 24,580</div>
                            <div class="stat-label">Total Contributions</div>
                            <div class="stat-trend trend-up">
                                <i class="fas fa-arrow-up"></i> 8% from last period
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(108, 117, 125, 0.1); color: #6c757d;">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stat-value">328</div>
                            <div class="stat-label">Active Members</div>
                            <div class="stat-trend trend-up">
                                <i class="fas fa-arrow-up"></i> 5 new members
                            </div>
                        </div>
                        
                        <div class="stat-card">
                            <div class="stat-icon" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                                <i class="fas fa-bible"></i>
                            </div>
                            <div class="stat-value">24</div>
                            <div class="stat-label">Sacraments Performed</div>
                            <div class="stat-trend trend-down">
                                <i class="fas fa-arrow-down"></i> 3 less than last period
                            </div>
                        </div>
                    </div>
                    
                    <!-- Attendance Chart -->
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">
                                <i class="fas fa-user-check" style="color: #28a745; margin-right: 8px;"></i>
                                Weekly Attendance Trend
                            </div>
                            <div class="chart-actions">
                                <button class="btn btn-sm"><i class="fas fa-download"></i></button>
                                <button class="btn btn-sm"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
                        </div>
                        <canvas id="attendanceChart" height="300"></canvas>
                    </div>
                    
                    <!-- Contributions Chart -->
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">
                                <i class="fas fa-donate" style="color: #0d6efd; margin-right: 8px;"></i>
                                Contribution Breakdown
                            </div>
                            <div class="chart-actions">
                                <button class="btn btn-sm"><i class="fas fa-download"></i></button>
                                <button class="btn btn-sm"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
                        </div>
                        <div style="display: flex; gap: 20px;">
                            <div style="flex: 2;">
                                <canvas id="contributionsChart" height="300"></canvas>
                            </div>
                            <div style="flex: 1;">
                                <canvas id="contributionPieChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Recent Activities Table -->
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">
                                <i class="fas fa-history" style="margin-right: 8px;"></i>
                                Recent Activities
                            </div>
                            <div class="chart-actions">
                                <button class="btn btn-sm"><i class="fas fa-download"></i></button>
                                <button class="btn btn-sm"><i class="fas fa-ellipsis-v"></i></button>
                            </div>
                        </div>
                        
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Activity</th>
                                    <th>Category</th>
                                    <th>Participants</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Jun 12, 2025</td>
                                    <td>Sunday Service</td>
                                    <td>Worship</td>
                                    <td>215</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <button class="btn-icon" title="View Details"><i class="far fa-eye"></i></button>
                                        <button class="btn-icon" title="Print Report"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jun 9, 2025</td>
                                    <td>Baptism Ceremony</td>
                                    <td>Sacrament</td>
                                    <td>8</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <button class="btn-icon" title="View Details"><i class="far fa-eye"></i></button>
                                        <button class="btn-icon" title="Print Report"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jun 5, 2025</td>
                                    <td>Youth Fellowship</td>
                                    <td>Event</td>
                                    <td>47</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <button class="btn-icon" title="View Details"><i class="far fa-eye"></i></button>
                                        <button class="btn-icon" title="Print Report"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jun 1, 2025</td>
                                    <td>Tithe Collection</td>
                                    <td>Contribution</td>
                                    <td>182</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <button class="btn-icon" title="View Details"><i class="far fa-eye"></i></button>
                                        <button class="btn-icon" title="Print Report"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>May 29, 2025</td>
                                    <td>Women's Prayer Meeting</td>
                                    <td>Event</td>
                                    <td>36</td>
                                    <td><span class="badge badge-success">Completed</span></td>
                                    <td>
                                        <button class="btn-icon" title="View Details"><i class="far fa-eye"></i></button>
                                        <button class="btn-icon" title="Print Report"><i class="fas fa-print"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        
                        <div class="pagination">
                            <div class="page-info">Showing 1 to 5 of 24 entries</div>
                            <div class="page-controls">
                                <button class="btn btn-sm" disabled><i class="fas fa-chevron-left"></i> Previous</button>
                                <button class="btn btn-sm btn-accent">Next <i class="fas fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Export Options -->
                <div class="export-options">
                    <button class="btn"><i class="far fa-file-pdf"></i> Export as PDF</button>
                    <button class="btn"><i class="far fa-file-excel"></i> Export as Excel</button>
                    <button class="btn"><i class="far fa-file-word"></i> Export as Word</button>
                    <button class="btn"><i class="far fa-file-image"></i> Export as Image</button>
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
        // Reports specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle report tabs
            const reportTabs = document.querySelectorAll('.report-tab');
            reportTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    reportTabs.forEach(t => t.classList.remove('active'));
                    this.classList.add('active');
                    // In a real app, you would load the appropriate report content here
                });
            });
            
            // Initialize charts
            // Attendance Chart
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceChart = new Chart(attendanceCtx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6', 'Week 7', 'Week 8', 'Week 9', 'Week 10', 'Week 11', 'Week 12'],
                    datasets: [{
                        label: 'Main Service',
                        data: [185, 192, 178, 205, 210, 198, 215, 225, 218, 230, 225, 240],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.3,
                        fill: true
                    }, {
                        label: 'Youth Service',
                        data: [45, 52, 48, 55, 60, 58, 62, 65, 68, 70, 72, 75],
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: false,
                            suggestedMin: 50
                        }
                    }
                }
            });
            
            // Contributions Bar Chart
            const contributionsCtx = document.getElementById('contributionsChart').getContext('2d');
            const contributionsChart = new Chart(contributionsCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: 'Tithes',
                        data: [3200, 3400, 3650, 3800, 4000, 4200, 4100, 4300, 4500, 4700, 4900, 5000],
                        backgroundColor: 'rgba(13, 110, 253, 0.7)'
                    }, {
                        label: 'Offerings',
                        data: [1500, 1600, 1700, 1800, 1900, 2000, 1950, 2100, 2200, 2300, 2400, 2500],
                        backgroundColor: 'rgba(40, 167, 69, 0.7)'
                    }, {
                        label: 'Donations',
                        data: [800, 900, 950, 1000, 1100, 1200, 1150, 1250, 1300, 1400, 1500, 1600],
                        backgroundColor: 'rgba(255, 193, 7, 0.7)'
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        x: {
                            stacked: false,
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Contributions Pie Chart
            const contributionPieCtx = document.getElementById('contributionPieChart').getContext('2d');
            const contributionPieChart = new Chart(contributionPieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Tithes', 'Offerings', 'Donations', 'Building Fund', 'Missions'],
                    datasets: [{
                        data: [45, 25, 15, 10, 5],
                        backgroundColor: [
                            'rgba(13, 110, 253, 0.7)',
                            'rgba(40, 167, 69, 0.7)',
                            'rgba(255, 193, 7, 0.7)',
                            'rgba(108, 117, 125, 0.7)',
                            'rgba(220, 53, 69, 0.7)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>