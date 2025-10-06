<?php
session_start();
include_once '../config/db.php';
//include_once '../includes/functions.php';

// Handle delete contribution
if (isset($_GET['delete_id'])) {
    $delete_id = mysqli_real_escape_string($conn, $_GET['delete_id']);
    $delete_query = "DELETE FROM contributions WHERE id = '$delete_id'";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['message'] = "Contribution deleted successfully";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error deleting contribution: " . mysqli_error($conn);
        $_SESSION['message_type'] = "error";
    }
    header("Location: contributions");
    exit();
}

// Handle edit contribution
if (isset($_POST['edit_contribution'])) {
    $id = mysqli_real_escape_string($conn, $_POST['contribution_id']);
    $member_id = mysqli_real_escape_string($conn, $_POST['member']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    
    $update_query = "UPDATE contributions SET member_id = '$member_id', contribution_date = '$date', 
                    contribution_type = '$type', amount = '$amount' WHERE id = '$id'";
    
    if (mysqli_query($conn, $update_query)) {
        $_SESSION['message'] = "Contribution updated successfully";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating contribution: " . mysqli_error($conn);
        $_SESSION['message_type'] = "error";
    }
    header("Location: contributions");
    exit();
}

// Handle search and filters
$where_conditions = [];
$search_sql = "";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $where_conditions[] = "(m.first_name LIKE '%$search%' OR m.last_name LIKE '%$search%')";
}

if (isset($_GET['type_filter']) && !empty($_GET['type_filter']) && $_GET['type_filter'] != 'all') {
    $type_filter = mysqli_real_escape_string($conn, $_GET['type_filter']);
    $where_conditions[] = "c.contribution_type = '$type_filter'";
}

if (isset($_GET['date_filter']) && !empty($_GET['date_filter'])) {
    $date_filter = mysqli_real_escape_string($conn, $_GET['date_filter']);
    switch($date_filter) {
        case 'this_month':
            $where_conditions[] = "MONTH(c.contribution_date) = MONTH(CURDATE()) AND YEAR(c.contribution_date) = YEAR(CURDATE())";
            break;
        case 'last_month':
            $where_conditions[] = "MONTH(c.contribution_date) = MONTH(DATE_SUB(CURDATE(), INTERVAL 1 MONTH)) AND YEAR(c.contribution_date) = YEAR(DATE_SUB(CURDATE(), INTERVAL 1 MONTH))";
            break;
        case 'last_3_months':
            $where_conditions[] = "c.contribution_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            break;
        case 'this_year':
            $where_conditions[] = "YEAR(c.contribution_date) = YEAR(CURDATE())";
            break;
    }
}

if (count($where_conditions) > 0) {
    $search_sql = " WHERE " . implode(" AND ", $where_conditions);
}

// Get contributions with filters
$contributions_query = "SELECT c.*, m.first_name, m.last_name, m.date_joined 
                       FROM contributions c 
                       JOIN members m ON c.member_id = m.id 
                       $search_sql 
                       ORDER BY c.contribution_date DESC 
                       LIMIT 10";
$contributions_result = mysqli_query($conn, $contributions_query);

// Get contribution types for filter dropdown
$types_query = "SELECT DISTINCT contribution_type FROM contributions";
$types_result = mysqli_query($conn, $types_query);
$contribution_types = [];
while($row = mysqli_fetch_assoc($types_result)) {
    $contribution_types[] = $row['contribution_type'];
}
?>
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
        
        .building {
            background: rgba(156, 39, 176, 0.2);
            color: #7b1fa2;
        }
        
        .mission {
            background: rgba(244, 67, 54, 0.2);
            color: #d32f2f;
        }
        
        .other {
            background: rgba(158, 158, 158, 0.2);
            color: #616161;
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
        
        /* Receipt modal styles */
        .receipt-modal .modal-content {
            max-width: 500px;
        }
        
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
        }
        
        .receipt-details {
            margin-bottom: 20px;
        }
        
        .receipt-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px dashed #eee;
        }
        
        .receipt-row.total {
            font-weight: bold;
            border-bottom: 2px solid #333;
            padding-top: 10px;
        }
        
        .receipt-footer {
            text-align: center;
            margin-top: 30px;
            font-style: italic;
            color: #666;
        }
        
        /* Message styles */
        .message {
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: 500;
        }
        
        .message.success {
            background-color: rgba(79, 195, 161, 0.2);
            color: #2e8b6d;
            border: 1px solid rgba(79, 195, 161, 0.5);
        }
        
        .message.error {
            background-color: rgba(244, 67, 54, 0.2);
            color: #d32f2f;
            border: 1px solid rgba(244, 67, 54, 0.5);
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
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Search contributions..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit"><i class="fas fa-search"></i></button>
            </form>
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
            
            <!-- Display Messages -->
            <?php if (isset($_SESSION['message'])): ?>
                <div class="message <?php echo $_SESSION['message_type']; ?>">
                    <?php 
                    echo $_SESSION['message']; 
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                    ?>
                </div>
            <?php endif; ?>
            
            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card totals">
                    <h3>Total Contributions</h3>
                    <p><?php
                    $total = 'SELECT SUM(amount) AS total FROM contributions';
                    $result = mysqli_query($conn, $total);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total'] ? number_format($row['total']) : 0;
                    ?> UGX</p>
                </div>
                <div class="summary-card tithes">
                    <h3>Total Tithes</h3>
                    <p><?php
                    $tithes = 'SELECT SUM(amount) AS total FROM contributions WHERE contribution_type = "tithe"';
                    $result = mysqli_query($conn, $tithes);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total'] ? number_format($row['total']) : 0;
                    ?> UGX</p>
                </div>
                <div class="summary-card offerings">
                    <h3>Total Offerings</h3>
                    <p><?php
                    $offerings = 'SELECT SUM(amount) AS total FROM contributions WHERE contribution_type = "offering"';
                    $result = mysqli_query($conn, $offerings);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total'] ? number_format($row['total']) : 0;
                    ?> UGX</p>
                </div>
                <div class="summary-card donations">
                    <h3>Total Donations</h3>
                    <p><?php
                    $donations = 'SELECT SUM(amount) AS total FROM contributions WHERE contribution_type = "donation"';
                    $result = mysqli_query($conn, $donations);
                    $row = mysqli_fetch_assoc($result);
                    echo $row['total'] ? number_format($row['total']) : 0;
                    ?> UGX</p>
                </div>
            </div>
            
            <div class="glass-card">
                <form method="GET" action="" class="contribution-filters">
                    <div class="filter-group">
                        <label>Date Range</label>
                        <select name="date_filter" class="form-control">
                            <option value="">All Dates</option>
                            <option value="this_month" <?php echo (isset($_GET['date_filter']) && $_GET['date_filter'] == 'this_month') ? 'selected' : ''; ?>>This Month</option>
                            <option value="last_month" <?php echo (isset($_GET['date_filter']) && $_GET['date_filter'] == 'last_month') ? 'selected' : ''; ?>>Last Month</option>
                            <option value="last_3_months" <?php echo (isset($_GET['date_filter']) && $_GET['date_filter'] == 'last_3_months') ? 'selected' : ''; ?>>Last 3 Months</option>
                            <option value="this_year" <?php echo (isset($_GET['date_filter']) && $_GET['date_filter'] == 'this_year') ? 'selected' : ''; ?>>This Year</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Contribution Type</label>
                        <select name="type_filter" class="form-control">
                            <option value="all">All Types</option>
                            <?php foreach($contribution_types as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo (isset($_GET['type_filter']) && $_GET['type_filter'] == $type) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-accent">Apply Filters</button>
                    </div>
                    <div class="filter-group">
                        <label>&nbsp;</label>
                        <a href="contributions" class="btn">Clear Filters</a>
                    </div>
                </form>
                
                <table class="contributions-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($contributions_result) > 0) {
                            while ($row = mysqli_fetch_assoc($contributions_result)) {
                                $type_class = strtolower($row['contribution_type']);
                                echo '<tr>';
                                echo ' <td>
                                        <div class="member-info">
                                            <div class="member-avatar" style="background-image: url(../public/images/user-logo.jpg)"></div>
                                            <div>
                                                <strong>' . $row['first_name'] . ' ' . $row['last_name'] . '</strong>
                                                <div style="font-size: 0.8rem; color: #666;">Member since ' . $row['date_joined'] . '</div>
                                            </div>
                                        </div>';
                                echo '</td>';
                                echo '<td>' . $row['contribution_date'] . '</td>';
                                echo '<td><span class="contribution-type ' . $type_class . '">' . ucfirst($row['contribution_type']) . '</span></td>';
                                echo '<td>' . number_format($row['amount']) . ' UGX</td>';
                                echo '<td>';
                                echo '<button class="btn btn-sm edit-btn" data-id="' . $row['id'] . '" data-member="' . $row['member_id'] . '" data-date="' . $row['contribution_date'] . '" data-type="' . $row['contribution_type'] . '" data-amount="' . $row['amount'] . '"><i class="fas fa-edit"></i></button>';
                                echo '<a href="?delete_id=' . $row['id'] . '" class="btn btn-sm" onclick="return confirm(\'Are you sure you want to delete this contribution?\')"><i class="fas fa-trash"></i></a>';
                                echo '<button class="btn btn-sm btn-accent receipt-btn" data-id="' . $row['id'] . '" data-member="' . $row['first_name'] . ' ' . $row['last_name'] . '" data-date="' . $row['contribution_date'] . '" data-type="' . $row['contribution_type'] . '" data-amount="' . $row['amount'] . '"><i class="fas fa-receipt"></i></button>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" style="text-align: center;">No contributions found.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <div>Showing <?php echo mysqli_num_rows($contributions_result); ?> contributions</div>
                    <div>
                        <!-- Pagination would go here -->
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
                <h2 id="modal-title">Record New Contribution</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="contribution-form" action="../includes/functions.php" method="POST">
                    <input type="hidden" name="contribution_id" id="contribution_id">
                    <div class="form-row">
                        <div class="form-group member-search">
                            <label>Member</label>
                            <select class="form-control" id="member-select" required name="member">
                                <option value="">Select member</option>
                                <?php
                                $members = 'SELECT * FROM members ORDER BY first_name, last_name';
                                $result = mysqli_query($conn, $members);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="'.$row['id'].'">'.$row['first_name'].' '.$row['last_name'].'</option>';
                                    }
                                } else {
                                    echo '<option value="">No members found</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input name="date" id="date-input" type="date" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Contribution Type</label>
                            <select name="type" id="type-select" class="form-control" required>
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
                            <input name="amount" id="amount-input" type="number" class="form-control" placeholder="Enter amount" required>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="cancel-contribution">Cancel</button>
                <button name="record_offering" class="btn btn-accent" id="save-contribution">Save Contribution</button>
            </div></form>
        </div>
    </div>
    
    <!-- Edit Contribution Modal -->
    <div class="modal" id="edit-contribution-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Contribution</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="edit-contribution-form" action="" method="POST">
                    <input type="hidden" name="contribution_id" id="edit-contribution-id">
                    <div class="form-row">
                        <div class="form-group member-search">
                            <label>Member</label>
                            <select class="form-control" id="edit-member-select" required name="member">
                                <option value="">Select member</option>
                                <?php
                                $members = 'SELECT * FROM members ORDER BY first_name, last_name';
                                $result = mysqli_query($conn, $members);
                                if (mysqli_num_rows($result) > 0) {
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="'.$row['id'].'">'.$row['first_name'].' '.$row['last_name'].'</option>';
                                    }
                                } else {
                                    echo '<option value="">No members found</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input name="date" id="edit-date-input" type="date" class="form-control" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Contribution Type</label>
                            <select name="type" id="edit-type-select" class="form-control" required>
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
                            <input name="amount" id="edit-amount-input" type="number" class="form-control" placeholder="Enter amount" required>
                        </div>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="cancel-edit">Cancel</button>
                <button name="edit_contribution" class="btn btn-accent">Update Contribution</button>
            </div></form>
        </div>
    </div>
    
    <!-- Receipt Modal -->
    <div class="modal receipt-modal" id="receipt-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Contribution Receipt</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="receipt-header">
                    <h3>St. Stephen C.O.U Church</h3>
                    <p>Contribution Receipt</p>
                </div>
                
                <div class="receipt-details">
                    <div class="receipt-row">
                        <span>Receipt No:</span>
                        <span id="receipt-number">STC-001</span>
                    </div>
                    <div class="receipt-row">
                        <span>Date:</span>
                        <span id="receipt-date"><?php echo date('Y-m-d'); ?></span>
                    </div>
                    <div class="receipt-row">
                        <span>Member Name:</span>
                        <span id="receipt-member">James Okello</span>
                    </div>
                    <div class="receipt-row">
                        <span>Contribution Type:</span>
                        <span id="receipt-type">Tithe</span>
                    </div>
                    <div class="receipt-row total">
                        <span>Amount:</span>
                        <span id="receipt-amount">50,000 UGX</span>
                    </div>
                </div>
                
                <div class="receipt-footer">
                    <p>Thank you for your generous contribution</p>
                    <p>May God bless you abundantly</p>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" id="close-receipt">Close</button>
                <button class="btn btn-accent" id="download-receipt">Download Receipt</button>
            </div>
        </div>
    </div>
    
    <script src="js/scripts.js"></script>
    <script>
        // Contributions specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Modal functionality
            const contributionModal = document.getElementById('contribution-modal');
            const editContributionModal = document.getElementById('edit-contribution-modal');
            const receiptModal = document.getElementById('receipt-modal');
            const newContributionBtn = document.getElementById('new-contribution-btn');
            const closeModalButtons = document.querySelectorAll('.close-modal');
            const cancelContribution = document.getElementById('cancel-contribution');
            const cancelEdit = document.getElementById('cancel-edit');
            const closeReceipt = document.getElementById('close-receipt');
            const downloadReceipt = document.getElementById('download-receipt');
            
            // Edit buttons
            const editButtons = document.querySelectorAll('.edit-btn');
            
            // Receipt buttons
            const receiptButtons = document.querySelectorAll('.receipt-btn');
            
            function openModal(modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
            
            function closeModal(modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            // New contribution modal
            newContributionBtn.addEventListener('click', function() {
                openModal(contributionModal);
            });
            
            // Edit contribution modal
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const member = this.getAttribute('data-member');
                    const date = this.getAttribute('data-date');
                    const type = this.getAttribute('data-type');
                    const amount = this.getAttribute('data-amount');
                    
                    document.getElementById('edit-contribution-id').value = id;
                    document.getElementById('edit-member-select').value = member;
                    document.getElementById('edit-date-input').value = date;
                    document.getElementById('edit-type-select').value = type;
                    document.getElementById('edit-amount-input').value = amount;
                    
                    openModal(editContributionModal);
                });
            });
            
            // Receipt modal
            receiptButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const member = this.getAttribute('data-member');
                    const date = this.getAttribute('data-date');
                    const type = this.getAttribute('data-type');
                    const amount = this.getAttribute('data-amount');
                    
                    document.getElementById('receipt-number').textContent = 'STC-' + id.toString().padStart(3, '0');
                    document.getElementById('receipt-date').textContent = date;
                    document.getElementById('receipt-member').textContent = member;
                    document.getElementById('receipt-type').textContent = type.charAt(0).toUpperCase() + type.slice(1);
                    document.getElementById('receipt-amount').textContent = parseInt(amount).toLocaleString() + ' UGX';
                    
                    openModal(receiptModal);
                });
            });
            
            // Close modals
            closeModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modal = this.closest('.modal');
                    closeModal(modal);
                });
            });
            
            cancelContribution.addEventListener('click', function() {
                closeModal(contributionModal);
            });
            
            cancelEdit.addEventListener('click', function() {
                closeModal(editContributionModal);
            });
            
            closeReceipt.addEventListener('click', function() {
                closeModal(receiptModal);
            });
            
            // Close modal when clicking outside
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModal(modal);
                    }
                });
            });
            
            // Download receipt functionality
            downloadReceipt.addEventListener('click', function() {
                // Create a printable version of the receipt
                const receiptContent = document.querySelector('.receipt-modal .modal-content').innerHTML;
                const originalContent = document.body.innerHTML;
                
                // Create a new window for printing
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                    <!DOCTYPE html>
                    <html>
                    <head>
                        <title>Contribution Receipt</title>
                        <style>
                            body { font-family: Arial, sans-serif; padding: 20px; }
                            .receipt-header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
                            .receipt-details { margin-bottom: 20px; }
                            .receipt-row { display: flex; justify-content: space-between; margin-bottom: 10px; padding-bottom: 5px; border-bottom: 1px dashed #eee; }
                            .receipt-row.total { font-weight: bold; border-bottom: 2px solid #333; padding-top: 10px; }
                            .receipt-footer { text-align: center; margin-top: 30px; font-style: italic; color: #666; }
                            @media print {
                                body { margin: 0; }
                                button { display: none; }
                            }
                        </style>
                    </head>
                    <body>
                        ${receiptContent}
                    </body>
                    </html>
                `);
                
                printWindow.document.close();
                printWindow.print();
            });
            
            // Form validation for contribution form
            document.getElementById('save-contribution').addEventListener('click', function(e) {
                const form = document.getElementById('contribution-form');
                if (!form.checkValidity()) {
                    e.preventDefault();
                    form.reportValidity();
                }
            });
        });
    </script>
</body>
</html>