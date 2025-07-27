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
            background: var(--primary-color);
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
            background: var(--primary-color);
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
        
        /* Show list view when active */
        .list-view.active ~ .events-list {
            display: flex;
        }
        
        .list-view.active ~ .calendar-view {
            display: none;
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
            <input type="text" placeholder="Search events...">
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
                    <a href="events" class="nav-link active">
                        <i class="fas fa-calendar-alt"></i>
                        <span class="nav-text">Events</span>
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
                    <div class="calendar-header">
                        <div class="calendar-nav">
                            <button class="btn-icon" id="prev-month"><i class="fas fa-chevron-left"></i></button>
                            <h2 class="calendar-title">June 2025</h2>
                            <button class="btn-icon" id="next-month"><i class="fas fa-chevron-right"></i></button>
                            <button class="btn btn-sm" id="today-btn">Today</button>
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
                        <div class="calendar-day-header">Sat</div>
                        
                        <!-- Calendar days will be generated by JavaScript -->
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day empty"></div>
                        <div class="calendar-day">
                            <div class="calendar-day-number">1</div>
                        </div>
                        <div class="calendar-day">
                            <div class="calendar-day-number">2</div>
                        </div>
                        <div class="calendar-day">
                            <div class="calendar-day-number">3</div>
                        </div>
                        <div class="calendar-day">
                            <div class="calendar-day-number">4</div>
                        </div>
                        <div class="calendar-day">
                            <div class="calendar-day-number">5</div>
                        </div>
                        
                        <!-- More days would be filled in here -->
                        <div class="calendar-day today">
                            <div class="calendar-day-number">15</div>
                            <div class="calendar-event">Annual Conference</div>
                            <div class="calendar-event">Men's Breakfast</div>
                        </div>
                        
                        <!-- Sample events for demonstration -->
                        <div class="calendar-day">
                            <div class="calendar-day-number">22</div>
                            <div class="calendar-event">Youth Camp</div>
                        </div>
                    </div>
                </div>
                
                <!-- List View -->
                <div class="events-list" id="events-list">
                    <div class="event-card">
                        <div class="event-date" style="background: var(--primary-color);">
                            <span>15</span>
                            <span>JUN</span>
                        </div>
                        <div class="event-details">
                            <h3>Annual Church Conference</h3>
                            <div class="event-meta">
                                <span><i class="fas fa-clock"></i> 9:00 AM - 4:00 PM</span>
                                <span><i class="fas fa-map-marker-alt"></i> Main Hall</span>
                                <span><i class="fas fa-users"></i> 120 attending</span>
                            </div>
                            <p>Our yearly gathering for spiritual renewal and fellowship with guest speaker Pastor Michael from Kampala.</p>
                            <div class="event-actions">
                                <button title="Edit"><i class="fas fa-edit"></i></button>
                                <button title="Delete"><i class="fas fa-trash"></i></button>
                                <button title="Share"><i class="fas fa-share"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-card">
                        <div class="event-date" style="background: var(--accent-color);">
                            <span>22</span>
                            <span>JUN</span>
                        </div>
                        <div class="event-details">
                            <h3>Youth Camp</h3>
                            <div class="event-meta">
                                <span><i class="fas fa-clock"></i> All day</span>
                                <span><i class="fas fa-map-marker-alt"></i> Lake View Resort</span>
                                <span><i class="fas fa-users"></i> 45 registered</span>
                            </div>
                            <p>3-day retreat for our young members with activities, Bible study, and fellowship.</p>
                            <div class="event-actions">
                                <button title="Edit"><i class="fas fa-edit"></i></button>
                                <button title="Delete"><i class="fas fa-trash"></i></button>
                                <button title="Share"><i class="fas fa-share"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- More events would be listed here -->
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
    
    <!-- Event Modal -->
    <div class="modal" id="event-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>New Event</h2>
                <button class="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <form id="event-form">
                    <div class="form-group">
                        <label>Event Title</label>
                        <input type="text" class="form-control" placeholder="Enter event title" required>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <input type="time" class="form-control">
                        </div>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>End Time</label>
                            <input type="time" class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Location</label>
                        <input type="text" class="form-control" placeholder="Enter location">
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" rows="4" placeholder="Enter event description"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Event Category</label>
                        <select class="form-control">
                            <option>Service</option>
                            <option>Meeting</option>
                            <option>Social</option>
                            <option>Conference</option>
                            <option>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Target Audience</label>
                        <select class="form-control" multiple>
                            <option>All Members</option>
                            <option>Men's Fellowship</option>
                            <option>Women's Ministry</option>
                            <option>Youth</option>
                            <option>Church Leaders</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" id="cancel-event">Cancel</button>
                <button class="btn btn-accent" id="save-event">Save Event</button>
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
            const modal = document.getElementById('event-modal');
            const newEventBtn = document.getElementById('new-event-btn');
            const addEventBtn = document.getElementById('add-event-btn');
            const closeModal = document.querySelector('.close-modal');
            const cancelEvent = document.getElementById('cancel-event');
            
            function openModal() {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
            
            function closeModalFunc() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            }
            
            newEventBtn.addEventListener('click', openModal);
            addEventBtn.addEventListener('click', openModal);
            closeModal.addEventListener('click', closeModalFunc);
            cancelEvent.addEventListener('click', closeModalFunc);
            
            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModalFunc();
                }
            });
            
            // Calendar navigation
            const prevMonthBtn = document.getElementById('prev-month');
            const nextMonthBtn = document.getElementById('next-month');
            const todayBtn = document.getElementById('today-btn');
            const calendarTitle = document.querySelector('.calendar-title');
            
            // In a real app, you would implement full calendar functionality here
            prevMonthBtn.addEventListener('click', function() {
                // Update calendar to show previous month
                console.log('Previous month');
            });
            
            nextMonthBtn.addEventListener('click', function() {
                // Update calendar to show next month
                console.log('Next month');
            });
            
            todayBtn.addEventListener('click', function() {
                // Update calendar to show current month
                console.log('Today');
            });
            
            // Event click handlers
            document.querySelectorAll('.calendar-event').forEach(event => {
                event.addEventListener('click', function(e) {
                    e.stopPropagation();
                    // In a real app, you would show event details
                    alert('Event details would be shown here');
                });
            });
            
            document.querySelectorAll('.calendar-day').forEach(day => {
                day.addEventListener('click', function() {
                    if (!this.classList.contains('empty')) {
                        // In a real app, you might create a new event for this day
                        openModal();
                    }
                });
            });
            
            // Save event
            document.getElementById('save-event').addEventListener('click', function() {
                // In a real app, you would save the event data
                closeModalFunc();
                alert('Event saved successfully!');
            });
        });
    </script>
</body>
</html>