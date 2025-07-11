<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>St. Stephen C.O.U - Church Management System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="glass-nav">
        <div class="logo">
            <img src="public/images/logo.png" alt="Church Logo">
            <span>St. Stephen C.O.U</span>
        </div>
        <ul class="nav-links">
            <li><a href="index" class="active">Home</a></li>
            <li><a href="about-us">About</a></li>
            <li><a href="events">Events</a></li>
            <li><a href="ministries">Ministries</a></li>
            <li><a href="forum">Forum</a></li>
            <li><a href="contact">Contact</a></li>
            <li><a href="login" class="login-btn">Login</a></li>
        </ul>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </nav>

    <!-- Hero Banner with Slideshow -->
    <section class="hero">
        <div class="slideshow-container">
            <div class="slide active">
                <div class="slide-content">
                    <h1>Welcome to St. Stephen C.O.U</h1>
                    <p>Join us in worship and fellowship every Sunday at 9:00 AM</p>
                    <a href="#" class="btn">Learn More</a>
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1>Growing Together in Faith</h1>
                    <p>Discover our Bible study groups and spiritual growth programs</p>
                    <a href="#" class="btn">Join a Group</a>
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1>Serving Our Community</h1>
                    <p>Find out how you can participate in our outreach programs</p>
                    <a href="#" class="btn">Volunteer</a>
                </div>
            </div>
            <div class="slide-controls">
                <button class="prev"><i class="fas fa-chevron-left"></i></button>
                <div class="dots">
                    <span class="dot active"></span>
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
                <button class="next"><i class="fas fa-chevron-right"></i></button>
            </div>
        </div>
    </section>

    <!-- Recent Events Section -->
    <section class="events-section">
        <h2 class="section-title">Upcoming Events</h2>
        <div class="events-container">
            <div class="event-card glass-card">
                <div class="event-image">
                    <img src="public/images/event1.jpg" alt="Bible Study">
                    <div class="event-date">
                        <span class="day">15</span>
                        <span class="month">JUL</span>
                    </div>
                </div>
                <div class="event-info">
                    <h3>Youth Bible Study</h3>
                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Church Hall</p>
                    <p class="event-desc">Join our weekly youth Bible study session for spiritual growth and fellowship.</p>
                    <a href="#" class="event-link">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="event-card glass-card">
                <div class="event-image">
                    <img src="public/images/event2.jpg" alt="Community Outreach">
                    <div class="event-date">
                        <span class="day">22</span>
                        <span class="month">JUL</span>
                    </div>
                </div>
                <div class="event-info">
                    <h3>Community Outreach</h3>
                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Kumi Town</p>
                    <p class="event-desc">Help us serve the less fortunate in our community through this outreach program.</p>
                    <a href="#" class="event-link">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="event-card glass-card">
                <div class="event-image">
                    <img src="public/images/event3.jpg" alt="Choir Practice">
                    <div class="event-date">
                        <span class="day">29</span>
                        <span class="month">JUL</span>
                    </div>
                </div>
                <div class="event-info">
                    <h3>Choir Practice</h3>
                    <p class="event-location"><i class="fas fa-map-marker-alt"></i> Choir Room</p>
                    <p class="event-desc">Weekly choir practice for all members. New voices are always welcome!</p>
                    <a href="#" class="event-link">View Details <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </section>

    <!-- Communications Carousel -->
    <section class="communications-section">
        <h2 class="section-title">Recent Communications</h2>
        <div class="comms-carousel">
            <div class="comms-container">
                <div class="comm-card glass-card">
                    <div class="comm-header">
                        <h3>Sunday Service Update</h3>
                        <span class="comm-date">July 10, 2025</span>
                    </div>
                    <p>This Sunday's service will feature a special guest speaker from Kumi Diocese. All members are encouraged to attend.</p>
                    <a href="#" class="comm-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="comm-card glass-card">
                    <div class="comm-header">
                        <h3>Building Fund Progress</h3>
                        <span class="comm-date">July 3, 2025</span>
                    </div>
                    <p>We've reached 65% of our building fund goal! Thank you for your generous contributions.</p>
                    <a href="#" class="comm-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="comm-card glass-card">
                    <div class="comm-header">
                        <h3>Vacation Bible School</h3>
                        <span class="comm-date">June 26, 2025</span>
                    </div>
                    <p>Registration is now open for our annual Vacation Bible School program for children ages 5-12.</p>
                    <a href="#" class="comm-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
                
                <div class="comm-card glass-card">
                    <div class="comm-header">
                        <h3>Pastoral Letter</h3>
                        <span class="comm-date">June 19, 2025</span>
                    </div>
                    <p>A special message from our pastor about spiritual growth during challenging times.</p>
                    <a href="#" class="comm-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <button class="carousel-prev"><i class="fas fa-chevron-left"></i></button>
            <button class="carousel-next"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="cta-section">
        <div class="cta-container">
            <div class="cta-card glass-card">
                <div class="cta-icon">
                    <i class="fas fa-hands-helping"></i>
                </div>
                <h3>Support Our Ministry</h3>
                <p>Your generous donations help us continue our mission and serve the community.</p>
                <a href="#" class="btn">Donate Now</a>
            </div>
            
            <div class="cta-card glass-card">
                <div class="cta-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Join Our Church</h3>
                <p>Become a member of our growing church family and participate in our ministries.</p>
                <a href="#" class="btn">Register Now</a>
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
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Ministries</a></li>
                    <li><a href="#">Events</a></li>
                    <li><a href="#">Contact</a></li>
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
</body>
</html>