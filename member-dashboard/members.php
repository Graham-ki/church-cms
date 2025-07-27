<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Members - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Members specific styles */
        .members-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .members-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
        }
        
        .filter-group label {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .members-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .members-table th {
            background: rgba(74, 111, 165, 0.1);
            text-align: left;
            padding: 12px 15px;
        }
        
        .members-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .members-table tr:hover td {
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
        
        .member-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .active-member {
            background: rgba(79, 195, 161, 0.2);
            color: #2e8b6d;
        }
        
        .inactive-member {
            background: rgba(255, 99, 71, 0.2);
            color: #cc4a37;
        }
        
        .member-actions {
            display: flex;
            gap: 5px;
        }
        
        .member-actions .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .stat-card h3 {
            margin: 0 0 10px;
            font-size: 1rem;
            color: #666;
        }
        
        .stat-card p {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .stat-card.total p {
            color: var(--primary-color);
        }
        
        .stat-card.active p {
            color: var(--accent-color);
        }
        
        .stat-card.new p {
            color: #4a6fa5;
        }
        
        .stat-card.families p {
            color: #ffa500;
        }
        
        /* Member modal styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background: white;
            border-radius: 10px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #eee;
            text-align: right;
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: #666;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
        }
        
        .member-tabs {
            display: flex;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        
        .member-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        
        .member-tab.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .member-tab:hover:not(.active) {
            border-bottom-color: rgba(74, 111, 165, 0.3);
        }
        
        .tab-content {
            display: none;
        }
        
        .tab-content.active {
            display: block;
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
            <input type="text" placeholder="Search members...">
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
                    <a href="events.html" class="nav-link">
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
                    <a href="members" class="nav-link active">
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
            <div class="members-header">
                <h1 class="page-title">Members Management</h1>
                <button class="btn btn-accent" id="new-member-btn"><i class="fas fa-plus"></i> Add Member</button>
            </div>
            
            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card total">
                    <h3>Total Members</h3>
                    <p>428</p>
                </div>
                <div class="stat-card active">
                    <h3>Active Members</h3>
                    <p>387</p>
                </div>
                <div class="stat-card new">
                    <h3>New This Year</h3>
                    <p>42</p>
                </div>
                <div class="stat-card families">
                    <h3>Family Units</h3>
                    <p>135</p>
                </div>
            </div>
            
            <div class="glass-card">
                <div class="members-filters">
                    <div class="filter-group">
                        <label>Member Status</label>
                        <select class="form-control">
                            <option>All Members</option>
                            <option>Active</option>
                            <option>Inactive</option>
                            <option>Visitors</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Ministry</label>
                        <select class="form-control">
                            <option>All Ministries</option>
                            <option>Men's Fellowship</option>
                            <option>Women's Ministry</option>
                            <option>Youth Group</option>
                            <option>Choir</option>
                            <option>Ushers</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Age Group</label>
                        <select class="form-control">
                            <option>All Ages</option>
                            <option>Children (0-12)</option>
                            <option>Youth (13-25)</option>
                            <option>Adults (26-59)</option>
                            <option>Seniors (60+)</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Join Date</label>
                        <select class="form-control">
                            <option>All Time</option>
                            <option>This Year</option>
                            <option>Last Year</option>
                            <option>Last 5 Years</option>
                        </select>
                    </div>
                </div>
                
                <table class="members-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Contact</th>
                            <th>Ministry</th>
                            <th>Status</th>
                            <th>Join Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar" style="background-image: url('../public/images/user1.jpeg');"></div>
                                    <div>
                                        <strong>James Okello</strong>
                                        <div style="font-size: 0.8rem; color: #666;">Member ID: MEM-001</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>james@example.com</div>
                                <div style="font-size: 0.8rem; color: #666;">+256 123 456 789</div>
                            </td>
                            <td>Men's Fellowship</td>
                            <td><span class="member-status active-member">Active</span></td>
                            <td>Jan 2018</td>
                            <td>
                                <div class="member-actions">
                                    <button class="btn btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm"><i class="fas fa-trash"></i></button>
                                    <button class="btn btn-sm btn-accent"><i class="fas fa-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar" style="background-image: url('../public/images/user2.jpeg');"></div>
                                    <div>
                                        <strong>Sarah Nalwoga</strong>
                                        <div style="font-size: 0.8rem; color: #666;">Member ID: MEM-002</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>sarah@example.com</div>
                                <div style="font-size: 0.8rem; color: #666;">+256 987 654 321</div>
                            </td>
                            <td>Women's Ministry</td>
                            <td><span class="member-status active-member">Active</span></td>
                            <td>Mar 2019</td>
                            <td>
                                <div class="member-actions">
                                    <button class="btn btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm"><i class="fas fa-trash"></i></button>
                                    <button class="btn btn-sm btn-accent"><i class="fas fa-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar" style="background-image: url('../public/images/user3.jpeg');"></div>
                                    <div>
                                        <strong>David Opio</strong>
                                        <div style="font-size: 0.8rem; color: #666;">Member ID: MEM-003</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>david@example.com</div>
                                <div style="font-size: 0.8rem; color: #666;">+256 456 789 123</div>
                            </td>
                            <td>Youth Group</td>
                            <td><span class="member-status active-member">Active</span></td>
                            <td>Jun 2020</td>
                            <td>
                                <div class="member-actions">
                                    <button class="btn btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm"><i class="fas fa-trash"></i></button>
                                    <button class="btn btn-sm btn-accent"><i class="fas fa-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar" style="background-image: url('../public/images/user4.jpeg');"></div>
                                    <div>
                                        <strong>Mary Akello</strong>
                                        <div style="font-size: 0.8rem; color: #666;">Member ID: MEM-004</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>mary@example.com</div>
                                <div style="font-size: 0.8rem; color: #666;">+256 789 123 456</div>
                            </td>
                            <td>Choir</td>
                            <td><span class="member-status inactive-member">Inactive</span></td>
                            <td>Sep 2021</td>
                            <td>
                                <div class="member-actions">
                                    <button class="btn btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm"><i class="fas fa-trash"></i></button>
                                    <button class="btn btn-sm btn-accent"><i class="fas fa-eye"></i></button>
                                </div>
                            </td>
                        </tr>
                        <!-- More members would be listed here -->
                    </tbody>
                </table>
                
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <div>Showing 1-10 of 428 members</div>
                    <div>
                        <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                        <button class="btn btn-sm btn-accent">Next</button>
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
    
    <!-- Member Modal -->
    <div class="modal" id="member-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Member</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="member-tabs">
                    <div class="member-tab active" data-tab="personal">Personal Info</div>
                    <div class="member-tab" data-tab="spiritual">Spiritual Info</div>
                    <div class="member-tab" data-tab="family">Family Info</div>
                </div>
                
                <form id="member-form">
                    <!-- Personal Info Tab -->
                    <div class="tab-content active" id="personal-tab">
                        <div class="form-row">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Date of Birth</label>
                                <input type="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control" required>
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="tel" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Address</label>
                            <textarea class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Occupation</label>
                                <input type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Marital Status</label>
                                <select class="form-control">
                                    <option value="">Select status</option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="widowed">Widowed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Spiritual Info Tab -->
                    <div class="tab-content" id="spiritual-tab">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Membership Status</label>
                                <select class="form-control" required>
                                    <option value="">Select status</option>
                                    <option value="member">Member</option>
                                    <option value="visitor">Visitor</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Join Date</label>
                                <input type="date" class="form-control" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Baptism Status</label>
                                <select class="form-control">
                                    <option value="">Select status</option>
                                    <option value="baptized">Baptized</option>
                                    <option value="unbaptized">Not Baptized</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Baptism Date</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Ministry Involvement</label>
                            <select class="form-control" multiple>
                                <option>Men's Fellowship</option>
                                <option>Women's Ministry</option>
                                <option>Youth Group</option>
                                <option>Choir</option>
                                <option>Ushers</option>
                                <option>Sunday School</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Spiritual Gifts</label>
                            <textarea class="form-control" rows="3" placeholder="List any known spiritual gifts"></textarea>
                        </div>
                    </div>
                    
                    <!-- Family Info Tab -->
                    <div class="tab-content" id="family-tab">
                        <div class="form-group">
                            <label>Family Unit</label>
                            <select class="form-control">
                                <option value="">Select family unit</option>
                                <option value="new">Create New Family</option>
                                <option value="okello">Okello Family</option>
                                <option value="nalwoga">Nalwoga Family</option>
                                <option value="opio">Opio Family</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Family Members</label>
                            <div style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
                                <p style="margin: 0; color: #666; font-style: italic;">Family members will appear here when a family unit is selected</p>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Emergency Contact</label>
                            <input type="text" class="form-control" placeholder="Name">
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label>Emergency Phone</label>
                                <input type="tel" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Relationship</label>
                                <input type="text" class="form-control" placeholder="Relationship to member">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" id="cancel-member">Cancel</button>
                <button class="btn btn-accent" id="save-member">Save Member</button>
            </div>
        </div>
    </div>
    
    <script src="js/scripts.js"></script>
    <script>
        // Members specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const modal = document.getElementById('member-modal');
            const newMemberBtn = document.getElementById('new-member-btn');
            const closeModal = document.querySelector('.close-modal');
            const cancelMember = document.getElementById('cancel-member');
            
            function openModal() {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
            
            function closeModalFunc() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            newMemberBtn.addEventListener('click', openModal);
            closeModal.addEventListener('click', closeModalFunc);
            cancelMember.addEventListener('click', closeModalFunc);
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModalFunc();
                }
            });
            
            // Tab functionality
            const tabs = document.querySelectorAll('.member-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all tab content
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.remove('active');
                    });
                    
                    // Show the selected tab content
                    const tabId = this.dataset.tab + '-tab';
                    document.getElementById(tabId).classList.add('active');
                });
            });
            
            // Save member
            document.getElementById('save-member').addEventListener('click', function() {
                // In a real app, you would validate and save the form data
                const form = document.getElementById('member-form');
                if (form.checkValidity()) {
                    closeModalFunc();
                    alert('Member saved successfully!');
                    // In a real app, you would refresh the members list
                } else {
                    form.reportValidity();
                }
            });
            
            // Delete member
            document.querySelectorAll('.member-actions .btn:nth-child(2)').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this member?')) {
                        // In a real app, you would delete the record
                        const row = this.closest('tr');
                        row.style.opacity = '0.5';
                        setTimeout(() => {
                            row.remove();
                            alert('Member deleted successfully!');
                        }, 300);
                    }
                });
            });
        });
    </script>
</body>
</html>