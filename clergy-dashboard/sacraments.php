<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sacraments - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Sacraments specific styles */
        .sacrament-tabs {
            display: flex;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        
        .sacrament-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .sacrament-tab.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .sacrament-tab:hover:not(.active) {
            border-bottom-color: rgba(74, 111, 165, 0.3);
        }
        
        .sacrament-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .sacrament-card {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .sacrament-card:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateY(-3px);
        }
        
        .sacrament-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .sacrament-title {
            font-size: 1.2rem;
            font-weight: 500;
            color: var(--primary-color);
        }
        
        .sacrament-count {
            background: var(--accent-color);
            color: white;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        
        .sacrament-details {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .sacrament-actions {
            display: flex;
            gap: 10px;
        }
        
        .sacrament-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group.full-width {
            grid-column: span 2;
        }
        
        .sacrament-record {
            display: flex;
            justify-content: space-between;
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            align-items: center;
        }
        
        .sacrament-record:hover {
            background: rgba(74, 111, 165, 0.05);
        }
        
        .record-info {
            display: flex;
            flex-direction: column;
        }
        
        .record-name {
            font-weight: 500;
        }
        
        .record-date {
            font-size: 0.9rem;
            color: #666;
        }
        
        .record-actions {
            display: flex;
            gap: 10px;
        }
        
        .calendar-icon {
            color: var(--primary-color);
            margin-right: 5px;
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
            <input type="text" placeholder="Search sacraments...">
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
                    <a href="sacraments" class="nav-link active">
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
                    <a href="communications" class="nav-link">
                        <i class="fas fa-envelope"></i>
                        <span class="nav-text">Communications</span>
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
                <h1 class="page-title">Sacraments</h1>
                <div class="actions">
                    <button class="btn btn-accent" id="add-sacrament-btn"><i class="fas fa-plus"></i> Add Record</button>
                </div>
            </div>
            
            <!-- Add Sacrament Section (Initially Hidden) -->
            <div class="glass-card" id="add-sacrament-section" style="display: none;">
                <div class="card-header">
                    <h2 class="card-title">Add Sacrament Record</h2>
                    <button class="btn btn-sm" id="cancel-add-sacrament">Cancel</button>
                </div>
                
                <form class="sacrament-form">
                    <div class="form-group">
                        <label>Sacrament Type</label>
                        <select class="form-control">
                            <option value="">Select sacrament...</option>
                            <option value="baptism">Baptism</option>
                            <option value="communion">Holy Communion</option>
                            <option value="confirmation">Confirmation</option>
                            <option value="matrimony">Matrimony</option>
                            <option value="holy-orders">Holy Orders</option>
                            <option value="reconciliation">Reconciliation</option>
                            <option value="anointing">Anointing of the Sick</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Member</label>
                        <select class="form-control">
                            <option value="">Select member...</option>
                            <option value="1">John Doe</option>
                            <option value="2">Mary Smith</option>
                            <option value="3">James Okello</option>
                            <option value="4">Sarah Johnson</option>
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Officiant</label>
                        <input type="text" class="form-control" placeholder="Name of officiating minister">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Location</label>
                        <input type="text" class="form-control" placeholder="Where the sacrament took place">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Notes</label>
                        <textarea class="form-control" rows="3" placeholder="Additional notes about the sacrament"></textarea>
                    </div>
                    
                    <div class="form-group full-width" style="text-align: right;">
                        <button type="button" class="btn"><i class="fas fa-save"></i> Save Draft</button>
                        <button type="submit" class="btn btn-accent"><i class="fas fa-check"></i> Save Record</button>
                    </div>
                </form>
            </div>
            
            <!-- Sacraments Overview -->
            <div class="glass-card">
                <div class="sacrament-tabs">
                    <div class="sacrament-tab active">Overview</div>
                    <div class="sacrament-tab">Baptism</div>
                    <div class="sacrament-tab">Communion</div>
                    <div class="sacrament-tab">Confirmation</div>
                    <div class="sacrament-tab">Matrimony</div>
                </div>
                
                <!-- Overview Content -->
                <div id="overview-content">
                    <div class="sacrament-list">
                        <!-- Baptism Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Baptism</div>
                                <div class="sacrament-count">24</div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have received the sacrament of baptism.
                            </div>
                            <div class="sacrament-actions">
                                <button class="btn btn-sm"><i class="fas fa-eye"></i> View All</button>
                                <button class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Add New</button>
                            </div>
                        </div>
                        
                        <!-- Communion Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Holy Communion</div>
                                <div class="sacrament-count">156</div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have received holy communion.
                            </div>
                            <div class="sacrament-actions">
                                <button class="btn btn-sm"><i class="fas fa-eye"></i> View All</button>
                                <button class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Add New</button>
                            </div>
                        </div>
                        
                        <!-- Confirmation Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Confirmation</div>
                                <div class="sacrament-count">18</div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have been confirmed in the faith.
                            </div>
                            <div class="sacrament-actions">
                                <button class="btn btn-sm"><i class="fas fa-eye"></i> View All</button>
                                <button class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Add New</button>
                            </div>
                        </div>
                        
                        <!-- Matrimony Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Matrimony</div>
                                <div class="sacrament-count">7</div>
                            </div>
                            <div class="sacrament-details">
                                Records of marriages performed in the church.
                            </div>
                            <div class="sacrament-actions">
                                <button class="btn btn-sm"><i class="fas fa-eye"></i> View All</button>
                                <button class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Add New</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Baptism Content (Initially Hidden) -->
                <div id="baptism-content" style="display: none;">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <!-- Record 1 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">John Doe</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> March 15, 2025</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                        
                        <!-- Record 2 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">Mary Smith</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> January 22, 2025</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                        
                        <!-- Record 3 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">James Okello</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> December 5, 2024</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div>Showing 1-10 of 24 records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
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
        // Sacraments specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle add sacrament section
            const addSacramentBtn = document.getElementById('add-sacrament-btn');
            const cancelAddSacramentBtn = document.getElementById('cancel-add-sacrament');
            const addSacramentSection = document.getElementById('add-sacrament-section');
            
            addSacramentBtn.addEventListener('click', function() {
                addSacramentSection.style.display = 'block';
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
            
            cancelAddSacramentBtn.addEventListener('click', function(e) {
                e.preventDefault();
                addSacramentSection.style.display = 'none';
            });
            
            // Toggle sacrament tabs
            const sacramentTabs = document.querySelectorAll('.sacrament-tab');
            const contentSections = {
                'overview': document.getElementById('overview-content'),
                'baptism': document.getElementById('baptism-content')
            };
            
            sacramentTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    sacramentTabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all content sections
                    Object.values(contentSections).forEach(section => {
                        section.style.display = 'none';
                    });
                    
                    // Show the appropriate content section
                    const tabText = this.textContent.trim().toLowerCase();
                    if (tabText === 'overview') {
                        contentSections.overview.style.display = 'block';
                    } else if (tabText === 'baptism') {
                        contentSections.baptism.style.display = 'block';
                    }
                    // Add more conditions for other tabs as needed
                });
            });
            
            // Initialize date picker for sacrament date
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[type="date"]').value = today;
        });
    </script>
</body>
</html>