<?php
include_once '../config/db.php';
// Set headers for JSON response
header('Content-Type: application/json');

// Get the action parameter
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    if ($action === 'get_inbox') {
        getInboxMessages($conn);
    } elseif ($action === 'get_sent') {
        getSentMessages($conn);
    } elseif ($action === 'get_drafts') {
        getDraftMessages($conn);
    } elseif ($action === 'get_trash') {
        getTrashMessages($conn);
    } elseif ($action === 'mark_read') {
        markMessageAsRead($conn);
    } elseif ($action === 'delete_message') {
        deleteMessage($conn);
    } elseif ($action === 'send_message') {
        sendMessage($conn);
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid action']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

function getInboxMessages($conn) {
    // Check if required columns exist, if not use fallback
    $checkColumns = "SHOW COLUMNS FROM communications LIKE 'is_read'";
    $result = mysqli_query($conn, $checkColumns);
    
    if (mysqli_num_rows($result) > 0) {
        // New schema with is_read, is_trash columns
        $query = "SELECT id, title, message, sent_by, sent_at, audience, is_read, is_trash 
                  FROM communications 
                  WHERE (is_trash = 0 OR is_trash IS NULL) AND sent_by != 'You' 
                  ORDER BY sent_at DESC";
    } else {
        // Fallback for old schema - assume all messages are inbox if not sent by 'You'
        $query = "SELECT id, title, message, sent_by, sent_at, audience 
                  FROM communications 
                  WHERE sent_by != 'You' 
                  ORDER BY sent_at DESC";
    }
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception('Query failed: ' . mysqli_error($conn));
    }
    
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = [
            'id' => $row['id'],
            'subject' => $row['title'],
            'sender' => $row['sent_by'],
            'preview' => substr(strip_tags($row['message']), 0, 100) . '...',
            'time' => formatDate($row['sent_at']),
            'read' => isset($row['is_read']) ? (bool)$row['is_read'] : false,
            'attachments' => false,
            'fullBody' => $row['message'],
            'recipients' => $row['audience']
        ];
    }
    
    echo json_encode(['success' => true, 'messages' => $messages]);
}

function getSentMessages($conn) {
    $checkColumns = "SHOW COLUMNS FROM communications LIKE 'is_trash'";
    $result = mysqli_query($conn, $checkColumns);
    
    if (mysqli_num_rows($result) > 0) {
        $query = "SELECT id, title, message, sent_by, sent_at, audience, is_read, is_trash 
                  FROM communications 
                  WHERE (is_trash = 0 OR is_trash IS NULL) AND sent_by = 'You' 
                  ORDER BY sent_at DESC";
    } else {
        $query = "SELECT id, title, message, sent_by, sent_at, audience 
                  FROM communications 
                  WHERE sent_by = 'You' 
                  ORDER BY sent_at DESC";
    }
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception('Query failed: ' . mysqli_error($conn));
    }
    
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = [
            'id' => $row['id'],
            'subject' => $row['title'],
            'sender' => $row['sent_by'],
            'preview' => substr(strip_tags($row['message']), 0, 100) . '...',
            'time' => formatDate($row['sent_at']),
            'read' => true, // Sent messages are always read
            'attachments' => false,
            'fullBody' => $row['message'],
            'recipients' => $row['audience']
        ];
    }
    
    echo json_encode(['success' => true, 'messages' => $messages]);
}

function getDraftMessages($conn) {
    $checkColumns = "SHOW COLUMNS FROM communications LIKE 'is_draft'";
    $result = mysqli_query($conn, $checkColumns);
    
    if (mysqli_num_rows($result) > 0) {
        $query = "SELECT id, title, message, sent_by, sent_at, audience, is_read, is_trash 
                  FROM communications 
                  WHERE is_draft = 1 AND (is_trash = 0 OR is_trash IS NULL) 
                  ORDER BY sent_at DESC";
    } else {
        // If no draft column, return empty array
        echo json_encode(['success' => true, 'messages' => []]);
        return;
    }
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception('Query failed: ' . mysqli_error($conn));
    }
    
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = [
            'id' => $row['id'],
            'subject' => $row['title'],
            'sender' => $row['sent_by'],
            'preview' => substr(strip_tags($row['message']), 0, 100) . '...',
            'time' => formatDate($row['sent_at']),
            'read' => true,
            'attachments' => false,
            'fullBody' => $row['message'],
            'recipients' => $row['audience']
        ];
    }
    
    echo json_encode(['success' => true, 'messages' => $messages]);
}

function getTrashMessages($conn) {
    $checkColumns = "SHOW COLUMNS FROM communications LIKE 'is_trash'";
    $result = mysqli_query($conn, $checkColumns);
    
    if (mysqli_num_rows($result) > 0) {
        $query = "SELECT id, title, message, sent_by, sent_at, audience, is_read, is_trash 
                  FROM communications 
                  WHERE is_trash = 1 
                  ORDER BY sent_at DESC";
    } else {
        // If no trash column, return empty array
        echo json_encode(['success' => true, 'messages' => []]);
        return;
    }
    
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        throw new Exception('Query failed: ' . mysqli_error($conn));
    }
    
    $messages = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = [
            'id' => $row['id'],
            'subject' => $row['title'],
            'sender' => $row['sent_by'],
            'preview' => substr(strip_tags($row['message']), 0, 100) . '...',
            'time' => formatDate($row['sent_at']),
            'read' => isset($row['is_read']) ? (bool)$row['is_read'] : true,
            'attachments' => false,
            'fullBody' => $row['message'],
            'recipients' => $row['audience']
        ];
    }
    
    echo json_encode(['success' => true, 'messages' => $messages]);
}

function markMessageAsRead($conn) {
    $messageId = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
    
    if ($messageId > 0) {
        // Check if is_read column exists
        $checkColumn = "SHOW COLUMNS FROM communications LIKE 'is_read'";
        $result = mysqli_query($conn, $checkColumn);
        
        if (mysqli_num_rows($result) > 0) {
            $query = "UPDATE communications SET is_read = 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $messageId);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to mark as read']);
            }
        } else {
            // If column doesn't exist, still return success
            echo json_encode(['success' => true]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid message ID']);
    }
}

function deleteMessage($conn) {
    $messageId = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
    
    if ($messageId > 0) {
        // Check if is_trash column exists
        $checkColumn = "SHOW COLUMNS FROM communications LIKE 'is_trash'";
        $result = mysqli_query($conn, $checkColumn);
        
        if (mysqli_num_rows($result) > 0) {
            // Soft delete - move to trash
            $query = "UPDATE communications SET is_trash = 1 WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $messageId);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete message']);
            }
        } else {
            // If no trash column, do a hard delete
            $query = "DELETE FROM communications WHERE id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'i', $messageId);
            
            if (mysqli_stmt_execute($stmt)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Failed to delete message']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid message ID']);
    }
}

function sendMessage($conn) {
    $title = isset($_POST['title']) ? mysqli_real_escape_string($conn, $_POST['title']) : '';
    $message = isset($_POST['message']) ? mysqli_real_escape_string($conn, $_POST['message']) : '';
    $audience = isset($_POST['audience']) ? mysqli_real_escape_string($conn, $_POST['audience']) : '';
    
    if (!empty($title) && !empty($message)) {
        // Check if new columns exist
        $checkColumns = "SHOW COLUMNS FROM communications LIKE 'is_read'";
        $result = mysqli_query($conn, $checkColumns);
        
        if (mysqli_num_rows($result) > 0) {
            $query = "INSERT INTO communications (title, message, sent_by, sent_at, audience, is_read, is_trash) 
                      VALUES (?, ?, 'You', NOW(), ?, 1, 0)";
        } else {
            $query = "INSERT INTO communications (title, message, sent_by, sent_at, audience) 
                      VALUES (?, ?, 'You', NOW(), ?)";
        }
        
        $stmt = mysqli_prepare($conn, $query);
        
        if (strpos($query, 'is_read') !== false) {
            mysqli_stmt_bind_param($stmt, 'sss', $title, $message, $audience);
        } else {
            mysqli_stmt_bind_param($stmt, 'sss', $title, $message, $audience);
        }
        
        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['success' => true, 'message_id' => mysqli_insert_id($conn)]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to send message: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Title and message are required']);
    }
}

function formatDate($dateString) {
    try {
        $date = new DateTime($dateString);
        $now = new DateTime();
        $diff = $now->diff($date);
        
        if ($diff->days == 0) {
            return 'Today, ' . $date->format('g:i A');
        } elseif ($diff->days == 1) {
            return 'Yesterday, ' . $date->format('g:i A');
        } elseif ($diff->days < 7) {
            return $date->format('D, g:i A');
        } else {
            return $date->format('M j, g:i A');
        }
    } catch (Exception $e) {
        return $dateString;
    }
}

mysqli_close($conn);
?>