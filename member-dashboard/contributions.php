<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contributions - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Contributions specific styles */
        .contributions-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .contribution-filters {
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
        
        .contributions-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .contributions-table th {
            background: rgba(74, 111, 165, 0.1);
            text-align: left;
            padding: 12px 15px;
        }
        
        .contributions-table td {
            padding: 12px 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .contributions-table tr:hover td {
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
        
        .contribution-type {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .tithe {
            background: rgba(79, 195, 161, 0.2);
            color: #2e8b6d;
        }
        
        .offering {
            background: rgba(74, 111, 165, 0.2);
            color: var(--primary-color);
        }
        
        .donation {
            background: rgba(255, 193, 7, 0.2);
            color: #b28704;
        }
        
        .contribution-actions {
            display: flex;
            gap: 5px;
        }
        
        .contribution-actions .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .summary-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.2);
        }
        
        .summary-card h3 {
            margin: 0 0 10px;
            font-size: 1rem;
            color: #666;
        }
        
        .summary-card p {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
        }
        
        .summary-card.totals p {
            color: var(--primary-color);
        }
        
        .summary-card.tithes p {
            color: var(--accent-color);
        }
        
        .summary-card.offerings p {
            color: #4a6fa5;
        }
        
        .summary-card.donations p {
            color: #ffa500;
        }
        
        /* Contribution modal styles */
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
            max-width: 600px;
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
        
        .member-search {
            position: relative;
        }
        
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: white;
            border: 1px solid #ddd;
            border-radius: 0 0 5px 5px;
            z-index: 10;
            display: none;
        }
        
        .search-result-item {
            padding: 10px 15px;
            cursor: pointer;
        }
        
        .search-result-item:hover {
            background: #f5f5f5;
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
            <input type="text" placeholder="Search contributions...">
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
                    <a href="ministries" class="nav-link">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="nav-text">Ministries</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="contributions" class="nav-link active">
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
            <div class="contributions-header">
                <h1 class="page-title">Contributions Management</h1>
                <button class="btn btn-accent" id="new-contribution-btn"><i class="fas fa-plus"></i> Record Contribution</button>
            </div>
            
            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card totals">
                    <h3>Total Contributions</h3>
                    <p>3,450,000 UGX</p>
                </div>
                <div class="summary-card tithes">
                    <h3>Total Tithes</h3>
                    <p>1,250,000 UGX</p>
                </div>
                <div class="summary-card offerings">
                    <h3>Total Offerings</h3>
                    <p>750,000 UGX</p>
                </div>
                <div class="summary-card donations">
                    <h3>Total Donations</h3>
                    <p>450,000 UGX</p>
                </div>
            </div>
            
            <div class="glass-card">
                <div class="contribution-filters">
                    <div class="filter-group">
                        <label>Date Range</label>
                        <select class="form-control">
                            <option>This Month</option>
                            <option>Last Month</option>
                            <option>Last 3 Months</option>
                            <option>This Year</option>
                            <option>Custom Range</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Contribution Type</label>
                        <select class="form-control">
                            <option>All Types</option>
                            <option>Tithe</option>
                            <option>Offering</option>
                            <option>Donation</option>
                            <option>Building Fund</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Payment Method</label>
                        <select class="form-control">
                            <option>All Methods</option>
                            <option>Cash</option>
                            <option>Mobile Money</option>
                            <option>Bank Transfer</option>
                            <option>Check</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Member</label>
                        <input type="text" class="form-control" placeholder="Search member...">
                    </div>
                </div>
                
                <table class="contributions-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Method</th>
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
                                        <div style="font-size: 0.8rem; color: #666;">Member since 2018</div>
                                    </div>
                                </div>
                            </td>
                            <td>Today, 10:15 AM</td>
                            <td><span class="contribution-type tithe">Tithe</span></td>
                            <td>50,000 UGX</td>
                            <td>Mobile Money</td>
                            <td>
                                <div class="contribution-actions">
                                    <button class="btn btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm"><i class="fas fa-trash"></i></button>
                                    <button class="btn btn-sm btn-accent"><i class="fas fa-receipt"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar" style="background-image: url('../public/images/user2.jpeg');"></div>
                                    <div>
                                        <strong>Sarah Nalwoga</strong>
                                        <div style="font-size: 0.8rem; color: #666;">Member since 2019</div>
                                    </div>
                                </div>
                            </td>
                            <td>Yesterday, 2:30 PM</td>
                            <td><span class="contribution-type offering">Offering</span></td>
                            <td>20,000 UGX</td>
                            <td>Cash</td>
                            <td>
                                <div class="contribution-actions">
                                    <button class="btn btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm"><i class="fas fa-trash"></i></button>
                                    <button class="btn btn-sm btn-accent"><i class="fas fa-receipt"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar" style="background-image: url('../public/images/user3.jpeg');"></div>
                                    <div>
                                        <strong>David Opio</strong>
                                        <div style="font-size: 0.8rem; color: #666;">Member since 2020</div>
                                    </div>
                                </div>
                            </td>
                            <td>Mar 15, 2025</td>
                            <td><span class="contribution-type donation">Donation</span></td>
                            <td>100,000 UGX</td>
                            <td>Bank Transfer</td>
                            <td>
                                <div class="contribution-actions">
                                    <button class="btn btn-sm"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm"><i class="fas fa-trash"></i></button>
                                    <button class="btn btn-sm btn-accent"><i class="fas fa-receipt"></i></button>
                                </div>
                            </td>
                        </tr>
                        <!-- More contributions would be listed here -->
                    </tbody>
                </table>
                
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <div>Showing 1-10 of 125 contributions</div>
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
    
    <!-- Contribution Modal -->
    <div class="modal" id="contribution-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Record New Contribution</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="contribution-form">
                    <div class="form-row">
                        <div class="form-group member-search">
                            <label>Member</label>
                            <input type="text" class="form-control" placeholder="Search member..." id="member-search">
                            <div class="search-results" id="search-results">
                                <!-- Search results will be populated by JavaScript -->
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Contribution Type</label>
                            <select class="form-control" required>
                                <option value="">Select type</option>
                                <option value="tithe">Tithe</option>
                                <option value="offering">Offering</option>
                                <option value="donation">Donation</option>
                                <option value="building">Building Fund</option>
                                <option value="mission">Mission Fund</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount (UGX)</label>
                            <input type="number" class="form-control" placeholder="Enter amount" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select class="form-control" required>
                                <option value="">Select method</option>
                                <option value="cash">Cash</option>
                                <option value="mobile">Mobile Money</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="check">Check</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Reference/Receipt No.</label>
                            <input type="text" class="form-control" placeholder="Optional">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Notes</label>
                        <textarea class="form-control" rows="3" placeholder="Any additional notes"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" id="cancel-contribution">Cancel</button>
                <button class="btn btn-accent" id="save-contribution">Save Contribution</button>
            </div>
        </div>
    </div>
    
    <script src="js/scripts.js"></script>
    <script>
        // Contributions specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const modal = document.getElementById('contribution-modal');
            const newContributionBtn = document.getElementById('new-contribution-btn');
            const closeModal = document.querySelector('.close-modal');
            const cancelContribution = document.getElementById('cancel-contribution');
            
            function openModal() {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
            
            function closeModalFunc() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            newContributionBtn.addEventListener('click', openModal);
            closeModal.addEventListener('click', closeModalFunc);
            cancelContribution.addEventListener('click', closeModalFunc);
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModalFunc();
                }
            });
            
            // Member search functionality
            const memberSearch = document.getElementById('member-search');
            const searchResults = document.getElementById('search-results');
            
            // Sample member data - in a real app, this would come from an API
            const members = [
                { id: 1, name: "James Okello", avatar: "images/user1.jpeg", joinDate: "2018" },
                { id: 2, name: "Sarah Nalwoga", avatar: "images/user2.jpeg", joinDate: "2019" },
                { id: 3, name: "David Opio", avatar: "images/user3.jpeg", joinDate: "2020" },
                { id: 4, name: "Mary Akello", avatar: "images/user4.jpeg", joinDate: "2021" },
                { id: 5, name: "John Okot", avatar: "images/user5.jpeg", joinDate: "2022" }
            ];
            
            memberSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                
                if (searchTerm.length > 1) {
                    const filteredMembers = members.filter(member => 
                        member.name.toLowerCase().includes(searchTerm)
                    );
                    
                    displaySearchResults(filteredMembers);
                } else {
                    searchResults.style.display = 'none';
                }
            });
            
            function displaySearchResults(results) {
                searchResults.innerHTML = '';
                
                if (results.length > 0) {
                    results.forEach(member => {
                        const item = document.createElement('div');
                        item.className = 'search-result-item';
                        item.innerHTML = `
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div class="member-avatar" style="background-image: url('${member.avatar}'); width: 30px; height: 30px;"></div>
                                <div>
                                    <div>${member.name}</div>
                                    <small style="color: #666;">Member since ${member.joinDate}</small>
                                </div>
                            </div>
                        `;
                        
                        item.addEventListener('click', function() {
                            memberSearch.value = member.name;
                            searchResults.style.display = 'none';
                            // In a real app, you would store the member ID for form submission
                        });
                        
                        searchResults.appendChild(item);
                    });
                    
                    searchResults.style.display = 'block';
                } else {
                    const noResults = document.createElement('div');
                    noResults.className = 'search-result-item';
                    noResults.textContent = 'No members found';
                    searchResults.appendChild(noResults);
                    searchResults.style.display = 'block';
                }
            }
            
            // Close search results when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target !== memberSearch) {
                    searchResults.style.display = 'none';
                }
            });
            
            // Save contribution
            document.getElementById('save-contribution').addEventListener('click', function() {
                // In a real app, you would validate and save the form data
                const form = document.getElementById('contribution-form');
                if (form.checkValidity()) {
                    closeModalFunc();
                    alert('Contribution recorded successfully!');
                    // In a real app, you would refresh the contributions list
                } else {
                    form.reportValidity();
                }
            });
            
            // Delete contribution
            document.querySelectorAll('.contribution-actions .btn:nth-child(2)').forEach(btn => {
                btn.addEventListener('click', function() {
                    if (confirm('Are you sure you want to delete this contribution?')) {
                        // In a real app, you would delete the record
                        const row = this.closest('tr');
                        row.style.opacity = '0.5';
                        setTimeout(() => {
                            row.remove();
                            alert('Contribution deleted successfully!');
                        }, 300);
                    }
                });
            });
        });
    </script>
</body>
</html>