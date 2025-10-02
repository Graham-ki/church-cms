<?php
// Start session and include necessary files
session_start();    
include_once '../config/db.php';

// Function to get all events with attendance count
function getEventsWithAttendance() {
    global $conn;
    
    $sql = "SELECT e.*, COUNT(a.id) as attendance_count 
            FROM events e 
            LEFT JOIN attendance a ON e.id = a.event_id 
            GROUP BY e.id 
            ORDER BY e.start_date ASC";
    
    $result = $conn->query($sql);
    $events = [];
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    
    return $events;
}

// Function to get a single event by ID
function getEventById($id) {
    global $conn;
    
    $sql = "SELECT e.*, COUNT(a.id) as attendance_count 
            FROM events e 
            LEFT JOIN attendance a ON e.id = a.event_id 
            WHERE e.id = ?
            GROUP BY e.id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Function to get events for a specific month
function getEventsByMonth($year, $month) {
    global $conn;
    
    $startDate = date('Y-m-01', strtotime("$year-$month-01"));
    $endDate = date('Y-m-t', strtotime("$year-$month-01"));
    
    $sql = "SELECT e.*, COUNT(a.id) as attendance_count 
            FROM events e 
            LEFT JOIN attendance a ON e.id = a.event_id 
            WHERE e.start_date BETWEEN ? AND ?
            GROUP BY e.id 
            ORDER BY e.start_date ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $events = [];
    
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $events[] = $row;
        }
    }
    
    return $events;
}

// Function to generate calendar HTML
function generateCalendar($year, $month, $events) {
    // First day of the month
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    // Number of days in the month
    $daysInMonth = date('t', $firstDay);
    // Day of the week for the first day (0=Sunday, 6=Saturday)
    $firstDayOfWeek = date('w', $firstDay);
    
    // Previous and next month for navigation
    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear = $year - 1;
    }
    
    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear = $year + 1;
    }
    
    // Month names
    $monthNames = [
        '', 'January', 'February', 'March', 'April', 'May', 'June', 
        'July', 'August', 'September', 'October', 'November', 'December'
    ];
    
    // Start building calendar HTML
    $calendarHTML = '
    <div class="calendar-header">
        <div class="calendar-nav">
            <a href="?month='.$prevMonth.'&year='.$prevYear.'" class="btn-icon" id="prev-month"><i class="fas fa-chevron-left"></i></a>
            <h2 class="calendar-title">'.$monthNames[$month].' '.$year.'</h2>
            <a href="?month='.$nextMonth.'&year='.$nextYear.'" class="btn-icon" id="next-month"><i class="fas fa-chevron-right"></i></a>
            <a href="?month='.date('n').'&year='.date('Y').'" class="btn btn-sm" id="today-btn">Today</a>
        </div>
        <button class="btn btn-sm btn-accent" id="add-event-btn"><i class="fas fa-plus"></i> Add Event</button>
    </div>
    
    <div class="calendar-grid">
        <div class="calendar-day-header">Sun</div>
        <div class="calendar-day-header">Mon</div>
        <div class="calendar-day-header">Tue</div>
        <div class="calendar-day-header">Wed</div>
        <div class="calendar-day-header">Thu</div>
        <div class="calendar-day-header">Fri</div>
        <div class="calendar-day-header">Sat</div>';
    
    // Fill in empty days before the first day of the month
    for ($i = 0; $i < $firstDayOfWeek; $i++) {
        $calendarHTML .= '<div class="calendar-day empty"></div>';
    }
    
    // Current day for highlighting
    $currentDay = date('j');
    $currentMonthNow = date('n');
    $currentYearNow = date('Y');
    
    // Fill in the days of the month
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $isToday = ($day == $currentDay && $month == $currentMonthNow && $year == $currentYearNow);
        $dayClass = $isToday ? 'calendar-day today' : 'calendar-day';
        
        $calendarHTML .= '<div class="'.$dayClass.'">';
        $calendarHTML .= '<div class="calendar-day-number">'.$day.'</div>';
        
        // Add events for this day
        $dayEvents = array_filter($events, function($event) use ($year, $month, $day) {
            $eventDate = date('Y-m-d', strtotime($event['start_date']));
            return $eventDate == sprintf('%04d-%02d-%02d', $year, $month, $day);
        });
        
        foreach ($dayEvents as $event) {
            // Determine color based on event category
            $colorClass = 'var(--primary-color)';
            if (!empty($event['event_category'])) {
                $categoryColors = [
                    'Worship' => 'var(--primary-color)',
                    'Bible Study' => 'var(--accent-color)',
                    'Youth' => '#4CAF50',
                    'Community' => '#FF9800',
                    'Special Events' => '#9C27B0',
                    'Other' => '#607D8B'
                ];
                
                $colorClass = $categoryColors[$event['event_category']] ?? 'var(--primary-color)';
            }
            
            $calendarHTML .= '<div class="calendar-event" data-event-id="'.$event['id'].'" style="background: '.$colorClass.';">'.$event['title'].'</div>';
        }
        
        $calendarHTML .= '</div>';
    }
    
    $calendarHTML .= '</div>';
    
    return $calendarHTML;
}

// Get all events
$allEvents = getEventsWithAttendance();

// Set current month and year for calendar
$currentMonth = isset($_GET['month']) ? (int)$_GET['month'] : date('n');
$currentYear = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');

// Get events for current month
$monthEvents = getEventsByMonth($currentYear, $currentMonth);

// Handle event deletion
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    // Add your delete logic here
    // For example: $sql = "DELETE FROM events WHERE id = $deleteId";
    // Then redirect back to avoid resubmission
    header("Location: events.php?deleted=true");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Events specific styles */
        .events-controls {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .view-options {
            display: flex;
            gap: 10px;
        }
        
        .view-option {
            padding: 8px 15px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .view-option.active {
            background: var(--primary-color);
            color: white;
        }
        
        .view-option:hover:not(.active) {
            background: rgba(74, 111, 165, 0.2);
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .calendar-title {
            font-size: 1.2rem;
            font-weight: 500;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 5px;
        }
        
        .calendar-day-header {
            text-align: center;
            padding: 10px;
            font-weight: 500;
            background: rgba(74, 111, 165, 0.1);
            border-radius: 5px;
        }
        
        .calendar-day {
            min-height: 100px;
            padding: 5px;
            border-radius: 5px;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .calendar-day:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .calendar-day.empty {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .calendar-day-number {
            text-align: right;
            padding: 2px 5px;
            font-size: 0.9rem;
        }
        
        .calendar-day.today .calendar-day-number {
            background: var(--accent-color);
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .calendar-event {
            font-size: 0.8rem;
            padding: 3px 5px;
            margin-top: 3px;
            border-radius: 3px;
            color: white;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            cursor: pointer;
        }
        
        .events-list {
            display: none;
            flex-direction: column;
            gap: 10px;
        }
        
        .event-card {
            display: flex;
            gap: 15px;
            padding: 15px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
        }
        
        .event-card:hover {
            background: rgba(255, 255, 255, 0.3);
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
            flex-shrink: 0;
        }
        
        .event-date span:first-child {
            font-size: 1.5rem;
            line-height: 1;
        }
        
        .event-details {
            flex: 1;
        }
        
        .event-details h3 {
            margin: 0 0 5px;
        }
        
        .event-meta {
            display: flex;
            gap: 15px;
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .event-meta i {
            margin-right: 5px;
            color: var(--primary-color);
        }
        
        .event-actions {
            display: flex;
            gap: 10px;
        }
        
        .event-actions button {
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .event-actions button:hover {
            color: var(--primary-color);
        }
        
        /* Event modal styles */
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
        
        /* Event detail modal */
        .event-detail-modal .modal-content {
            max-width: 500px;
        }
        
        .event-detail-item {
            display: flex;
            margin-bottom: 10px;
        }
        
        .event-detail-label {
            font-weight: bold;
            width: 120px;
            flex-shrink: 0;
        }
        
        .event-detail-value {
            flex: 1;
        }
        
        .event-status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-upcoming {
            background: #e3f2fd;
            color: #1976d2;
        }
        
        .status-ongoing {
            background: #e8f5e9;
            color: #388e3c;
        }
        
        .status-completed {
            background: #f5f5f5;
            color: #616161;
        }
        
        /* Show list view when active */
        .list-view.active ~ .events-list {
            display: flex;
        }
        
        .list-view.active ~ .calendar-view {
            display: none;
        }
        
        /* Delete confirmation modal */
        .delete-confirm-modal .modal-content {
            max-width: 400px;
        }
        
        .delete-message {
            margin-bottom: 20px;
        }
        
        .delete-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php
    include_once 'header.php';
    ?>
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
                    <a href="events" class="nav-link active">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="nav-text">Events</span>
                    </a>
                </div>
                    <div class="nav-item">
                    <a href="ministries" class="nav-link">
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
                <h1 class="page-title">Events Management</h1>
                <div class="actions">
                    <button class="btn btn-accent" id="new-event-btn"><i class="fas fa-plus"></i> New Event</button>
                </div>
            </div>
            
            <div class="glass-card">
                <div class="events-controls">
                    <div class="view-options">
                        <div class="view-option calendar-view active" data-view="calendar">
                            <i class="fas fa-calendar"></i> Calendar
                        </div>
                        <div class="view-option list-view" data-view="list">
                            <i class="fas fa-list"></i> List
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-sm"><i class="fas fa-filter"></i> Filter</button>
                        <button class="btn btn-sm"><i class="fas fa-download"></i> Export</button>
                    </div>
                </div>
                
                <!-- Calendar View -->
                <div class="calendar-view" id="calendar-view">
                    <?php echo generateCalendar($currentYear, $currentMonth, $monthEvents); ?>
                </div>
                
                <!-- List View -->
                <div class="events-list" id="events-list">
                    <?php
                    if (count($allEvents) > 0) {
                        foreach ($allEvents as $event) {
                            $eventDate = strtotime($event['start_date']);
                            $day = date('d', $eventDate);
                            $month = date('M', $eventDate);
                            
                            // Determine color based on event category or use default
                            $colorClass = 'var(--primary-color)';
                            if (!empty($event['event_category'])) {
                                $categoryColors = [
                                    'Worship' => 'var(--primary-color)',
                                    'Bible Study' => 'var(--accent-color)',
                                    'Youth' => '#4CAF50',
                                    'Community' => '#FF9800',
                                    'Special Events' => '#9C27B0',
                                    'Other' => '#607D8B'
                                ];
                                
                                $colorClass = $categoryColors[$event['event_category']] ?? 'var(--primary-color)';
                            }
                            
                            // Format time
                            $startTime = !empty($event['start_time']) ? date('g:i A', strtotime($event['start_time'])) : 'All day';
                            $endTime = !empty($event['end_time']) ? ' - ' . date('g:i A', strtotime($event['end_time'])) : '';
                            
                            echo '
                            <div class="event-card">
                                <div class="event-date" style="background: '.$colorClass.';">
                                    <span>'.$day.'</span>
                                    <span>'.$month.'</span>
                                </div>
                                <div class="event-details">
                                    <h3>'.htmlspecialchars($event['title']).'</h3>
                                    <div class="event-meta">
                                        <span><i class="fas fa-clock"></i> '.$startTime.$endTime.'</span>
                                        <span><i class="fas fa-map-marker-alt"></i> '.htmlspecialchars($event['location'] ?? 'TBA').'</span>
                                        <span><i class="fas fa-users"></i> '.$event['attendance_count'].' attending</span>
                                    </div>
                                    <p>'.htmlspecialchars($event['description']).'</p>
                                    <div class="event-actions">
                                        <button class="edit-event" data-event-id="'.$event['id'].'" title="Edit"><i class="fas fa-edit"></i></button>
                                        <button class="delete-event" data-event-id="'.$event['id'].'" data-event-title="'.htmlspecialchars($event['title']).'" title="Delete"><i class="fas fa-trash"></i></button>
                                        <button title="Share"><i class="fas fa-share"></i></button>
                                    </div>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<p>No events found.</p>';
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
    
    <!-- Add/Edit Event Modal -->
    <div class="modal" id="event-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="event-modal-title">New Event</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../includes/functions.php" enctype="multipart/form-data" id="event-form">
                    <input type="hidden" name="event_id" id="event_id" value="">
                    <div class="form-group">
                        <label>Event Title</label>
                        <input type="text" class="form-control" name="title" id="event_title" placeholder="Enter event title" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input name="start_date" id="event_start_date" type="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <input name="start_time" id="event_start_time" type="time" class="form-control" required>
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>End Date</label>
                            <input name="end_date" id="event_end_date" type="date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>End Time</label>
                            <input name="end_time" id="event_end_time" type="time" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Location</label>
                        <input name="location" id="event_location" type="text" class="form-control" placeholder="Enter location">
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" id="event_description" class="form-control" rows="4" placeholder="Enter event description"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Relevant Image</label>
                        <input type="file" class="form-control" name="event_image">
                    </div>
                    <div class="form-group">
                        <label>Event Category</label>
                        <select name="event_category" id="event_category" class="form-control">
                            <option value="Worship">Worship</option>
                            <option value="Bible Study">Bible Study</option>
                            <option value="Youth">Youth</option>
                            <option value="Community">Community</option>
                            <option value="Special Events">Special Events</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Target Audience (press down control key to select multiple)</label>
                        <select name="target_audience[]" id="event_target_audience" class="form-control" multiple>
                            <option value="All Members">All Members</option>
                            <option value="Men">Men</option>
                            <option value="Women">Women</option>
                            <option value="Youth">Youth</option>
                            <option value="Elders">Elders</option>
                            <option value="Leaders">Leaders</option>
                            <option value="Children">Children</option>
                        </select>
                    </div>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn" id="cancel-event">Cancel</button>
                <button type="submit" name="save-event" class="btn btn-accent">Save Event</button>
            </div>
        </form>
        </div>
    </div>
    
    <!-- Event Detail Modal -->
    <div class="modal event-detail-modal" id="event-detail-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="event-detail-title">Event Title</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="event-detail-item">
                    <div class="event-detail-label">Date:</div>
                    <div class="event-detail-value" id="event-detail-date"></div>
                </div>
                <div class="event-detail-item">
                    <div class="event-detail-label">Time:</div>
                    <div class="event-detail-value" id="event-detail-time"></div>
                </div>
                <div class="event-detail-item">
                    <div class="event-detail-label">Location:</div>
                    <div class="event-detail-value" id="event-detail-location"></div>
                </div>
                <div class="event-detail-item">
                    <div class="event-detail-label">Attending:</div>
                    <div class="event-detail-value" id="event-detail-attendance"></div>
                </div>
                <div class="event-detail-item">
                    <div class="event-detail-label">Status:</div>
                    <div class="event-detail-value" id="event-detail-status"></div>
                </div>
                <div class="event-detail-item">
                    <div class="event-detail-value" id="event-detail-description"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-accent" id="event-detail-edit">Edit</button>
                <button class="btn" id="event-detail-close">Close</button>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal delete-confirm-modal" id="delete-confirm-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Confirm Delete</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="delete-message">
                    <p>Are you sure you want to delete the event: <strong id="delete-event-title"></strong>?</p>
                    <p>This action cannot be undone.</p>
                </div>
                <div class="delete-actions">
                    <button class="btn" id="delete-cancel">Cancel</button>
                    <a href="#" class="btn btn-accent" id="delete-confirm">Delete</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="js/scripts.js"></script>
    <script>
        // Events specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // View toggle
            const viewOptions = document.querySelectorAll('.view-option');
            viewOptions.forEach(option => {
                option.addEventListener('click', function() {
                    viewOptions.forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    
                    if (this.dataset.view === 'calendar') {
                        document.getElementById('calendar-view').style.display = 'block';
                        document.getElementById('events-list').style.display = 'none';
                    } else {
                        document.getElementById('calendar-view').style.display = 'none';
                        document.getElementById('events-list').style.display = 'flex';
                    }
                });
            });
            
            // Modal functionality
            const eventModal = document.getElementById('event-modal');
            const eventDetailModal = document.getElementById('event-detail-modal');
            const deleteModal = document.getElementById('delete-confirm-modal');
            const newEventBtn = document.getElementById('new-event-btn');
            const addEventBtn = document.getElementById('add-event-btn');
            const closeModalButtons = document.querySelectorAll('.close-modal');
            const cancelEvent = document.getElementById('cancel-event');
            const eventDetailClose = document.getElementById('event-detail-close');
            const deleteCancel = document.getElementById('delete-cancel');
            
            function openModal(modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
            
            function closeModalFunc(modal) {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            function closeAllModals() {
                closeModalFunc(eventModal);
                closeModalFunc(eventDetailModal);
                closeModalFunc(deleteModal);
            }
            
            // New event button
            newEventBtn.addEventListener('click', function() {
                document.getElementById('event-modal-title').textContent = 'New Event';
                document.getElementById('event-form').reset();
                document.getElementById('event_id').value = '';
                openModal(eventModal);
            });
            
            // Add event button in calendar header
            addEventBtn.addEventListener('click', function() {
                document.getElementById('event-modal-title').textContent = 'New Event';
                document.getElementById('event-form').reset();
                document.getElementById('event_id').value = '';
                openModal(eventModal);
            });
            
            // Close modal buttons
            closeModalButtons.forEach(button => {
                button.addEventListener('click', closeAllModals);
            });
            
            cancelEvent.addEventListener('click', function() {
                closeModalFunc(eventModal);
            });
            
            eventDetailClose.addEventListener('click', function() {
                closeModalFunc(eventDetailModal);
            });
            
            deleteCancel.addEventListener('click', function() {
                closeModalFunc(deleteModal);
            });
            
            // Close modal when clicking outside
            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        closeModalFunc(modal);
                    }
                });
            });
            
            // Event click handlers for calendar events
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('calendar-event')) {
                    const eventId = e.target.dataset.eventId;
                    fetchEventDetails(eventId);
                }
            });
            
            // Edit event buttons
            document.querySelectorAll('.edit-event').forEach(button => {
                button.addEventListener('click', function() {
                    const eventId = this.dataset.eventId;
                    fetchEventForEdit(eventId);
                });
            });
            
            // Delete event buttons
            document.querySelectorAll('.delete-event').forEach(button => {
                button.addEventListener('click', function() {
                    const eventId = this.dataset.eventId;
                    const eventTitle = this.dataset.eventTitle;
                    document.getElementById('delete-event-title').textContent = eventTitle;
                    document.getElementById('delete-confirm').href = `?delete_id=${eventId}`;
                    openModal(deleteModal);
                });
            });
            
            // Edit button in event detail modal
            document.getElementById('event-detail-edit').addEventListener('click', function() {
                const eventId = document.getElementById('event-detail-title').dataset.eventId;
                fetchEventForEdit(eventId);
            });
            
            // Function to fetch event details for display
            function fetchEventDetails(eventId) {
                // In a real app, you would fetch from the server
                // For this example, we'll use the events data we already have
                const events = <?php echo json_encode($allEvents); ?>;
                const event = events.find(e => e.id == eventId);
                
                if (event) {
                    document.getElementById('event-detail-title').textContent = event.title;
                    document.getElementById('event-detail-title').dataset.eventId = event.id;
                    
                    // Format date
                    const eventDate = new Date(event.start_date);
                    const formattedDate = eventDate.toLocaleDateString('en-US', { 
                        weekday: 'long', 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    });
                    document.getElementById('event-detail-date').textContent = formattedDate;
                    
                    // Format time
                    let timeText = 'All day';
                    if (event.start_time) {
                        const startTime = formatTime(event.start_time);
                        const endTime = event.end_time ? formatTime(event.end_time) : '';
                        timeText = startTime + (endTime ? ' - ' + endTime : '');
                    }
                    document.getElementById('event-detail-time').textContent = timeText;
                    
                    // Location
                    document.getElementById('event-detail-location').textContent = event.location || 'TBA';
                    
                    // Attendance
                    document.getElementById('event-detail-attendance').textContent = event.attendance_count + ' attending';
                    
                    // Status
                    const now = new Date();
                    const startDateTime = new Date(event.start_date + ' ' + (event.start_time || '00:00:00'));
                    const endDateTime = event.end_date ? new Date(event.end_date + ' ' + (event.end_time || '23:59:59')) : null;
                    
                    let status = '';
                    if (now < startDateTime) {
                        status = '<span class="event-status status-upcoming">Upcoming</span>';
                    } else if (endDateTime && now > endDateTime) {
                        status = '<span class="event-status status-completed">Completed</span>';
                    } else {
                        status = '<span class="event-status status-ongoing">Ongoing</span>';
                    }
                    document.getElementById('event-detail-status').innerHTML = status;
                    
                    // Description
                    document.getElementById('event-detail-description').textContent = event.description || 'No description provided.';
                    
                    openModal(eventDetailModal);
                }
            }
            
            // Function to fetch event for editing
            function fetchEventForEdit(eventId) {
                // In a real app, you would fetch from the server
                // For this example, we'll use the events data we already have
                const events = <?php echo json_encode($allEvents); ?>;
                const event = events.find(e => e.id == eventId);
                
                if (event) {
                    document.getElementById('event-modal-title').textContent = 'Edit Event';
                    document.getElementById('event_id').value = event.id;
                    document.getElementById('event_title').value = event.title;
                    document.getElementById('event_start_date').value = event.start_date;
                    document.getElementById('event_start_time').value = event.start_time || '';
                    document.getElementById('event_end_date').value = event.end_date || '';
                    document.getElementById('event_end_time').value = event.end_time || '';
                    document.getElementById('event_location').value = event.location || '';
                    document.getElementById('event_description').value = event.description || '';
                    document.getElementById('event_category').value = event.event_category || 'Worship';
                    
                    // Set target audience (this would need more complex handling in a real app)
                    if (event.target_audience) {
                        // This is a simplified version - you'd need to parse the stored value
                        const audienceSelect = document.getElementById('event_target_audience');
                        Array.from(audienceSelect.options).forEach(option => {
                            option.selected = event.target_audience.includes(option.value);
                        });
                    }
                    
                    closeModalFunc(eventDetailModal);
                    openModal(eventModal);
                }
            }
            
            // Helper function to format time
            function formatTime(timeString) {
                if (!timeString) return '';
                
                const timeParts = timeString.split(':');
                let hours = parseInt(timeParts[0]);
                const minutes = timeParts[1];
                const ampm = hours >= 12 ? 'PM' : 'AM';
                
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                
                return hours + ':' + minutes + ' ' + ampm;
            }
            
            // Set today's date as default for new event form
            const today = new Date();
            const formattedDate = today.toISOString().split('T')[0];
            document.querySelector('input[name="start_date"]').value = formattedDate;
            
            // Set default time (current time + 1 hour)
            const nextHour = new Date(today.getTime() + 60 * 60 * 1000);
            const formattedTime = nextHour.toTimeString().substr(0, 5);
            document.querySelector('input[name="start_time"]').value = formattedTime;
        });
    </script>
</body>
</html>