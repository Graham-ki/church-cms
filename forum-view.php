<?php
session_start();
include 'config/db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$forum_id = isset($_GET['forum_id']) ? intval($_GET['forum_id']) : 0;

// Get forum details
if($forum_id > 0) {
    $forum_sql = "SELECT * FROM forums WHERE id = $forum_id AND is_active = 1";
    $forum_result = mysqli_query($conn, $forum_sql);
    $forum = mysqli_fetch_assoc($forum_result);
}

if(!$forum) {
    die('Forum not found');
}

// Get threads for this forum
$threads_sql = "SELECT dt.*, u.name as author_name, u.avatar,
                       (SELECT COUNT(*) FROM thread_messages WHERE thread_id = dt.id) as message_count,
                       (SELECT COUNT(*) FROM thread_participants WHERE thread_id = dt.id) as participant_count,
                       MAX(tm.created_at) as last_activity
                FROM discussion_threads dt
                LEFT JOIN users u ON dt.author_id = u.id
                LEFT JOIN thread_messages tm ON dt.id = tm.thread_id
                WHERE dt.forum_id = $forum_id AND dt.is_locked = 0
                GROUP BY dt.id
                ORDER BY dt.is_pinned DESC, last_activity DESC";
$threads_result = mysqli_query($conn, $threads_sql);
$threads = [];

while($row = mysqli_fetch_assoc($threads_result)) {
    $threads[] = $row;
}

mysqli_close($conn);

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
    <title><?php echo htmlspecialchars($forum['name']); ?> - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        .forum-hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('public/images/banner.jpeg');
            background-size: cover;
            background-position: center;
            height: 40vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        
        .breadcrumb-nav {
            padding: 20px 5%;
            background: white;
            border-bottom: 1px solid #eee;
        }
        
        .breadcrumb-nav a {
            color: var(--primary-color);
            text-decoration: none;
        }
        
        .breadcrumb-nav a:hover {
            text-decoration: underline;
        }
        
        .threads-container {
            padding: 40px 5%;
            background: linear-gradient(to bottom, #f5f7fa 0%, #e4e8ed 100%);
            min-height: 70vh;
        }
        
        .thread-list {
            margin-top: 30px;
        }
        
        .thread-item {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .thread-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }
        
        .thread-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
        }
        
        .thread-author {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .author-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 2px solid var(--accent-color);
        }
        
        .thread-stats {
            display: flex;
            gap: 20px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .thread-stats span {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .pinned-badge {
            background: var(--primary-color);
            color: white;
            padding: 3px 10px;
            border-radius: 30px;
            font-size: 0.8rem;
            margin-left: 10px;
        }
        
        .no-data {
            text-align: center;
            padding: 60px;
            color: #666;
        }
        
        .no-data i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
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
            <li><a href="forum.php" class="active">Forums</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php
            if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
                echo '<li><a href="dashboard.php" class="dashboard-btn">My Dashboard</a></li>
                <li><a href="logout.php" class="login-btn">Logout</a></li>';
            } elseif (isset($_SESSION['clergy']) && $_SESSION['clergy'] === true) {
                echo '<li><a href="clergy-dashboard.php" class="dashboard-btn">My Dashboard</a></li>
                <li><a href="logout.php" class="login-btn">Logout</a></li>';
            } elseif (isset($_SESSION['member']) && $_SESSION['member'] === true) {
                echo '<li><a href="member-dashboard.php" class="dashboard-btn">My Dashboard</a></li>
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

    <section class="forum-hero">
        <div class="hero-content">
            <div class="forum-icon" style="font-size: 4rem; margin-bottom: 20px;">
                <i class="<?php echo htmlspecialchars($forum['icon']); ?>"></i>
            </div>
            <h1><?php echo htmlspecialchars($forum['name']); ?></h1>
            <p><?php echo htmlspecialchars($forum['description']); ?></p>
        </div>
    </section>

    <section class="breadcrumb-nav">
        <div class="container">
            <nav style="font-size: 0.9rem; color: #666;">
                <a href="forums.php">Forums</a>
                <span style="margin: 0 10px;">></span>
                <span style="color: #333;"><?php echo htmlspecialchars($forum['name']); ?></span>
            </nav>
        </div>
    </section>

    <section class="threads-container">
        <div class="container">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
                <h2 class="section-title">Discussion Threads</h2>
                <a href="create-thread.php?forum_id=<?php echo $forum_id; ?>" class="btn">
                    <i class="fas fa-plus"></i> New Thread
                </a>
            </div>
            
            <?php if(empty($threads)): ?>
                <div class="no-data">
                    <i class="fas fa-comments"></i>
                    <h3>No Threads Yet</h3>
                    <p>Be the first to start a discussion in this forum!</p>
                    <a href="create-thread.php?forum_id=<?php echo $forum_id; ?>" class="btn" style="margin-top: 20px;">
                        Start First Thread
                    </a>
                </div>
            <?php else: ?>
                <div class="thread-list">
                    <?php foreach($threads as $thread): ?>
                    <div class="thread-item" onclick="window.location.href='forum-discussion.php?thread_id=<?php echo $thread['id']; ?>'">
                        <div class="thread-header">
                            <div>
                                <h3 style="margin: 0 0 10px 0; display: flex; align-items: center;">
                                    <?php echo htmlspecialchars($thread['title']); ?>
                                    <?php if($thread['is_pinned']): ?>
                                    <span class="pinned-badge"><i class="fas fa-thumbtack"></i> Pinned</span>
                                    <?php endif; ?>
                                </h3>
                                <div class="thread-author">
                                    <div class="author-avatar" style="background-image: url('public/images/avatars/<?php echo $thread['avatar'] ?: 'default.png'; ?>');"></div>
                                    <div>
                                        <div style="font-weight: 500;"><?php echo htmlspecialchars($thread['author_name']); ?></div>
                                        <small><?php echo time_elapsed_string($thread['created_at']); ?></small>
                                    </div>
                                </div>
                            </div>
                            <div class="thread-stats">
                                <span><i class="far fa-comment"></i> <?php echo $thread['message_count']; ?> replies</span>
                                <span><i class="fas fa-users"></i> <?php echo $thread['participant_count']; ?> participants</span>
                                <span><i class="far fa-clock"></i> <?php echo time_elapsed_string($thread['last_activity']); ?></span>
                            </div>
                        </div>
                        <p style="color: #666; margin: 15px 0 0 0; line-height: 1.5;">
                            <?php echo substr(htmlspecialchars($thread['content']), 0, 200); ?>...
                        </p>
                    </div>
                    <?php endforeach; ?>
                </div>
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
</body>
</html>