<?php
session_start();
include_once '../config/db.php';

// Check if user is logged in
/*if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}*/

// Function to get events from attendance table
function getEventsFromAttendance($conn) {
    $sql = "SELECT DISTINCT 
                   a.event_id, 
                   a.service, 
                   a.date, 
                   e.title as event_name, 
                   e.location,
                   e.start_time,
                   COUNT(DISTINCT a.member_id) as total_attendance,
                   (SELECT COUNT(*) FROM members) as total_members
            FROM attendance a 
            LEFT JOIN events e ON a.event_id = e.id 
            WHERE (a.event_id IS NOT NULL OR a.service IS NOT NULL)
            GROUP BY a.event_id, a.service, a.date, e.title, e.location, e.start_time
            ORDER BY a.date DESC, e.start_time DESC";
    
    $result = mysqli_query($conn, $sql);
    $events = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $events[] = $row;
        }
    }
    
    return $events;
}

// Function to get attendance details for a specific event - CORRECTED VERSION
function getAttendanceDetails($conn, $eventId = null, $service = null, $date = null) {
    if ($eventId && $eventId != '0') {
        // For events with event_id
        $sql = "SELECT a.*, 
                       m.first_name, 
                       m.last_name, 
                       m.phone, 
                       m.email,
                       e.title as event_name, 
                       e.location,
                       'present' as status
                FROM attendance a 
                JOIN members m ON a.member_id = m.id 
                LEFT JOIN events e ON a.event_id = e.id 
                WHERE a.event_id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $eventId);
    } else {
        // For services without event_id
        $sql = "SELECT a.*, 
                       m.first_name, 
                       m.last_name, 
                       m.phone, 
                       m.email,
                       a.service as event_name, 
                       a.service as type, 
                       'Main Hall' as location,
                       'present' as status
                FROM attendance a 
                JOIN members m ON a.member_id = m.id 
                WHERE a.service = ? AND a.date = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $service, $date);
    }
    
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $attendance = [];
    
    while ($row = mysqli_fetch_assoc($result)) {
        $attendance[] = $row;
    }
    
    return $attendance;
}

// Function to get all members
function getAllMembers($conn) {
    $sql = "SELECT id, first_name, last_name, phone, email FROM members ORDER BY first_name, last_name";
    $result = mysqli_query($conn, $sql);
    $members = [];
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = $row;
        }
    }
    
    return $members;
}

// Function to get attendance statistics
function getAttendanceStatistics($conn, $period = '3 months') {
    $dateCondition = "";
    switch ($period) {
        case '7 days':
            $dateCondition = "WHERE a.date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)";
            break;
        case '30 days':
            $dateCondition = "WHERE a.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";
            break;
        case '3 months':
            $dateCondition = "WHERE a.date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
            break;
        case '6 months':
            $dateCondition = "WHERE a.date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)";
            break;
        case '1 year':
            $dateCondition = "WHERE a.date >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
            break;
        default:
            $dateCondition = "WHERE a.date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH)";
    }
    
    $stats = [];
    
    // Total members
    $sql = "SELECT COUNT(*) as total FROM members WHERE is_active = 1";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $stats['total_members'] = mysqli_fetch_assoc($result)['total'];
    } else {
        $stats['total_members'] = 0;
    }
    
    // Attendance statistics
    $sql = "SELECT 
                COUNT(DISTINCT a.member_id) as unique_attendees,
                COUNT(a.id) as total_records,
                (SELECT COUNT(DISTINCT CONCAT(COALESCE(event_id, 0), '-', COALESCE(service, ''), '-', date)) 
                 FROM attendance $dateCondition) as total_events
            FROM attendance a $dateCondition
            ";
    
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $attendanceData = mysqli_fetch_assoc($result);
        
        if ($attendanceData['total_events'] > 0) {
            $stats['average_present'] = round($attendanceData['total_records'] / $attendanceData['total_events']);
            $stats['average_absent'] = max(0, $stats['total_members'] - $stats['average_present']);
        } else {
            $stats['average_present'] = 0;
            $stats['average_absent'] = $stats['total_members'];
        }
        
        // Weekly statistics
        $weeklySql = "SELECT 
                         COUNT(*) as present_this_week 
                      FROM attendance 
                      WHERE date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
                      ";
        $weeklyResult = mysqli_query($conn, $weeklySql);
        if ($weeklyResult) {
            $weeklyData = mysqli_fetch_assoc($weeklyResult);
            $stats['present_this_week'] = $weeklyData['present_this_week'];
            $stats['absent_this_week'] = max(0, $stats['total_members'] - $stats['present_this_week']);
            
            if ($stats['total_members'] > 0) {
                $stats['weekly_average'] = round(($stats['present_this_week'] / $stats['total_members']) * 100);
            } else {
                $stats['weekly_average'] = 0;
            }
        } else {
            $stats['present_this_week'] = 0;
            $stats['absent_this_week'] = $stats['total_members'];
            $stats['weekly_average'] = 0;
        }
    } else {
        $stats['average_present'] = 0;
        $stats['average_absent'] = $stats['total_members'];
        $stats['present_this_week'] = 0;
        $stats['absent_this_week'] = $stats['total_members'];
        $stats['weekly_average'] = 0;
    }
    
    return $stats;
}

// Function to save attendance
function saveAttendance($conn, $attendanceData) {
    // Begin transaction
    mysqli_begin_transaction($conn);
    
    try {
        foreach ($attendanceData as $record) {
            // Check if record already exists
            $checkSql = "SELECT id FROM attendance WHERE 
                        member_id = ? AND 
                        date = ? AND 
                        (event_id = ? OR (event_id IS NULL AND ? IS NULL)) AND 
                        (service = ? OR (service IS NULL AND ? IS NULL))";
            
            $stmt = mysqli_prepare($conn, $checkSql);
            mysqli_stmt_bind_param($stmt, "isiss", 
                $record['member_id'], 
                $record['date'],
                $record['event_id'], $record['event_id'],
                $record['service'], $record['service']
            );
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $existing = mysqli_fetch_assoc($result);
            
            if ($existing) {
                // Update existing record
                $updateSql = "UPDATE attendance SET attended = ? WHERE id = ?";
                $stmt = mysqli_prepare($conn, $updateSql);
                $attended = $record['status'] === 'present' ? '1' : '0';
                mysqli_stmt_bind_param($stmt, "si", $attended, $existing['id']);
            } else {
                // Insert new record only if present
                if ($record['status'] === 'present') {
                    $insertSql = "INSERT INTO attendance (event_id, service, member_id, date, attended, created_at) 
                                 VALUES (?, ?, ?, ?, '1', NOW())";
                    $stmt = mysqli_prepare($conn, $insertSql);
                    mysqli_stmt_bind_param($stmt, "isss", 
                        $record['event_id'], 
                        $record['service'], 
                        $record['member_id'], 
                        $record['date']
                    );
                    mysqli_stmt_execute($stmt);
                }
            }
            
            if (isset($stmt) && $stmt) {
                mysqli_stmt_execute($stmt);
            }
        }
        
        // Commit transaction
        mysqli_commit($conn);
        return true;
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        error_log("Error saving attendance: " . $e->getMessage());
        return false;
    }
}

// Get data from database
$events = getEventsFromAttendance($conn);
$allMembers = getAllMembers($conn);
$stats = getAttendanceStatistics($conn, '3 months');

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] == 'save_attendance') {
        $attendanceData = json_decode($_POST['attendance_data'], true);
        $success = saveAttendance($conn, $attendanceData);
        
        echo json_encode(['success' => $success]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    header('Content-Type: application/json');
    
    if ($_GET['action'] == 'get_attendance_details') {
        $eventId = isset($_GET['event_id']) ? intval($_GET['event_id']) : null;
        $service = isset($_GET['service']) ? $_GET['service'] : null;
        $date = isset($_GET['date']) ? $_GET['date'] : null;
        
        $attendanceDetails = getAttendanceDetails($conn, $eventId, $service, $date);
        echo json_encode($attendanceDetails);
        exit;
    }
    
    if ($_GET['action'] == 'get_statistics') {
        $period = isset($_GET['period']) ? $_GET['period'] : '3 months';
        $stats = getAttendanceStatistics($conn, $period);
        echo json_encode($stats);
        exit;
    }
    
    if ($_GET['action'] == 'get_events') {
        $dateRange = isset($_GET['date_range']) ? $_GET['date_range'] : 'all';
        $eventType = isset($_GET['event_type']) ? $_GET['event_type'] : 'all';
        
        $filteredEvents = getEventsFromAttendance($conn);
        
        // Apply filters
        if ($dateRange !== 'all') {
            $now = new DateTime();
            $filteredEvents = array_filter($filteredEvents, function($event) use ($dateRange, $now) {
                $eventDate = new DateTime($event['date']);
                
                switch($dateRange) {
                    case 'today':
                        return $eventDate->format('Y-m-d') === $now->format('Y-m-d');
                    case 'week':
                        $weekAgo = clone $now;
                        $weekAgo->modify('-7 days');
                        return $eventDate >= $weekAgo;
                    case 'month':
                        $monthAgo = clone $now;
                        $monthAgo->modify('-1 month');
                        return $eventDate >= $monthAgo;
                    case 'quarter':
                        $quarterAgo = clone $now;
                        $quarterAgo->modify('-3 months');
                        return $eventDate >= $quarterAgo;
                    case 'year':
                        $yearAgo = clone $now;
                        $yearAgo->modify('-1 year');
                        return $eventDate >= $yearAgo;
                    default:
                        return true;
                }
            });
            $filteredEvents = array_values($filteredEvents); // Reindex array
        }
        
        if ($eventType !== 'all') {
            $filteredEvents = array_filter($filteredEvents, function($event) use ($eventType) {
                return $event['type'] === $eventType;
            });
            $filteredEvents = array_values($filteredEvents);
        }
        
        echo json_encode($filteredEvents);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .attendance-controls { display: flex; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
        .filters-container { display: flex; gap: 15px; flex-wrap: wrap; }
        .filter-group { display: flex; flex-direction: column; }
        .filter-group label { font-size: 0.85rem; margin-bottom: 5px; color: #666; }
        .events-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; margin-bottom: 30px; }
        .event-card { display: flex; flex-direction: column; padding: 20px; background: rgba(255, 255, 255, 0.2); border-radius: 10px; cursor: pointer; transition: all 0.3s ease; border: 1px solid rgba(255, 255, 255, 0.3); }
        .event-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); background: rgba(255, 255, 255, 0.3); }
        .event-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
        .event-date { display: flex; flex-direction: column; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 8px; color: white; font-weight: bold; background: var(--primary-color); }
        .event-type { padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; background: rgba(74, 111, 165, 0.1); color: var(--primary-color); }
        .event-title { font-size: 1.2rem; margin: 0 0 10px 0; color: var(--dark-color); }
        .event-details { margin-bottom: 15px; color: #666; }
        .event-details p { margin: 5px 0; display: flex; align-items: center; gap: 8px; }
        .event-stats { display: flex; justify-content: space-between; margin-top: auto; font-size: 0.9rem; color: #666; }
        .attendance-table { width: 100%; border-collapse: collapse; }
        .attendance-table th { background: rgba(74, 111, 165, 0.1); text-align: left; padding: 12px 15px; }
        .attendance-table td { padding: 12px 15px; border-bottom: 1px solid rgba(0, 0, 0, 0.05); }
        .attendance-table tr:hover td { background: rgba(74, 111, 165, 0.05); }
        .member-info { display: flex; align-items: center; gap: 10px; }
        .member-avatar { width: 40px; height: 40px; border-radius: 50%; background-size: cover; background-position: center; border: 2px solid var(--accent-color); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        .attendance-status { display: inline-block; padding: 5px 10px; border-radius: 20px; font-size: 0.9rem; font-weight: 500; }
        .present { background: rgba(79, 195, 161, 0.2); color: #2e8b6d; }
        .absent { background: rgba(255, 99, 71, 0.2); color: #cc4a37; }
        .attendance-actions { display: flex; gap: 5px; }
        .attendance-actions .btn { padding: 5px 10px; font-size: 0.8rem; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1000; align-items: center; justify-content: center; }
        .modal-content { background-color: white; border-radius: 10px; width: 90%; max-width: 900px; max-height: 90vh; overflow-y: auto; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); }
        .modal-header { padding: 15px 20px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .modal-title { margin: 0; font-size: 1.25rem; color: var(--primary-color); }
        .close-btn { background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #777; }
        .close-btn:hover { color: #333; }
        .modal-body { padding: 20px; }
        .form-control { padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 0.9rem; }
        .form-control:focus { outline: none; border-color: var(--primary-color); }
        .btn { padding: 8px 16px; border: none; border-radius: 5px; cursor: pointer; font-weight: 500; transition: all 0.3s ease; }
        .btn-sm { padding: 5px 10px; font-size: 0.8rem; }
        .btn-accent { background-color: var(--accent-color); color: white; }
        .btn-accent:hover { background-color: #e67e22; }
        .btn-secondary { background-color: #e2e8f0; color: #4a5568; }
        .btn-secondary:hover { background-color: #cbd5e0; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-success:hover { background-color: #218838; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-danger:hover { background-color: #c82333; }
        .attendance-summary { display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 20px; text-align: center; }
        .summary-card { padding: 15px; border-radius: 8px; background: rgba(74, 111, 165, 0.05); }
        .summary-value { font-size: 1.5rem; font-weight: bold; margin: 5px 0; }
        .summary-label { font-size: 0.9rem; color: #666; }
        .present-count { color: #2e8b6d; }
        .absent-count { color: #cc4a37; }
        .total-count { color: var(--primary-color); }
        .loading { text-align: center; padding: 20px; color: #666; }
        .no-events { text-align: center; padding: 40px; color: #666; }
        .no-events i { font-size: 3rem; margin-bottom: 15px; color: #ccc; }
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
                    <a href="attendance" class="nav-link active">
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
                    <a href="ministries" class="nav-link ">
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
                <h1 class="page-title">Attendance Management</h1>
                <div class="actions">
                    <button class="btn btn-accent" id="take-attendance-btn"><i class="fas fa-user-check"></i> Take Attendance</button>
                    <button class="btn"><i class="fas fa-download"></i> Export</button>
                </div>
            </div>
            
            <!-- Filters Section -->
            <div class="glass-card">
                <div class="attendance-controls">
                    <div class="filters-container">
                        <div class="filter-group">
                            <label for="date-range">Date Range</label>
                            <select id="date-range" class="form-control">
                                <option value="all">All Dates</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month" selected>This Month</option>
                                <option value="quarter">This Quarter</option>
                                <option value="year">This Year</option>
                                <option value="custom">Custom Range</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="event-type">Event Type</label>
                            <select id="event-type" class="form-control">
                                <option value="all">All Types</option>
                                <option value="service">Service</option>
                                <option value="fellowship">Fellowship</option>
                                <option value="outreach">Outreach</option>
                                <option value="training">Training</option>
                                <option value="social">Social Event</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label for="ministry">Ministry</label>
                            <select id="ministry" class="form-control">
                                <option value="all">All Ministries</option>
                                <option value="youth">Youth Ministry</option>
                                <option value="women">Women's Ministry</option>
                                <option value="men">Men's Ministry</option>
                                <option value="children">Children's Ministry</option>
                                <option value="worship">Worship Team</option>
                            </select>
                        </div>
                    </div>
                    
                    <div>
                        <button class="btn btn-sm btn-accent" id="apply-filters">Apply Filters</button>
                        <button class="btn btn-sm btn-secondary" id="reset-filters">Reset</button>
                    </div>
                </div>
            </div>
            
            <!-- Events/Services List -->
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">Events & Services</h2>
                    <div class="search-filter">
                        <input type="text" class="form-control" id="event-search" placeholder="Search events...">
                    </div>
                </div>
                
                <div class="events-grid" id="events-container">
                    <?php if (empty($events)): ?>
                        <div class="no-events">
                            <i class="fas fa-calendar-times"></i>
                            <h3>No Events Found</h3>
                            <p>There are no attendance records yet. Start by taking attendance for an event.</p>
                            <button class="btn btn-accent" id="take-attendance-first-btn"><i class="fas fa-user-check"></i> Take First Attendance</button>
                        </div>
                    <?php else: ?>
                        <?php foreach ($events as $event): ?>
                            <?php
                            $eventDate = new DateTime($event['date']);
                            $day = $eventDate->format('d');
                            $month = strtoupper($eventDate->format('M'));
                            $eventName = !empty($event['event_name']) ? $event['event_name'] : $event['service'];
                            $eventType = !empty($event['type']) ? $event['type'] : 'service';
                            $attendancePercentage = $event['total_members'] > 0 ? 
                                round(($event['total_attendance'] / $event['total_members']) * 100) : 0;
                            ?>
                            <div class="event-card" data-event-id="<?php echo $event['event_id'] ?: '0'; ?>" 
                                 data-service="<?php echo htmlspecialchars($event['service'] ?? ''); ?>" 
                                 data-date="<?php echo $event['date']; ?>">
                                <div class="event-header">
                                    <div class="event-date" style="background: <?php echo getEventColor($eventType); ?>;">
                                        <span><?php echo $day; ?></span>
                                        <span><?php echo $month; ?></span>
                                    </div>
                                    <div class="event-type"><?php echo strtoupper($eventType); ?></div>
                                </div>
                                <h3 class="event-title"><?php echo htmlspecialchars($eventName); ?></h3>
                                <div class="event-details">
                                    <p><i class="fas fa-calendar"></i> <?php echo $eventDate->format('M j, Y'); ?></p>
                                    <?php if (!empty($event['time'])): ?>
                                        <p><i class="fas fa-clock"></i> <?php echo date('g:i A', strtotime($event['time'])); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($event['location'])): ?>
                                        <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['location']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="event-stats">
                                    <span><i class="fas fa-users"></i> <?php echo $event['total_attendance']; ?>/<?php echo $event['total_members']; ?> attended</span>
                                    <span><?php echo $attendancePercentage; ?>%</span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Statistics -->
            <div class="glass-card">
                <div class="card-header">
                    <h2 class="card-title">Attendance Statistics</h2>
                    <select class="form-control" id="stats-period" style="width: auto; display: inline-block;">
                        <option value="7 days">Last 7 Days</option>
                        <option value="30 days">Last 30 Days</option>
                        <option value="3 months" selected>Last 3 Months</option>
                        <option value="6 months">Last 6 Months</option>
                        <option value="1 year">This Year</option>
                    </select>
                </div>
                
                <div class="attendance-summary">
                    <div class="summary-card">
                        <div class="summary-value total-count"><?php echo $stats['total_members']; ?></div>
                        <div class="summary-label">Total Members</div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-value present-count"><?php echo $stats['average_present']; ?></div>
                        <div class="summary-label">Average Present</div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-value absent-count"><?php echo $stats['average_absent']; ?></div>
                        <div class="summary-label">Average Absent</div>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; text-align: center; margin-top: 20px;">
                    <div>
                        <h3 style="margin: 0; color: var(--primary-color);"><?php echo $stats['weekly_average']; ?>%</h3>
                        <p style="margin: 5px 0; color: #666;">Weekly Average</p>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: var(--accent-color);"><?php echo $stats['present_this_week']; ?></h3>
                        <p style="margin: 5px 0; color: #666;">Present This Week</p>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #ff6347;"><?php echo $stats['absent_this_week']; ?></h3>
                        <p style="margin: 5px 0; color: #666;">Absent This Week</p>
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
    
    <!-- Attendance Modal -->
    <div id="attendance-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modal-event-title">Event Attendance</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <div class="attendance-summary">
                    <div class="summary-card">
                        <div class="summary-value total-count" id="modal-total">0</div>
                        <div class="summary-label">Total Members</div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-value present-count" id="modal-present">0</div>
                        <div class="summary-label">Present</div>
                    </div>
                    <div class="summary-card">
                        <div class="summary-value absent-count" id="modal-absent">0</div>
                        <div class="summary-label">Absent</div>
                    </div>
                </div>
                
                <div class="attendance-controls" style="margin-bottom: 15px;">
                    <div class="search-filter">
                        <input type="text" class="form-control" placeholder="Search members..." id="member-search">
                    </div>
                    <div>
                        <button class="btn btn-sm btn-success" id="mark-all-present">Mark All Present</button>
                        <button class="btn btn-sm btn-danger" id="mark-all-absent">Mark All Absent</button>
                        <button class="btn btn-sm btn-accent" id="save-attendance">Save Attendance</button>
                    </div>
                </div>
                
                <table class="attendance-table" id="attendance-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Ministry</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="attendance-table-body">
                        <!-- Attendance rows will be populated by JavaScript -->
                    </tbody>
                </table>
                
                <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                    <div id="attendance-count">Showing 0 of 0 members</div>
                    <div>
                        <button class="btn btn-sm btn-secondary" id="prev-page">Previous</button>
                        <button class="btn btn-sm btn-accent" id="next-page">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Take Attendance Modal -->
    <div id="take-attendance-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Take New Attendance</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p style="
  text-align: center;
  margin: 25px auto;
  padding: 15px 20px;
  background: #e8f4ff;
  color: #0b5ed7;
  border: 1px solid #b6daff;
  border-radius: 8px;
  font-family: 'Poppins', sans-serif;
  font-size: 15px;
  font-weight: 500;
  width: fit-content;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
">
  💡 Visit dashboard quick actions to take attendance! 
  <a href="index.php" style="
    color: #0b5ed7;
    text-decoration: none;
    font-weight: 600;
    margin-left: 5px;
  ">
    Continue <i class="fas fa-arrow-right"></i>
  </a>
</p>
            </div>
        </div>
    </div>
    
    <script src="js/scripts.js"></script>
    <script>
        // PHP data passed to JavaScript
        const allMembers = <?php echo json_encode($allMembers); ?>;
        const initialStats = <?php echo json_encode($stats); ?>;
        const initialEvents = <?php echo json_encode($events); ?>;

        // DOM Elements
        const eventsContainer = document.getElementById('events-container');
        const attendanceModal = document.getElementById('attendance-modal');
        const takeAttendanceModal = document.getElementById('take-attendance-modal');
        const modalEventTitle = document.getElementById('modal-event-title');
        const attendanceTableBody = document.getElementById('attendance-table-body');
        const modalTotal = document.getElementById('modal-total');
        const modalPresent = document.getElementById('modal-present');
        const modalAbsent = document.getElementById('modal-absent');
        const memberSearch = document.getElementById('member-search');
        const eventSearch = document.getElementById('event-search');
        const markAllPresentBtn = document.getElementById('mark-all-present');
        const markAllAbsentBtn = document.getElementById('mark-all-absent');
        const saveAttendanceBtn = document.getElementById('save-attendance');
        const closeButtons = document.querySelectorAll('.close-btn');
        const applyFiltersBtn = document.getElementById('apply-filters');
        const resetFiltersBtn = document.getElementById('reset-filters');
        const statsPeriod = document.getElementById('stats-period');
        const takeAttendanceBtn = document.getElementById('take-attendance-btn');
        const takeAttendanceFirstBtn = document.getElementById('take-attendance-first-btn');
        const cancelNewAttendanceBtn = document.getElementById('cancel-new-attendance');

        // Current state
        let currentEvent = null;
        let currentAttendance = [];
        let filteredMembers = [];
        let currentPage = 1;
        const membersPerPage = 10;

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            initializeEventHandlers();
            updateStatisticsDisplay(initialStats);
        });

        function initializeEventHandlers() {
            // Logout button
            document.getElementById('logout-btn').addEventListener('click', function(e) {
                e.preventDefault();
                if(confirm('Are you sure you want to logout?')) {
                    window.location.href = 'login.php';
                }
            });

            // Event card click handlers
            document.addEventListener('click', function(e) {
                const eventCard = e.target.closest('.event-card');
                if (eventCard) {
                    const eventId = eventCard.getAttribute('data-event-id');
                    const service = eventCard.getAttribute('data-service');
                    const date = eventCard.getAttribute('data-date');
                    const eventName = eventCard.querySelector('.event-title').textContent;
                    
                    currentEvent = { eventId, service, date, eventName };
                    openAttendanceModal(currentEvent);
                }
            });

            // Modal functionality
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    attendanceModal.style.display = 'none';
                    takeAttendanceModal.style.display = 'none';
                });
            });

            // Close modal when clicking outside
            [attendanceModal, takeAttendanceModal].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            });

            // Search functionality
            memberSearch.addEventListener('input', filterMembers);
            eventSearch.addEventListener('input', filterEvents);

            // Attendance actions
            markAllPresentBtn.addEventListener('click', () => markAllMembers('present'));
            markAllAbsentBtn.addEventListener('click', () => markAllMembers('absent'));
            saveAttendanceBtn.addEventListener('click', saveAttendance);

            // Statistics period change
            statsPeriod.addEventListener('change', updateStatistics);

            // Filter buttons
            applyFiltersBtn.addEventListener('click', applyFilters);
            resetFiltersBtn.addEventListener('click', resetFilters);

            // Take attendance buttons
            if (takeAttendanceBtn) {
                takeAttendanceBtn.addEventListener('click', openTakeAttendanceModal);
            }
            if (takeAttendanceFirstBtn) {
                takeAttendanceFirstBtn.addEventListener('click', openTakeAttendanceModal);
            }
            if (cancelNewAttendanceBtn) {
                cancelNewAttendanceBtn.addEventListener('click', () => {
                    takeAttendanceModal.style.display = 'none';
                });
            }

            // Pagination event handlers
            document.getElementById('prev-page')?.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    renderAttendanceTable();
                }
            });

            document.getElementById('next-page')?.addEventListener('click', () => {
                const totalPages = Math.ceil(currentAttendance.length / membersPerPage);
                if (currentPage < totalPages) {
                    currentPage++;
                    renderAttendanceTable();
                }
            });
        }

        // Get color based on event type
        function getEventColor(type) {
            const colors = {
                service: 'var(--primary-color)',
                fellowship: 'var(--accent-color)',
                outreach: '#4fc3a1',
                training: '#ffa500',
                social: '#9b59b6'
            };
            return colors[type] || 'var(--primary-color)';
        }

        // Open attendance modal for a specific event
        function openAttendanceModal(event) {
            modalEventTitle.textContent = `${event.eventName} - Attendance`;
            
            // Show loading state
            attendanceTableBody.innerHTML = '<tr><td colspan="4" class="loading">Loading attendance data...</td></tr>';
            
            // Show modal
            attendanceModal.style.display = 'flex';
            
            // Fetch attendance details
            fetchAttendanceDetails(event);
        }

        // Open take attendance modal
        function openTakeAttendanceModal() {
            takeAttendanceModal.style.display = 'flex';
        }

        // Fetch attendance details from server - CORRECTED VERSION
        async function fetchAttendanceDetails(event) {
            try {
                const params = new URLSearchParams();
                if (event.eventId !== '0') {
                    params.append('event_id', event.eventId);
                } else {
                    params.append('service', event.service);
                    params.append('date', event.date);
                }
                params.append('action', 'get_attendance_details');
                
                const response = await fetch(`?${params.toString()}`);
                if (!response.ok) throw new Error('Network response was not ok');
                
                const attendedMembers = await response.json();
                
                // Create a map of members who attended
                const attendanceMap = {};
                attendedMembers.forEach(record => {
                    attendanceMap[record.member_id] = {
                        status: 'present',
                        record: record
                    };
                });
                
                // Merge with all members to create complete attendance list
                currentAttendance = allMembers.map(member => {
                    if (attendanceMap[member.id]) {
                        return {
                            ...member,
                            status: 'present',
                            ...attendanceMap[member.id].record
                        };
                    } else {
                        return {
                            ...member,
                            status: 'absent',
                            member_id: member.id,
                            event_id: event.eventId !== '0' ? parseInt(event.eventId) : null,
                            service: event.service,
                            date: event.date
                        };
                    }
                });
                
                renderAttendanceTable();
            } catch (error) {
                console.error('Error fetching attendance details:', error);
                attendanceTableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: red;">Error loading attendance data</td></tr>';
            }
        }

        // Render attendance table - UPDATED VERSION
        function renderAttendanceTable() {
            attendanceTableBody.innerHTML = '';
            
            if (currentAttendance.length === 0) {
                attendanceTableBody.innerHTML = '<tr><td colspan="4" class="loading">No attendance data found</td></tr>';
                return;
            }
            
            // Calculate summary
            let presentCount = 0;
            let absentCount = 0;
            
            // Get current page members
            const startIndex = (currentPage - 1) * membersPerPage;
            const endIndex = startIndex + membersPerPage;
            const currentMembers = currentAttendance.slice(startIndex, endIndex);
            
            currentMembers.forEach(member => {
                const status = member.status || 'absent';
                if (status === 'present') presentCount++;
                else absentCount++;
                
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="member-info">
                            <div class="member-avatar" style="background-color: #${getRandomColor(member.id)};">
                                ${member.first_name.charAt(0)}${member.last_name.charAt(0)}
                            </div>
                            <div>
                                <strong>${member.first_name} ${member.last_name}</strong>
                                <div style="font-size: 0.8rem; color: #666;">${member.phone || 'No phone'}</div>
                            </div>
                        </div>
                    </td>
                    <td>${member.ministry || 'Not specified'}</td>
                    <td><span class="attendance-status ${status === 'present' ? 'present' : 'absent'}">${status === 'present' ? 'Present' : 'Absent'}</span></td>
                    <td>
                        <div class="attendance-actions">
                            <button class="btn btn-sm ${status !== 'present' ? 'btn-success' : 'btn-secondary'}" data-action="present" data-member-id="${member.id}">Present</button>
                            <button class="btn btn-sm ${status === 'present' ? 'btn-danger' : 'btn-secondary'}" data-action="absent" data-member-id="${member.id}">Absent</button>
                        </div>
                    </td>
                `;
                
                // Add event listeners to action buttons
                const buttons = row.querySelectorAll('.attendance-actions .btn');
                buttons.forEach(btn => {
                    btn.addEventListener('click', function() {
                        const action = this.getAttribute('data-action');
                        const memberId = parseInt(this.getAttribute('data-member-id'));
                        updateAttendanceStatus(memberId, action);
                        
                        // Update UI
                        updateAttendanceRow(row, memberId, action);
                        updateAttendanceSummary();
                    });
                });
                
                attendanceTableBody.appendChild(row);
            });
            
            // Update summary and pagination
            updateAttendanceSummary();
            updatePagination();
        }

        // Update attendance row UI
        function updateAttendanceRow(row, memberId, action) {
            const statusCell = row.querySelector('.attendance-status');
            const buttons = row.querySelectorAll('.attendance-actions .btn');
            
            if (action === 'present') {
                statusCell.textContent = 'Present';
                statusCell.className = 'attendance-status present';
                buttons[0].classList.remove('btn-success');
                buttons[0].classList.add('btn-secondary');
                buttons[1].classList.remove('btn-secondary');
                buttons[1].classList.add('btn-danger');
            } else {
                statusCell.textContent = 'Absent';
                statusCell.className = 'attendance-status absent';
                buttons[0].classList.remove('btn-secondary');
                buttons[0].classList.add('btn-success');
                buttons[1].classList.remove('btn-danger');
                buttons[1].classList.add('btn-secondary');
            }
        }

        // Generate consistent random color based on member ID
        function getRandomColor(seed) {
            const colors = ['4A6FA5', 'F39C12', '2ECC71', 'E74C3C', '9B59B6', '1ABC9C', '34495E', 'E67E22'];
            return colors[seed % colors.length];
        }

        // Update attendance status for a member - UPDATED VERSION
        function updateAttendanceStatus(memberId, status) {
            // Find the member in currentAttendance
            const memberIndex = currentAttendance.findIndex(member => member.member_id === memberId || member.id === memberId);
            
            if (memberIndex !== -1) {
                // Update the status
                currentAttendance[memberIndex].status = status;
                
                // If marking as present and this was originally an absent member (no attendance record)
                if (status === 'present' && !currentAttendance[memberIndex].attended) {
                    // Add the attended flag to indicate this is a new attendance record
                    currentAttendance[memberIndex].attended = '1';
                }
            }
        }

        // Update attendance summary in modal
        function updateAttendanceSummary() {
            const presentCount = currentAttendance.filter(record => record.status === 'present').length;
            modalTotal.textContent = currentAttendance.length;
            modalPresent.textContent = presentCount;
            modalAbsent.textContent = currentAttendance.length - presentCount;
        }

        // Update pagination
        function updatePagination() {
            const totalPages = Math.ceil(currentAttendance.length / membersPerPage);
            const attendanceCount = document.getElementById('attendance-count');
            if (attendanceCount) {
                const startIndex = (currentPage - 1) * membersPerPage + 1;
                const endIndex = Math.min(currentPage * membersPerPage, currentAttendance.length);
                attendanceCount.textContent = `Showing ${startIndex}-${endIndex} of ${currentAttendance.length} members`;
            }
            
            // Update button states
            const prevBtn = document.getElementById('prev-page');
            const nextBtn = document.getElementById('next-page');
            if (prevBtn) prevBtn.disabled = currentPage === 1;
            if (nextBtn) nextBtn.disabled = currentPage === totalPages;
        }

        // Filter members based on search input
        function filterMembers() {
            const searchTerm = memberSearch.value.toLowerCase();
            const rows = attendanceTableBody.querySelectorAll('tr');
            
            rows.forEach(row => {
                const memberName = row.querySelector('.member-info strong').textContent.toLowerCase();
                const ministry = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                
                if (memberName.includes(searchTerm) || ministry.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Filter events based on search input
        function filterEvents() {
            const searchTerm = eventSearch.value.toLowerCase();
            const eventCards = eventsContainer.querySelectorAll('.event-card');
            
            eventCards.forEach(card => {
                const eventName = card.querySelector('.event-title').textContent.toLowerCase();
                const eventType = card.querySelector('.event-type').textContent.toLowerCase();
                
                if (eventName.includes(searchTerm) || eventType.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        }

        // Mark all members with specified status
        function markAllMembers(status) {
            currentAttendance.forEach(member => {
                member.status = status;
            });
            
            renderAttendanceTable();
        }

        // Save attendance to server
        async function saveAttendance() {
            try {
                // Prepare data for saving - only include records that are marked as present
                const attendanceData = currentAttendance
                    .filter(record => record.status === 'present')
                    .map(record => ({
                        member_id: record.member_id || record.id,
                        event_id: record.event_id,
                        service: record.service,
                        date: record.date,
                        status: record.status
                    }));

                const response = await fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'save_attendance',
                        attendance_data: JSON.stringify(attendanceData)
                    })
                });

                const result = await response.json();
                
                if (result.success) {
                    alert('Attendance saved successfully!');
                    attendanceModal.style.display = 'none';
                    location.reload(); // Refresh to show updated data
                } else {
                    throw new Error('Failed to save attendance');
                }
                
            } catch (error) {
                console.error('Error saving attendance:', error);
                alert('Error saving attendance. Please try again.');
            }
        }

        // Update statistics based on selected period
        async function updateStatistics() {
            const period = statsPeriod.value;
            
            try {
                const response = await fetch(`?action=get_statistics&period=${period}`);
                if (!response.ok) throw new Error('Network response was not ok');
                
                const stats = await response.json();
                updateStatisticsDisplay(stats);
            } catch (error) {
                console.error('Error updating statistics:', error);
            }
        }

        // Update statistics display
        function updateStatisticsDisplay(stats) {
            document.querySelector('.total-count').textContent = stats.total_members;
            document.querySelector('.present-count').textContent = stats.average_present;
            document.querySelector('.absent-count').textContent = stats.average_absent;
            
            // Update weekly stats
            const weeklyStats = document.querySelector('.glass-card:last-child .grid');
            if (weeklyStats) {
                weeklyStats.innerHTML = `
                    <div>
                        <h3 style="margin: 0; color: var(--primary-color);">${stats.weekly_average}%</h3>
                        <p style="margin: 5px 0; color: #666;">Weekly Average</p>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: var(--accent-color);">${stats.present_this_week}</h3>
                        <p style="margin: 5px 0; color: #666;">Present This Week</p>
                    </div>
                    <div>
                        <h3 style="margin: 0; color: #ff6347;">${stats.absent_this_week}</h3>
                        <p style="margin: 5px 0; color: #666;">Absent This Week</p>
                    </div>
                `;
            }
        }

        // Apply filters to events
        async function applyFilters() {
            const dateRange = document.getElementById('date-range').value;
            const eventType = document.getElementById('event-type').value;
            
            try {
                const params = new URLSearchParams({
                    action: 'get_events',
                    date_range: dateRange,
                    event_type: eventType
                });
                
                const response = await fetch(`?${params.toString()}`);
                if (!response.ok) throw new Error('Network response was not ok');
                
                const events = await response.json();
                renderFilteredEvents(events);
            } catch (error) {
                console.error('Error applying filters:', error);
                alert('Error applying filters. Please try again.');
            }
        }

        // Render filtered events
        function renderFilteredEvents(events) {
            if (events.length === 0) {
                eventsContainer.innerHTML = `
                    <div class="no-events">
                        <i class="fas fa-search"></i>
                        <h3>No Events Match Your Filters</h3>
                        <p>Try adjusting your search criteria or <a href="#" id="reset-filters-link">reset the filters</a>.</p>
                    </div>
                `;
                
                // Add event listener to reset filters link
                document.getElementById('reset-filters-link').addEventListener('click', function(e) {
                    e.preventDefault();
                    resetFilters();
                });
                return;
            }
            
            let eventsHTML = '';
            events.forEach(event => {
                const eventDate = new Date(event.date);
                const day = eventDate.getDate().toString().padStart(2, '0');
                const month = eventDate.toLocaleString('default', { month: 'short' }).toUpperCase();
                const eventName = event.event_name || event.service;
                const eventType = event.type || 'service';
                const attendancePercentage = event.total_members > 0 ? 
                    Math.round((event.total_attendance / event.total_members) * 100) : 0;
                
                eventsHTML += `
                    <div class="event-card" data-event-id="${event.event_id || '0'}" 
                         data-service="${event.service || ''}" 
                         data-date="${event.date}">
                        <div class="event-header">
                            <div class="event-date" style="background: ${getEventColor(eventType)};">
                                <span>${day}</span>
                                <span>${month}</span>
                            </div>
                            <div class="event-type">${eventType.toUpperCase()}</div>
                        </div>
                        <h3 class="event-title">${eventName}</h3>
                        <div class="event-details">
                            <p><i class="fas fa-calendar"></i> ${eventDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                            ${event.time ? `<p><i class="fas fa-clock"></i> ${new Date('1970-01-01T' + event.time).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</p>` : ''}
                            ${event.location ? `<p><i class="fas fa-map-marker-alt"></i> ${event.location}</p>` : ''}
                        </div>
                        <div class="event-stats">
                            <span><i class="fas fa-users"></i> ${event.total_attendance}/${event.total_members} attended</span>
                            <span>${attendancePercentage}%</span>
                        </div>
                    </div>
                `;
            });
            
            eventsContainer.innerHTML = eventsHTML;
        }

        // Reset all filters
        function resetFilters() {
            document.getElementById('date-range').value = 'month';
            document.getElementById('event-type').value = 'all';
            document.getElementById('ministry').value = 'all';
            eventSearch.value = '';
            
            // Reload original events
            renderFilteredEvents(initialEvents);
        }
    </script>
</body>
</html>

<?php
// Helper function to get event color (PHP version)
function getEventColor($type) {
    $colors = [
        'service' => 'var(--primary-color)',
        'fellowship' => 'var(--accent-color)',
        'outreach' => '#4fc3a1',
        'training' => '#ffa500',
        'social' => '#9b59b6'
    ];
    return $colors[$type] ?? 'var(--primary-color)';
}

// Close database connection
mysqli_close($conn);
?>