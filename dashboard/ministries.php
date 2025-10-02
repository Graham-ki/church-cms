<?php
// Include database connection
include_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministries - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        :root {
            --primary-color: #4a6fa5;
            --accent-color: #6d8bc7;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --border-color: #ddd;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* Header Styles */
        /* Main Container */
        .main-container {
            display: flex;
            min-height: calc(100vh - 70px);
        }
        
        /* Sidebar Styles */
        
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 20px;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .page-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 13px;
        }
        
        .btn-accent {
            background-color: var(--accent-color);
            color: white;
        }
        
        .btn-accent:hover {
            background-color: #5a7bb7;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-success {
            background-color: var(--success-color);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            color: #212529;
        }
        
        .btn-warning:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .btn-icon {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 16px;
            color: #666;
            transition: color 0.3s ease;
        }
        
        .btn-icon:hover {
            color: var(--primary-color);
        }
        
        /* Card Styles */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .card-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        /* Sacraments specific styles */
        .sacrament-tabs {
            display: flex;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow-x: auto;
        }
        
        .sacrament-tab {
            padding: 12px 20px;
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
            background: rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            position: relative;
            overflow: hidden;

        }
        
        .sacrament-card:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
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
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .sacrament-details {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 15px;
            line-height: 1.5;
            flex-grow: 1;
            overflow: hidden;
        }
        
        .sacrament-actions {
            display: flex;
            justify-content: flex-end;
            gap: 5px;
            margin-top: auto;
            overflow: hidden;
            position: relative;
            margin-left: 0px;
        }
        
        .sacrament-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group.full-width {
            grid-column: span 2;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(109, 139, 199, 0.2);
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #555;
        }
        
        .sacrament-record {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            align-items: center;
            transition: background-color 0.3s;
        }
        
        .sacrament-record:hover {
            background: rgba(74, 111, 165, 0.05);
            border-radius: 6px;
        }
        
        .record-info {
            display: flex;
            flex-direction: column;
        }
        
        .record-name {
            font-weight: 500;
            margin-bottom: 5px;
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
            animation: fadeIn 0.5s ease;
        }
        
        .tab-content.active {
            display: block;
        }
        
        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            display: none;
            animation: fadeIn 0.3s ease;
        }
        
        .modal {
            background: white;
            border-radius: 10px;
            width: 90%;
            max-width: 700px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #999;
            transition: color 0.3s;
        }
        
        .modal-close:hover {
            color: #666;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid var(--border-color);
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        
        /* Participants List */
        .participants-list {
            max-height: 300px;
            overflow-y: auto;
        }
        
        .participant-item {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            transition: background-color 0.3s;
        }
        
        .participant-item:hover {
            background-color: var(--light-gray);
            border-radius: 6px;
        }
        
        .participant-info {
            flex: 1;
        }
        
        .participant-name {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .participant-contact {
            font-size: 14px;
            color: #666;
        }
        
        .participant-actions {
            display: flex;
            gap: 10px;
        }
        
        /* Form Styles */
        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .form-column {
            flex: 1;
        }
        
        /* Footer */
        .footer {
            background-color: white;
            padding: 20px;
            text-align: center;
            border-top: 1px solid var(--border-color);
            margin-top: 40px;
        }
        
        .footer-links {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: var(--primary-color);
            text-decoration: none;
            margin: 0 10px;
            font-size: 14px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .copyright {
            font-size: 13px;
            color: #666;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                order: 2;
            }
            
            .sacrament-list {
                grid-template-columns: 1fr;
            }
            
            .form-row {
                flex-direction: column;
                gap: 0;
            }
            
            .sacrament-form {
                grid-template-columns: 1fr;
            }
            
            .form-group.full-width {
                grid-column: span 1;
            }
        }
        
        /* Ministry Form Styles */
        .ministry-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        .ministry-form .form-group.full-width {
            grid-column: span 2;
        }
        
        /* Confirmation Modal */
        .confirmation-modal .modal {
            max-width: 500px;
        }
        
        .confirmation-message {
            text-align: center;
            padding: 20px 0;
            font-size: 16px;
        }
        
        .confirmation-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        /* Loading Spinner */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>
    <!-- Main Container -->
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
                    <a href="events" class="nav-link ">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="nav-text">Events</span>
                    </a>
                </div>
                    <div class="nav-item">
                    <a href="ministries" class="nav-link active">
                        <i class="fas fa-building"></i>
                        <span class="nav-text">Ministries</span>
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
                <h1 class="page-title">Ministries</h1>
                <div class="actions">
                    <button class="btn btn-accent" id="add-ministry-btn"><i class="fas fa-plus"></i> Add New Ministry</button>
                </div>
            </div>
            
            <!-- Ministries Overview -->
            <div class="glass-card">
                <!--<div class="sacrament-tabs">
                    <div class="sacrament-tab active" data-tab="overview">Overview</div>
                    <div class="sacrament-tab" data-tab="children">Children</div>
                    <div class="sacrament-tab" data-tab="youth">Youth</div>
                    <div class="sacrament-tab" data-tab="women">Women</div>
                    <div class="sacrament-tab" data-tab="men">Men</div>
                    <div class="sacrament-tab" data-tab="worship">Worship</div>
                    <div class="sacrament-tab" data-tab="outreach">Outreach</div>
                </div>-->

                <!-- Overview Content -->
                <div id="overview-content" class="tab-content active">
                    <div class="sacrament-list" id="ministries-list">
                        <!-- Ministry cards will be dynamically loaded here -->
                    </div>
                </div>
                
                <!-- Children Content -->
                <div id="children-content" class="tab-content">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records" id="children-records">
                        <!-- Records will be dynamically loaded here -->
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                        <div id="children-pagination-info">Loading...</div>
                        <div>
                            <button class="btn btn-sm" id="children-prev-btn" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent" id="children-next-btn">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Other tab contents would go here -->
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
    
    <!-- Add Ministry Modal -->
    <div class="modal-overlay" id="add-ministry-modal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title" id="add-ministry-modal-title">Add New Ministry</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="add-ministry-form" method="POST" action="../includes/functions.php">
                    <div class="ministry-form">
                        <div class="form-group full-width">
                            <label for="ministry-category">Select Name</label>
                            <select id="ministry-category" name="ministry_name" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="Children">Children</option>
                                <option value="Youth">Youth</option>
                                <option value="Women">Women</option>
                                <option value="Men">Men</option>
                                <option value="Worship">Worship</option>
                                <option value="Outreach">Outreach</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="ministry-description">Description</label>
                            <textarea id="ministry-description" name="description" class="form-control" rows="3" placeholder="Brief description of the ministry"></textarea>
                        </div>
                         <div class="form-group">
                            <label for="ministry-day">Day</label>
                            <input type="text" id="ministry-day" name="day" class="form-control" required>
                        </div>
                         <div class="form-group">
                            <label for="ministry-time">Time</label>
                            <input type="text" id="ministry-time" name="time" class="form-control" required>
                        </div>
                         <div class="form-group">
                            <label for="ministry-age-range">Age Range</label>
                            <input type="text" id="ministry-age-range" name="age_group" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="ministry-leader">Leader</label>
                            <select id="ministry-leader" name="leader" class="form-control" required>
                                <option value="">Select Leader</option>
                                <?php
                                $leaders = "SELECT * FROM members";
                                $result = mysqli_query($conn, $leaders);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="'.$row['first_name'].' '.$row['last_name'].'">'.$row['first_name'].' '.$row['last_name'].'</option>';
                                    }
                                } else {
                                    echo '<option value="">No leaders found</option>';
                                }
                                mysqli_free_result($result);
                                ?>
                            </select>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="cancel-add-ministry">Cancel</button>
                <button type="submit" name="add-ministry" class="btn btn-success">Add Ministry</button>
            </div>
            </form>
        </div>
    </div>
    
    <!-- Edit Ministry Modal -->
    <div class="modal-overlay" id="edit-ministry-modal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title" id="edit-ministry-modal-title">Edit Ministry</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-ministry-form">
                    <input type="hidden" id="edit-ministry-id" name="ministry-id">
                    <div class="ministry-form">
                        <div class="form-group">
                            <label for="edit-ministry-name">Ministry Name *</label>
                            <input type="text" id="edit-ministry-name" name="ministry-name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit-ministry-category">Category *</label>
                            <select id="edit-ministry-category" name="ministry-category" class="form-control" required>
                                <option value="">Select Category</option>
                                <option value="Children">Children</option>
                                <option value="Youth">Youth</option>
                                <option value="Women">Women</option>
                                <option value="Men">Men</option>
                                <option value="Worship">Worship</option>
                                <option value="Outreach">Outreach</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group full-width">
                            <label for="edit-ministry-description">Description</label>
                            <textarea id="edit-ministry-description" name="ministry-description" class="form-control" rows="3" placeholder="Brief description of the ministry"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit-ministry-leader">Leader</label>
                            <input type="text" id="edit-ministry-leader" name="ministry-leader" class="form-control" placeholder="Name of ministry leader">
                        </div>
                        
                        <div class="form-group">
                            <label for="edit-ministry-contact">Contact Email</label>
                            <input type="email" id="edit-ministry-contact" name="ministry-contact" class="form-control" placeholder="Contact email for the ministry">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" id="cancel-edit-ministry">Cancel</button>
                <button class="btn btn-success" id="submit-edit-ministry">Update Ministry</button>
            </div>
        </div>
    </div>
    
    <!-- View All Modal -->
    <div class="modal-overlay" id="view-all-modal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title" id="view-all-modal-title">Participants</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="participants-list" id="participants-list">
                    <!-- Participants will be dynamically added here -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="close-view-all">Close</button>
            </div>
        </div>
    </div>
    
    <!-- Add New Participant Modal -->
    <div class="modal-overlay" id="add-new-modal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title" id="add-new-modal-title">Add New Participant</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <form id="add-participant-form" method="POST" action="../includes/functions.php">
                    <input type="hidden" id="ministry-type" name="ministry-type" value="">
                    <input type="hidden" id="ministry-id" name="ministry_id" value="">
                    <div class="form-row">
                        <div class="form-column">
                            <label for="first-name">Name</label>
                            <select id="" name="member_id" class="form-control" required>
                                <option value="">Select Member</option>
                                <?php
                                $members = "SELECT * FROM members";
                                $result = mysqli_query($conn, $members);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="'.$row['id'].'">'.$row['first_name'].' '.$row['last_name'].'</option>';
                                    }
                                } else {
                                    echo '<option value="">No members found</option>';
                                }
                                mysqli_free_result($result);
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="join-date">Join Date *</label>
                        <input type="date" id="join-date" name="join_date" class="form-control" required>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="cancel-add-new">Cancel</button>
                <button type="submit" class="btn btn-success" name="add-participant">Add Participant</button>
            </div>
            </form>
        </div>
    </div>
    
    <!-- Confirmation Modal -->
    <div class="modal-overlay confirmation-modal" id="confirmation-modal">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Confirm Action</h2>
                <button class="modal-close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="confirmation-message" id="confirmation-message">
                    Are you sure you want to delete this ministry?
                </div>
                <div class="confirmation-buttons">
                    <button class="btn" id="cancel-confirmation">Cancel</button>
                    <button class="btn btn-danger" id="confirm-action">Delete</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Database integration functions
        const MinistryManager = {
            // Get all ministries from the database
            async getMinistries() {
                try {
                    const response = await fetch('../includes/functions.php?action=getAll');
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error fetching ministries:', error);
                    return [];
                }
            },
            
            // Get ministries by category
            async getMinistriesByCategory(category) {
                try {
                    const response = await fetch(`api/ministries.php?action=getByCategory&category=${category}`);
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error fetching ministries by category:', error);
                    return [];
                }
            },
            
            // Get participants for a ministry
            async getParticipants(ministryId) {
                try {
                    const response = await fetch(`../includes/functions.php?action=getParticipants&ministryId=${ministryId}`);
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error fetching participants:', error);
                    return [];
                }
            },
            
            // Add a new ministry
            async addMinistry(ministryData) {
                try {
                    const response = await fetch('includes/functions.php?action=add-ministry', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(ministryData)
                    });
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error adding ministry:', error);
                    return { success: false, message: 'Failed to add ministry' };
                }
            },
            
            // Update a ministry
            async updateMinistry(ministryData) {
                try {
                    const response = await fetch('api/ministries.php?action=update', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(ministryData)
                    });
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error updating ministry:', error);
                    return { success: false, message: 'Failed to update ministry' };
                }
            },
            
            // Delete a ministry
            async deleteMinistry(ministryId) {
                try {
                    const response = await fetch('api/ministries.php?action=delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ ministryId: ministryId })
                    });
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error deleting ministry:', error);
                    return { success: false, message: 'Failed to delete ministry' };
                }
            },
            
            // Add a participant to a ministry
            async addParticipant(participantData) {
                try {
                    const response = await fetch('api/ministries.php?action=addParticipant', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(participantData)
                    });
                    const data = await response.json();
                    return data;
                } catch (error) {
                    console.error('Error adding participant:', error);
                    return { success: false, message: 'Failed to add participant' };
                }
            }
        };

        // Application state
        const AppState = {
            currentMinistries: [],
            currentParticipants: [],
            currentMinistryId: null,
            currentCategory: 'overview',
            currentPage: 1,
            itemsPerPage: 10
        };

        // DOM Ready
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the page
            loadMinistries();
            
            // Tab functionality
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
                    AppState.currentCategory = tabId;
                    
                    const contentToShow = document.getElementById(`${tabId}-content`);
                    if (contentToShow) {
                        contentToShow.classList.add('active');
                    }
                    
                    // If not overview, load specific category data
                    if (tabId !== 'overview') {
                        loadMinistriesByCategory(tabId);
                    }
                });
            });
            
            // Add Ministry functionality
            const addMinistryBtn = document.getElementById('add-ministry-btn');
            const addMinistryModal = document.getElementById('add-ministry-modal');
            const cancelAddMinistryBtn = document.getElementById('cancel-add-ministry');
            const addMinistryForm = document.getElementById('add-ministry-form');
            
            addMinistryBtn.addEventListener('click', function() {
                addMinistryModal.style.display = 'flex';
            });
            
            cancelAddMinistryBtn.addEventListener('click', function() {
                closeModal(addMinistryModal);
                addMinistryForm.reset();
            });
            
            // Edit Ministry functionality
            const editMinistryModal = document.getElementById('edit-ministry-modal');
            const cancelEditMinistryBtn = document.getElementById('cancel-edit-ministry');
            const submitEditMinistryBtn = document.getElementById('submit-edit-ministry');
            const editMinistryForm = document.getElementById('edit-ministry-form');
            
            cancelEditMinistryBtn.addEventListener('click', function() {
                closeModal(editMinistryModal);
                editMinistryForm.reset();
            });
            
            submitEditMinistryBtn.addEventListener('click', async function() {
                // Show loading state
                const originalText = submitEditMinistryBtn.innerHTML;
                submitEditMinistryBtn.innerHTML = '<span class="loading-spinner"></span> Updating...';
                submitEditMinistryBtn.disabled = true;
                
                // Get form data
                const formData = new FormData(editMinistryForm);
                const ministryData = {
                    id: formData.get('ministry-id'),
                    name: formData.get('ministry-name'),
                    category: formData.get('ministry-category'),
                    description: formData.get('ministry-description'),
                    leader: formData.get('ministry-leader'),
                    contact: formData.get('ministry-contact')
                };
                
                // Validate form
                if (!ministryData.name || !ministryData.category) {
                    alert('Please fill in all required fields: Ministry Name and Category');
                    submitEditMinistryBtn.innerHTML = originalText;
                    submitEditMinistryBtn.disabled = false;
                    return;
                }
                
                // Submit to database
                const result = await MinistryManager.updateMinistry(ministryData);
                
                if (result.success) {
                    // Close modal and reset form
                    closeModal(editMinistryModal);
                    editMinistryForm.reset();
                    
                    // Reload ministries
                    loadMinistries();
                    
                    // Show success message
                    alert('Ministry updated successfully!');
                } else {
                    alert('Error: ' + result.message);
                }
                
                // Restore button state
                submitEditMinistryBtn.innerHTML = originalText;
                submitEditMinistryBtn.disabled = false;
            });
            
            // Confirmation Modal functionality
            const confirmationModal = document.getElementById('confirmation-modal');
            const cancelConfirmationBtn = document.getElementById('cancel-confirmation');
            const confirmActionBtn = document.getElementById('confirm-action');
            const confirmationMessage = document.getElementById('confirmation-message');
            
            let pendingDeleteId = null;
            
            cancelConfirmationBtn.addEventListener('click', function() {
                closeModal(confirmationModal);
                pendingDeleteId = null;
            });
            
            confirmActionBtn.addEventListener('click', async function() {
                if (pendingDeleteId) {
                    // Show loading state
                    const originalText = confirmActionBtn.innerHTML;
                    confirmActionBtn.innerHTML = '<span class="loading-spinner"></span> Deleting...';
                    confirmActionBtn.disabled = true;
                    
                    // Delete the ministry
                    const result = await MinistryManager.deleteMinistry(pendingDeleteId);
                    
                    if (result.success) {
                        // Close modal
                        closeModal(confirmationModal);
                        
                        // Reload ministries
                        loadMinistries();
                        
                        // Show success message
                        alert('Ministry deleted successfully!');
                    } else {
                        alert('Error: ' + result.message);
                    }
                    
                    // Restore button state
                    confirmActionBtn.innerHTML = originalText;
                    confirmActionBtn.disabled = false;
                    pendingDeleteId = null;
                }
            });
            
            // View All functionality
            const viewAllModal = document.getElementById('view-all-modal');
            const closeViewAllBtn = document.getElementById('close-view-all');
            
            closeViewAllBtn.addEventListener('click', function() {
                closeModal(viewAllModal);
            });
            
            // Add New Participant functionality
            const addNewModal = document.getElementById('add-new-modal');
            const cancelAddNewBtn = document.getElementById('cancel-add-new');
            const addParticipantForm = document.getElementById('add-participant-form');
            
            cancelAddNewBtn.addEventListener('click', function() {
                closeModal(addNewModal);
                addParticipantForm.reset();
            });
            
            // Close modals when clicking on overlay
            document.querySelectorAll('.modal-overlay').forEach(overlay => {
                overlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeModal(this);
                        addMinistryForm.reset();
                        editMinistryForm.reset();
                        addParticipantForm.reset();
                    }
                });
            });
            
            // Close modals when clicking on close button
            document.querySelectorAll('.modal-close').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    const modal = this.closest('.modal-overlay');
                    closeModal(modal);
                    addMinistryForm.reset();
                    editMinistryForm.reset();
                    addParticipantForm.reset();
                });
            });
            
            // Set today's date as default for join date
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('join-date').value = today;
        });
        
        // Function to load all ministries
        async function loadMinistries() {
            const ministriesList = document.getElementById('ministries-list');
            ministriesList.innerHTML = '<p style="text-align: center; padding: 20px;">Loading ministries...</p>';
            
            const ministries = await MinistryManager.getMinistries();
            AppState.currentMinistries = ministries;
            
            if (ministries.length === 0) {
                ministriesList.innerHTML = '<p style="text-align: center; padding: 20px;">No ministries found. Click "Add New Ministry" to create one.</p>';
                return;
            }
            
            ministriesList.innerHTML = '';
            
            ministries.forEach(ministry => {
                const ministryCard = document.createElement('div');
                ministryCard.className = 'sacrament-card';
                ministryCard.innerHTML = `
                    <div class="sacrament-header">
                        <div class="sacrament-title">${ministry.name} Ministry</div>
                        <div class="sacrament-count">${ministry.participantCount || 0}</div>
                    </div>
                    <div class="sacrament-details">
                        ${ministry.description || 'No description available.'}
                        ${ministry.leader ? `<br><strong>Leader:</strong> ${ministry.leader}` : ''}
                    </div>
                    <div class="sacrament-actions">
                        <button class="btn btn-sm view-all-btn" data-ministry-id="${ministry.id}"><i class="fas fa-eye"></i>View</button>
                        <button class="btn btn-sm btn-accent add-new-btn" data-ministry-id="${ministry.id}"><i class="fas fa-plus"></i> Add New</button>
                        <button class="btn btn-sm btn-warning edit-ministry-btn" data-ministry-id="${ministry.id}"><i class="fas fa-edit"></i> Edit</button>
                        <button class="btn btn-sm btn-danger delete-ministry-btn" data-ministry-id="${ministry.id}"><i class="fas fa-trash"></i> Delete</button>
                    </div>
                `;
                ministriesList.appendChild(ministryCard);
            });
            
            // Add event listeners to the new buttons
            document.querySelectorAll('.view-all-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const ministryId = this.getAttribute('data-ministry-id');
                    viewParticipants(ministryId);
                });
            });
            
            document.querySelectorAll('.add-new-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const ministryId = this.getAttribute('data-ministry-id');
                    const ministry = AppState.currentMinistries.find(m => m.id == ministryId);
                    if (ministry) {
                        document.getElementById('ministry-id').value = ministryId;
                        document.getElementById('ministry-type').value = ministry.name;
                        document.getElementById('add-new-modal-title').textContent = `Add New Participant to ${ministry.name} Ministry`;
                        document.getElementById('add-new-modal').style.display = 'flex';
                    }
                });
            });
            
            document.querySelectorAll('.edit-ministry-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const ministryId = this.getAttribute('data-ministry-id');
                    editMinistry(ministryId);
                });
            });
            
            document.querySelectorAll('.delete-ministry-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const ministryId = this.getAttribute('data-ministry-id');
                    confirmDelete(ministryId);
                });
            });
        }
        
        // Function to load ministries by category
        async function loadMinistriesByCategory(category) {
            const categoryContent = document.getElementById(`${category}-content`);
            if (!categoryContent) return;
            
            const recordsContainer = categoryContent.querySelector('.sacrament-records');
            if (!recordsContainer) return;
            
            recordsContainer.innerHTML = '<p style="text-align: center; padding: 20px;">Loading records...</p>';
            
            const ministries = await MinistryManager.getMinistriesByCategory(category);
            
            if (ministries.length === 0) {
                recordsContainer.innerHTML = '<p style="text-align: center; padding: 20px;">No records found for this category.</p>';
                return;
            }
            
            recordsContainer.innerHTML = '';
            
            ministries.forEach(ministry => {
                const recordElement = document.createElement('div');
                recordElement.className = 'sacrament-record';
                recordElement.innerHTML = `
                    <div class="record-info">
                        <div class="record-name">${ministry.name}</div>
                        <div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> Created: ${ministry.createdDate || 'Unknown'}</div>
                    </div>
                    <div class="record-actions">
                        <button class="btn-icon view-ministry-btn" data-ministry-id="${ministry.id}" title="View"><i class="far fa-eye"></i></button>
                        <button class="btn-icon edit-ministry-btn" data-ministry-id="${ministry.id}" title="Edit"><i class="far fa-edit"></i></button>
                        <button class="btn-icon delete-ministry-btn" data-ministry-id="${ministry.id}" title="Delete"><i class="far fa-trash-alt"></i></button>
                    </div>
                `;
                recordsContainer.appendChild(recordElement);
            });
            
            // Add event listeners to the action buttons
            document.querySelectorAll('.view-ministry-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const ministryId = this.getAttribute('data-ministry-id');
                    viewParticipants(ministryId);
                });
            });
            
            document.querySelectorAll('.edit-ministry-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const ministryId = this.getAttribute('data-ministry-id');
                    editMinistry(ministryId);
                });
            });
            
            document.querySelectorAll('.delete-ministry-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const ministryId = this.getAttribute('data-ministry-id');
                    confirmDelete(ministryId);
                });
            });
        }
        
        // Function to view participants of a ministry
        async function viewParticipants(ministryId) {
            const participantsList = document.getElementById('participants-list');
            participantsList.innerHTML = '<p style="text-align: center; padding: 20px;">Loading participants...</p>';
            
            const participants = await MinistryManager.getParticipants(ministryId);
            const ministry = AppState.currentMinistries.find(m => m.id == ministryId);
            
            document.getElementById('view-all-modal-title').textContent = `${ministry ? ministry.name : 'Ministry'} Ministry Participants`;
            
            if (participants.length === 0) {
                participantsList.innerHTML = '<p style="padding: 20px; text-align: center;">No participants found for this ministry.</p>';
            } else {
                participantsList.innerHTML = '';
                
                participants.forEach(participant => {
                    const participantItem = document.createElement('div');
                    participantItem.className = 'participant-item';
                    participantItem.innerHTML = `
                        <div class="participant-info">
                            <div class="participant-name">${participant.firstName} ${participant.lastName}</div>
                            <div class="participant-contact">${participant.email || ''} ${participant.email && participant.phone ? '|' : ''} ${participant.phone || ''}</div>
                        </div>
                        <div class="participant-actions">
                            <button class="btn-icon" title="Contact"><i class="fas fa-envelope"></i></button>
                            <button class="btn-icon" title="View Profile"><i class="fas fa-user"></i></button>
                        </div>
                    `;
                    participantsList.appendChild(participantItem);
                });
            }
            
            document.getElementById('view-all-modal').style.display = 'flex';
        }
        
        // Function to edit a ministry
        async function editMinistry(ministryId) {
            const ministry = AppState.currentMinistries.find(m => m.id == ministryId);
            if (!ministry) return;
            
            // Populate the form with ministry data
            document.getElementById('edit-ministry-id').value = ministry.id;
            document.getElementById('edit-ministry-name').value = ministry.name;
            document.getElementById('edit-ministry-category').value = ministry.category;
            document.getElementById('edit-ministry-description').value = ministry.description || '';
            document.getElementById('edit-ministry-leader').value = ministry.leader || '';
            document.getElementById('edit-ministry-contact').value = ministry.contact || '';
            
            // Show the modal
            document.getElementById('edit-ministry-modal').style.display = 'flex';
        }
        
        // Function to confirm deletion of a ministry
        function confirmDelete(ministryId) {
            const ministry = AppState.currentMinistries.find(m => m.id == ministryId);
            if (!ministry) return;
            
            document.getElementById('confirmation-message').textContent = `Are you sure you want to delete the "${ministry.name}" ministry? This action cannot be undone.`;
            pendingDeleteId = ministryId;
            document.getElementById('confirmation-modal').style.display = 'flex';
        }
        
        // Function to close a modal
        function closeModal(modal) {
            modal.style.display = 'none';
        }
    </script>
</body>
</html>