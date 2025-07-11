<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        /* Events Page Specific Styles */
        .events-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('public/images/banner.jpeg');
            
            background-position: center;
            height: 30vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        
        .events-container {
            padding: 80px 5%;
            background: linear-gradient(to bottom, #f5f7fa 0%, #e4e8ed 100%);
        }
        
        .events-filter {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        
        .filter-btn {
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .filter-btn.active, .filter-btn:hover {
            background: var(--primary-color);
            color: white;
        }
        
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
        }
        
        .event-card-large {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .event-card-large:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .event-image-large {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .event-date-large {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--primary-color);
            color: white;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }
        
        .event-date-large .day {
            font-size: 1.8rem;
            line-height: 1;
        }
        
        .event-date-large .month {
            font-size: 0.9rem;
            text-transform: uppercase;
        }
        
        .event-details {
            padding: 25px;
        }
        
        .event-time {
            display: flex;
            align-items: center;
            color: var(--primary-color);
            margin: 10px 0;
        }
        
        .event-time i {
            margin-right: 8px;
        }
        
        .event-desc {
            margin: 15px 0;
            color: #555;
        }
        
        .event-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        
        .event-category {
            display: inline-block;
            padding: 5px 15px;
            background: var(--accent-color);
            color: white;
            border-radius: 30px;
            font-size: 0.8rem;
            margin-bottom: 10px;
        }
        
        .upcoming-section {
            padding: 60px 5%;
            background: white;
        }
        
        .weekly-schedule {
            padding: 60px 5%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }
        
        .schedule-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 40px;
        }
        
        .schedule-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .schedule-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.2);
        }
        
        .schedule-day {
            font-weight: bold;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .schedule-day i {
            margin-right: 10px;
            color: var(--accent-color);
        }
        
        .schedule-item {
            margin-bottom: 15px;
        }
        
        .schedule-time {
            font-weight: bold;
            color: var(--accent-color);
        }
        
        .no-events {
            text-align: center;
            padding: 40px;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="glass-nav">
        <div class="logo">
            <img src="public/images/logo.png" alt="Church Logo">
            <span>St. Stephen C.O.U</span>
        </div>
        <ul class="nav-links">
            <li><a href="index">Home</a></li>
            <li><a href="about-us" class="">About</a></li>
            <li><a href="events" class="active">Events</a></li>
            <li><a href="ministries" class="">Ministries</a></li>
            <li><a href="forum" class="">Forum</a></li>
            <li><a href="contact" class="">Contact</a></li>
            <li><a href="login" class="login-btn">Login</a></li>
        </ul>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </nav>

    <!-- Events Hero Section -->
    <section class="events-hero">
        <div class="hero-content">
            <h1>Church Events</h1>
            <p>Join us for worship, fellowship, and community activities</p>
        </div>
    </section>

    <!-- Main Events Section -->
    <section class="events-container">
        <div class="container">
            <h2 class="section-title">Upcoming Events</h2>
            <p class="section-subtitle">Find opportunities to connect, grow, and serve</p>
            
            <div class="events-filter">
                <button class="filter-btn active" data-filter="all">All Events</button>
                <button class="filter-btn" data-filter="worship">Worship</button>
                <button class="filter-btn" data-filter="bible">Bible Study</button>
                <button class="filter-btn" data-filter="youth">Youth</button>
                <button class="filter-btn" data-filter="community">Community</button>
                <button class="filter-btn" data-filter="special">Special Events</button>
            </div>
            
            <div class="events-grid">
                <!-- Event 1 -->
                <div class="event-card-large" data-category="worship">
                    <div class="event-image-large" style="background-image: url('public/images/sunday-service.jpeg');">
                        <div class="event-date-large">
                            <span class="day">15</span>
                            <span class="month">JUL</span>
                        </div>
                    </div>
                    <div class="event-details">
                        <span class="event-category">Worship</span>
                        <h3>Sunday Worship Service</h3>
                        <div class="event-time">
                            <i class="far fa-clock"></i> 9:00 AM - 12:00 PM
                        </div>
                        <div class="event-time">
                            <i class="fas fa-map-marker-alt"></i> Main Sanctuary
                        </div>
                        <p class="event-desc">Join us for our weekly Sunday worship service featuring inspiring music, prayer, and a message from Rev. Sarah Nakalema.</p>
                        <div class="event-actions">
                            <a href="#" class="btn">More Details</a>
                            <a href="#" class="event-link">Add to Calendar <i class="far fa-calendar-plus"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Event 2 -->
                <div class="event-card-large" data-category="bible">
                    <div class="event-image-large" style="background-image: url('public/images/bible-study.jpeg');">
                        <div class="event-date-large">
                            <span class="day">17</span>
                            <span class="month">JUL</span>
                        </div>
                    </div>
                    <div class="event-details">
                        <span class="event-category">Bible Study</span>
                        <h3>Midweek Bible Study</h3>
                        <div class="event-time">
                            <i class="far fa-clock"></i> 5:00 PM - 6:30 PM
                        </div>
                        <div class="event-time">
                            <i class="fas fa-map-marker-alt"></i> Fellowship Hall
                        </div>
                        <p class="event-desc">Deep dive into the Book of Romans with Pastor David. All adults welcome for this interactive study session.</p>
                        <div class="event-actions">
                            <a href="#" class="btn">More Details</a>
                            <a href="#" class="event-link">Add to Calendar <i class="far fa-calendar-plus"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Event 3 -->
                <div class="event-card-large" data-category="youth">
                    <div class="event-image-large" style="background-image: url('public/images/game-night.jpeg');">
                        <div class="event-date-large">
                            <span class="day">20</span>
                            <span class="month">JUL</span>
                        </div>
                    </div>
                    <div class="event-details">
                        <span class="event-category">Youth</span>
                        <h3>Youth Game Night</h3>
                        <div class="event-time">
                            <i class="far fa-clock"></i> 6:00 PM - 9:00 PM
                        </div>
                        <div class="event-time">
                            <i class="fas fa-map-marker-alt"></i> Youth Center
                        </div>
                        <p class="event-desc">Fun evening of games, music, and fellowship for teens ages 13-18. Bring a friend!</p>
                        <div class="event-actions">
                            <a href="#" class="btn">More Details</a>
                            <a href="#" class="event-link">Add to Calendar <i class="far fa-calendar-plus"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Event 4 -->
                <div class="event-card-large" data-category="community">
                    <div class="event-image-large" style="background-image: url('public/images/outreach.jpeg');">
                        <div class="event-date-large">
                            <span class="day">22</span>
                            <span class="month">JUL</span>
                        </div>
                    </div>
                    <div class="event-details">
                        <span class="event-category">Community</span>
                        <h3>Community Outreach Day</h3>
                        <div class="event-time">
                            <i class="far fa-clock"></i> 8:00 AM - 2:00 PM
                        </div>
                        <div class="event-time">
                            <i class="fas fa-map-marker-alt"></i> Kumi Town Center
                        </div>
                        <p class="event-desc">Join us as we serve our community through medical checkups, food distribution, and prayer ministry.</p>
                        <div class="event-actions">
                            <a href="#" class="btn">More Details</a>
                            <a href="#" class="event-link">Add to Calendar <i class="far fa-calendar-plus"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Event 5 -->
                <div class="event-card-large" data-category="special">
                    <div class="event-image-large" style="background-image: url('public/images/wedding.jpeg');">
                        <div class="event-date-large">
                            <span class="day">29</span>
                            <span class="month">JUL</span>
                        </div>
                    </div>
                    <div class="event-details">
                        <span class="event-category">Special Events</span>
                        <h3>Couples' Workshop</h3>
                        <div class="event-time">
                            <i class="far fa-clock"></i> 10:00 AM - 3:00 PM
                        </div>
                        <div class="event-time">
                            <i class="fas fa-map-marker-alt"></i> Church Conference Room
                        </div>
                        <p class="event-desc">Special seminar on building strong Christian marriages with guest speakers Dr. & Mrs. Okello.</p>
                        <div class="event-actions">
                            <a href="#" class="btn">More Details</a>
                            <a href="#" class="event-link">Add to Calendar <i class="far fa-calendar-plus"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Event 6 -->
                <div class="event-card-large" data-category="worship">
                    <div class="event-image-large" style="background-image: url('public/images/prayer-night.jpeg');">
                        <div class="event-date-large">
                            <span class="day">31</span>
                            <span class="month">JUL</span>
                        </div>
                    </div>
                    <div class="event-details">
                        <span class="event-category">Worship</span>
                        <h3>Monthly Prayer Night</h3>
                        <div class="event-time">
                            <i class="far fa-clock"></i> 7:00 PM - 9:00 PM
                        </div>
                        <div class="event-time">
                            <i class="fas fa-map-marker-alt"></i> Main Sanctuary
                        </div>
                        <p class="event-desc">Powerful evening of corporate prayer for our church, community, and nation. All prayer warriors welcome!</p>
                        <div class="event-actions">
                            <a href="#" class="btn">More Details</a>
                            <a href="#" class="event-link">Add to Calendar <i class="far fa-calendar-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Weekly Schedule Section -->
    <section class="weekly-schedule">
        <div class="container">
            <h2 class="section-title" style="color: white;">Weekly Schedule</h2>
            <p class="section-subtitle" style="color: rgba(255,255,255,0.8);">Our regular weekly programs and activities</p>
            
            <div class="schedule-grid">
                <div class="schedule-card glass-card">
                    <div class="schedule-day">
                        <i class="fas fa-church"></i> Sunday
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">9:00 AM</div>
                        <div class="schedule-event">Morning Worship Service</div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">11:00 AM</div>
                        <div class="schedule-event">Sunday School (All Ages)</div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">4:00 PM</div>
                        <div class="schedule-event">Evening Prayer Service</div>
                    </div>
                </div>
                
                <div class="schedule-card glass-card">
                    <div class="schedule-day">
                        <i class="fas fa-bible"></i> Wednesday
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">5:00 PM</div>
                        <div class="schedule-event">Bible Study</div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">6:30 PM</div>
                        <div class="schedule-event">Choir Practice</div>
                    </div>
                </div>
                
                <div class="schedule-card glass-card">
                    <div class="schedule-day">
                        <i class="fas fa-pray"></i> Friday
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">6:00 PM</div>
                        <div class="schedule-event">Prayer Meeting</div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">7:30 PM</div>
                        <div class="schedule-event">Youth Fellowship</div>
                    </div>
                </div>
                
                <div class="schedule-card glass-card">
                    <div class="schedule-day">
                        <i class="fas fa-users"></i> Saturday
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">9:00 AM</div>
                        <div class="schedule-event">Men's Fellowship</div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">2:00 PM</div>
                        <div class="schedule-event">Women's Ministry</div>
                    </div>
                    <div class="schedule-item">
                        <div class="schedule-time">4:00 PM</div>
                        <div class="schedule-event">Children's Club</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="glass-footer">
        <div class="footer-content">
            <div class="footer-section">
                <h3>Contact Us</h3>
                <p><i class="fas fa-map-marker-alt"></i> Otaaba Village, Kumi District, Uganda</p>
                <p><i class="fas fa-phone"></i> +256 123 456 789</p>
                <p><i class="fas fa-envelope"></i> info@ststephencou.org</p>
            </div>
            <div class="footer-section">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="ministries.html">Ministries</a></li>
                    <li><a href="events.html">Events</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Service Times</h3>
                <p>Sunday Worship: 9:00 AM</p>
                <p>Bible Study: Wednesday 5:00 PM</p>
                <p>Prayer Meeting: Friday 6:00 PM</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 St. Stephen C.O.U, Kumi Uganda. All Rights Reserved.</p>
            <div class="social-icons">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </footer>

    <script src="public/js/scripts.js"></script>
    <script>
        // Event Filtering Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const eventCards = document.querySelectorAll('.event-card-large');
            
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    // Add active class to clicked button
                    button.classList.add('active');
                    
                    const filterValue = button.getAttribute('data-filter');
                    
                    // Filter events
                    eventCards.forEach(card => {
                        if (filterValue === 'all' || card.getAttribute('data-category') === filterValue) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>