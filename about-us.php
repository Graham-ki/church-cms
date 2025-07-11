<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        /* About Page Specific Styles */
        .about-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('public/images/banner.jpeg');
            
            background-position: center;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        
        .about-content {
            padding: 80px 5%;
            background: linear-gradient(to bottom, #f5f7fa 0%, #e4e8ed 100%);
        }
        
        .mission-vision {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin: 50px 0;
        }
        
        .mission-card, .vision-card {
            padding: 30px;
            border-radius: 15px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        
        .mission-card h3, .vision-card h3 {
            color: var(--secondary-color);
            margin-bottom: 20px;
            font-size: 1.5rem;
        }
        
        .leadership-section {
            padding: 60px 5%;
            background: white;
        }
        
        .leaders-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 40px;
        }
        
        .leader-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .leader-card:hover {
            transform: translateY(-10px);
        }
        
        .leader-img {
            height: 250px;
            background-size: cover;
            background-position: center;
        }
        
        .leader-info {
            padding: 20px;
            text-align: center;
        }
        
        .leader-info h4 {
            color: var(--secondary-color);
            margin-bottom: 5px;
        }
        
        .leader-info p {
            color: var(--primary-color);
            font-weight: 500;
        }
        
        .leader-social {
            margin-top: 15px;
        }
        
        .leader-social a {
            color: var(--dark-color);
            margin: 0 5px;
            transition: all 0.3s ease;
        }
        
        .leader-social a:hover {
            color: var(--primary-color);
        }
        
        .history-section {
            padding: 60px 5%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
        }
        
        .history-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
        }
        
        .milestone {
            display: flex;
            align-items: center;
            margin: 30px 0;
            text-align: left;
        }
        
        .milestone-icon {
            font-size: 2rem;
            margin-right: 20px;
            color: var(--accent-color);
        }
        
        .milestone-content {
            flex: 1;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar (Same as Homepage) -->
    <nav class="glass-nav">
        <div class="logo">
            <img src="public/images/logo.png" alt="Church Logo">
            <span>St. Stephen C.O.U</span>
        </div>
        <ul class="nav-links">
            <li><a href="index">Home</a></li>
            <li><a href="about" class="active">About</a></li>
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

    <!-- About Hero Section -->
    <section class="about-hero">
        <div class="hero-content">
            <h1>About Our Church</h1>
            <p>Discover our rich history, mission, and the team guiding our spiritual journey</p>
        </div>
    </section>

    <!-- About Content Section -->
    <section class="about-content">
        <div class="container">
            <h2 class="section-title">Our Story</h2>
            <p>St. Stephen C.O.U (Church of Uganda) has been a beacon of faith and hope in Kumi District since 1952. Founded by missionaries with a vision for spiritual transformation, our church has grown from a small gathering to a vibrant community impacting thousands of lives.</p>
            
            <div class="mission-vision">
                <div class="mission-card glass-card">
                    <div class="icon-title">
                        <i class="fas fa-bullseye" style="color: var(--accent-color); font-size: 2rem; margin-bottom: 15px;"></i>
                        <h3>Our Mission</h3>
                    </div>
                    <p>To lead people into a growing relationship with Jesus Christ through worship, discipleship, and service. We strive to be a church that reflects God's love in our community and beyond.</p>
                </div>
                
                <div class="vision-card glass-card">
                    <div class="icon-title">
                        <i class="fas fa-eye" style="color: var(--accent-color); font-size: 2rem; margin-bottom: 15px;"></i>
                        <h3>Our Vision</h3>
                    </div>
                    <p>To be a spiritually vibrant, multi-generational church that transforms lives through the power of the Gospel, equips believers for ministry, and serves as a catalyst for positive change in Kumi and surrounding regions.</p>
                </div>
            </div>
            
            <div class="core-values">
                <h3 class="sub-title"><i class="fas fa-heart" style="color: var(--accent-color); margin-right: 10px;"></i> Our Core Values</h3>
                <div class="values-grid">
                    <div class="value-item">
                        <i class="fas fa-pray" style="color: var(--primary-color);"></i>
                        <h4>Prayer</h4>
                        <p>We prioritize communion with God</p>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-bible" style="color: var(--primary-color);"></i>
                        <h4>Biblical Truth</h4>
                        <p>We ground everything in Scripture</p>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-hands-helping" style="color: var(--primary-color);"></i>
                        <h4>Service</h4>
                        <p>We follow Christ's example of serving</p>
                    </div>
                    <div class="value-item">
                        <i class="fas fa-users" style="color: var(--primary-color);"></i>
                        <h4>Community</h4>
                        <p>We grow better together</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="leadership-section">
        <div class="container">
            <h2 class="section-title">Church Leadership</h2>
            <p class="section-subtitle">Meet the dedicated team guiding our spiritual journey</p>
            
            <div class="leaders-grid">
                <div class="leader-card glass-card">
                    <div class="leader-img" style="background-image: url('public/images/user3.jpeg');"></div>
                    <div class="leader-info">
                        <h4>Rev. Sarah Nakalema</h4>
                        <p>Senior Pastor</p>
                        <div class="leader-social">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="leader-card glass-card">
                    <div class="leader-img" style="background-image: url('public/images/user1.jpeg');"></div>
                    <div class="leader-info">
                        <h4>Elder Joseph Okello</h4>
                        <p>Chairman, Church Council</p>
                        <div class="leader-social">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="leader-card glass-card">
                    <div class="leader-img" style="background-image: url('public/images/user2.jpeg');"></div>
                    <div class="leader-info">
                        <h4>Deaconess Grace Amono</h4>
                        <p>Women's Ministry Leader</p>
                        <div class="leader-social">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="leader-card glass-card">
                    <div class="leader-img" style="background-image: url('public/images/user4.jpeg');"></div>
                    <div class="leader-info">
                        <h4>Pastor David Opio</h4>
                        <p>Youth Ministry Pastor</p>
                        <div class="leader-social">
                            <a href="#"><i class="fab fa-facebook"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fas fa-envelope"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Church History Section -->
    <section class="history-section">
        <div class="container">
            <h2 class="section-title" style="color: white;">Our Journey</h2>
            
            <div class="history-content">
                <div class="milestone">
                    <div class="milestone-icon">
                        <i class="fas fa-church"></i>
                    </div>
                    <div class="milestone-content">
                        <h3>1952 - Foundation</h3>
                        <p>St. Stephen C.O.U was established by Anglican missionaries with just 12 founding members meeting under a mango tree.</p>
                    </div>
                </div>
                
                <div class="milestone">
                    <div class="milestone-icon">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="milestone-content">
                        <h3>1968 - First Permanent Structure</h3>
                        <p>The congregation built its first brick church building through communal labor and donations.</p>
                    </div>
                </div>
                
                <div class="milestone">
                    <div class="milestone-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="milestone-content">
                        <h3>1995 - Rapid Growth</h3>
                        <p>Under Rev. Michael Ogwal's leadership, membership grew to over 500, requiring expansion.</p>
                    </div>
                </div>
                
                <div class="milestone">
                    <div class="milestone-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <div class="milestone-content">
                        <h3>2010 - Current Sanctuary</h3>
                        <p>Dedication of our modern worship center accommodating 1,200 worshippers.</p>
                    </div>
                </div>
                
                <div class="milestone">
                    <div class="milestone-icon">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div class="milestone-content">
                        <h3>2020 - Digital Transformation</h3>
                        <p>Launched online services and digital ministry platforms to reach more people.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (Same as Homepage) -->
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
</body>
</html>