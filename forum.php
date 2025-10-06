<?php
session_start();
include 'config/db.php';

// Function to get forum categories with statistics
function getForumCategories($conn) {
    $sql = "SELECT f.*, 
                   COUNT(DISTINCT dt.id) as discussions_count,
                   COUNT(DISTINCT dt.author_id) as participants_count
            FROM forums f
            LEFT JOIN discussion_threads dt ON f.id = dt.forum_id AND dt.is_locked = 0
            WHERE f.is_active = 1
            GROUP BY f.id
            ORDER BY f.sort_order ASC, f.name ASC";
    
    $result = mysqli_query($conn, $sql);
    $forums = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $forums[] = $row;
        }
    }
    
    return $forums;
}

// Function to get recent discussions
function getRecentDiscussions($conn, $limit = 5) {
    $sql = "SELECT dt.*, f.name as forum_name, u.name as author_name, u.avatar,
                   (SELECT COUNT(*) FROM thread_messages WHERE thread_id = dt.id) as reply_count,
                   (SELECT COUNT(*) FROM thread_participants WHERE thread_id = dt.id) as view_count
            FROM discussion_threads dt
            LEFT JOIN forums f ON dt.forum_id = f.id
            LEFT JOIN users u ON dt.author_id = u.id
            WHERE dt.is_locked = 0
            ORDER BY dt.created_at DESC
            LIMIT $limit";
    
    $result = mysqli_query($conn, $sql);
    $discussions = [];
    
    if ($result && mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $discussions[] = $row;
        }
    }
    
    return $discussions;
}

// Get data from database
$forum_categories = getForumCategories($conn);
$recent_discussions = getRecentDiscussions($conn, 3);

// Close connection
mysqli_close($conn);

// Time formatting function
function time_elapsed_string($datetime, $full = false) {
    if(empty($datetime)) return 'Recently';
    
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = [];
    
    if ($diff->y > 0) $string[] = $diff->y . ' year' . ($diff->y > 1 ? 's' : '');
    if ($diff->m > 0) $string[] = $diff->m . ' month' . ($diff->m > 1 ? 's' : '');
    if ($diff->d > 0) $string[] = $diff->d . ' day' . ($diff->d > 1 ? 's' : '');
    if ($diff->h > 0) $string[] = $diff->h . ' hour' . ($diff->h > 1 ? 's' : '');
    if ($diff->i > 0) $string[] = $diff->i . ' minute' . ($diff->i > 1 ? 's' : '');
    
    if (empty($string)) return 'just now';
    
    if (!$full) {
        return $string[0] . ' ago';
    }
    
    return implode(', ', $string) . ' ago';
}
?>
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
            cursor: pointer;
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
            line-height: 1.6;
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
            font-family: inherit;
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
            transition: background 0.3s;
        }
        
        .search-forums button:hover {
            background: var(--secondary-color);
        }
        
        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: var(--primary-color);
            color: white;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-decoration: none;
            color: white;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .no-data i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #ddd;
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
            <li><a href="home.php">Home</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="events.php">Events</a></li>
            <li><a href="ministries.php">Ministries</a></li>
            <li><a href="forums.php" class="active">Forums</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php
            if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                echo '<li><a href="dashboard.php" class="dashboard-btn">My Dashboard</a></li>
                <li><a href="logout.php" class="login-btn">Logout</a></li>';
            } elseif (isset($_SESSION['clergy']) && $_SESSION['clergy'] === true) {
                echo '
                <li><a href="logout.php" class="login-btn">Logout</a></li>';
            } elseif (isset($_SESSION['member']) && $_SESSION['member'] === true) {
                echo '
                <li><a href="logout.php" class="login-btn">Logout</a></li>';
            } else {
                echo '<li><a href="login.php" class="login-btn">Login</a></li>';
            }
            ?>
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
                <input type="text" id="forum-search" placeholder="Search forums and discussions...">
                <button type="submit" id="search-button"><i class="fas fa-search"></i></button>
            </div>
            
            <div class="forum-categories" id="forum-categories">
                <?php if(empty($forum_categories)): ?>
                    <div class="no-data">
                        <i class="fas fa-comments"></i>
                        <h3>No Forums Available</h3>
                        <p>Forum categories will be available soon. Check back later!</p>
                    </div>
                <?php else: ?>
                    <?php foreach($forum_categories as $forum): ?>
                    <div class="forum-card glass-card" data-forum-name="<?php echo strtolower(htmlspecialchars($forum['name'])); ?>" data-forum-desc="<?php echo strtolower(htmlspecialchars($forum['description'])); ?>">
                        <div class="forum-icon">
                            <i class="<?php echo htmlspecialchars($forum['icon']); ?>"></i>
                        </div>
                        <h3><?php echo htmlspecialchars($forum['name']); ?></h3>
                        <p><?php echo htmlspecialchars($forum['description']); ?></p>
                        <div class="forum-stats">
                            <span><i class="fas fa-comments"></i> <?php echo $forum['discussions_count']; ?> Discussions</span>
                            <span><i class="fas fa-user-friends"></i> <?php echo $forum['participants_count']; ?> Participants</span>
                        </div>
                        <a href="forum-view.php?forum_id=<?php echo $forum['id']; ?>" class="btn" style="margin-top: 20px;">Browse Discussions</a>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Recent Discussions -->
    <section class="recent-discussions">
        <div class="container">
            <h2 class="section-title">Recent Discussions</h2>
            <p class="section-subtitle">Latest activity across all forums</p>
            
            <div class="discussion-list" id="discussion-list">
                <?php if(empty($recent_discussions)): ?>
                    <div class="no-data">
                        <i class="fas fa-comment-slash"></i>
                        <h3>No Discussions Yet</h3>
                        <p>Be the first to start a discussion in our community!</p>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <a href="create-thread.php" class="btn" style="margin-top: 20px;">Start First Discussion</a>
                        <?php else: ?>
                            <a href="login.php" class="btn" style="margin-top: 20px;">Login to Start Discussion</a>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php foreach($recent_discussions as $discussion): ?>
                    <div class="discussion-item glass-card" onclick="window.location.href='forum-discussion.php?thread_id=<?php echo $discussion['id']; ?>'">
                        <div class="discussion-header">
                            <div class="discussion-author">
                                <div class="author-avatar" style="background-image: url('public/images/user-logo.jpg')"></div>
                                <div>
                                    <h4><?php echo htmlspecialchars($discussion['author_name']); ?></h4>
                                    <small>Posted in <?php echo htmlspecialchars($discussion['forum_name']); ?></small>
                                </div>
                            </div>
                            <div class="discussion-meta">
                                <span><i class="far fa-clock"></i> <?php echo time_elapsed_string($discussion['created_at']); ?></span>
                            </div>
                        </div>
                        <h3><?php echo htmlspecialchars($discussion['title']); ?></h3>
                        <p class="discussion-excerpt"><?php echo substr(htmlspecialchars($discussion['content']), 0, 150); ?>...</p>
                        <div class="discussion-actions">
                            <div class="discussion-tags">
                                <span class="tag"><?php echo htmlspecialchars($discussion['forum_name']); ?></span>
                            </div>
                            <div class="discussion-stats">
                                <span><i class="far fa-comment"></i> <?php echo $discussion['reply_count']; ?> replies</span>
                                <span><i class="far fa-eye"></i> <?php echo $discussion['view_count']; ?> views</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <?php if(!empty($recent_discussions)): ?>
            <div style="text-align: center; margin-top: 40px;">
                <a href="all-discussions.php" class="btn">View All Discussions</a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Start Discussion CTA -->
    <section class="start-discussion">
        <div class="container">
            <h2 class="section-title" style="color: white;">Start a Discussion</h2>
            <p>Have a question or topic you'd like to discuss with the church community? Start a new conversation!</p>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="create-thread.php" class="btn" style="background: white; color: var(--primary-color); margin-top: 30px;">New Discussion</a>
            <?php else: ?>
                <a href="login.php" class="btn" style="background: white; color: var(--primary-color); margin-top: 30px;">Login to Start Discussion</a>
            <?php endif; ?>
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
                    <li><a href="home.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="ministries.php">Ministries</a></li>
                    <li><a href="forums.php">Forums</a></li>
                    <li><a href="contact.php">Contact</a></li>
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
        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('forum-search');
            const searchButton = document.getElementById('search-button');
            const forumCards = document.querySelectorAll('.forum-card');
            const discussionItems = document.querySelectorAll('.discussion-item');
            
            function performSearch() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                
                if (searchTerm === '') {
                    // Show all items if search is empty
                    forumCards.forEach(card => {
                        card.style.display = 'block';
                    });
                    discussionItems.forEach(item => {
                        item.style.display = 'flex';
                    });
                    return;
                }
                
                let foundInForums = false;
                let foundInDiscussions = false;
                
                // Search in forum categories
                forumCards.forEach(card => {
                    const forumName = card.dataset.forumName || '';
                    const forumDesc = card.dataset.forumDesc || '';
                    const cardText = forumName + ' ' + forumDesc;
                    
                    if (cardText.includes(searchTerm)) {
                        card.style.display = 'block';
                        foundInForums = true;
                    } else {
                        card.style.display = 'none';
                    }
                });
                
                // Search in recent discussions
                discussionItems.forEach(item => {
                    const itemText = item.textContent.toLowerCase();
                    if (itemText.includes(searchTerm)) {
                        item.style.display = 'flex';
                        foundInDiscussions = true;
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Show no results message if nothing found
                showNoResultsMessage(!foundInForums && !foundInDiscussions && searchTerm !== '');
            }
            
            function showNoResultsMessage(show) {
                // Remove existing no results message
                const existingMessage = document.getElementById('no-results-message');
                if (existingMessage) {
                    existingMessage.remove();
                }
                
                if (show) {
                    const noResultsMessage = document.createElement('div');
                    noResultsMessage.id = 'no-results-message';
                    noResultsMessage.className = 'no-data';
                    noResultsMessage.innerHTML = `
                        <i class="fas fa-search"></i>
                        <h3>No Results Found</h3>
                        <p>No forums or discussions match your search criteria. Try different keywords.</p>
                    `;
                    
                    // Insert after forum categories
                    const forumCategories = document.getElementById('forum-categories');
                    forumCategories.parentNode.insertBefore(noResultsMessage, forumCategories.nextSibling);
                }
            }
            
            searchButton.addEventListener('click', performSearch);
            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
            
            // Clear search when input is empty
            searchInput.addEventListener('input', function() {
                if (this.value === '') {
                    performSearch();
                }
            });
            
            // Make discussion items clickable
            discussionItems.forEach(item => {
                item.style.cursor = 'pointer';
            });
        });
    </script>
</body>
</html>