<?php
include_once '../config/db.php';

// Certificate generation functions (define them at the top)
function generateBaptismCertificate($data) {
    $fullName = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
    $date = date('F j, Y', strtotime($data['date']));
    $officiant = htmlspecialchars($data['officiated_by'] ?: 'Not specified');
    $location = htmlspecialchars($data['location'] ?: 'St. Stephen C.O.U Church');
    $notes = htmlspecialchars($data['notes'] ?: 'No additional notes');
    
    return "
        <div class='certificate-header'>
            <div class='certificate-title'>Certificate of Baptism</div>
            <div class='certificate-subtitle'>St. Stephen C.O.U Church</div>
        </div>
        <div class='certificate-body'>
            <div class='certificate-text'>
                This certifies that
            </div>
            <div class='certificate-name'>{$fullName}</div>
            <div class='certificate-text'>
                was baptized according to the rites of the Church
            </div>
            <div class='certificate-details'>
                <div class='certificate-detail'><strong>Date of Baptism:</strong> {$date}</div>
                <div class='certificate-detail'><strong>Officiating Minister:</strong> {$officiant}</div>
                <div class='certificate-detail'><strong>Location:</strong> {$location}</div>
                <div class='certificate-detail'><strong>Additional Notes:</strong> {$notes}</div>
            </div>
        </div>
        <div class='certificate-footer'>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Officiating Minister</div>
            </div>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Church Seal</div>
            </div>
        </div>
    ";
}

function generateCommunionCertificate($data) {
    $fullName = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
    $date = date('F j, Y', strtotime($data['date']));
    $officiant = htmlspecialchars($data['officiated_by'] ?: 'Not specified');
    $location = htmlspecialchars($data['location'] ?: 'St. Stephen C.O.U Church');
    
    return "
        <div class='certificate-header'>
            <div class='certificate-title'>Certificate of Holy Communion</div>
            <div class='certificate-subtitle'>St. Stephen C.O.U Church</div>
        </div>
        <div class='certificate-body'>
            <div class='certificate-text'>
                This certifies that
            </div>
            <div class='certificate-name'>{$fullName}</div>
            <div class='certificate-text'>
                has received the Sacrament of Holy Communion
            </div>
            <div class='certificate-details'>
                <div class='certificate-detail'><strong>Date:</strong> {$date}</div>
                <div class='certificate-detail'><strong>Officiating Minister:</strong> {$officiant}</div>
                <div class='certificate-detail'><strong>Location:</strong> {$location}</div>
            </div>
        </div>
        <div class='certificate-footer'>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Officiating Minister</div>
            </div>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Church Seal</div>
            </div>
        </div>
    ";
}

function generateConfirmationCertificate($data) {
    $fullName = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
    $date = date('F j, Y', strtotime($data['date']));
    $officiant = htmlspecialchars($data['officiated_by'] ?: 'Not specified');
    $location = htmlspecialchars($data['location'] ?: 'St. Stephen C.O.U Church');
    
    return "
        <div class='certificate-header'>
            <div class='certificate-title'>Certificate of Confirmation</div>
            <div class='certificate-subtitle'>St. Stephen C.O.U Church</div>
        </div>
        <div class='certificate-body'>
            <div class='certificate-text'>
                This certifies that
            </div>
            <div class='certificate-name'>{$fullName}</div>
            <div class='certificate-text'>
                has been confirmed in the faith and received the gift of the Holy Spirit
            </div>
            <div class='certificate-details'>
                <div class='certificate-detail'><strong>Date of Confirmation:</strong> {$date}</div>
                <div class='certificate-detail'><strong>Confirming Bishop:</strong> {$officiant}</div>
                <div class='certificate-detail'><strong>Location:</strong> {$location}</div>
            </div>
        </div>
        <div class='certificate-footer'>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Confirming Bishop</div>
            </div>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Church Seal</div>
            </div>
        </div>
    ";
}

function generateMatrimonyCertificate($data) {
    $fullName = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
    $date = date('F j, Y', strtotime($data['date']));
    $officiant = htmlspecialchars($data['officiated_by'] ?: 'Not specified');
    $location = htmlspecialchars($data['location'] ?: 'St. Stephen C.O.U Church');
    
    return "
        <div class='certificate-header'>
            <div class='certificate-title'>Certificate of Matrimony</div>
            <div class='certificate-subtitle'>St. Stephen C.O.U Church</div>
        </div>
        <div class='certificate-body'>
            <div class='certificate-text'>
                This certifies that
            </div>
            <div class='certificate-name'>{$fullName}</div>
            <div class='certificate-text'>
                was united in Holy Matrimony according to the rites of the Church
            </div>
            <div class='certificate-details'>
                <div class='certificate-detail'><strong>Date of Marriage:</strong> {$date}</div>
                <div class='certificate-detail'><strong>Officiating Minister:</strong> {$officiant}</div>
                <div class='certificate-detail'><strong>Location:</strong> {$location}</div>
            </div>
        </div>
        <div class='certificate-footer'>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Officiating Minister</div>
            </div>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Church Seal</div>
            </div>
        </div>
    ";
}

function generateHolyOrdersCertificate($data) {
    $fullName = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
    $date = date('F j, Y', strtotime($data['date']));
    $officiant = htmlspecialchars($data['officiated_by'] ?: 'Not specified');
    $location = htmlspecialchars($data['location'] ?: 'St. Stephen C.O.U Church');
    
    return "
        <div class='certificate-header'>
            <div class='certificate-title'>Certificate of Holy Orders</div>
            <div class='certificate-subtitle'>St. Stephen C.O.U Church</div>
        </div>
        <div class='certificate-body'>
            <div class='certificate-text'>
                This certifies that
            </div>
            <div class='certificate-name'>{$fullName}</div>
            <div class='certificate-text'>
                has received the Sacrament of Holy Orders
            </div>
            <div class='certificate-details'>
                <div class='certificate-detail'><strong>Date of Ordination:</strong> {$date}</div>
                <div class='certificate-detail'><strong>Ordaining Bishop:</strong> {$officiant}</div>
                <div class='certificate-detail'><strong>Location:</strong> {$location}</div>
            </div>
        </div>
        <div class='certificate-footer'>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Ordaining Bishop</div>
            </div>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Church Seal</div>
            </div>
        </div>
    ";
}

function generateReconciliationCertificate($data) {
    $fullName = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
    $date = date('F j, Y', strtotime($data['date']));
    $officiant = htmlspecialchars($data['officiated_by'] ?: 'Not specified');
    $location = htmlspecialchars($data['location'] ?: 'St. Stephen C.O.U Church');
    
    return "
        <div class='certificate-header'>
            <div class='certificate-title'>Certificate of Reconciliation</div>
            <div class='certificate-subtitle'>St. Stephen C.O.U Church</div>
        </div>
        <div class='certificate-body'>
            <div class='certificate-text'>
                This certifies that
            </div>
            <div class='certificate-name'>{$fullName}</div>
            <div class='certificate-text'>
                has received the Sacrament of Reconciliation
            </div>
            <div class='certificate-details'>
                <div class='certificate-detail'><strong>Date:</strong> {$date}</div>
                <div class='certificate-detail'><strong>Confessor:</strong> {$officiant}</div>
                <div class='certificate-detail'><strong>Location:</strong> {$location}</div>
            </div>
        </div>
        <div class='certificate-footer'>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Confessor</div>
            </div>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Church Seal</div>
            </div>
        </div>
    ";
}

function generateAnointingCertificate($data) {
    $fullName = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
    $date = date('F j, Y', strtotime($data['date']));
    $officiant = htmlspecialchars($data['officiated_by'] ?: 'Not specified');
    $location = htmlspecialchars($data['location'] ?: 'St. Stephen C.O.U Church');
    
    return "
        <div class='certificate-header'>
            <div class='certificate-title'>Certificate of Anointing</div>
            <div class='certificate-subtitle'>St. Stephen C.O.U Church</div>
        </div>
        <div class='certificate-body'>
            <div class='certificate-text'>
                This certifies that
            </div>
            <div class='certificate-name'>{$fullName}</div>
            <div class='certificate-text'>
                has received the Anointing of the Sick
            </div>
            <div class='certificate-details'>
                <div class='certificate-detail'><strong>Date:</strong> {$date}</div>
                <div class='certificate-detail'><strong>Officiating Minister:</strong> {$officiant}</div>
                <div class='certificate-detail'><strong>Location:</strong> {$location}</div>
            </div>
        </div>
        <div class='certificate-footer'>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Officiating Minister</div>
            </div>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Church Seal</div>
            </div>
        </div>
    ";
}

function generateGenericCertificate($data) {
    $fullName = htmlspecialchars($data['first_name'] . ' ' . $data['last_name']);
    $date = date('F j, Y', strtotime($data['date']));
    $officiant = htmlspecialchars($data['officiated_by'] ?: 'Not specified');
    $location = htmlspecialchars($data['location'] ?: 'St. Stephen C.O.U Church');
    $notes = htmlspecialchars($data['notes'] ?: 'No additional notes');
    
    return "
        <div class='certificate-header'>
            <div class='certificate-title'>Certificate of " . htmlspecialchars($data['type']) . "</div>
            <div class='certificate-subtitle'>St. Stephen C.O.U Church</div>
        </div>
        <div class='certificate-body'>
            <div class='certificate-text'>
                This certifies that
            </div>
            <div class='certificate-name'>{$fullName}</div>
            <div class='certificate-text'>
                has received the Sacrament of " . htmlspecialchars($data['type']) . "
            </div>
            <div class='certificate-details'>
                <div class='certificate-detail'><strong>Date:</strong> {$date}</div>
                <div class='certificate-detail'><strong>Officiating Minister:</strong> {$officiant}</div>
                <div class='certificate-detail'><strong>Location:</strong> {$location}</div>
                <div class='certificate-detail'><strong>Additional Notes:</strong> {$notes}</div>
            </div>
        </div>
        <div class='certificate-footer'>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Officiating Minister</div>
            </div>
            <div class='certificate-signature'>
                <div class='signature-line'></div>
                <div>Church Seal</div>
            </div>
        </div>
    ";
}

// Handle delete action
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_query = "DELETE FROM sacraments WHERE id = ?";
    $stmt = mysqli_prepare($conn, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $delete_id);
    mysqli_stmt_execute($stmt);
    
    // Redirect to avoid resubmission on refresh
    header("Location: sacraments");
    exit();
}

// Handle edit form submission
if (isset($_POST['update-sacrament'])) {
    $sacrament_id = $_POST['sacrament_id'];
    $sacrament_type = $_POST['sacrament_type'];
    $sacrament_date = $_POST['sacrament_date'];
    $member_id = $_POST['member_id'];
    $officiant = $_POST['officiant'];
    $location = $_POST['location'];
    $notes = $_POST['notes'];
    
    $update_query = "UPDATE sacraments SET type = ?, date = ?, member_id = ?, officiated_by = ?, location = ?, notes = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, "ssisssi", $sacrament_type, $sacrament_date, $member_id, $officiant, $location, $notes, $sacrament_id);
    mysqli_stmt_execute($stmt);
    
    // Redirect to avoid resubmission on refresh
    header("Location: sacraments");
    exit();
}

// Fetch sacrament data for editing
$edit_sacrament = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $edit_query = "SELECT * FROM sacraments WHERE id = ?";
    $stmt = mysqli_prepare($conn, $edit_query);
    mysqli_stmt_bind_param($stmt, "i", $edit_id);
    mysqli_stmt_execute($stmt);
    $edit_result = mysqli_stmt_get_result($stmt);
    $edit_sacrament = mysqli_fetch_assoc($edit_result);
}

// Fetch certificate data when requested
$certificate_data = null;
if (isset($_GET['certificate_id'])) {
    $certificate_id = $_GET['certificate_id'];
    $certificate_query = "SELECT s.*, m.first_name, m.last_name, m.date_of_birth, m.gender 
                         FROM sacraments s 
                         LEFT JOIN members m ON s.member_id = m.id 
                         WHERE s.id = ?";
    $stmt = mysqli_prepare($conn, $certificate_query);
    mysqli_stmt_bind_param($stmt, "i", $certificate_id);
    mysqli_stmt_execute($stmt);
    $certificate_result = mysqli_stmt_get_result($stmt);
    $certificate_data = mysqli_fetch_assoc($certificate_result);
}
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
        
        /* Certificate Modal Styles */
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
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        
        .certificate {
            border: 2px solid #8B4513;
            padding: 40px;
            background-color: #f9f5e9;
            font-family: 'Times New Roman', serif;
            text-align: center;
            position: relative;
        }
        
        .certificate-header {
            margin-bottom: 30px;
        }
        
        .certificate-title {
            font-size: 2.5rem;
            font-weight: bold;
            color: #8B4513;
            margin-bottom: 10px;
        }
        
        .certificate-subtitle {
            font-size: 1.2rem;
            color: #8B4513;
            margin-bottom: 30px;
        }
        
        .certificate-body {
            margin-bottom: 40px;
        }
        
        .certificate-text {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        
        .certificate-name {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 20px 0;
            color: #8B4513;
        }
        
        .certificate-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 30px 0;
            text-align: left;
        }
        
        .certificate-detail {
            margin-bottom: 10px;
        }
        
        .certificate-footer {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .certificate-signature {
            text-align: center;
            width: 45%;
        }
        
        .signature-line {
            border-top: 1px solid #8B4513;
            margin-top: 60px;
            margin-bottom: 10px;
        }
        
        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }
        
        .btn-print {
            background-color: #4a6fa5;
            color: white;
        }
        
        .btn-close {
            background-color: #6c757d;
            color: white;
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            .modal, .modal * {
                visibility: visible;
            }
            .modal {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background-color: white;
            }
            .modal-actions {
                display: none;
            }
        }
        
        /* Confirmation Modal */
        .confirmation-modal {
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
        
        .confirmation-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            text-align: center;
        }
        
        .confirmation-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }
        
        .btn-danger {
            background-color: #dc3545;
            color: white;
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
            
            <!-- Add/Edit Sacrament Section (Initially Hidden) -->
            <div class="glass-card" id="add-sacrament-section" style="display: <?php echo (isset($_GET['edit_id']) || isset($_POST['save-sacrament'])) ? 'block' : 'none'; ?>;">
                <div class="card-header">
                    <h2 class="card-title"><?php echo isset($_GET['edit_id']) ? 'Edit Sacrament Record' : 'Add Sacrament Record'; ?></h2>
                    <button class="btn btn-sm" id="cancel-add-sacrament">Cancel</button>
                </div>
                
                <form class="sacrament-form" method="post" action="../includes/functions.php">
                    <?php if (isset($_GET['edit_id'])): ?>
                        <input type="hidden" name="sacrament_id" value="<?php echo $edit_sacrament['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Sacrament Type</label>
                        <select class="form-control" name="sacrament_type" required>
                            <option value="">Select sacrament...</option>
                            <option value="Baptism" <?php echo (isset($edit_sacrament) && $edit_sacrament['type'] == 'Baptism') ? 'selected' : ''; ?>>Baptism</option>
                            <option value="Communion" <?php echo (isset($edit_sacrament) && $edit_sacrament['type'] == 'Communion') ? 'selected' : ''; ?>>Holy Communion</option>
                            <option value="Confirmation" <?php echo (isset($edit_sacrament) && $edit_sacrament['type'] == 'Confirmation') ? 'selected' : ''; ?>>Confirmation</option>
                            <option value="Matrimony" <?php echo (isset($edit_sacrament) && $edit_sacrament['type'] == 'Matrimony') ? 'selected' : ''; ?>>Matrimony</option>
                            <option value="Holy Orders" <?php echo (isset($edit_sacrament) && $edit_sacrament['type'] == 'Holy Orders') ? 'selected' : ''; ?>>Holy Orders</option>
                            <option value="Reconciliation" <?php echo (isset($edit_sacrament) && $edit_sacrament['type'] == 'Reconciliation') ? 'selected' : ''; ?>>Reconciliation</option>
                            <option value="Anointing" <?php echo (isset($edit_sacrament) && $edit_sacrament['type'] == 'Anointing') ? 'selected' : ''; ?>>Anointing of the Sick</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" class="form-control" name="sacrament_date" value="<?php echo isset($edit_sacrament) ? $edit_sacrament['date'] : date('Y-m-d'); ?>" required>
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
                                    $selected = (isset($edit_sacrament) && $edit_sacrament['member_id'] == $member['id']) ? 'selected' : '';
                                    echo '<option value="' . $member['id'] . '" ' . $selected . '>' . $member['first_name'] . ' ' . $member['last_name'] . '</option>';
                                }
                            } else {
                                echo '<option value="">No members found</option>';
                            }
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Officiant</label>
                        <input name="officiant" type="text" class="form-control" placeholder="Name of officiating minister" value="<?php echo isset($edit_sacrament) ? $edit_sacrament['officiated_by'] : ''; ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Location</label>
                        <input name="location" type="text" class="form-control" placeholder="Where the sacrament took place" value="<?php echo isset($edit_sacrament) ? $edit_sacrament['location'] : ''; ?>">
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Notes</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes about the sacrament"><?php echo isset($edit_sacrament) ? $edit_sacrament['notes'] : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group full-width" style="text-align: right;">
                        <?php if (isset($_GET['edit_id'])): ?>
                            <button name="update-sacrament" type="submit" class="btn btn-accent"><i class="fas fa-check"></i> Update Record</button>
                        <?php else: ?>
                            <button name="save-sacrament" type="submit" class="btn btn-accent"><i class="fas fa-check"></i> Save Record</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            
            <!-- Certificate Display (if certificate_id is set) -->
            <?php if (isset($_GET['certificate_id']) && $certificate_data): ?>
            <div class="glass-card" id="certificate-section">
                <div class="card-header">
                    <h2 class="card-title">Certificate of <?php echo htmlspecialchars($certificate_data['type']); ?></h2>
                    <button class="btn btn-sm" onclick="window.location.href='sacraments'">Close</button>
                </div>
                <div class="certificate">
                    <?php
                    // Generate certificate based on sacrament type
                    switch($certificate_data['type']) {
                        case 'Baptism':
                            echo generateBaptismCertificate($certificate_data);
                            break;
                        case 'Communion':
                            echo generateCommunionCertificate($certificate_data);
                            break;
                        case 'Confirmation':
                            echo generateConfirmationCertificate($certificate_data);
                            break;
                        case 'Matrimony':
                            echo generateMatrimonyCertificate($certificate_data);
                            break;
                        case 'Holy Orders':
                            echo generateHolyOrdersCertificate($certificate_data);
                            break;
                        case 'Reconciliation':
                            echo generateReconciliationCertificate($certificate_data);
                            break;
                        case 'Anointing':
                            echo generateAnointingCertificate($certificate_data);
                            break;
                        default:
                            echo generateGenericCertificate($certificate_data);
                    }
                    ?>
                </div>
                <div class="modal-actions">
                    <button class="btn btn-print" onclick="window.print()"><i class="fas fa-print"></i> Print Certificate</button>
                    <button class="btn btn-close" onclick="window.location.href='sacraments'"><i class="fas fa-times"></i> Close</button>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Sacraments Overview -->
            <div class="glass-card" <?php echo (isset($_GET['certificate_id'])) ? 'style="display:none;"' : ''; ?>>
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
                                echo '<button class="btn-icon edit-btn" title="Edit" data-id="' . $record['id'] . '"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon delete-btn" title="Delete" data-id="' . $record['id'] . '" data-name="' . $record['first_name'] . ' ' . $record['last_name'] . '"><i class="far fa-trash-alt"></i></button>';
                                echo '<a href="sacraments?certificate_id=' . $record['id'] . '" class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></a>';
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
                                echo '<button class="btn-icon edit-btn" title="Edit" data-id="' . $record['id'] . '"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon delete-btn" title="Delete" data-id="' . $record['id'] . '" data-name="' . $record['first_name'] . ' ' . $record['last_name'] . '"><i class="far fa-trash-alt"></i></button>';
                                echo '<a href="sacraments?certificate_id=' . $record['id'] . '" class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></a>';
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
                                echo '<button class="btn-icon edit-btn" title="Edit" data-id="' . $record['id'] . '"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon delete-btn" title="Delete" data-id="' . $record['id'] . '" data-name="' . $record['first_name'] . ' ' . $record['last_name'] . '"><i class="far fa-trash-alt"></i></button>';
                                echo '<a href="sacraments?certificate_id=' . $record['id'] . '" class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></a>';
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
                                echo '<button class="btn-icon edit-btn" title="Edit" data-id="' . $record['id'] . '"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon delete-btn" title="Delete" data-id="' . $record['id'] . '" data-name="' . $record['first_name'] . ' ' . $record['last_name'] . '"><i class="far fa-trash-alt"></i></button>';
                                echo '<a href="sacraments?certificate_id=' . $record['id'] . '" class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></a>';
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
                                echo '<button class="btn-icon edit-btn" title="Edit" data-id="' . $record['id'] . '"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon delete-btn" title="Delete" data-id="' . $record['id'] . '" data-name="' . $record['first_name'] . ' ' . $record['last_name'] . '"><i class="far fa-trash-alt"></i></button>';
                                echo '<a href="sacraments?certificate_id=' . $record['id'] . '" class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></a>';
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
                                echo '<button class="btn-icon edit-btn" title="Edit" data-id="' . $record['id'] . '"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon delete-btn" title="Delete" data-id="' . $record['id'] . '" data-name="' . $record['first_name'] . ' ' . $record['last_name'] . '"><i class="far fa-trash-alt"></i></button>';
                                echo '<a href="sacraments?certificate_id=' . $record['id'] . '" class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></a>';
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
                                echo '<button class="btn-icon edit-btn" title="Edit" data-id="' . $record['id'] . '"><i class="far fa-edit"></i></button>';
                                echo '<button class="btn-icon delete-btn" title="Delete" data-id="' . $record['id'] . '" data-name="' . $record['first_name'] . ' ' . $record['last_name'] . '"><i class="far fa-trash-alt"></i></button>';
                                echo '<a href="sacraments?certificate_id=' . $record['id'] . '" class="btn-icon" title="View Certificate"><i class="far fa-file-alt"></i></a>';
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
    
    <!-- Delete Confirmation Modal -->
    <div class="confirmation-modal" id="delete-confirmation">
        <div class="confirmation-content">
            <h3>Confirm Deletion</h3>
            <p>Are you sure you want to delete the sacrament record for <span id="delete-name"></span>?</p>
            <div class="confirmation-actions">
                <button class="btn btn-danger" id="confirm-delete">Delete</button>
                <button class="btn" id="cancel-delete">Cancel</button>
            </div>
        </div>
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
            
            if (addSacramentBtn && addSacramentSection) {
                addSacramentBtn.addEventListener('click', function() {
                    addSacramentSection.style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
            
            if (cancelAddSacramentBtn && addSacramentSection) {
                cancelAddSacramentBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    addSacramentSection.style.display = 'none';
                    // Remove edit parameter from URL if present
                    if (window.location.search.includes('edit_id')) {
                        window.history.replaceState({}, document.title, window.location.pathname);
                    }
                });
            }
            
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
            const dateInput = document.querySelector('input[name="sacrament_date"]');
            if (dateInput && !dateInput.value) {
                const today = new Date().toISOString().split('T')[0];
                dateInput.value = today;
            }
            
            // Edit button functionality
            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const recordId = this.getAttribute('data-id');
                    window.location.href = 'sacraments?edit_id=' + recordId;
                });
            });
            
            // Delete button functionality
            const deleteButtons = document.querySelectorAll('.delete-btn');
            const deleteConfirmation = document.getElementById('delete-confirmation');
            const deleteNameSpan = document.getElementById('delete-name');
            const confirmDeleteBtn = document.getElementById('confirm-delete');
            const cancelDeleteBtn = document.getElementById('cancel-delete');
            let recordToDelete = null;
            
            if (deleteButtons.length > 0 && deleteConfirmation) {
                deleteButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        recordToDelete = this.getAttribute('data-id');
                        const recordName = this.getAttribute('data-name');
                        deleteNameSpan.textContent = recordName;
                        deleteConfirmation.style.display = 'flex';
                    });
                });
                
                confirmDeleteBtn.addEventListener('click', function() {
                    if (recordToDelete) {
                        window.location.href = 'sacraments?delete_id=' + recordToDelete;
                    }
                });
                
                cancelDeleteBtn.addEventListener('click', function() {
                    deleteConfirmation.style.display = 'none';
                    recordToDelete = null;
                });
            }
        });
    </script>
</body>
</html>