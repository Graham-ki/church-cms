<?php
session_start();
include_once 'config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ministries - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        /* Ministries Page Specific Styles */
        .ministries-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('public/images/banner.jpeg');
            
            background-position: center;
            height: 50vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        
        .ministries-intro {
            padding: 80px 5%;
            background: linear-gradient(to bottom, #f5f7fa 0%, #e4e8ed 100%);
            text-align: center;
        }
        
        .ministries-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .ministry-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .ministry-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .ministry-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .ministry-content {
            padding: 25px;
        }
        
        .ministry-meta {
            display: flex;
            justify-content: space-between;
            margin: 15px 0;
            color: var(--primary-color);
        }
        
        .ministry-meta span {
            display: flex;
            align-items: center;
        }
        
        .ministry-meta i {
            margin-right: 5px;
        }
        
        .ministry-desc {
            margin: 15px 0;
            color: #555;
        }
        
        .join-ministry {
            display: inline-block;
            margin-top: 15px;
            color: var(--primary-color);
            font-weight: 600;
        }
        
        .join-ministry i {
            margin-left: 5px;
            transition: all 0.3s ease;
        }
        
        .join-ministry:hover i {
            transform: translateX(5px);
        }
        
        .ministry-leaders {
            padding: 60px 5%;
            background: white;
        }
        
        .leaders-slider {
            margin-top: 40px;
            position: relative;
        }
        
        .leader-card-mini {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 10px;
            padding: 20px;
            margin: 0 10px;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .leader-card-mini:hover {
            transform: translateY(-5px);
        }
        
        .leader-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            margin: 0 auto 15px;
            border: 3px solid var(--accent-color);
        }
        
        .call-to-serve {
            padding: 80px 5%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            text-align: center;
        }
        
        .serve-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .serve-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 30px;
            transition: all 0.3s ease;
        }
        
        .serve-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.2);
        }
        
        .serve-icon {
            font-size: 2.5rem;
            color: var(--accent-color);
            margin-bottom: 20px;
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
            <li><a href="home">Home</a></li>
            <li><a href="about">About</a></li>
            <li><a href="events">Events</a></li>
            <li><a href="ministries" class="active">Ministries</a></li>
            <li><a href="forum">Forum</a></li>
            <li><a href="contact">Contact</a></li>
              <?php
            if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                echo '<li><a href="dashboard" class="dashboard-btn">My Dashboard</a></li>
                <li><a href="logout" class="login-btn">Logout</a></li>
                ';
            } elseif (isset($_SESSION['clergy']) && $_SESSION['clergy'] === true) {
                echo '<li><a href="clergy-dashboard" class="dashboard-btn">My Dashboard</a></li>
                <li><a href="logout" class="login-btn">Logout</a></li>
                ';
            } elseif (isset($_SESSION['member']) && $_SESSION['member'] === true) {
                echo '<li><a href="member-dashboard" class="dashboard-btn">My Dashboard</a></li>
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

    <!-- Ministries Hero Section -->
    <section class="ministries-hero">
        <div class="hero-content">
            <h1>Our Ministries</h1>
            <p>Discover opportunities to grow, serve, and connect</p>
        </div>
    </section>

    <!-- Ministries Intro -->
    <section class="ministries-intro">
        <div class="container">
            <h2 class="section-title">Serving Together in Christ</h2>
            <p>At St. Stephen C.O.U, we believe every member has been gifted by God to serve the body of Christ. Our ministries provide avenues for spiritual growth, fellowship, and service to our church and community. Find your place to belong and make a difference!</p>
            
            <div class="ministries-grid">
                <!-- Children's Ministry -->
                 <?php
                 if(isset($_GET['id'])){
                    $ministryId = $_GET['id'];
                    if(isset($_SESSION['member']) && $_SESSION['member'] === true){
                        $memberId = $_SESSION['member_id'];
                        $sql = "INSERT INTO participants (activity_id, participant_id, join_date) VALUES ($ministryId, $memberId, NOW())";
                        $result = mysqli_query($conn, $sql);
                    }else{
                        echo "<script>alert('You need to be logged in as a member to join a ministry.'); window.location.href = 'login';</script>";
                    }   
                    echo "<script>alert('You have successfully joined the ministry!');</script>";
                 }
                 $ministry= " SELECT * from ministries";
                 $result = mysqli_query($conn, $ministry);
                 if (mysqli_num_rows($result) > 0) {
                     while ($row = mysqli_fetch_assoc($result)) {
                            echo '<div class="ministry-card glass-card">
                            <div class="ministry-content">
                                <h3>'.$row['name'].' Ministry</h3>
                                <div class="ministry-meta">
                                    <span><i class="fas fa-users"></i> '.$row['age_range'].'</span>
                                    <span><i class="fas fa-calendar"></i> '.$row['day'].'</span>
                                    <span><i class="fas fa-clock"></i> '.$row['time'].'</span>
                                </div>
                                <p class="ministry-desc">'.$row['description'].'</p>
                                <a href="ministries.php?id='.$row['id'].'" class="join-ministry">Join <i class="fas fa-arrow-right"></i></a>
                            </div>    
                        </div>';
                     }
                 }
                 ?>
                <!-- Children's Ministry -->
                <!--<div class="ministry-card glass-card">
                    <div class="ministry-content">
                        <h3>Children's Ministry</h3>
                        <div class="ministry-meta">
                            <span><i class="fas fa-users"></i> Ages 3-12</span>
                            <span><i class="fas fa-clock"></i> Sundays 11AM</span>
                        </div>
                        <p class="ministry-desc">Engaging, age-appropriate Bible teaching and activities that help children grow in their faith through fun, interactive lessons and worship.</p>
                        <a href="#" class="join-ministry">Learn More <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>-->
            </div>
        </div>
    </section>

    <!-- Ministry Leaders Section 
    <section class="ministry-leaders">
        <div class="container">
            <h2 class="section-title">Ministry Leaders</h2>
            <p class="section-subtitle">Meet the dedicated servants leading our ministries</p>
            
            <div class="leaders-slider">
                <div class="leader-card-mini">
                    <div class="leader-avatar" style="background-image: url('public/images/user1.jpeg');"></div>
                    <h4>Racheal Akello</h4>
                    <p>Children's Ministry Director</p>
                    <div class="leader-contact">
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
                
                <div class="leader-card-mini">
                    <div class="leader-avatar" style="background-image: url('public/images/user2.jpeg');"></div>
                    <h4>David Opio</h4>
                    <p>Youth Pastor</p>
                    <div class="leader-contact">
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
                
                <div class="leader-card-mini">
                    <div class="leader-avatar" style="background-image: url('public/images/user3.jpeg');"></div>
                    <h4>Grace Amono</h4>
                    <p>Women's Ministry Leader</p>
                    <div class="leader-contact">
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
                
                <div class="leader-card-mini">
                    <div class="leader-avatar" style="background-image: url('public/images/user4.jpeg');"></div>
                    <h4>Joseph Okello</h4>
                    <p>Men's Ministry Coordinator</p>
                    <div class="leader-contact">
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
                
                <div class="leader-card-mini">
                    <div class="leader-avatar" style="background-image: url('public/images/user5.jpeg');"></div>
                    <h4>Sarah Nakalema</h4>
                    <p>Worship Ministry Director</p>
                    <div class="leader-contact">
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
                
                <div class="leader-card-mini">
                    <div class="leader-avatar" style="background-image: url('public/images/user7.png');"></div>
                    <h4>Mark Ocen</h4>
                    <p>Outreach Ministry Leader</p>
                    <div class="leader-contact">
                        <a href="#"><i class="fas fa-envelope"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>-->

    <!-- Call to Serve Section -->
    <section class="call-to-serve">
        <div class="container">
            <h2 class="section-title" style="color: white;">Ready to Serve?</h2>
            <p>God has gifted each believer for ministry. Discover how you can use your gifts to serve the body of Christ.</p>
            
            <div class="serve-options">
                <div class="serve-card glass-card">
                    <div class="serve-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Volunteer</h3>
                    <p>Join one of our ministry teams and make a difference in our church and community.</p>
                    <a href="#" class="btn" style="margin-top: 20px;">Serve Now</a>
                </div>
                
                <div class="serve-card glass-card">
                    <div class="serve-icon">
                        <i class="fas fa-gifts"></i>
                    </div>
                    <h3>Discover Your Gifts</h3>
                    <p>Take our spiritual gifts assessment to discover how God has uniquely equipped you.</p>
                    <a href="#" class="btn" style="margin-top: 20px;">Take Assessment</a>
                </div>
                
                <div class="serve-card glass-card">
                    <div class="serve-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3>Start a Ministry</h3>
                    <p>Have a vision for a new ministry? We'd love to explore it with you.</p>
                    <a href="#" class="btn" style="margin-top: 20px;">Share Your Idea</a>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
    <script>
        // Initialize leaders slider
        $(document).ready(function(){
            $('.leaders-slider').slick({
                dots: true,
                infinite: true,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            });
        });
    </script>
</body>
</html>