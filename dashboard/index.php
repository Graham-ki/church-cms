<?php
session_start();
include_once '../config/db.php';
?>
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
            cursor: pointer;
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
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }
        
        .modal-content {
            background-color: white;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            color: var(--primary-color);
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: #777;
        }
        
        .close-btn:hover {
            color: #333;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2c5282;
        }
        
        .btn-secondary {
            background-color: #e2e8f0;
            color: #4a5568;
        }
        
        .btn-secondary:hover {
            background-color: #cbd5e0;
        }
        
        .members-list {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 10px;
        }
        
        .member-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .member-item:last-child {
            border-bottom: none;
        }
        
        .member-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e2e8f0;
            background-size: cover;
            background-position: center;
        }
        
        .member-name {
            flex: 1;
        }
        
        .checkbox-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 5px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php include 'header.php'; ?>
    
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
                    <a href="ministries" class="nav-link">
                        <i class="fas fa-church"></i>
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
                        <p class="stat-value">
                            <?php 
                            $check = "SELECT * FROM members";
                            $result = mysqli_query($conn, $check);
                            if ($result && mysqli_num_rows($result) > 0) {
                                $totalMembers = mysqli_num_rows($result);
                            } else {
                                $totalMembers = 0;
                            }
                            echo $totalMembers; ?></p>
                        <p class="stat-change positive"><i class="fas fa-arrow-up"></i> 12 this month</p>
                    </div>
                </div>
                
                <div class="glass-card stat-card">
                    <div class="stat-icon" style="color: var(--accent-color);">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Last Attendance</h3>
                        <p class="stat-value"><?php
                        $check = "SELECT * FROM attendance WHERE attended = 'service' AND date = CURDATE()";
                        $result = mysqli_query($conn, $check);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $totalMembers = mysqli_num_rows($result);
                        } else {
                            $totalMembers = 0;
                        }
                        echo $totalMembers;
                        ?></p>
                        <p class="stat-change negative"><i class="fas fa-arrow-down"></i> 5% from last</p>
                    </div>
                </div>
                
                <div class="glass-card stat-card">
                    <div class="stat-icon" style="color: #4fc3a1;">
                        <i class="fas fa-donate"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Monthly Contributions</h3>
                        <p class="stat-value"><?php
                        $check = "SELECT SUM(amount) as total FROM contributions WHERE MONTH(contribution_date) = MONTH(CURRENT_DATE())";
                        $result = mysqli_query($conn, $check);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $totalContributions = $row['total'] ? $row['total'] : 0;
                        } else {
                            $totalContributions = 0;
                        }
                        echo number_format($totalContributions, 2);
                        ?> UGX</p>
                        <p class="stat-change positive"><i class="fas fa-arrow-up"></i> 8% increase</p>
                    </div>
                </div>
                
                <div class="glass-card stat-card">
                    <div class="stat-icon" style="color: #ffa500;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div class="stat-info">
                        <h3>Upcoming Events</h3>
                        <p class="stat-value"><?php 
                        $check = "SELECT COUNT(*) as total FROM events WHERE start_date > CURDATE()";
                        $result = mysqli_query($conn, $check);
                        if ($result && mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_assoc($result);
                            $upcomingEvents = $row['total'] ? $row['total'] : 0;
                        } else {
                            $upcomingEvents = 0;
                        }
                        echo $upcomingEvents; ?></p>
                        <a href="events" class="stat-link">View all</a>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="glass-card quick-actions-card">
                <div class="card-header">
                    <h2 class="card-title">Quick Actions</h2>
                </div>
                <div class="actions-grid">
                    <div class="action-btn" id="take-attendance-btn">
                        <div class="action-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <span>Take Attendance</span>
                    </div>
                    <div class="action-btn" id="record-offering-btn">
                        <div class="action-icon">
                            <i class="fas fa-donate"></i>
                        </div>
                        <span>Record Contribution</span>
                    </div>
                    <div class="action-btn" id="send-announcement-btn">
                        <div class="action-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <span>Send Announcement</span>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity -->
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">Recent Activity</h2>
                </div>
                <div class="activity-list">
                    <?php
                    // Fetch recent activities from the database
                    $check = "SELECT * FROM activity_log ORDER BY date DESC LIMIT 5";
                    $result = mysqli_query($conn, $check);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $activity = $row['action'];
                            $date = $row['date'];
                            echo '<div class="activity-item">';
                            echo '<div class="activity-details">';
                            echo '<p><strong>' . $activity . '</strong></p>';
                            echo '<small>' . $date . '</small>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="no-data">No recent activities found.</p>';
                    }
                    ?>
                </div>
            </div>
            
            <!-- Upcoming Events -->
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">Upcoming Events</h2>
                    <a href="events" class="btn btn-sm">View All</a>
                </div>
                <div class="events-grid">
                    <?php
                    // Fetch upcoming events from the database
                    $check = "SELECT * FROM events WHERE start_date >= CURDATE() ORDER BY start_date ASC LIMIT 3";
                    $result = mysqli_query($conn, $check);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $event_name = $row['title'];
                            $event_date = $row['start_date'];
                            $event_time = $row['start_time'];
                            $event_venue = $row['location'];
                            echo '<div class="event-card">';
                            echo '<div class="event-date" style="background: var(--primary-color);">';
                            echo '<span>' . date('d', strtotime($event_date)) . '</span>';
                            echo '<span>' . strtoupper(date('M', strtotime($event_date))) . '</span>';
                            echo '</div>';
                            echo '<div class="event-info">';
                            echo '<h3>' . $event_name . '</h3>';
                            echo '<p><i class="fas fa-clock"></i> ' . $event_time . '</p>';
                            echo '<p><i class="fas fa-map-marker-alt"></i> ' . $event_venue . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="no-data">No upcoming events found.</p>';
                    }
                    ?>
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
    
    <!-- Modal Forms -->
    
    <!-- Take Attendance Modal -->
    <div id="attendance-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Take Attendance</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <form id="" action="../includes/functions.php" method="POST">
                    <div class="form-group">
                        <label for="attendance-date">Date</label>
                        <input type="date" id="attendance-date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="attendance-service">Service</label>
                        <select id="attendance-service" name="service" class="form-control" required>
                            <option value="">Select Service</option>
                            <option value="sunday-morning">Sunday Morning Service</option>
                            <option value="sunday-evening">Sunday Evening Service</option>
                            <option value="midweek">Midweek Service</option>
                            <option value="special">Special Service</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Members Present</label>
                        <div class="members-list">
                            <?php
                            $selectMembers = "SELECT id, first_name, last_name, profile_pic FROM members ORDER BY first_name";
                            $result = mysqli_query($conn, $selectMembers);
                            if (mysqli_num_rows($result) > 0) {
                                while ($member = mysqli_fetch_assoc($result)) {
                                    echo '<div class="member-item">';
                                    echo '<div class="member-avatar" style="background-image: url(../public/images/user-logo.jpg);"></div>';
                                    echo '<div class="member-name">' . $member['first_name'] . ' ' . $member['last_name'] . '</div>';
                                    echo '<input type="checkbox" name="members[]" class="member-checkbox" value="' . $member['id'] . '">';
                                    echo '</div>';
                                }
                            } else {
                                echo '<div class="no-data">No members found.</div>';
                            }  ?>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                        <button type="submit" name="take_attendance" class="btn btn-primary">Save Attendance</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Record Offering Modal -->
    <div id="offering-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Record Contribution</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <form id="" action="../includes/functions.php" method="POST">
                    <div class="form-group">
                        <label for="offering-date">Date</label>
                        <input type="date" id="offering-date" name="date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="offering-type">Contribution Type</label>
                        <select id="offering-type" name="type" class="form-control" required>
                            <option value="">Select</option>
                            <option value="tithe">Tithe</option>
                            <option value="offering">General Offering</option>
                            <option value="building">Building Fund</option>
                            <option value="mission">Missions</option>
                            <option value="special">Special Offering</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="offering-amount">Amount (UGX)</label>
                        <input type="number" name="amount" id="offering-amount" class="form-control" placeholder="Enter amount"  required>
                    </div>
                    <div class="form-group">
                        <label for="offering-member">Member (Optional)</label>
                        <select id="offering-member" name="member" class="form-control">
                            <option value="">Select</option><?php
                            $sql = "SELECT * FROM members";
                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                while ($member = $result->fetch_assoc()) {
                                echo '<option value="' . $member['id'] . '">' . $member['first_name'] . ' ' . $member['last_name'] . '</option>';
                            }
                            } else {
                                echo '<option value="">No members found</option>';
                            }   
                            
                            ?>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                        <button type="submit" name="record_offering" class="btn btn-primary">Record Contribution</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Send Announcement Modal -->
    <div id="announcement-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Send Announcement</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <form id="" action="../includes/functions.php" method="POST">
                    <div class="form-group">
                        <label for="announcement-title">Title</label>
                        <input type="text" id="announcement-title" name="title" class="form-control" placeholder="Enter announcement title" required>
                    </div>
                    <div class="form-group">
                        <label for="announcement-message">Message</label>
                        <textarea id="announcement-message" name="message" class="form-control" rows="5" placeholder="Enter announcement message" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Send To</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="send-all" name="send_to[]" value="all" checked>
                                <label for="send-all">All Members</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="send-youth" name="send_to[]" value="youth">
                                <label for="send-youth">Youth Group</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="send-women" name="send_to[]" value="women">
                                <label for="send-women">Women's Fellowship</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="send-men" name="send_to[]" value="men">
                                <label for="send-men">Men's Fellowship</label>
                            </div>
                        </div>
                    </div>
                    <!--<div class="form-group">
                        <label for="announcement-channel">Channel</label>
                        <select id="announcement-channel" class="form-control" required>
                            <option value="">Select Channel</option>
                            <option value="sms">SMS</option>
                            <option value="email">Email</option>
                            <option value="both">Both SMS and Email</option>
                        </select>
                    </div>-->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                        <button type="submit" name="send_announcement" class="btn btn-primary">Send Announcement</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add Event Modal -->
    <div id="event-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add Event</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <form id="event-form">
                    <div class="form-group">
                        <label for="event-title">Event Title</label>
                        <input type="text" id="event-title" class="form-control" placeholder="Enter event title" required>
                    </div>
                    <div class="form-group">
                        <label for="event-description">Description</label>
                        <textarea id="event-description" class="form-control" rows="3" placeholder="Enter event description"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="event-date">Date</label>
                            <input type="date" id="event-date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="event-time">Time</label>
                            <input type="time" id="event-time" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event-location">Location</label>
                        <input type="text" id="event-location" class="form-control" placeholder="Enter event location" required>
                    </div>
                    <div class="form-group">
                        <label for="event-category">Category</label>
                        <select id="event-category" class="form-control" required>
                            <option value="">Select Category</option>
                            <option value="service">Service</option>
                            <option value="fellowship">Fellowship</option>
                            <option value="outreach">Outreach</option>
                            <option value="training">Training</option>
                            <option value="social">Social Event</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add Member Modal -->
    <div id="member-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Add Member</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <form id="member-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="member-firstname">First Name</label>
                            <input type="text" id="member-firstname" class="form-control" placeholder="Enter first name" required>
                        </div>
                        <div class="form-group">
                            <label for="member-lastname">Last Name</label>
                            <input type="text" id="member-lastname" class="form-control" placeholder="Enter last name" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="member-email">Email</label>
                        <input type="email" id="member-email" class="form-control" placeholder="Enter email address">
                    </div>
                    <div class="form-group">
                        <label for="member-phone">Phone Number</label>
                        <input type="tel" id="member-phone" class="form-control" placeholder="Enter phone number" required>
                    </div>
                    <div class="form-group">
                        <label for="member-address">Address</label>
                        <textarea id="member-address" class="form-control" rows="2" placeholder="Enter address"></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="member-gender">Gender</label>
                            <select id="member-gender" class="form-control" required>
                                <option value="">Select Gender</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="member-birthdate">Date of Birth</label>
                            <input type="date" id="member-birthdate" class="form-control">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="member-join-date">Join Date</label>
                        <input type="date" id="member-join-date" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="member-group">Member Group</label>
                        <select id="member-group" class="form-control">
                            <option value="">Select Group</option>
                            <option value="general">General</option>
                            <option value="youth">Youth</option>
                            <option value="women">Women's Fellowship</option>
                            <option value="men">Men's Fellowship</option>
                            <option value="children">Children's Ministry</option>
                        </select>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary close-modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
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
            
            // Modal functionality
            const modals = document.querySelectorAll('.modal');
            const closeButtons = document.querySelectorAll('.close-btn, .close-modal');
            
            // Open modal functions
            document.getElementById('take-attendance-btn').addEventListener('click', function() {
                document.getElementById('attendance-modal').style.display = 'flex';
            });
            
            document.getElementById('record-offering-btn').addEventListener('click', function() {
                document.getElementById('offering-modal').style.display = 'flex';
            });
            
            document.getElementById('send-announcement-btn').addEventListener('click', function() {
                document.getElementById('announcement-modal').style.display = 'flex';
            });
            
            document.getElementById('add-event-btn').addEventListener('click', function() {
                document.getElementById('event-modal').style.display = 'flex';
            });
            
            document.getElementById('add-member-btn').addEventListener('click', function() {
                document.getElementById('member-modal').style.display = 'flex';
            });
            
            // Close modal functions
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    modals.forEach(modal => {
                        modal.style.display = 'none';
                    });
                });
            });
            
            // Close modal when clicking outside
            modals.forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });
            
            // Form submission handlers
            document.getElementById('attendance-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Attendance recorded successfully!');
                document.getElementById('attendance-modal').style.display = 'none';
                // Reset form
                this.reset();
            });
            
            document.getElementById('offering-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Offering recorded successfully!');
                document.getElementById('offering-modal').style.display = 'none';
                // Reset form
                this.reset();
            });
            
            document.getElementById('announcement-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Announcement sent successfully!');
                document.getElementById('announcement-modal').style.display = 'none';
                // Reset form
                this.reset();
            });
            
            document.getElementById('event-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Event added successfully!');
                document.getElementById('event-modal').style.display = 'none';
                // Reset form
                this.reset();
            });
            
            document.getElementById('member-form').addEventListener('submit', function(e) {
                e.preventDefault();
                alert('Member added successfully!');
                document.getElementById('member-modal').style.display = 'none';
                // Reset form
                this.reset();
            });
            
            // Set today's date as default for date fields
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('attendance-date').value = today;
            document.getElementById('offering-date').value = today;
            document.getElementById('event-date').value = today;
            document.getElementById('member-join-date').value = today;
        });
    </script>
</body>
</html>