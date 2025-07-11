<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Forums - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        /* Forums Page Specific Styles */
        .forums-hero {
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
        
        .forums-container {
            padding: 80px 5%;
            background: linear-gradient(to bottom, #f5f7fa 0%, #e4e8ed 100%);
        }
        
        .forum-categories {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .forum-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            padding: 25px;
        }
        
        .forum-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .forum-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }
        
        .forum-stats {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            color: var(--primary-color);
            font-size: 0.9rem;
        }
        
        .forum-stats span {
            display: flex;
            align-items: center;
        }
        
        .forum-stats i {
            margin-right: 5px;
        }
        
        .recent-discussions {
            padding: 60px 5%;
            background: white;
        }
        
        .discussion-list {
            margin-top: 40px;
        }
        
        .discussion-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }
        
        .discussion-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .discussion-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .discussion-author {
            display: flex;
            align-items: center;
        }
        
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            margin-right: 10px;
            border: 2px solid var(--accent-color);
        }
        
        .discussion-meta {
            display: flex;
            gap: 15px;
            color: #777;
            font-size: 0.9rem;
        }
        
        .discussion-meta i {
            margin-right: 5px;
            color: var(--primary-color);
        }
        
        .discussion-excerpt {
            margin: 15px 0;
            color: #555;
        }
        
        .discussion-actions {
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        
        .discussion-tags {
            display: flex;
            gap: 10px;
        }
        
        .tag {
            background: var(--accent-color);
            color: white;
            padding: 3px 10px;
            border-radius: 30px;
            font-size: 0.8rem;
        }
        
        .discussion-stats {
            display: flex;
            gap: 15px;
        }
        
        .discussion-stats span {
            display: flex;
            align-items: center;
        }
        
        .start-discussion {
            padding: 60px 5%;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            text-align: center;
        }
        
        .search-forums {
            margin: 40px auto;
            max-width: 600px;
            position: relative;
        }
        
        .search-forums input {
            width: 100%;
            padding: 15px 20px;
            border-radius: 30px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding-right: 50px;
        }
        
        .search-forums button {
            position: absolute;
            right: 5px;
            top: 5px;
            background: var(--primary-color);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
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
            <li><a href="forums" class="active">Forums</a></li>
            <li><a href="contact">Contact</a></li>
            <li><a href="login" class="login-btn">Login</a></li>
        </ul>
        <div class="hamburger">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
    </nav>

    <!-- Forums Hero Section -->
    <section class="forums-hero">
        <div class="hero-content">
            <h1>Community Forums</h1>
            <p>Connect, discuss, and grow together in faith</p>
        </div>
    </section>

    <!-- Forums Categories -->
    <section class="forums-container">
        <div class="container">
            <h2 class="section-title">Discussion Categories</h2>
            <p class="section-subtitle">Join conversations that matter to you</p>
            
            <div class="search-forums">
                <input type="text" placeholder="Search discussions...">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
            
            <div class="forum-categories">
                <!-- Bible Study Forum -->
                <div class="forum-card glass-card">
                    <div class="forum-icon">
                        <i class="fas fa-bible"></i>
                    </div>
                    <h3>Bible Study</h3>
                    <p>Discuss Scripture passages, ask questions, and share insights from your personal study.</p>
                    <div class="forum-stats">
                        <span><i class="fas fa-comments"></i> 245 Discussions</span>
                        <span><i class="fas fa-user-friends"></i> 1.2K Participants</span>
                    </div>
                    <a href="#" class="btn" style="margin-top: 20px;">Join Discussion</a>
                </div>
                
                <!-- Prayer Requests -->
                <div class="forum-card glass-card">
                    <div class="forum-icon">
                        <i class="fas fa-pray"></i>
                    </div>
                    <h3>Prayer Requests</h3>
                    <p>Share prayer needs and pray for others in our church family.</p>
                    <div class="forum-stats">
                        <span><i class="fas fa-comments"></i> 189 Requests</span>
                        <span><i class="fas fa-user-friends"></i> 876 Participants</span>
                    </div>
                    <a href="#" class="btn" style="margin-top: 20px;">Share Request</a>
                </div>
                
                <!-- Christian Living -->
                <div class="forum-card glass-card">
                    <div class="forum-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h3>Christian Living</h3>
                    <p>Discuss practical aspects of living out your faith in daily life.</p>
                    <div class="forum-stats">
                        <span><i class="fas fa-comments"></i> 156 Discussions</span>
                        <span><i class="fas fa-user-friends"></i> 943 Participants</span>
                    </div>
                    <a href="#" class="btn" style="margin-top: 20px;">Join Discussion</a>
                </div>
                
                <!-- Marriage & Family -->
                <div class="forum-card glass-card">
                    <div class="forum-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3>Marriage & Family</h3>
                    <p>Building godly families in today's world. Share wisdom and encouragement.</p>
                    <div class="forum-stats">
                        <span><i class="fas fa-comments"></i> 98 Discussions</span>
                        <span><i class="fas fa-user-friends"></i> 542 Participants</span>
                    </div>
                    <a href="#" class="btn" style="margin-top: 20px;">Join Discussion</a>
                </div>
                
                <!-- Youth Corner -->
                <div class="forum-card glass-card">
                    <div class="forum-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3>Youth Corner</h3>
                    <p>Discussions by and for our young people (ages 13-25).</p>
                    <div class="forum-stats">
                        <span><i class="fas fa-comments"></i> 112 Discussions</span>
                        <span><i class="fas fa-user-friends"></i> 387 Participants</span>
                    </div>
                    <a href="#" class="btn" style="margin-top: 20px;">Join Discussion</a>
                </div>
                
                <!-- Missions & Outreach -->
                <div class="forum-card glass-card">
                    <div class="forum-icon">
                        <i class="fas fa-globe-africa"></i>
                    </div>
                    <h3>Missions & Outreach</h3>
                    <p>Share ideas and experiences about local and global missions.</p>
                    <div class="forum-stats">
                        <span><i class="fas fa-comments"></i> 76 Discussions</span>
                        <span><i class="fas fa-user-friends"></i> 321 Participants</span>
                    </div>
                    <a href="#" class="btn" style="margin-top: 20px;">Join Discussion</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Discussions -->
    <section class="recent-discussions">
        <div class="container">
            <h2 class="section-title">Recent Discussions</h2>
            <p class="section-subtitle">Latest activity across all forums</p>
            
            <div class="discussion-list">
                <!-- Discussion 1 -->
                <div class="discussion-item glass-card">
                    <div class="discussion-header">
                        <div class="discussion-author">
                            <div class="author-avatar" style="background-image: url('public/images/user1.jpeg');"></div>
                            <div>
                                <h4>James Okello</h4>
                                <small>Posted in Bible Study</small>
                            </div>
                        </div>
                        <div class="discussion-meta">
                            <span><i class="far fa-clock"></i> 2 hours ago</span>
                        </div>
                    </div>
                    <h3>Understanding Romans 8 - The Spirit's Role</h3>
                    <p class="discussion-excerpt">I've been studying Romans 8 and would love to hear others' perspectives on verses 26-27 about the Spirit interceding for us. How have you experienced this in your prayer life?</p>
                    <div class="discussion-actions">
                        <div class="discussion-tags">
                            <span class="tag">Bible Study</span>
                            <span class="tag">Romans</span>
                        </div>
                        <div class="discussion-stats">
                            <span><i class="far fa-comment"></i> 14 replies</span>
                            <span><i class="far fa-eye"></i> 87 views</span>
                        </div>
                    </div>
                </div>
                
                <!-- Discussion 2 -->
                <div class="discussion-item glass-card">
                    <div class="discussion-header">
                        <div class="discussion-author">
                            <div class="author-avatar" style="background-image: url('public/images/user2.jpeg');"></div>
                            <div>
                                <h4>Sarah Nalwoga</h4>
                                <small>Posted in Prayer Requests</small>
                            </div>
                        </div>
                        <div class="discussion-meta">
                            <span><i class="far fa-clock"></i> 5 hours ago</span>
                        </div>
                    </div>
                    <h3>Prayer for My Mother's Healing</h3>
                    <p class="discussion-excerpt">Please pray for my mother who was diagnosed with malaria yesterday. She's running a high fever and we're trusting God for complete healing. Thank you church family!</p>
                    <div class="discussion-actions">
                        <div class="discussion-tags">
                            <span class="tag">Prayer</span>
                            <span class="tag">Healing</span>
                        </div>
                        <div class="discussion-stats">
                            <span><i class="far fa-comment"></i> 23 prayers</span>
                            <span><i class="far fa-eye"></i> 112 views</span>
                        </div>
                    </div>
                </div>
                
                <!-- Discussion 3 -->
                <div class="discussion-item glass-card">
                    <div class="discussion-header">
                        <div class="discussion-author">
                            <div class="author-avatar" style="background-image: url('public/images/user3.jpeg');"></div>
                            <div>
                                <h4>David Opio</h4>
                                <small>Posted in Youth Corner</small>
                            </div>
                        </div>
                        <div class="discussion-meta">
                            <span><i class="far fa-clock"></i> 1 day ago</span>
                        </div>
                    </div>
                    <h3>How Do You Stay Strong in Faith at School?</h3>
                    <p class="discussion-excerpt">Fellow youth, I'm curious how you maintain your Christian witness in school environments that might not be supportive of faith. What practical tips do you have?</p>
                    <div class="discussion-actions">
                        <div class="discussion-tags">
                            <span class="tag">Youth</span>
                            <span class="tag">Faith</span>
                        </div>
                        <div class="discussion-stats">
                            <span><i class="far fa-comment"></i> 18 replies</span>
                            <span><i class="far fa-eye"></i> 145 views</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="#" class="btn">View All Discussions</a>
            </div>
        </div>
    </section>

    <!-- Start Discussion CTA -->
    <section class="start-discussion">
        <div class="container">
            <h2 class="section-title" style="color: white;">Start a Discussion</h2>
            <p>Have a question or topic you'd like to discuss with the church community? Start a new conversation!</p>
            <a href="#" class="btn" style="background: white; color: var(--primary-color); margin-top: 30px;">New Discussion</a>
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
                    <li><a href="forums.html">Forums</a></li>
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