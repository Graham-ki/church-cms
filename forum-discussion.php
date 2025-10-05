<?php
session_start();
include 'config/db.php';

// Check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$thread_id = isset($_GET['thread_id']) ? intval($_GET['thread_id']) : 0;
$user_id = $_SESSION['user_id'];

// Handle AJAX requests first
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    // For AJAX requests, we need thread_id to be valid
    if($thread_id <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid thread ID']);
        exit;
    }
    
    switch($_POST['action']) {
        case 'send_message':
            $content = mysqli_real_escape_string($conn, $_POST['content']);
            $parent_id = isset($_POST['parent_id']) ? intval($_POST['parent_id']) : 0;
            
            // Insert message
            $sql = "INSERT INTO thread_messages (thread_id, author_id, content, parent_id) 
                    VALUES ($thread_id, $user_id, '$content', " . ($parent_id ?: 'NULL') . ")";
            
            if(mysqli_query($conn, $sql)) {
                $message_id = mysqli_insert_id($conn);
                
                // Update participant
                $participant_sql = "INSERT INTO thread_participants (thread_id, user_id, last_seen) 
                                   VALUES ($thread_id, $user_id, NOW()) 
                                   ON DUPLICATE KEY UPDATE last_seen = NOW()";
                mysqli_query($conn, $participant_sql);
                
                echo json_encode(['success' => true, 'message_id' => $message_id]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to send message: ' . mysqli_error($conn)]);
            }
            exit;
            
        case 'like_message':
            $message_id = intval($_POST['message_id']);
            
            // Check if already liked
            $check_sql = "SELECT id FROM message_likes WHERE message_id = $message_id AND user_id = $user_id";
            $check_result = mysqli_query($conn, $check_sql);
            
            if(mysqli_num_rows($check_result) > 0) {
                // Unlike
                $sql = "DELETE FROM message_likes WHERE message_id = $message_id AND user_id = $user_id";
                $action = 'unliked';
            } else {
                // Like
                $sql = "INSERT INTO message_likes (message_id, user_id) VALUES ($message_id, $user_id)";
                $action = 'liked';
            }
            
            if(mysqli_query($conn, $sql)) {
                // Get updated like count
                $count_sql = "SELECT COUNT(*) as like_count FROM message_likes WHERE message_id = $message_id";
                $count_result = mysqli_query($conn, $count_sql);
                $like_count = mysqli_fetch_assoc($count_result)['like_count'];
                
                echo json_encode(['success' => true, 'action' => $action, 'like_count' => $like_count]);
            } else {
                echo json_encode(['success' => false, 'error' => mysqli_error($conn)]);
            }
            exit;
            
        case 'get_messages':
            $messages_sql = "SELECT tm.*, u.username as author_name, u.avatar,
                                    (SELECT COUNT(*) FROM message_likes WHERE message_id = tm.id) as like_count,
                                    (SELECT COUNT(*) FROM thread_messages WHERE parent_id = tm.id) as reply_count,
                                    p.username as parent_author
                             FROM thread_messages tm
                             LEFT JOIN users u ON tm.author_id = u.id
                             LEFT JOIN users p ON tm.parent_id = (SELECT id FROM thread_messages WHERE id = tm.parent_id)
                             WHERE tm.thread_id = $thread_id AND tm.is_deleted = 0
                             ORDER BY tm.created_at ASC";
            $messages_result = mysqli_query($conn, $messages_sql);
            $messages = [];
            
            while($row = mysqli_fetch_assoc($messages_result)) {
                $messages[] = $row;
            }
            echo json_encode($messages);
            exit;
            
        case 'get_participants':
            $participants_sql = "SELECT tp.user_id, u.username, u.avatar, tp.last_seen,
                                        TIMESTAMPDIFF(MINUTE, tp.last_seen, NOW()) as minutes_ago
                                 FROM thread_participants tp
                                 LEFT JOIN users u ON tp.user_id = u.id
                                 WHERE tp.thread_id = $thread_id
                                 ORDER BY tp.last_seen DESC";
            $participants_result = mysqli_query($conn, $participants_sql);
            $participants = [];
            
            while($row = mysqli_fetch_assoc($participants_result)) {
                $participants[] = $row;
            }
            echo json_encode($participants);
            exit;
    }
}

// Initialize variables to avoid undefined errors
$thread = null;
$messages = [];
$participants = [];
$threads = [];

// Get thread details only if thread_id is valid
if($thread_id > 0) {
    $thread_sql = "SELECT dt.*, u.name as author_name, f.name as forum_name, f.icon as forum_icon, f.id as forum_id
                   FROM discussion_threads dt 
                   LEFT JOIN users u ON dt.author_id = u.id 
                   LEFT JOIN forums f ON dt.forum_id = f.id 
                   WHERE dt.id = $thread_id";
    $thread_result = mysqli_query($conn, $thread_sql);
    
    if($thread_result && mysqli_num_rows($thread_result) > 0) {
        $thread = mysqli_fetch_assoc($thread_result);
    } else {
        // Thread not found
        $error = "Thread not found or doesn't exist.";
    }
} else {
    $error = "No thread ID provided.";
}

// If thread exists, load related data
if($thread) {
    // Get messages
    $messages_sql = "SELECT tm.*, u.name as author_name, u.avatar,
                            (SELECT COUNT(*) FROM message_likes WHERE message_id = tm.id) as like_count,
                            (SELECT COUNT(*) FROM thread_messages WHERE parent_id = tm.id) as reply_count,
                            p.name as parent_author
                     FROM thread_messages tm
                     LEFT JOIN users u ON tm.author_id = u.id
                     LEFT JOIN users p ON tm.parent_id = (SELECT id FROM thread_messages WHERE id = tm.parent_id)
                     WHERE tm.thread_id = $thread_id AND tm.is_deleted = 0
                     ORDER BY tm.created_at ASC";
    $messages_result = mysqli_query($conn, $messages_sql);
    
    if($messages_result) {
        while($row = mysqli_fetch_assoc($messages_result)) {
            $messages[] = $row;
        }
    }

    // Get participants
    $participants_sql = "SELECT tp.user_id, u.name, u.avatar, tp.last_seen,
                                TIMESTAMPDIFF(MINUTE, tp.last_seen, NOW()) as minutes_ago
                         FROM thread_participants tp
                         LEFT JOIN users u ON tp.user_id = u.id
                         WHERE tp.thread_id = $thread_id
                         ORDER BY tp.last_seen DESC";
    $participants_result = mysqli_query($conn, $participants_sql);
    
    if($participants_result) {
        while($row = mysqli_fetch_assoc($participants_result)) {
            $participants[] = $row;
        }
    }

    // Get related threads
    $threads_sql = "SELECT dt.*, u.name as author_name,
                           (SELECT COUNT(*) FROM thread_messages WHERE thread_id = dt.id) as message_count,
                           MAX(tm.created_at) as last_activity
                    FROM discussion_threads dt
                    LEFT JOIN users u ON dt.author_id = u.id
                    LEFT JOIN thread_messages tm ON dt.id = tm.thread_id
                    WHERE dt.forum_id = {$thread['forum_id']} AND dt.is_locked = 0 AND dt.id != $thread_id
                    GROUP BY dt.id
                    ORDER BY dt.is_pinned DESC, last_activity DESC
                    LIMIT 10";
    $threads_result = mysqli_query($conn, $threads_sql);
    
    if($threads_result) {
        while($row = mysqli_fetch_assoc($threads_result)) {
            $threads[] = $row;
        }
    }

    // Update current user's participant status
    $update_participant_sql = "INSERT INTO thread_participants (thread_id, user_id, last_seen) 
                              VALUES ($thread_id, $user_id, NOW()) 
                              ON DUPLICATE KEY UPDATE last_seen = NOW()";
    mysqli_query($conn, $update_participant_sql);
}

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
    <title><?php echo $thread ? htmlspecialchars($thread['title']) : 'Thread Not Found'; ?> - St. Stephen C.O.U</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="public/css/styles.css">
    <style>
        /* Chat Page Specific Styles */
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
        
        .chat-container {
            padding: 40px 5%;
            background: linear-gradient(to bottom, #f5f7fa 0%, #e4e8ed 100%);
            min-height: 70vh;
        }
        
        .chat-wrapper {
            display: flex;
            gap: 30px;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .chat-sidebar {
            flex: 0 0 300px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            padding: 25px;
            height: fit-content;
        }
        
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border-radius: 15px;
            overflow: hidden;
        }
        
        .chat-header {
            padding: 20px 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .chat-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .chat-icon {
            font-size: 1.8rem;
            color: var(--primary-color);
        }
        
        .chat-info {
            flex: 1;
        }
        
        .chat-info h2 {
            margin: 0;
            color: var(--primary-color);
        }
        
        .chat-info p {
            margin: 5px 0 0;
            color: #666;
            font-size: 0.9rem;
        }
        
        .chat-participants {
            display: flex;
            align-items: center;
            margin-top: 15px;
        }
        
        .participant-count {
            color: #666;
            font-size: 0.9rem;
        }
        
        .participant-avatars {
            display: flex;
            margin-left: 10px;
        }
        
        .participant-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            margin-left: -10px;
            border: 2px solid white;
        }
        
        .chat-messages {
            flex: 1;
            padding: 25px;
            overflow-y: auto;
            max-height: 500px;
        }
        
        .message {
            margin-bottom: 25px;
            display: flex;
            gap: 15px;
        }
        
        .message-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 2px solid var(--accent-color);
            flex-shrink: 0;
        }
        
        .message-content {
            flex: 1;
        }
        
        .message-header {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
        }
        
        .message-author {
            font-weight: bold;
            color: var(--primary-color);
            margin-right: 10px;
        }
        
        .message-time {
            color: #888;
            font-size: 0.85rem;
        }
        
        .message-text {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px;
            border-radius: 15px;
            border-top-left-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .message-reply {
            margin-top: 15px;
            padding-left: 20px;
            border-left: 3px solid var(--accent-color);
        }
        
        .reply-indicator {
            font-size: 0.8rem;
            color: var(--accent-color);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .message-actions {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .message-action {
            color: #888;
            font-size: 0.85rem;
            cursor: pointer;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .message-action:hover {
            color: var(--primary-color);
        }
        
        .message-action.liked {
            color: var(--primary-color);
        }
        
        .chat-input {
            padding: 20px 25px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .reply-preview {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .reply-info {
            font-size: 0.9rem;
            color: #666;
        }
        
        .cancel-reply {
            color: #888;
            cursor: pointer;
        }
        
        .input-group {
            display: flex;
            gap: 15px;
        }
        
        .message-input {
            flex: 1;
            padding: 15px 20px;
            border: none;
            border-radius: 30px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            font-family: inherit;
            resize: none;
            min-height: 50px;
            max-height: 150px;
        }
        
        .send-button {
            background: var(--primary-color);
            color: white;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .send-button:hover {
            background: var(--secondary-color);
            transform: scale(1.05);
        }
        
        .sidebar-section {
            margin-bottom: 30px;
        }
        
        .sidebar-section h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .participant-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .participant-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .participant-avatar-small {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            border: 2px solid var(--accent-color);
        }
        
        .participant-name {
            font-weight: 500;
        }
        
        .participant-role {
            font-size: 0.8rem;
            color: #888;
        }
        
        .online-indicator {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #4CAF50;
            margin-left: auto;
        }
        
        .thread-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .thread-item {
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            display: block;
            color: inherit;
        }
        
        .thread-item:hover {
            background: rgba(255, 255, 255, 0.2);
            text-decoration: none;
            color: inherit;
        }
        
        .thread-item.active {
            background: rgba(255, 255, 255, 0.3);
            border-left: 3px solid var(--primary-color);
        }
        
        .thread-title {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .thread-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.8rem;
            color: #888;
        }
        
        @media (max-width: 992px) {
            .chat-wrapper {
                flex-direction: column;
            }
            
            .chat-sidebar {
                flex: none;
                width: 100%;
            }
        }
        
        .error-container {
            padding: 60px 20px;
            text-align: center;
            background: white;
            border-radius: 15px;
            margin: 20px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
        }
        
        .error-container h2 {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        
        .error-container p {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1rem;
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

    <?php if(!$thread): ?>
        <!-- Error State -->
        <section class="chat-hero">
            <div class="hero-content">
                <h1>Thread Not Found</h1>
                <p>We couldn't find the discussion thread you're looking for</p>
            </div>
        </section>

        <section class="chat-container">
            <div class="error-container">
                <h2><i class="fas fa-exclamation-triangle"></i> Oops!</h2>
                <p><?php echo isset($error) ? $error : 'The thread you are looking for does not exist or has been removed.'; ?></p>
                <div style="margin-top: 30px;">
                    <a href="forums.php" class="btn" style="margin-right: 15px;">
                        <i class="fas fa-arrow-left"></i> Back to Forums
                    </a>
                    <a href="create-thread.php" class="btn" style="background: var(--secondary-color);">
                        <i class="fas fa-plus"></i> Start New Discussion
                    </a>
                </div>
            </div>
        </section>

    <?php else: ?>
        <!-- Normal Chat Page -->
        <section class="chat-hero">
            <div class="hero-content">
                <h1><?php echo htmlspecialchars($thread['title']); ?></h1>
                <p><?php echo htmlspecialchars($thread['forum_name']); ?> - Join the conversation and grow together in faith</p>
            </div>
        </section>

        <!-- Breadcrumb Navigation -->
        <section class="breadcrumb-nav">
            <div class="container">
                <nav style="font-size: 0.9rem; color: #666;">
                    <a href="forums.php">Forums</a>
                    <span style="margin: 0 10px;">></span>
                    <a href="forum-view.php?forum_id=<?php echo $thread['forum_id']; ?>">
                        <?php echo htmlspecialchars($thread['forum_name']); ?>
                    </a>
                    <span style="margin: 0 10px;">></span>
                    <span style="color: #333;"><?php echo htmlspecialchars($thread['title']); ?></span>
                </nav>
            </div>
        </section>

        <section class="chat-container">
            <div class="chat-wrapper">
                <!-- Sidebar -->
                <div class="chat-sidebar">
                    <div class="sidebar-section">
                        <h3>Active Participants</h3>
                        <ul class="participant-list" id="participant-list">
                            <?php foreach($participants as $participant): 
                                $is_online = $participant['minutes_ago'] < 5;
                            ?>
                            <li class="participant-item">
                                <div class="participant-avatar-small" 
                                     style="background-image: url('public/images/user-logo.jpg');">
                                </div>
                                <div>
                                    <div class="participant-name"><?php echo htmlspecialchars($participant['name']); ?></div>
                                    <div class="participant-role">Member</div>
                                </div>
                                <?php if($is_online): ?>
                                <div class="online-indicator" title="Online"></div>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    
                    <div class="sidebar-section">
                        <h3>Discussion Threads</h3>
                        <ul class="thread-list">
                            <li class="thread-item active">
                                <a href="forum-discussion.php?thread_id=<?php echo $thread_id; ?>" style="text-decoration: none; color: inherit; display: block;">
                                    <div class="thread-title"><?php echo htmlspecialchars($thread['title']); ?></div>
                                    <div class="thread-meta">
                                        <span><?php echo count($messages); ?> messages</span>
                                        <span>Current</span>
                                    </div>
                                </a>
                            </li>
                            <?php foreach($threads as $t): ?>
                            <li class="thread-item">
                                <a href="forum-discussion.php?thread_id=<?php echo $t['id']; ?>" style="text-decoration: none; color: inherit; display: block;">
                                    <div class="thread-title"><?php echo htmlspecialchars($t['title']); ?></div>
                                    <div class="thread-meta">
                                        <span><?php echo $t['message_count']; ?> messages</span>
                                        <span><?php echo time_elapsed_string($t['last_activity']); ?></span>
                                    </div>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Main Chat Area -->
                <div class="chat-main">
                    <div class="chat-header">
                        <div class="chat-title">
                            <div class="chat-icon">
                                <i class="<?php echo htmlspecialchars($thread['forum_icon']); ?>"></i>
                            </div>
                            <div class="chat-info">
                                <h2><?php echo htmlspecialchars($thread['title']); ?></h2>
                                <p>Started by <?php echo htmlspecialchars($thread['author_name']); ?> • <?php echo date('M j, Y', strtotime($thread['created_at'])); ?></p>
                            </div>
                        </div>
                        <div class="chat-participants">
                            <span class="participant-count"><?php echo count($participants); ?> participants</span>
                            <div class="participant-avatars" id="participant-avatars">
                                <?php 
                                $display_count = min(3, count($participants));
                                for($i = 0; $i < $display_count; $i++): 
                                ?>
                                <div class="participant-avatar" 
                                     style="background-image: url('public/images/avatars/<?php echo $participants[$i]['avatar'] ?: 'default.png'; ?>');">
                                </div>
                                <?php endfor; ?>
                                <?php if(count($participants) > 3): ?>
                                <div class="participant-avatar" style="background-color: #4CAF50; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    +<?php echo count($participants) - 3; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="chat-messages" id="chat-messages">
                        <?php foreach($messages as $message): ?>
                        <div class="message" id="message-<?php echo $message['id']; ?>">
                            <div class="message-avatar" 
                                 style="background-image: url('public/images/avatars/<?php echo $message['avatar'] ?: 'default.png'; ?>');">
                            </div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-author"><?php echo htmlspecialchars($message['author_name']); ?></span>
                                    <span class="message-time"><?php echo time_elapsed_string($message['created_at']); ?></span>
                                </div>
                                <div class="message-text">
                                    <?php if($message['parent_id']): ?>
                                    <div class="reply-indicator">
                                        <i class="fas fa-reply"></i> Replying to <?php echo htmlspecialchars($message['parent_author']); ?>
                                    </div>
                                    <?php endif; ?>
                                    <p><?php echo nl2br(htmlspecialchars($message['content'])); ?></p>
                                </div>
                                <div class="message-actions">
                                    <span class="message-action like-btn" 
                                          data-message-id="<?php echo $message['id']; ?>">
                                        <i class="far fa-thumbs-up"></i> Like (<span class="like-count"><?php echo $message['like_count']; ?></span>)
                                    </span>
                                    <span class="message-action reply-btn" 
                                          data-message-id="<?php echo $message['id']; ?>" 
                                          data-author="<?php echo htmlspecialchars($message['author_name']); ?>">
                                        <i class="far fa-comment"></i> Reply
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="chat-input">
                        <div class="reply-preview" id="reply-preview" style="display: none;">
                            <div class="reply-info">Replying to <strong id="reply-author"></strong></div>
                            <div class="cancel-reply" id="cancel-reply"><i class="fas fa-times"></i></div>
                        </div>
                        <div class="input-group">
                            <textarea class="message-input" id="message-input" placeholder="Type your message..." rows="1"></textarea>
                            <button class="send-button" id="send-button">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

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
        <?php if($thread): ?>
        // Chat functionality with real backend (only if thread exists)
        document.addEventListener('DOMContentLoaded', function() {
            const chatMessages = document.getElementById('chat-messages');
            const messageInput = document.getElementById('message-input');
            const sendButton = document.getElementById('send-button');
            const replyPreview = document.getElementById('reply-preview');
            const cancelReply = document.getElementById('cancel-reply');
            const replyAuthor = document.getElementById('reply-author');
            const participantList = document.getElementById('participant-list');
            const participantAvatars = document.getElementById('participant-avatars');
            
            let replyingTo = null;
            const threadId = <?php echo $thread_id; ?>;
            const currentUserId = <?php echo $user_id; ?>;
            
            // Auto-resize textarea
            messageInput.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
            
            // Reply functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.reply-btn')) {
                    const button = e.target.closest('.reply-btn');
                    replyingTo = button.dataset.messageId;
                    replyAuthor.textContent = button.dataset.author;
                    replyPreview.style.display = 'flex';
                    messageInput.focus();
                }
            });
            
            // Cancel reply
            cancelReply.addEventListener('click', function() {
                replyingTo = null;
                replyPreview.style.display = 'none';
            });
            
            // Like functionality
            document.addEventListener('click', function(e) {
                if (e.target.closest('.like-btn')) {
                    const button = e.target.closest('.like-btn');
                    const messageId = button.dataset.messageId;
                    likeMessage(messageId, button);
                }
            });
            
            // Send message
            sendButton.addEventListener('click', sendMessage);
            messageInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    sendMessage();
                }
            });
            
            // Auto-refresh messages and participants
            setInterval(refreshMessages, 5000);
            setInterval(refreshParticipants, 10000);
            
            // Scroll to bottom on load
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            function sendMessage() {
                const content = messageInput.value.trim();
                if (!content) return;
                
                const formData = new FormData();
                formData.append('action', 'send_message');
                formData.append('content', content);
                if (replyingTo) {
                    formData.append('parent_id', replyingTo);
                }
                
                fetch('forum-discussion.php?thread_id=' + threadId, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageInput.value = '';
                        messageInput.style.height = 'auto';
                        replyingTo = null;
                        replyPreview.style.display = 'none';
                        refreshMessages();
                        refreshParticipants();
                    } else {
                        alert('Failed to send message: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to send message');
                });
            }
            
            function likeMessage(messageId, button) {
                const formData = new FormData();
                formData.append('action', 'like_message');
                formData.append('message_id', messageId);
                
                fetch('forum-discussion.php?thread_id=' + threadId, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const likeCount = button.querySelector('.like-count');
                        likeCount.textContent = data.like_count;
                        
                        if (data.action === 'liked') {
                            button.classList.add('liked');
                        } else {
                            button.classList.remove('liked');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
            }
            
            function refreshMessages() {
                const formData = new FormData();
                formData.append('action', 'get_messages');
                
                fetch('forum-discussion.php?thread_id=' + threadId, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(messages => {
                    // Store current scroll position
                    const scrollPos = chatMessages.scrollTop;
                    const isScrolledToBottom = chatMessages.scrollHeight - chatMessages.clientHeight <= chatMessages.scrollTop + 1;
                    
                    // Update messages (in real app, you'd update only new messages)
                    chatMessages.innerHTML = '';
                    messages.forEach(message => {
                        const messageDiv = document.createElement('div');
                        messageDiv.className = 'message';
                        messageDiv.id = 'message-' + message.id;
                        messageDiv.innerHTML = `
                            <div class="message-avatar" 
                                 style="background-image: url('public/images/avatars/${message.avatar || 'default.png'}');">
                            </div>
                            <div class="message-content">
                                <div class="message-header">
                                    <span class="message-author">${escapeHtml(message.author_name)}</span>
                                    <span class="message-time">${timeAgo(message.created_at)}</span>
                                </div>
                                <div class="message-text">
                                    ${message.parent_id ? `
                                    <div class="reply-indicator">
                                        <i class="fas fa-reply"></i> Replying to ${escapeHtml(message.parent_author)}
                                    </div>
                                    ` : ''}
                                    <p>${escapeHtml(message.content).replace(/\n/g, '<br>')}</p>
                                </div>
                                <div class="message-actions">
                                    <span class="message-action like-btn" data-message-id="${message.id}">
                                        <i class="far fa-thumbs-up"></i> Like (<span class="like-count">${message.like_count}</span>)
                                    </span>
                                    <span class="message-action reply-btn" data-message-id="${message.id}" data-author="${escapeHtml(message.author_name)}">
                                        <i class="far fa-comment"></i> Reply
                                    </span>
                                </div>
                            </div>
                        `;
                        chatMessages.appendChild(messageDiv);
                    });
                    
                    // Restore scroll position or scroll to bottom if was at bottom
                    if (isScrolledToBottom) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                    } else {
                        chatMessages.scrollTop = scrollPos;
                    }
                })
                .catch(error => console.error('Error:', error));
            }
            
            function refreshParticipants() {
                const formData = new FormData();
                formData.append('action', 'get_participants');
                
                fetch('forum-discussion.php?thread_id=' + threadId, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(participants => {
                    // Update participant list
                    participantList.innerHTML = '';
                    participants.forEach(participant => {
                        const isOnline = participant.minutes_ago < 5;
                        const participantItem = document.createElement('li');
                        participantItem.className = 'participant-item';
                        participantItem.innerHTML = `
                            <div class="participant-avatar-small" 
                                 style="background-image: url('public/images/avatars/${participant.avatar || 'default.png'}');">
                            </div>
                            <div>
                                <div class="participant-name">${escapeHtml(participant.username)}</div>
                                <div class="participant-role">Member</div>
                            </div>
                            ${isOnline ? '<div class="online-indicator" title="Online"></div>' : ''}
                        `;
                        participantList.appendChild(participantItem);
                    });
                    
                    // Update participant avatars
                    updateParticipantAvatars(participants);
                })
                .catch(error => console.error('Error:', error));
            }
            
            function updateParticipantAvatars(participants) {
                participantAvatars.innerHTML = '';
                const displayCount = Math.min(3, participants.length);
                
                for(let i = 0; i < displayCount; i++) {
                    const avatar = document.createElement('div');
                    avatar.className = 'participant-avatar';
                    avatar.style.backgroundImage = `url('public/images/avatars/${participants[i].avatar || 'default.png'}')`;
                    participantAvatars.appendChild(avatar);
                }
                
                if(participants.length > 3) {
                    const more = document.createElement('div');
                    more.className = 'participant-avatar';
                    more.style.backgroundColor = '#4CAF50';
                    more.style.color = 'white';
                    more.style.display = 'flex';
                    more.style.alignItems = 'center';
                    more.style.justifyContent = 'center';
                    more.style.fontWeight = 'bold';
                    more.textContent = '+' + (participants.length - 3);
                    participantAvatars.appendChild(more);
                }
                
                // Update participant count
                document.querySelector('.participant-count').textContent = participants.length + ' participants';
            }
            
            // Helper functions
            function escapeHtml(unsafe) {
                return unsafe
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
            
            function timeAgo(dateTime) {
                const now = new Date();
                const time = new Date(dateTime);
                const diffMs = now - time;
                const diffSecs = Math.floor(diffMs / 1000);
                const diffMins = Math.floor(diffSecs / 60);
                const diffHours = Math.floor(diffMins / 60);
                const diffDays = Math.floor(diffHours / 24);
                const diffWeeks = Math.floor(diffDays / 7);
                const diffMonths = Math.floor(diffDays / 30);
                const diffYears = Math.floor(diffDays / 365);

                if (diffYears > 0) return diffYears + ' year' + (diffYears > 1 ? 's' : '') + ' ago';
                if (diffMonths > 0) return diffMonths + ' month' + (diffMonths > 1 ? 's' : '') + ' ago';
                if (diffWeeks > 0) return diffWeeks + ' week' + (diffWeeks > 1 ? 's' : '') + ' ago';
                if (diffDays > 0) return diffDays + ' day' + (diffDays > 1 ? 's' : '') + ' ago';
                if (diffHours > 0) return diffHours + ' hour' + (diffHours > 1 ? 's' : '') + ' ago';
                if (diffMins > 0) return diffMins + ' minute' + (diffMins > 1 ? 's' : '') + ' ago';
                
                return 'just now';
            }
        });
        <?php endif; ?>
    </script>
</body>
</html>