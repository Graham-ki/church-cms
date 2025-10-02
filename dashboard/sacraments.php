<?php
include_once '../config/db.php';
?>
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
            flex-wrap: wrap;
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
            flex-grow: 1;
        }
        
        .record-name {
            font-weight: 500;
        }
        
        .record-date {
            font-size: 0.9rem;
            color: #666;
        }
        
        .record-officiant {
            font-size: 0.85rem;
            color: #888;
            margin-top: 3px;
        }
        
        .record-actions {
            display: flex;
            gap: 10px;
        }
        
        .calendar-icon {
            color: var(--primary-color);
            margin-right: 5px;
        }
        
        .no-records {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            margin: 20px 0;
        }
        
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 15px 0;
            border-top: 1px solid rgba(0, 0, 0, 0.1);
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
                
                <form class="sacrament-form" method="post" action="../includes/functions.php">
                    <div class="form-group">
                        <label>Sacrament Type</label>
                        <select class="form-control" name="sacrament_type" required>
                            <option value="">Select sacrament...</option>
                            <option value="Baptism">Baptism</option>
                            <option value="Communion">Holy Communion</option>
                            <option value="Confirmation">Confirmation</option>
                            <option value="Matrimony">Matrimony</option>
                            <option value="Holy Orders">Holy Orders</option>
                            <option value="Reconciliation">Reconciliation</option>
                            <option value="Anointing">Anointing of the Sick</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" name="sacrament_date" required>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Member</label>
                        <select class="form-control" name="member_id" required>
                            <option value="">Select member...</option>
                            <?php
                            $member_query = "SELECT id, first_name, last_name FROM members ORDER BY first_name, last_name";
                            $member_result = mysqli_query($conn, $member_query);
                            if(mysqli_num_rows($member_result) > 0) {
                                while ($member = mysqli_fetch_assoc($member_result)) {
                                    echo '<option value="' . $member['id'] . '">' . $member['first_name'] . ' ' . $member['last_name'] . '</option>';
                                }
                            } else {
                                echo '<option value="">No members found</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Officiant</label>
                        <input name="officiant" type="text" class="form-control" placeholder="Name of officiating minister">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Location</label>
                        <input name="location" type="text" class="form-control" placeholder="Where the sacrament took place">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about the sacrament"></textarea>
                    </div>
                    
                    <div class="form-group full-width" style="text-align: right;">
                        <button name="save-sacrament" type="submit" class="btn btn-accent"><i class="fas fa-check"></i> Save Record</button>
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
                    <div class="sacrament-tab">Holy Orders</div>
                    <div class="sacrament-tab">Reconciliation</div>
                    <div class="sacrament-tab">Anointing</div>
                </div>
                
                <!-- Overview Content -->
                <div id="overview-content">
                    <div class="sacrament-list">
                        <!-- Baptism Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Baptism</div>
                                <div class="sacrament-count"><?php
                                $count_query = "SELECT COUNT(*) AS count FROM sacraments WHERE type = 'Baptism'";
                                $count_result = mysqli_query($conn, $count_query);
                                $count_row = mysqli_fetch_assoc($count_result);
                                echo $count_row['count'];
                                ?></div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have received the sacrament of baptism.
                            </div>
                        </div>
                        
                        <!-- Communion Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Holy Communion</div>
                                <div class="sacrament-count"><?php
                                $count_query = "SELECT COUNT(*) AS count FROM sacraments WHERE type = 'Communion'";
                                $count_result = mysqli_query($conn, $count_query);
                                $count_row = mysqli_fetch_assoc($count_result);
                                echo $count_row['count'];
                                ?></div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have received holy communion.
                            </div>
                        </div>
                        
                        <!-- Confirmation Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Confirmation</div>
                                <div class="sacrament-count">
                                    <?php
                                    $count_query = "SELECT COUNT(*) AS count FROM sacraments WHERE type = 'Confirmation'";
                                    $count_result = mysqli_query($conn, $count_query);
                                    $count_row = mysqli_fetch_assoc($count_result);
                                    echo $count_row['count'];
                                    ?></div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have been confirmed in the faith.
                            </div>
                        </div>
                        
                        <!-- Matrimony Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Matrimony</div>
                                <div class="sacrament-count">
                                    <?php
                                    $count_query = "SELECT COUNT(*) AS count FROM sacraments WHERE type = 'Matrimony'";
                                    $count_result = mysqli_query($conn, $count_query);
                                    $count_row = mysqli_fetch_assoc($count_result);
                                    echo $count_row['count'];
                                    ?>
                                </div>
                            </div>
                            <div class="sacrament-details">
                                Records of marriages performed in the church.
                            </div>
                        </div>
                        
                        <!-- Holy Orders Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Holy Orders</div>
                                <div class="sacrament-count">
                                    <?php
                                    $count_query = "SELECT COUNT(*) AS count FROM sacraments WHERE type = 'Holy Orders'";
                                    $count_result = mysqli_query($conn, $count_query);
                                    $count_row = mysqli_fetch_assoc($count_result);
                                    echo $count_row['count'];
                                    ?>
                                </div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have received Holy Orders.
                            </div>
                        </div>
                        
                        <!-- Reconciliation Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Reconciliation</div>
                                <div class="sacrament-count">
                                    <?php
                                    $count_query = "SELECT COUNT(*) AS count FROM sacraments WHERE type = 'Reconciliation'";
                                    $count_result = mysqli_query($conn, $count_query);
                                    $count_row = mysqli_fetch_assoc($count_result);
                                    echo $count_row['count'];
                                    ?>
                                </div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have received the Sacrament of Reconciliation.
                            </div>
                        </div>
                        
                        <!-- Anointing Card -->
                        <div class="sacrament-card">
                            <div class="sacrament-header">
                                <div class="sacrament-title">Anointing of the Sick</div>
                                <div class="sacrament-count">
                                    <?php
                                    $count_query = "SELECT COUNT(*) AS count FROM sacraments WHERE type = 'Anointing'";
                                    $count_result = mysqli_query($conn, $count_query);
                                    $count_row = mysqli_fetch_assoc($count_result);
                                    echo $count_row['count'];
                                    ?>
                                </div>
                            </div>
                            <div class="sacrament-details">
                                Records of members who have received the Anointing of the Sick.
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Baptism Content -->
                <div id="baptism-content" style="display: none;">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <?php
                        $baptism_query = "SELECT s.*, m.first_name, m.last_name 
                                         FROM sacraments s 
                                         LEFT JOIN members m ON s.member_id = m.id 
                                         WHERE s.type = 'Baptism' 
                                         ORDER BY s.date DESC 
                                         LIMIT 10";
                        $baptism_result = mysqli_query($conn, $baptism_query);
                        
                        if(mysqli_num_rows($baptism_result) > 0) {
                            while($record = mysqli_fetch_assoc($baptism_result)) {
                                echo '<div class="sacrament-record">';
                                echo '<div class="record-info">';
                                echo '<div class="record-name">' . $record['first_name'] . ' ' . $record['last_name'] . '</div>';
                                echo '<div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> ' . date('F j, Y', strtotime($record['date'])) . '</div>';
                                if(!empty($record['officiated_by'])) {
                                    echo '<div class="record-officiant"><i class="fas fa-user-tie calendar-icon"></i> ' . $record['officiated_by'] . '</div>';
                                }
                                echo '</div>';
                                echo '<div class="record-actions">';
                                echo '<button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>';
                                echo '<button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="no-records">No baptism records found</div>';
                        }
                        ?>
                    </div>
                    
                    <div class="pagination">
                        <div>Showing 1-10 of <?php
                        $total_query = "SELECT COUNT(*) AS total FROM sacraments WHERE type = 'Baptism'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        echo $total_row['total'];
                        ?> records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Communion Content -->
                <div id="communion-content" style="display: none;">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <?php
                        $communion_query = "SELECT s.*, m.first_name, m.last_name 
                                           FROM sacraments s 
                                           LEFT JOIN members m ON s.member_id = m.id 
                                           WHERE s.type = 'Communion' 
                                           ORDER BY s.date DESC 
                                           LIMIT 10";
                        $communion_result = mysqli_query($conn, $communion_query);
                        
                        if(mysqli_num_rows($communion_result) > 0) {
                            while($record = mysqli_fetch_assoc($communion_result)) {
                                echo '<div class="sacrament-record">';
                                echo '<div class="record-info">';
                                echo '<div class="record-name">' . $record['first_name'] . ' ' . $record['last_name'] . '</div>';
                                echo '<div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> ' . date('F j, Y', strtotime($record['date'])) . '</div>';
                                if(!empty($record['officiated_by'])) {
                                    echo '<div class="record-officiant"><i class="fas fa-user-tie calendar-icon"></i> ' . $record['officiated_by'] . '</div>';
                                }
                                echo '</div>';
                                echo '<div class="record-actions">';
                                echo '<button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>';
                                echo '<button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="no-records">No communion records found</div>';
                        }
                        ?>
                    </div>
                    
                    <div class="pagination">
                        <div>Showing 1-10 of <?php
                        $total_query = "SELECT COUNT(*) AS total FROM sacraments WHERE type = 'Communion'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        echo $total_row['total'];
                        ?> records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Confirmation Content -->
                <div id="confirmation-content" style="display: none;">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <?php
                        $confirmation_query = "SELECT s.*, m.first_name, m.last_name 
                                              FROM sacraments s 
                                              LEFT JOIN members m ON s.member_id = m.id 
                                              WHERE s.type = 'Confirmation' 
                                              ORDER BY s.date DESC 
                                              LIMIT 10";
                        $confirmation_result = mysqli_query($conn, $confirmation_query);
                        
                        if(mysqli_num_rows($confirmation_result) > 0) {
                            while($record = mysqli_fetch_assoc($confirmation_result)) {
                                echo '<div class="sacrament-record">';
                                echo '<div class="record-info">';
                                echo '<div class="record-name">' . $record['first_name'] . ' ' . $record['last_name'] . '</div>';
                                echo '<div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> ' . date('F j, Y', strtotime($record['date'])) . '</div>';
                                if(!empty($record['officiated_by'])) {
                                    echo '<div class="record-officiant"><i class="fas fa-user-tie calendar-icon"></i> ' . $record['officiated_by'] . '</div>';
                                }
                                echo '</div>';
                                echo '<div class="record-actions">';
                                echo '<button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>';
                                echo '<button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="no-records">No confirmation records found</div>';
                        }
                        ?>
                    </div>
                    
                    <div class="pagination">
                        <div>Showing 1-10 of <?php
                        $total_query = "SELECT COUNT(*) AS total FROM sacraments WHERE type = 'Confirmation'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        echo $total_row['total'];
                        ?> records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Matrimony Content -->
                <div id="matrimony-content" style="display: none;">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <?php
                        $matrimony_query = "SELECT s.*, m.first_name, m.last_name 
                                           FROM sacraments s 
                                           LEFT JOIN members m ON s.member_id = m.id 
                                           WHERE s.type = 'Matrimony' 
                                           ORDER BY s.date DESC 
                                           LIMIT 10";
                        $matrimony_result = mysqli_query($conn, $matrimony_query);
                        
                        if(mysqli_num_rows($matrimony_result) > 0) {
                            while($record = mysqli_fetch_assoc($matrimony_result)) {
                                echo '<div class="sacrament-record">';
                                echo '<div class="record-info">';
                                echo '<div class="record-name">' . $record['first_name'] . ' ' . $record['last_name'] . '</div>';
                                echo '<div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> ' . date('F j, Y', strtotime($record['date'])) . '</div>';
                                if(!empty($record['officiated_by'])) {
                                    echo '<div class="record-officiant"><i class="fas fa-user-tie calendar-icon"></i> ' . $record['officiated_by'] . '</div>';
                                }
                                echo '</div>';
                                echo '<div class="record-actions">';
                                echo '<button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>';
                                echo '<button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="no-records">No matrimony records found</div>';
                        }
                        ?>
                    </div>
                    
                    <div class="pagination">
                        <div>Showing 1-10 of <?php
                        $total_query = "SELECT COUNT(*) AS total FROM sacraments WHERE type = 'Matrimony'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        echo $total_row['total'];
                        ?> records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Holy Orders Content -->
                <div id="holy-orders-content" style="display: none;">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <?php
                        $holy_orders_query = "SELECT s.*, m.first_name, m.last_name 
                                             FROM sacraments s 
                                             LEFT JOIN members m ON s.member_id = m.id 
                                             WHERE s.type = 'Holy Orders' 
                                             ORDER BY s.date DESC 
                                             LIMIT 10";
                        $holy_orders_result = mysqli_query($conn, $holy_orders_query);
                        
                        if(mysqli_num_rows($holy_orders_result) > 0) {
                            while($record = mysqli_fetch_assoc($holy_orders_result)) {
                                echo '<div class="sacrament-record">';
                                echo '<div class="record-info">';
                                echo '<div class="record-name">' . $record['first_name'] . ' ' . $record['last_name'] . '</div>';
                                echo '<div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> ' . date('F j, Y', strtotime($record['date'])) . '</div>';
                                if(!empty($record['officiated_by'])) {
                                    echo '<div class="record-officiant"><i class="fas fa-user-tie calendar-icon"></i> ' . $record['officiated_by'] . '</div>';
                                }
                                echo '</div>';
                                echo '<div class="record-actions">';
                                echo '<button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>';
                                echo '<button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="no-records">No holy orders records found</div>';
                        }
                        ?>
                    </div>
                    
                    <div class="pagination">
                        <div>Showing 1-10 of <?php
                        $total_query = "SELECT COUNT(*) AS total FROM sacraments WHERE type = 'Holy Orders'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        echo $total_row['total'];
                        ?> records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Reconciliation Content -->
                <div id="reconciliation-content" style="display: none;">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <?php
                        $reconciliation_query = "SELECT s.*, m.first_name, m.last_name 
                                               FROM sacraments s 
                                               LEFT JOIN members m ON s.member_id = m.id 
                                               WHERE s.type = 'Reconciliation' 
                                               ORDER BY s.date DESC 
                                               LIMIT 10";
                        $reconciliation_result = mysqli_query($conn, $reconciliation_query);
                        
                        if(mysqli_num_rows($reconciliation_result) > 0) {
                            while($record = mysqli_fetch_assoc($reconciliation_result)) {
                                echo '<div class="sacrament-record">';
                                echo '<div class="record-info">';
                                echo '<div class="record-name">' . $record['first_name'] . ' ' . $record['last_name'] . '</div>';
                                echo '<div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> ' . date('F j, Y', strtotime($record['date'])) . '</div>';
                                if(!empty($record['officiated_by'])) {
                                    echo '<div class="record-officiant"><i class="fas fa-user-tie calendar-icon"></i> ' . $record['officiated_by'] . '</div>';
                                }
                                echo '</div>';
                                echo '<div class="record-actions">';
                                echo '<button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>';
                                echo '<button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="no-records">No reconciliation records found</div>';
                        }
                        ?>
                    </div>
                    
                    <div class="pagination">
                        <div>Showing 1-10 of <?php
                        $total_query = "SELECT COUNT(*) AS total FROM sacraments WHERE type = 'Reconciliation'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        echo $total_row['total'];
                        ?> records</div>
                        <div>
                            <button class="btn btn-sm" style="margin-right: 5px;">Previous</button>
                            <button class="btn btn-sm btn-accent">Next</button>
                        </div>
                    </div>
                </div>
                
                <!-- Anointing Content -->
                <div id="anointing-content" style="display: none;">
                    <div class="actions" style="margin-bottom: 15px;">
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                        <button class="btn btn-sm"><i class="fas fa-print"></i> Print</button>
                    </div>
                    
                    <div class="sacrament-records">
                        <?php
                        $anointing_query = "SELECT s.*, m.first_name, m.last_name 
                                          FROM sacraments s 
                                          LEFT JOIN members m ON s.member_id = m.id 
                                          WHERE s.type = 'Anointing' 
                                          ORDER BY s.date DESC 
                                          LIMIT 10";
                        $anointing_result = mysqli_query($conn, $anointing_query);
                        
                        if(mysqli_num_rows($anointing_result) > 0) {
                            while($record = mysqli_fetch_assoc($anointing_result)) {
                                echo '<div class="sacrament-record">';
                                echo '<div class="record-info">';
                                echo '<div class="record-name">' . $record['first_name'] . ' ' . $record['last_name'] . '</div>';
                                echo '<div class="record-date"><i class="fas fa-calendar-alt calendar-icon"></i> ' . date('F j, Y', strtotime($record['date'])) . '</div>';
                                if(!empty($record['officiated_by'])) {
                                    echo '<div class="record-officiant"><i class="fas fa-user-tie calendar-icon"></i> ' . $record['officiated_by'] . '</div>';
                                }
                                echo '</div>';
                                echo '<div class="record-actions">';
                                echo '<button class="btn-icon" title="Edit"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon" title="Delete"><i class="far fa-trash-alt"></i></button>';
                                echo '<button class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></button>';
                                echo '</div>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="no-records">No anointing records found</div>';
                        }
                        ?>
                    </div>
                    
                    <div class="pagination">
                        <div>Showing 1-10 of <?php
                        $total_query = "SELECT COUNT(*) AS total FROM sacraments WHERE type = 'Anointing'";
                        $total_result = mysqli_query($conn, $total_query);
                        $total_row = mysqli_fetch_assoc($total_result);
                        echo $total_row['total'];
                        ?> records</div>
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
                'baptism': document.getElementById('baptism-content'),
                'communion': document.getElementById('communion-content'),
                'confirmation': document.getElementById('confirmation-content'),
                'matrimony': document.getElementById('matrimony-content'),
                'holy orders': document.getElementById('holy-orders-content'),
                'reconciliation': document.getElementById('reconciliation-content'),
                'anointing': document.getElementById('anointing-content')
            };
            
            sacramentTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    // Remove active class from all tabs
                    sacramentTabs.forEach(t => t.classList.remove('active'));
                    
                    // Add active class to clicked tab
                    this.classList.add('active');
                    
                    // Hide all content sections
                    Object.values(contentSections).forEach(section => {
                        if(section) section.style.display = 'none';
                    });
                    
                    // Show the appropriate content section
                    const tabText = this.textContent.trim().toLowerCase();
                    if (contentSections[tabText]) {
                        contentSections[tabText].style.display = 'block';
                    }
                });
            });
            
            // Initialize date picker for sacrament date
            const today = new Date().toISOString().split('T')[0];
            document.querySelector('input[type="date"]').value = today;
        });
    </script>
</body>
</html>