<?php
session_start();
include_once 'config/db.php';
?>
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
            <span>St. Stephen </span>
        </div>
        <ul class="nav-links">
            <li><a href="home" class="active">Home</a></li>
            <li><a href="about-us">About</a></li>
            <li><a href="events">Events</a></li>
            <li><a href="ministries">Ministries</a></li>
            <li><a href="forum">Forum</a></li>
            <li><a href="contact">Contact</a></li>
              <?php
            if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                echo '<li><a href="dashboard" class="dashboard-btn">My Dashboard</a></li>
                <li><a href="logout" class="login-btn">Logout</a></li>
                ';
            } elseif (isset($_SESSION['clergy']) && $_SESSION['clergy'] === true) {
                echo '
                <li><a href="logout" class="login-btn">Logout</a></li>
                ';
            } elseif (isset($_SESSION['member']) && $_SESSION['member'] === true) {
                echo '
                <li><a href="logout" class="login-btn">Logout</a></li>
                ';
            } else {
                echo '<li><a href="login" class="login-btn">Login</a></li>';
            }
            ?>
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
                    <a href="service-schedule" class="btn">Learn More</a>
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1>Growing Together in Faith</h1>
                    <p>Discover our Bible study groups and spiritual growth programs</p>
                    <a href="forum" class="btn">Join a Group</a>
                </div>
            </div>
            <div class="slide">
                <div class="slide-content">
                    <h1>Serving Our Community</h1>
                    <p>Find out how you can participate in our outreach programs</p>
                    <a href="events" class="btn">Volunteer</a>
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
            <?php
            $sql = "SELECT * FROM events ORDER BY start_date ASC";
            $result = $conn->query($sql);
            if ($result->num_rows > 0) {
                while ($event = $result->fetch_assoc()) {
                    echo '<div class="event-card glass-card">';
                    echo '<div class="event-image">';
                    echo '<img src="uploads/events/' . $event['image'] . '" alt="' . $event['title'] . '">';
                    echo '<div class="event-date">';
                    echo '<span class="day">' . date('d', strtotime($event['start_date'])) . '</span>';
                    echo '<span class="month">' . date('M', strtotime($event['start_date'])) . '</span>';
                    echo '</div></div>';
                    echo '<div class="event-info">';
                    echo '<h3>' . $event['title'] . '</h3>';
                    echo '<p class="event-location"><i class="fas fa-map-marker-alt"></i> ' . $event['location'] . '</p>';
                    echo '<p class="event-time"><i class="fas fa-clock"></i> ' . date('g:i A', strtotime($event['start_time'])) . ' - ' . date('g:i A', strtotime($event['end_time'])) . '</p>';
                    echo '<p class="event-target-audience"><i class="fas fa-users"></i> ' . $event['target_audience'] . '</p>';
                    echo '<p class="event-desc">' . $event['description'] . '</p>';
                    echo '</div></div>';
                }
            } else {
                echo '<p>No upcoming events found.</p>';
            }
            ?>
            <!--<div class="event-card glass-card">
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
                </div>
            </div>-->
        </div>
    </section>

    <!-- Communications Carousel -->
    <section class="communications-section">
        <h2 class="section-title">Recent Communications</h2>
        <div class="comms-carousel">
            <div class="comms-container">
                <?php
                $sql = "SELECT * FROM communications ORDER BY sent_at DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while ($comm = $result->fetch_assoc()) {
                        echo '<div class="comm-card glass-card">';
                        echo '<div class="comm-header">';
                        echo '<h3>' . $comm['title'] . '</h3>';
                        echo '<span class="comm-date">' . date('F j, Y', strtotime($comm['sent_at'])) . '</span>';
                        echo '</div>';
                        echo '<p>' . $comm['message'] . '</p>';
                        echo '<a href="index.php?comm_id=' . $comm['id'] . '" class="comm-link">Read More <i class="fas fa-arrow-right"></i></a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No recent communications found.</p>';
                }
                ?>
                <!--<div class="comm-card glass-card">
                    <div class="comm-header">
                        <h3>Sunday Service Update</h3>
                        <span class="comm-date">July 10, 2025</span>
                    </div>
                    <p>This Sunday's service will feature a special guest speaker from Kumi Diocese. All members are encouraged to attend.</p>
                    <a href="#" class="comm-link">Read More <i class="fas fa-arrow-right"></i></a>
                </div>-->
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
                <a href="donations" class="btn">Donate Now</a>
            </div>
            
            <div class="cta-card glass-card">
                <div class="cta-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h3>Join Our Church</h3>
                <p>Become a member of our growing church family and participate in our ministries.</p>
                <a href="register" class="btn">Register Now</a>
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