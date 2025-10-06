<?php
session_start();
include 'config/db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$forum_id = isset($_GET['forum_id']) ? intval($_GET['forum_id']) : 0;

// Handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $user_id = $_SESSION['user_id'];
    $forum_id = intval($_POST['forum_id']);
    
    $sql = "INSERT INTO discussion_threads (forum_id, title, content, author_id) 
            VALUES ($forum_id, '$title', '$content', $user_id)";
    
    if(mysqli_query($conn, $sql)) {
        $thread_id = mysqli_insert_id($conn);
        header("Location: forum-discussion.php?thread_id=$thread_id");
        exit;
    } else {
        $error = "Failed to create thread: " . mysqli_error($conn);
    }
}

// Get forum details
if($forum_id > 0) {
    $forum_sql = "SELECT * FROM forums WHERE id = $forum_id AND is_active = 1";
    $forum_result = mysqli_query($conn, $forum_sql);
    $forum = mysqli_fetch_assoc($forum_result);
}

// Get all forums for dropdown
$forums_sql = "SELECT * FROM forums WHERE is_active = 1 ORDER BY name";
$forums_result = mysqli_query($conn, $forums_sql);
$all_forums = [];

while($row = mysqli_fetch_assoc($forums_result)) {
    $all_forums[] = $row;
}

if(!$forum && $forum_id > 0) {
    die('Forum not found');
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Thread - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        .chat-hero {
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
        
        .chat-container {
            padding: 40px 5%;
            background: linear-gradient(to bottom, #f5f7fa 0%, #e4e8ed 100%);
            min-height: 70vh;
        }
        
        .form-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 200px;
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #c33;
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
        
        .btn-secondary {
            background: #666;
        }
        
        .btn-secondary:hover {
            background: #555;
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

    <section class="chat-hero">
        <div class="hero-content">
            <h1>Start New Discussion</h1>
            <p><?php echo $forum ? 'Share your thoughts in ' . htmlspecialchars($forum['name']) : 'Create a new discussion thread'; ?></p>
        </div>
    </section>

    <section class="chat-container">
        <div class="container">
            <div class="form-container">
                <?php if(isset($error)): ?>
                    <div class="error-message">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="form-group">
                        <label for="forum_id">Forum Category</label>
                        <select name="forum_id" id="forum_id" required>
                            <option value="">Select a forum</option>
                            <?php foreach($all_forums as $f): ?>
                            <option value="<?php echo $f['id']; ?>" <?php echo ($forum && $f['id'] == $forum['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($f['name']); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="title">Thread Title</label>
                        <input type="text" name="title" id="title" required placeholder="Enter a descriptive title for your discussion">
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Your Message</label>
                        <textarea name="content" id="content" required placeholder="Share your thoughts, questions, or insights..."></textarea>
                    </div>
                    
                    <div style="display: flex; gap: 15px;">
                        <button type="submit" class="btn">
                            <i class="fas fa-paper-plane"></i> Create Thread
                        </button>
                        <?php if($forum): ?>
                        <a href="forum-view.php?forum_id=<?php echo $forum_id; ?>" class="btn btn-secondary">
                            Cancel
                        </a>
                        <?php else: ?>
                        <a href="forums.php" class="btn btn-secondary">
                            Cancel
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
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