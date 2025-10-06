<?php
session_start();
include_once '../config/db.php';

// Get active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'overview';

// Get date range for filtering (default to last 90 days)
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d', strtotime('-90 days'));
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
$time_period = isset($_GET['time_period']) ? $_GET['time_period'] : 'last_90_days';

// Handle predefined time periods
if (isset($_GET['time_period'])) {
    switch ($_GET['time_period']) {
        case 'last_7_days':
            $start_date = date('Y-m-d', strtotime('-7 days'));
            break;
        case 'last_30_days':
            $start_date = date('Y-m-d', strtotime('-30 days'));
            break;
        case 'last_90_days':
            $start_date = date('Y-m-d', strtotime('-90 days'));
            break;
        case 'this_year':
            $start_date = date('Y-01-01');
            break;
        case 'last_year':
            $start_date = date('Y-01-01', strtotime('-1 year'));
            $end_date = date('Y-12-31', strtotime('-1 year'));
            break;
    }
}

// Common data for all tabs
// Key Statistics
$attendance_query = "SELECT COUNT(*) as total FROM attendance WHERE date BETWEEN '$start_date' AND '$end_date'";
$attendance_result = mysqli_query($conn, $attendance_query);
$total_attendance = mysqli_fetch_assoc($attendance_result)['total'];

$contributions_query = "SELECT SUM(amount) as total FROM contributions WHERE contribution_date BETWEEN '$start_date' AND '$end_date'";
$contributions_result = mysqli_query($conn, $contributions_query);
$total_contributions = mysqli_fetch_assoc($contributions_result)['total'] ?: 0;

// FIXED: Active Members query using is_active column
$active_members_query = "SELECT COUNT(*) as total FROM members WHERE is_active = 1";
$active_members_result = mysqli_query($conn, $active_members_query);
$active_members = mysqli_fetch_assoc($active_members_result)['total'];

// FIXED: Total members count
$total_members_query = "SELECT COUNT(*) as total FROM members";
$total_members_result = mysqli_query($conn, $total_members_query);
$total_members = mysqli_fetch_assoc($total_members_result)['total'];

$sacraments_query = "SELECT COUNT(*) as total FROM sacraments WHERE date BETWEEN '$start_date' AND '$end_date'";
$sacraments_result = mysqli_query($conn, $sacraments_query);
$total_sacraments = mysqli_fetch_assoc($sacraments_result)['total'];

// NEW: Get average attendance per service
// NEW: Get average attendance per service
$avg_attendance_query = "
    SELECT AVG(service_count) as avg_attendance 
    FROM (
        SELECT date, service, COUNT(*) as service_count 
        FROM attendance 
        WHERE date BETWEEN '$start_date' AND '$end_date'
        GROUP BY date, service
    ) as service_counts
";
$avg_attendance_result = mysqli_query($conn, $avg_attendance_query);

if ($avg_attendance_result && mysqli_num_rows($avg_attendance_result) > 0) {
    $avg_attendance_row = mysqli_fetch_assoc($avg_attendance_result);
    $avg_attendance = $avg_attendance_row['avg_attendance'] ? round($avg_attendance_row['avg_attendance']) : 0;
} else {
    $avg_attendance = 0;
}
// NEW: Get top contributors
$top_contributors_query = "
    SELECT m.first_name, m.last_name, SUM(c.amount) as total_contributed
    FROM contributions c
    JOIN members m ON c.member_id = m.id
    WHERE c.contribution_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY c.member_id, m.first_name, m.last_name
    ORDER BY total_contributed DESC
    LIMIT 5
";
$top_contributors_result = mysqli_query($conn, $top_contributors_query);
// NEW: Get most attended services
$popular_services_query = "
    SELECT service, COUNT(*) as attendance_count
    FROM attendance
    WHERE date BETWEEN '$start_date' AND '$end_date'
    GROUP BY service
    ORDER BY attendance_count DESC
    LIMIT 5
";
$popular_services_result = mysqli_query($conn, $popular_services_query);

// ATTENDANCE TAB DATA
$attendance_by_service_query = "
    SELECT service, COUNT(*) as count 
    FROM attendance 
    WHERE date BETWEEN '$start_date' AND '$end_date'
    GROUP BY service
    ORDER BY count DESC
";
$attendance_by_service_result = mysqli_query($conn, $attendance_by_service_query);

$weekly_attendance_query = "
    SELECT 
        YEARWEEK(date) as week,
        COUNT(*) as count,
        MIN(date) as week_start
    FROM attendance 
    WHERE date BETWEEN '$start_date' AND '$end_date'
    GROUP BY YEARWEEK(date)
    ORDER BY week
    LIMIT 12
";
$weekly_attendance_result = mysqli_query($conn, $weekly_attendance_query);
$attendance_weeks = [];
$attendance_counts = [];
while ($row = mysqli_fetch_assoc($weekly_attendance_result)) {
    $attendance_weeks[] = date('M d', strtotime($row['week_start']));
    $attendance_counts[] = $row['count'];
}

// CONTRIBUTIONS TAB DATA
$contributions_by_type_query = "
    SELECT 
        contribution_type,
        SUM(amount) as total,
        COUNT(*) as count
    FROM contributions 
    WHERE contribution_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY contribution_type
    ORDER BY total DESC
";
$contributions_by_type_result = mysqli_query($conn, $contributions_by_type_query);

$monthly_contributions_query = "
    SELECT 
        DATE_FORMAT(contribution_date, '%Y-%m') as month,
        SUM(amount) as total,
        contribution_type
    FROM contributions 
    WHERE contribution_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY DATE_FORMAT(contribution_date, '%Y-%m'), contribution_type
    ORDER BY month
";
$monthly_contributions_result = mysqli_query($conn, $monthly_contributions_query);

$contribution_months = [];
$contribution_data = [
    'tithe' => [],
    'offering' => [],
    'donation' => [],
    'building' => [],
    'mission' => [],
    'other' => []
];

// Initialize all months with zeros
$month_query = "
    SELECT DISTINCT DATE_FORMAT(contribution_date, '%Y-%m') as month 
    FROM contributions 
    WHERE contribution_date BETWEEN '$start_date' AND '$end_date'
    ORDER BY month
";
$month_result = mysqli_query($conn, $month_query);
while ($month_row = mysqli_fetch_assoc($month_result)) {
    $contribution_months[] = date('M Y', strtotime($month_row['month'] . '-01'));
    foreach ($contribution_data as $type => $values) {
        $contribution_data[$type][] = 0;
    }
}

// Fill in actual data
mysqli_data_seek($monthly_contributions_result, 0);
while ($row = mysqli_fetch_assoc($monthly_contributions_result)) {
    $month_index = array_search(date('M Y', strtotime($row['month'] . '-01')), $contribution_months);
    if ($month_index !== false && isset($contribution_data[$row['contribution_type']])) {
        $contribution_data[$row['contribution_type']][$month_index] = floatval($row['total']);
    }
}

// Contribution Type Breakdown for Pie Chart
$contribution_breakdown_query = "
    SELECT 
        contribution_type,
        SUM(amount) as total
    FROM contributions 
    WHERE contribution_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY contribution_type
";
$contribution_breakdown_result = mysqli_query($conn, $contribution_breakdown_query);
$contribution_types = [];
$contribution_totals = [];
$contribution_colors = [
    'tithe' => 'rgba(13, 110, 253, 0.7)',
    'offering' => 'rgba(40, 167, 69, 0.7)',
    'donation' => 'rgba(255, 193, 7, 0.7)',
    'building' => 'rgba(108, 117, 125, 0.7)',
    'mission' => 'rgba(220, 53, 69, 0.7)',
    'other' => 'rgba(111, 66, 193, 0.7)'
];

while ($row = mysqli_fetch_assoc($contribution_breakdown_result)) {
    $contribution_types[] = ucfirst($row['contribution_type']);
    $contribution_totals[] = floatval($row['total']);
}

// MEMBERS TAB DATA
// FIXED: Members by active status using is_active column
$members_by_status_query = "
    SELECT 
        CASE 
            WHEN is_active = 1 THEN 'Active' 
            ELSE 'Inactive' 
        END as status,
        COUNT(*) as count
    FROM members 
    GROUP BY is_active
";
$members_by_status_result = mysqli_query($conn, $members_by_status_query);

$members_joined_query = "
    SELECT 
        DATE_FORMAT(date_joined, '%Y-%m') as month,
        COUNT(*) as count
    FROM members 
    WHERE date_joined BETWEEN '$start_date' AND '$end_date'
    GROUP BY DATE_FORMAT(date_joined, '%Y-%m')
    ORDER BY month
";
$members_joined_result = mysqli_query($conn, $members_joined_query);
$members_joined_months = [];
$members_joined_counts = [];
while ($row = mysqli_fetch_assoc($members_joined_result)) {
    $members_joined_months[] = date('M Y', strtotime($row['month'] . '-01'));
    $members_joined_counts[] = $row['count'];
}

// NEW: Member demographics
$members_by_gender_query = "
    SELECT 
        gender,
        COUNT(*) as count
    FROM members 
    WHERE gender IS NOT NULL AND gender != ''
    GROUP BY gender
";
$members_by_gender_result = mysqli_query($conn, $members_by_gender_query);

// SACRAMENTS TAB DATA
$sacraments_by_type_query = "
    SELECT 
        type,
        COUNT(*) as count
    FROM sacraments 
    WHERE date BETWEEN '$start_date' AND '$end_date'
    GROUP BY type
    ORDER BY count DESC
";
$sacraments_by_type_result = mysqli_query($conn, $sacraments_by_type_query);

$monthly_sacraments_query = "
    SELECT 
        DATE_FORMAT(date, '%Y-%m') as month,
        COUNT(*) as count
    FROM sacraments 
    WHERE date BETWEEN '$start_date' AND '$end_date'
    GROUP BY DATE_FORMAT(date, '%Y-%m')
    ORDER BY month
";
$monthly_sacraments_result = mysqli_query($conn, $monthly_sacraments_query);
$sacraments_months = [];
$sacraments_counts = [];
while ($row = mysqli_fetch_assoc($monthly_sacraments_result)) {
    $sacraments_months[] = date('M Y', strtotime($row['month'] . '-01'));
    $sacraments_counts[] = $row['count'];
}

// EVENTS TAB DATA
$events_query = "
    SELECT 
        title,
        start_date,
        location,
        category,
        (SELECT COUNT(*) FROM attendance WHERE event_id = events.id) as attendance_count
    FROM events 
    WHERE start_date BETWEEN '$start_date' AND '$end_date'
    ORDER BY start_date DESC
    LIMIT 10
";
$events_result = mysqli_query($conn, $events_query);

// Recent Activities for Overview Tab
$recent_activities_query = "
    (SELECT 
        date as activity_date,
        CONCAT('Attendance: ', service) as activity,
        'attendance' as category,
        COUNT(*) as participants,
        'Completed' as status
    FROM attendance 
    WHERE date BETWEEN '$start_date' AND '$end_date'
    GROUP BY date, service
    ORDER BY date DESC
    LIMIT 3)
    
    UNION ALL
    
    (SELECT 
        contribution_date as activity_date,
        CONCAT('Contributions: ', contribution_type) as activity,
        'contribution' as category,
        COUNT(*) as participants,
        'Completed' as status
    FROM contributions 
    WHERE contribution_date BETWEEN '$start_date' AND '$end_date'
    GROUP BY contribution_date, contribution_type
    ORDER BY contribution_date DESC
    LIMIT 3)
    
    UNION ALL
    
    (SELECT 
        date as activity_date,
        CONCAT('Sacrament: ', type) as activity,
        'sacrament' as category,
        1 as participants,
        'Completed' as status
    FROM sacraments 
    WHERE date BETWEEN '$start_date' AND '$end_date'
    ORDER BY date DESC
    LIMIT 3)
    
    ORDER BY activity_date DESC
    LIMIT 10
";

$recent_activities_result = mysqli_query($conn, $recent_activities_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <!-- Chart.js for data visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Enhanced dashboard styles */
        .insight-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .insight-card {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 25px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .insight-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
        }
        
        .insight-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .insight-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 1.5rem;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
        }
        
        .insight-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .insight-label {
            color: #666;
            font-size: 0.95rem;
            margin-bottom: 10px;
            font-weight: 500;
        }
        
        .insight-trend {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            font-weight: 500;
            padding: 4px 8px;
            border-radius: 20px;
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            width: fit-content;
        }
        
        .insight-trend.down {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }
        
        .progress-container {
            margin-top: 15px;
        }
        
        .progress-label {
            display: flex;
            justify-content: between;
            margin-bottom: 5px;
            font-size: 0.85rem;
            color: #666;
        }
        
        .progress-bar {
            height: 6px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--accent-color));
            border-radius: 3px;
            transition: width 0.3s ease;
        }
        
        .highlight-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 768px) {
            .highlight-grid {
                grid-template-columns: 1fr;
            }
        }
        
        .highlight-card {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        .highlight-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
            color: var(--primary-color);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .highlight-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .highlight-item {
            display: flex;
            justify-content: between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .highlight-item:last-child {
            border-bottom: none;
        }
        
        .member-name {
            flex: 1;
            font-weight: 500;
        }
        
        .highlight-value {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .service-name {
            flex: 1;
            font-weight: 500;
        }
        
        .service-count {
            font-weight: 600;
            color: var(--accent-color);
        }
        
        /* Existing styles remain the same */
        .report-tabs {
            display: flex;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .report-tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            display: block;
        }
        
        .report-tab.active {
            border-bottom-color: var(--primary-color);
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .report-tab:hover:not(.active) {
            border-bottom-color: rgba(74, 111, 165, 0.3);
        }
        
        .report-filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 200px;
        }
        
        .chart-container {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        
        .chart-container:hover {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .chart-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .report-table th {
            text-align: left;
            padding: 15px;
            background: rgba(74, 111, 165, 0.1);
            font-weight: 600;
            color: var(--primary-color);
        }
        
        .report-table td {
            padding: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        .badge-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }
        
        .date-range-inputs {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }
        
        .date-input-group {
            display: flex;
            flex-direction: column;
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
                    <a href="members" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span class="nav-text">Members</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="reports" class="nav-link active">
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
                <h1 class="page-title">Church Analytics Dashboard</h1>
                <div class="actions">
                    <button class="btn btn-accent" onclick="exportReport()"><i class="fas fa-download"></i> Export Report</button>
                    <button class="btn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>
            
            <!-- Report Tabs -->
            <div class="glass-card">
                <div class="report-tabs">
                    <a href="?tab=overview" class="report-tab <?php echo $active_tab == 'overview' ? 'active' : ''; ?>">
                        <i class="fas fa-chart-pie"></i> Overview
                    </a>
                    <a href="?tab=attendance" class="report-tab <?php echo $active_tab == 'attendance' ? 'active' : ''; ?>">
                        <i class="fas fa-user-check"></i> Attendance
                    </a>
                    <a href="?tab=contributions" class="report-tab <?php echo $active_tab == 'contributions' ? 'active' : ''; ?>">
                        <i class="fas fa-donate"></i> Contributions
                    </a>
                    <a href="?tab=members" class="report-tab <?php echo $active_tab == 'members' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Members
                    </a>
                    <a href="?tab=sacraments" class="report-tab <?php echo $active_tab == 'sacraments' ? 'active' : ''; ?>">
                        <i class="fas fa-bible"></i> Sacraments
                    </a>
                    <a href="?tab=events" class="report-tab <?php echo $active_tab == 'events' ? 'active' : ''; ?>">
                        <i class="fas fa-calendar-alt"></i> Events
                    </a>
                </div>
                
                <!-- Filters -->
                <form method="GET" action="" class="report-filters">
                    <input type="hidden" name="tab" value="<?php echo $active_tab; ?>">
                    <div class="filter-group">
                        <label>Time Period</label>
                        <select name="time_period" class="form-control" onchange="this.form.submit()">
                            <option value="last_7_days" <?php echo $time_period == 'last_7_days' ? 'selected' : ''; ?>>Last 7 days</option>
                            <option value="last_30_days" <?php echo $time_period == 'last_30_days' ? 'selected' : ''; ?>>Last 30 days</option>
                            <option value="last_90_days" <?php echo $time_period == 'last_90_days' ? 'selected' : ''; ?>>Last 90 days</option>
                            <option value="this_year" <?php echo $time_period == 'this_year' ? 'selected' : ''; ?>>This year</option>
                            <option value="last_year" <?php echo $time_period == 'last_year' ? 'selected' : ''; ?>>Last year</option>
                            <option value="custom" <?php echo $time_period == 'custom' ? 'selected' : ''; ?>>Custom range...</option>
                        </select>
                    </div>
                    
                    <?php if ($time_period == 'custom'): ?>
                    <div class="date-range-inputs">
                        <div class="date-input-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
                        </div>
                        <div class="date-input-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="filter-group" style="align-self: flex-end;">
                        <button type="submit" class="btn btn-accent"><i class="fas fa-filter"></i> Apply Filters</button>
                    </div>
                    
                    <div class="filter-group" style="align-self: flex-end;">
                        <a href="reports?tab=<?php echo $active_tab; ?>" class="btn">Clear Filters</a>
                    </div>
                </form>
                
                <!-- OVERVIEW TAB CONTENT -->
                <div id="overview-content" class="tab-content <?php echo $active_tab == 'overview' ? 'active' : ''; ?>">
                    <!-- Enhanced Insight Cards -->
                    <div class="insight-grid">
                        <div class="insight-card">
                            <div class="insight-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="insight-value"><?php echo number_format($total_attendance); ?></div>
                            <div class="insight-label">Total Church Attendance</div>
                            <div class="insight-trend">
                                <i class="fas fa-arrow-up"></i> Average <?php echo number_format($avg_attendance); ?> per service
                            </div>
                        </div>
                        
                        <div class="insight-card">
                            <div class="insight-icon">
                                <i class="fas fa-donate"></i>
                            </div>
                            <div class="insight-value">UGX <?php echo number_format($total_contributions); ?></div>
                            <div class="insight-label">Total Contributions</div>
                            <div class="progress-container">
                                <div class="progress-label">
                                    <span>Collection Progress</span>
                                    <span>75%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: 75%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="insight-card">
                            <div class="insight-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="insight-value"><?php echo number_format($active_members); ?></div>
                            <div class="insight-label">Active Members</div>
                            <div class="progress-container">
                                <div class="progress-label">
                                    <span>Active Rate</span>
                                    <span><?php echo $total_members > 0 ? number_format(($active_members / $total_members) * 100, 1) : 0; ?>%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: <?php echo $total_members > 0 ? ($active_members / $total_members) * 100 : 0; ?>%"></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="insight-card">
                            <div class="insight-icon">
                                <i class="fas fa-bible"></i>
                            </div>
                            <div class="insight-value"><?php echo number_format($total_sacraments); ?></div>
                            <div class="insight-label">Sacraments Performed</div>
                            <div class="insight-trend">
                                <i class="fas fa-star"></i> Spiritual Growth
                            </div>
                        </div>
                    </div>
                    
                    <!-- Highlights Section -->
                    <div class="highlight-grid">
                        <div class="highlight-card">
                            <div class="highlight-title">
                                <i class="fas fa-trophy"></i>
                                Top Contributors
                            </div>
                            <ul class="highlight-list">
                                <?php if (mysqli_num_rows($top_contributors_result) > 0): ?>
                                    <?php while ($contributor = mysqli_fetch_assoc($top_contributors_result)): ?>
                                        <li class="highlight-item">
                                            <span class="member-name"><?php echo htmlspecialchars($contributor['first_name'] . ' ' . $contributor['last_name']); ?></span>
                                            <span class="highlight-value">UGX <?php echo number_format($contributor['total_contributed']); ?></span>
                                        </li>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <li class="highlight-item">
                                        <span class="member-name">No contribution data</span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        
                        <div class="highlight-card">
                            <div class="highlight-title">
                                <i class="fas fa-church"></i>
                                Most Attended Services
                            </div>
                            <ul class="highlight-list">
                                <?php if (mysqli_num_rows($popular_services_result) > 0): ?>
                                    <?php while ($service = mysqli_fetch_assoc($popular_services_result)): ?>
                                        <li class="highlight-item">
                                            <span class="service-name"><?php echo ucfirst($service['service']); ?> Service</span>
                                            <span class="service-count"><?php echo number_format($service['attendance_count']); ?></span>
                                        </li>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <li class="highlight-item">
                                        <span class="service-name">No attendance data</span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Charts Section -->
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">
                                <i class="fas fa-user-check"></i>
                                Weekly Attendance Trend
                            </div>
                        </div>
                        <canvas id="attendanceChart" height="300"></canvas>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">
                                <i class="fas fa-donate"></i>
                                Contribution Analysis
                            </div>
                        </div>
                        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                            <div style="flex: 2; min-width: 300px;">
                                <canvas id="contributionsChart" height="300"></canvas>
                            </div>
                            <div style="flex: 1; min-width: 250px;">
                                <canvas id="contributionPieChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Other tabs content remains similar but enhanced -->
                <!-- ATTENDANCE TAB CONTENT -->
                <div id="attendance-content" class="tab-content <?php echo $active_tab == 'attendance' ? 'active' : ''; ?>">
                    <!-- Enhanced attendance content -->
                    <div class="insight-grid">
                        <div class="insight-card">
                            <div class="insight-value"><?php echo number_format($total_attendance); ?></div>
                            <div class="insight-label">Total Attendance</div>
                        </div>
                        <div class="insight-card">
                            <div class="insight-value"><?php echo number_format($avg_attendance); ?></div>
                            <div class="insight-label">Average per Service</div>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Attendance by Service Type</div>
                        </div>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Service Type</th>
                                    <th>Attendance Count</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($attendance_by_service_result) > 0): ?>
                                    <?php while ($service = mysqli_fetch_assoc($attendance_by_service_result)): ?>
                                        <tr>
                                            <td><?php echo ucfirst($service['service']); ?></td>
                                            <td><?php echo number_format($service['count']); ?></td>
                                            <td><?php echo $total_attendance > 0 ? number_format(($service['count'] / $total_attendance) * 100, 1) : 0; ?>%</td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" style="text-align: center;">No attendance data found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Weekly Attendance Trend</div>
                        </div>
                        <canvas id="attendanceTrendChart" height="300"></canvas>
                    </div>
                </div>
                
                <!-- CONTRIBUTIONS TAB CONTENT -->
                <div id="contributions-content" class="tab-content <?php echo $active_tab == 'contributions' ? 'active' : ''; ?>">
                    <div class="insight-grid">
                        <div class="insight-card">
                            <div class="insight-value">UGX <?php echo number_format($total_contributions); ?></div>
                            <div class="insight-label">Total Contributions</div>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Contributions by Type</div>
                        </div>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Contribution Type</th>
                                    <th>Total Amount</th>
                                    <th>Transaction Count</th>
                                    <th>Average Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($contributions_by_type_result) > 0): ?>
                                    <?php mysqli_data_seek($contributions_by_type_result, 0); ?>
                                    <?php while ($contribution = mysqli_fetch_assoc($contributions_by_type_result)): ?>
                                        <tr>
                                            <td><?php echo ucfirst($contribution['contribution_type']); ?></td>
                                            <td>UGX <?php echo number_format($contribution['total']); ?></td>
                                            <td><?php echo number_format($contribution['count']); ?></td>
                                            <td>UGX <?php echo number_format($contribution['total'] / $contribution['count']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" style="text-align: center;">No contribution data found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Monthly Contributions</div>
                        </div>
                        <canvas id="monthlyContributionsChart" height="300"></canvas>
                    </div>
                </div>
                
                <!-- MEMBERS TAB CONTENT -->
                <div id="members-content" class="tab-content <?php echo $active_tab == 'members' ? 'active' : ''; ?>">
                    <div class="insight-grid">
                        <div class="insight-card">
                            <div class="insight-value"><?php echo number_format($total_members); ?></div>
                            <div class="insight-label">Total Members</div>
                        </div>
                        <div class="insight-card">
                            <div class="insight-value"><?php echo number_format($active_members); ?></div>
                            <div class="insight-label">Active Members</div>
                        </div>
                        <div class="insight-card">
                            <div class="insight-value"><?php echo $total_members > 0 ? number_format(($active_members / $total_members) * 100, 1) : 0; ?>%</div>
                            <div class="insight-label">Active Rate</div>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Members Joined Over Time</div>
                        </div>
                        <canvas id="membersJoinedChart" height="300"></canvas>
                    </div>
                    
                    <?php if (mysqli_num_rows($members_by_status_result) > 0): ?>
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Members by Status</div>
                        </div>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($status = mysqli_fetch_assoc($members_by_status_result)): ?>
                                    <tr>
                                        <td><?php echo $status['status']; ?></td>
                                        <td><?php echo number_format($status['count']); ?></td>
                                        <td><?php echo $total_members > 0 ? number_format(($status['count'] / $total_members) * 100, 1) : 0; ?>%</td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (mysqli_num_rows($members_by_gender_result) > 0): ?>
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Members by Gender</div>
                        </div>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Gender</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($gender = mysqli_fetch_assoc($members_by_gender_result)): ?>
                                    <tr>
                                        <td><?php echo ucfirst($gender['gender']); ?></td>
                                        <td><?php echo number_format($gender['count']); ?></td>
                                        <td><?php echo $total_members > 0 ? number_format(($gender['count'] / $total_members) * 100, 1) : 0; ?>%</td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Other tabs (Sacraments, Events) follow similar pattern -->
                <!-- SACRAMENTS TAB CONTENT -->
                <div id="sacraments-content" class="tab-content <?php echo $active_tab == 'sacraments' ? 'active' : ''; ?>">
                    <div class="insight-grid">
                        <div class="insight-card">
                            <div class="insight-value"><?php echo number_format($total_sacraments); ?></div>
                            <div class="insight-label">Total Sacraments</div>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Sacraments by Type</div>
                        </div>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Sacrament Type</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($sacraments_by_type_result) > 0): ?>
                                    <?php while ($sacrament = mysqli_fetch_assoc($sacraments_by_type_result)): ?>
                                        <tr>
                                            <td><?php echo ucfirst($sacrament['type']); ?></td>
                                            <td><?php echo number_format($sacrament['count']); ?></td>
                                            <td><?php echo $total_sacraments > 0 ? number_format(($sacrament['count'] / $total_sacraments) * 100, 1) : 0; ?>%</td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" style="text-align: center;">No sacrament data found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Monthly Sacraments</div>
                        </div>
                        <canvas id="monthlySacramentsChart" height="300"></canvas>
                    </div>
                </div>
                
                <!-- EVENTS TAB CONTENT -->
                <div id="events-content" class="tab-content <?php echo $active_tab == 'events' ? 'active' : ''; ?>">
                    <div class="chart-container">
                        <div class="chart-header">
                            <div class="chart-title">Recent Events</div>
                        </div>
                        <table class="report-table">
                            <thead>
                                <tr>
                                    <th>Event Title</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Category</th>
                                    <th>Attendance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($events_result) > 0): ?>
                                    <?php while ($event = mysqli_fetch_assoc($events_result)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                                            <td><?php echo date('M j, Y', strtotime($event['start_date'])); ?></td>
                                            <td><?php echo htmlspecialchars($event['location']); ?></td>
                                            <td><?php echo ucfirst($event['category']); ?></td>
                                            <td><?php echo number_format($event['attendance_count']); ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center;">No events found for the selected period.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
        // Initialize charts based on active tab
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($active_tab == 'overview'): ?>
            initializeOverviewCharts();
            <?php elseif ($active_tab == 'attendance'): ?>
            initializeAttendanceCharts();
            <?php elseif ($active_tab == 'contributions'): ?>
            initializeContributionsCharts();
            <?php elseif ($active_tab == 'members'): ?>
            initializeMembersCharts();
            <?php elseif ($active_tab == 'sacraments'): ?>
            initializeSacramentsCharts();
            <?php endif; ?>
        });

        // Chart initialization functions remain the same as previous version
        function initializeOverviewCharts() {
            // Attendance Chart
            const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(attendanceCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($attendance_weeks); ?>,
                    datasets: [{
                        label: 'Attendance',
                        data: <?php echo json_encode($attendance_counts); ?>,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Contributions Bar Chart
            const contributionsCtx = document.getElementById('contributionsChart').getContext('2d');
            new Chart(contributionsCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($contribution_months); ?>,
                    datasets: [
                        <?php if (!empty($contribution_data['tithe'])): ?>
                        {
                            label: 'Tithes',
                            data: <?php echo json_encode($contribution_data['tithe']); ?>,
                            backgroundColor: 'rgba(13, 110, 253, 0.7)'
                        },
                        <?php endif; ?>
                        <?php if (!empty($contribution_data['offering'])): ?>
                        {
                            label: 'Offerings',
                            data: <?php echo json_encode($contribution_data['offering']); ?>,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)'
                        },
                        <?php endif; ?>
                        <?php if (!empty($contribution_data['donation'])): ?>
                        {
                            label: 'Donations',
                            data: <?php echo json_encode($contribution_data['donation']); ?>,
                            backgroundColor: 'rgba(255, 193, 7, 0.7)'
                        },
                        <?php endif; ?>
                    ].filter(dataset => dataset !== null)
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            
            // Contributions Pie Chart
            const contributionPieCtx = document.getElementById('contributionPieChart').getContext('2d');
            new Chart(contributionPieCtx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode($contribution_types); ?>,
                    datasets: [{
                        data: <?php echo json_encode($contribution_totals); ?>,
                        backgroundColor: [
                            <?php foreach ($contribution_types as $type): ?>
                            '<?php echo $contribution_colors[strtolower($type)] ?? 'rgba(158, 158, 158, 0.7)'; ?>',
                            <?php endforeach; ?>
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        }

        // Other chart initialization functions remain the same...
        function initializeAttendanceCharts() {
            const attendanceTrendCtx = document.getElementById('attendanceTrendChart').getContext('2d');
            new Chart(attendanceTrendCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($attendance_weeks); ?>,
                    datasets: [{
                        label: 'Weekly Attendance',
                        data: <?php echo json_encode($attendance_counts); ?>,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function initializeContributionsCharts() {
            const monthlyContributionsCtx = document.getElementById('monthlyContributionsChart').getContext('2d');
            new Chart(monthlyContributionsCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($contribution_months); ?>,
                    datasets: [
                        <?php if (!empty($contribution_data['tithe'])): ?>
                        {
                            label: 'Tithes',
                            data: <?php echo json_encode($contribution_data['tithe']); ?>,
                            backgroundColor: 'rgba(13, 110, 253, 0.7)'
                        },
                        <?php endif; ?>
                        <?php if (!empty($contribution_data['offering'])): ?>
                        {
                            label: 'Offerings',
                            data: <?php echo json_encode($contribution_data['offering']); ?>,
                            backgroundColor: 'rgba(40, 167, 69, 0.7)'
                        },
                        <?php endif; ?>
                        <?php if (!empty($contribution_data['donation'])): ?>
                        {
                            label: 'Donations',
                            data: <?php echo json_encode($contribution_data['donation']); ?>,
                            backgroundColor: 'rgba(255, 193, 7, 0.7)'
                        },
                        <?php endif; ?>
                    ].filter(dataset => dataset !== null)
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function initializeMembersCharts() {
            const membersJoinedCtx = document.getElementById('membersJoinedChart').getContext('2d');
            new Chart(membersJoinedCtx, {
                type: 'line',
                data: {
                    labels: <?php echo json_encode($members_joined_months); ?>,
                    datasets: [{
                        label: 'New Members',
                        data: <?php echo json_encode($members_joined_counts); ?>,
                        borderColor: '#6c757d',
                        backgroundColor: 'rgba(108, 117, 125, 0.1)',
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function initializeSacramentsCharts() {
            const monthlySacramentsCtx = document.getElementById('monthlySacramentsChart').getContext('2d');
            new Chart(monthlySacramentsCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($sacraments_months); ?>,
                    datasets: [{
                        label: 'Sacraments',
                        data: <?php echo json_encode($sacraments_counts); ?>,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Export function
        function exportReport() {
            // Simple export implementation
            window.print();
        }
    </script>
</body>
</html>