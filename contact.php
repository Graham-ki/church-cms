<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        /* Contact Page Specific Styles */
        .contact-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('public/images/banner.jpeg');
            background-size: cover;
            background-position: center;
            height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        
        .contact-container {
            padding: 80px 5%;
            background: linear-gradient(to bottom, #f5f7fa 0%, #e4e8ed 100%);
        }
        
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
            margin-top: 50px;
        }
        
        .contact-info {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            padding: 40px;
            transition: all 0.3s ease;
        }
        
        .contact-info:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .contact-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .contact-method {
            margin-bottom: 30px;
        }
        
        .contact-method h3 {
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        
        .contact-method p {
            color: #555;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
        }
        
        .contact-method i {
            margin-right: 10px;
            color: var(--primary-color);
            width: 20px;
            text-align: center;
        }
        
        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .social-links a:hover {
            background: var(--secondary-color);
            transform: translateY(-3px);
        }
        
        .contact-form {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            padding: 40px;
            transition: all 0.3s ease;
        }
        
        .contact-form:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--secondary-color);
            font-weight: 500;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        
        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 111, 165, 0.2);
        }
        
        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .map-container {
            margin-top: 80px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            height: 400px;
        }
        
        .map-container iframe {
            width: 100%;
            height: 100%;
            border: none;
        }
        
        .office-hours {
            margin-top: 30px;
        }
        
        .office-hours h3 {
            color: var(--secondary-color);
            margin-bottom: 15px;
        }
        
        .hours-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .hours-table tr:nth-child(even) {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .hours-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .hours-table td:first-child {
            font-weight: 500;
        }
        
        .staff-contacts {
            padding: 60px 5%;
            background: white;
        }
        
        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .staff-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .staff-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .staff-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            margin: 0 auto 20px;
            border: 3px solid var(--accent-color);
        }
        
        .staff-contact {
            margin-top: 15px;
        }
        
        .staff-contact a {
            color: var(--primary-color);
            display: block;
            margin-bottom: 5px;
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
            <li><a href="about">About</a></li>
            <li><a href="events">Events</a></li>
            <li><a href="ministries">Ministries</a></li>
            <li><a href="contact" class="active">Contact</a></li>
            <li><a href="login" class="login-btn">Login</a></li>
        </ul>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </nav>

    <!-- Contact Hero Section -->
    <section class="contact-hero">
        <div class="hero-content">
            <h1>Contact Us</h1>
            <p>We'd love to hear from you. Reach out with questions, prayer requests, or to learn more.</p>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="contact-container">
        <div class="container">
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">Choose your preferred method of contact</p>
            
            <div class="contact-grid">
                <div class="contact-info glass-card">
                    <div class="contact-icon">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div class="contact-method">
                        <h3>Our Location</h3>
                        <p><i class="fas fa-church"></i> St. Stephen C.O.U Kumi</p>
                        <p><i class="fas fa-road"></i> Otaaba Village, Northern Division</p>
                        <p><i class="fas fa-city"></i> Kumi District, Uganda</p>
                    </div>
                    
                    <div class="contact-method">
                        <h3>Contact Info</h3>
                        <p><i class="fas fa-phone"></i> +256 123 456 789</p>
                        <p><i class="fas fa-envelope"></i> info@ststephencou.org</p>
                        <p><i class="fas fa-clock"></i> Office Hours: Mon-Fri, 8AM-5PM</p>
                    </div>
                    
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="contact-form glass-card">
                    <h3 style="color: var(--secondary-color); margin-bottom: 25px;">Send Us a Message</h3>
                    <form>
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject" required>
                                <option value="" disabled selected>Select a topic</option>
                                <option value="general">General Inquiry</option>
                                <option value="prayer">Prayer Request</option>
                                <option value="visitor">First Time Visitor</option>
                                <option value="ministry">Ministry Information</option>
                                <option value="event">Event Question</option>
                                <option value="giving">Giving/Donations</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" name="message" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn" style="width: 100%;">Send Message</button>
                    </form>
                </div>
            </div>
            
            <div class="map-container glass-card">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.7526223157563!2d33.93661531532693!d3.123456789012345!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zM8KwMDcnMjQuNSJOIDMzwKw1NCc0OC4xIkU!5e0!3m2!1sen!2sug!4v1620000000000!5m2!1sen!2sug" allowfullscreen="" loading="lazy"></iframe>
            </div>
            
            <div class="office-hours glass-card" style="padding: 30px; margin-top: 40px;">
                <h3 class="section-title" style="font-size: 1.5rem; text-align: center;">Church Office Hours</h3>
                <table class="hours-table">
                    <tr>
                        <td>Sunday</td>
                        <td>7:00 AM - 2:00 PM (Worship Services)</td>
                    </tr>
                    <tr>
                        <td>Monday</td>
                        <td>8:00 AM - 5:00 PM</td>
                    </tr>
                    <tr>
                        <td>Tuesday</td>
                        <td>8:00 AM - 5:00 PM</td>
                    </tr>
                    <tr>
                        <td>Wednesday</td>
                        <td>8:00 AM - 5:00 PM</td>
                    </tr>
                    <tr>
                        <td>Thursday</td>
                        <td>8:00 AM - 5:00 PM</td>
                    </tr>
                    <tr>
                        <td>Friday</td>
                        <td>8:00 AM - 5:00 PM</td>
                    </tr>
                    <tr>
                        <td>Saturday</td>
                        <td>9:00 AM - 1:00 PM</td>
                    </tr>
                </table>
            </div>
        </div>
    </section>

    <!-- Staff Contacts -->
    <section class="staff-contacts">
        <div class="container">
            <h2 class="section-title">Key Staff Contacts</h2>
            <p class="section-subtitle">Reach out directly to our ministry leaders</p>
            
            <div class="staff-grid">
                <div class="staff-card glass-card">
                    <div class="staff-avatar" style="background-image: url('public/images/user1.jpeg');"></div>
                    <h3>Rev. Sarah Nakalema</h3>
                    <p>Senior Pastor</p>
                    <div class="staff-contact">
                        <a href="mailto:pastor@ststephencou.org"><i class="fas fa-envelope"></i> pastor@ststephencou.org</a>
                        <a href="tel:+256123456789"><i class="fas fa-phone"></i> +256 123 456 789</a>
                    </div>
                </div>
                
                <div class="staff-card glass-card">
                    <div class="staff-avatar" style="background-image: url('public/images/user2.jpeg');"></div>
                    <h3>Joseph Okello</h3>
                    <p>Church Administrator</p>
                    <div class="staff-contact">
                        <a href="mailto:admin@ststephencou.org"><i class="fas fa-envelope"></i> admin@ststephencou.org</a>
                        <a href="tel:+256987654321"><i class="fas fa-phone"></i> +256 987 654 321</a>
                    </div>
                </div>
                
                <div class="staff-card glass-card">
                    <div class="staff-avatar" style="background-image: url('public/images/user3.jpeg');"></div>
                    <h3>David Opio</h3>
                    <p>Youth Pastor</p>
                    <div class="staff-contact">
                        <a href="mailto:youth@ststephencou.org"><i class="fas fa-envelope"></i> youth@ststephencou.org</a>
                        <a href="tel:+256789123456"><i class="fas fa-phone"></i> +256 789 123 456</a>
                    </div>
                </div>
                
                <div class="staff-card glass-card">
                    <div class="staff-avatar" style="background-image: url('public/images/user4.jpeg');"></div>
                    <h3>Grace Amono</h3>
                    <p>Women's Ministry</p>
                    <div class="staff-contact">
                        <a href="mailto:women@ststephencou.org"><i class="fas fa-envelope"></i> women@ststephencou.org</a>
                        <a href="tel:+256456789123"><i class="fas fa-phone"></i> +256 456 789 123</a>
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
                    <li><a href="index">Home</a></li>
                    <li><a href="about">About Us</a></li>
                    <li><a href="ministries">Ministries</a></li>
                    <li><a href="events">Events</a></li>
                    <li><a href="contact">Contact</a></li>
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