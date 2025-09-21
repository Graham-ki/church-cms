<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministries - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Sacraments specific styles */
        .sacrament-tabs {
            display: flex;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow-x: auto;
        }
        
        .sacrament-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            white-space: nowrap;
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
    <?php include_once 'header.php'; ?>
    <!-- Main Content -->
    <div class="main-container">
        <!-- Sidebar Navigation -->
        <?php include_once 'sidebar.php'; ?>
        
        <!-- Page Content -->
        <main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Ministries</h1>
                <div class="actions">
                    <button class="btn btn-accent" id="add-sacrament-btn"><i class="fas fa-plus"></i> Add Record</button>
                </div>
            </div>
            
            <!-- Add Sacrament Section (Initially Hidden) -->
            <div class="glass-card" id="add-sacrament-section" style="display: none;">
                <div class="card-header">
                    <h2 class="card-title">Add New Record</h2>
                    <button class="btn btn-sm" id="cancel-add-sacrament">Cancel</button>
                </div>
                
                <form class="sacrament-form" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Ministry Type</label>
                        <select class="form-control">
                            <option value="">Select ministry...</option>
                            <option value="youth-ministry">Youth Ministry</option>
                            <option value="children-ministry">Children Ministry</option>
                            <option value="confirmation">Women's Ministry</option>
                            <option value="matrimony">Men's Ministry</option>
                            <option value="holy-orders">Worship Ministry</option>
                            <option value="reconciliation">Outreach Ministry</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Day</label>
                        <input type="text" class="form-control" placeholder="Day of the week">
                    </div>
                    <div class="form-group">
                        <label>Time</label>
                        <input type="text" class="form-control" placeholder="Time of the ministry">
                    </div>
                    <div class="form-group">
                        <label>Age Group</label>
                        <input type="text" class="form-control" placeholder="Age group for the ministry">
                    </div>
                    <div class="form-group full-width">
                        <label>Leader</label>
                        <input type="text" class="form-control" placeholder="Name of ministry leader">
                    </div>
                    <div class="form-group full-width">
                        <label>Description</label>
                        <textarea class="form-control" rows="3" placeholder="Additional notes about the ministry"></textarea>
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
                    <div class="sacrament-tab active" data-tab="overview">Overview</div>
                    <div class="sacrament-tab" data-tab="children">Children</div>
                    <div class="sacrament-tab" data-tab="youth">Youth</div>
                    <div class="sacrament-tab" data-tab="women">Women</div>
                    <div class="sacrament-tab" data-tab="men">Men</div>
                    <div class="sacrament-tab" data-tab="worship">Worship</div>
                    <div class="sacrament-tab" data-tab="outreach">Outreach</div>
                </div>

                <!-- Overview Content -->
                <div id="overview-content" class="tab-content active">
                    <div class="sacrament-list">
                        <!-- Baptism Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Children</div>
                                <div class="sacrament-count">24</div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have participated in children's ministries.
                            </div>
                            <div class="sacrament-actions">
                                <button class="btn btn-sm"><i class="fas fa-eye"></i> View All</button>
                                <button class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Add New</button>
                            </div>
                        </div>
                        
                        <!-- Communion Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Youth</div>
                                <div class="sacrament-count">156</div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have participated in youth ministries.
                            </div>
                            <div class="sacrament-actions">
                                <button class="btn btn-sm"><i class="fas fa-eye"></i> View All</button>
                                <button class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Add New</button>
                            </div>
                        </div>
                        
                        <!-- Confirmation Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Women's Ministries</div>
                                <div class="sacrament-count">18</div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have participated in women's ministries.
                            </div>
                            <div class="sacrament-actions">
                                <button class="btn btn-sm"><i class="fas fa-eye"></i> View All</button>
                                <button class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Add New</button>
                            </div>
                        </div>
                        
                        <!-- Matrimony Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Men's Ministries</div>
                                <div class="sacrament-count">7</div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have participated in men's ministries.
                            </div>
                            <div class="sacrament-actions">
                                <button class="btn btn-sm"><i class="fas fa-eye"></i> View All</button>
                                <button class="btn btn-sm btn-accent"><i class="fas fa-plus"></i> Add New</button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Children Content -->
                <div id="children-content" class="tab-content">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <!-- Record 1 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">Sarah Johnson</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> March 15, 2025</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Details"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                        
                        <!-- Record 2 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">Michael Brown</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> January 22, 2025</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Details"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                        
                        <!-- Record 3 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">Emily Wilson</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> December 5, 2024</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Details"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div>Showing 1-3 of 24 records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Youth Content -->
                <div id="youth-content" class="tab-content">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <!-- Record 1 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">David Thompson</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> February 10, 2025</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Details"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                        
                        <!-- Record 2 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">Jessica Parker</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> January 5, 2025</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Details"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div>Showing 1-2 of 156 records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Women Content -->
                <div id="women-content" class="tab-content">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <!-- Record 1 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">Rebecca Williams</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> March 22, 2025</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Details"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div>Showing 1-1 of 18 records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Men Content -->
                <div id="men-content" class="tab-content">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <!-- Record 1 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">Robert Johnson</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> April 2, 2025</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Details"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div>Showing 1-1 of 7 records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Worship Content -->
                <div id="worship-content" class="tab-content">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <!-- Record 1 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">Choir Members</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> Ongoing</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Details"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div>Showing 1-1 of 12 records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Outreach Content -->
                <div id="outreach-content" class="tab-content">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <!-- Record 1 -->
                        <div class="sacrament-record">
                            <div class="record-info">
                                <div class="record-name">Community Service Team</div>
                                <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> Monthly</div>
                            </div>
                            <div class="record-actions">
                                <button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>
                                <button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>
                                <button class="btn-icon" title="View Details"><i class="far fa-file-alt"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div>Showing 1-1 of 9 records</div>
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
            
            sacramentTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    sacramentTabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all content sections
                    const allTabContents = document.querySelectorAll('.tab-content');
                    allTabContents.forEach(content => {
                        content.classList.remove('active');
                    });
                    
                    // Show the appropriate content section
                    const tabId = this.getAttribute('data-tab');
                    const contentToShow = document.getElementById(`${tabId}-content`);
                    if (contentToShow) {
                        contentToShow.classList.add('active');
                    }
                });
            });
            
            // Initialize date picker for sacrament date
            const today = new Date().toISOString().split('T')[0];
            const dateInputs = document.querySelectorAll('input[type="date"]');
            dateInputs.forEach(input => {
                input.value = today;
            });
        });
    </script>
</body>
</html>