<?php
session_start();
header('Content-Type: text/html; charset=utf-8'); 
include_once '../config/db.php';

//register user
if (isset($_POST['register'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $date_registered = $_POST['date_joined'];
    $profile_pic = $_POST['profile_pic'];
    $profile_pic = $_FILES['profile_pic']['name'];
    $target_dir = "../public/images/";
    $target_file = $target_dir . basename($profile_pic);
    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);

    // Check if email already exists
    $sql = "SELECT * FROM members WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists!');</script>";
        echo "<script>window.location.href = '../register';</script>";
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
        echo "<script>window.location.href = '../register';</script>";
        exit;
    }

    // Insert user data into the database
    $sql = "INSERT INTO members (first_name, last_name, email, phone, password, gender,  address, date_joined, profile_pic) VALUES ('$first_name', '$last_name', '$email', '$phone', '$password', '$gender', '$address', '$date_registered', '$profile_pic')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!');</script>";
        echo "<script>window.location.href = '../login';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
//register user1
if (isset($_POST['register1'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    $date_registered = $_POST['date_joined'];
    $profile_pic = $_POST['profile_pic'];
    $profile_pic = $_FILES['profile_pic']['name'];
    $target_dir = "../public/images/";
    $target_file = $target_dir . basename($profile_pic);
    move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file);

    // Check if email already exists
    $sql = "SELECT * FROM members WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists!');</script>";
        echo "<script>window.location.href = '../dashboard/members';</script>";
        exit;
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
        echo "<script>window.location.href = '../dashboard/members';</script>";
        exit;
    }

    // Insert user data into the database
    $sql = "INSERT INTO members (first_name, last_name, email, phone, password, gender,  address, date_joined, profile_pic) VALUES ('$first_name', '$last_name', '$email', '$phone', '$password', '$gender', '$address', '$date_registered', '$profile_pic')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!');</script>";
       echo "<script>window.location.href = '../dashboard/members';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
// Simple role-based login simulation

if (isset($_POST['login'])) {
    $role     = $_POST['role'];
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Decide which table to use
    if ($role === 'member') {
        $table = "members";
    } elseif ($role === 'clergy' || $role === 'admin') {
        $table = "users";
    } else {
        echo "<script>alert('No such role!');</script>";
        exit;
    }

/*************  ✨ Windsurf Command 🌟  *************/
    // Fetch user by email and password
    $stmt = $conn->prepare("SELECT id, email, password FROM $table WHERE email = ?");
    // Fetch user by email
    //$stmt = $conn->prepare("SELECT * FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    $pass1= $user['password'];
    $email1= $user['email'];
    $id = $user['id'];
    // Validate user existence and password
    
    if(mysqli_num_rows($result) > 0) {
        if($email === $email1 && $password === $pass1){
            // Valid user
            $user = $result->fetch_assoc();
            // Set session variables
            
            // ✅ Set session flags properly
            $_SESSION['admin']  = ($role === 'admin');
            $_SESSION['clergy'] = ($role === 'clergy');
            $_SESSION['member'] = ($role === 'member');
            $_SESSION['user_id'] = $id;

            echo '<script>alert("Login successful!");</script>';
            echo "<script>window.location.href = '../home';</script>";
            exit;

        } else {
            echo "<script>alert('Invalid email or password!');</script>";
            echo "<script>window.location.href = '../login';</script>";
            exit;
        }
    } else {
        // Invalid user
        echo "<script>alert('Invalid email or password!');</script>";
    }
        echo "<script>window.location.href = '../login';</script>";
        exit;
    }
// Add event
if (isset($_POST['add-event'])) {    
    $event_name        = $_POST['title'];
    $start_date        = $_POST['start_date'];
    $start_time        = $_POST['start_time'];
    $end_date          = $_POST['end_date'];
    $end_time          = $_POST['end_time'];
    $event_venue       = $_POST['location'];
    $event_description = $_POST['description'];
    $event_category    = $_POST['event_category'];
    $created_by        = 'Admin'; 
    $target_audience   = implode(", ", $_POST['target_audience']); 

    // ---------- Handle image upload ----------
    $event_image = "";
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $targetDir  = "../uploads/events/"; 
        $fileName   = time() . "_" . basename($_FILES["event_image"]["name"]);
        $targetFile = $targetDir . $fileName;

        // Optional: validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $targetFile)) {
                $event_image = $fileName; 
            } else {
                echo "<script>alert('Failed to upload image.');</script>";
            }
        } else {
            echo "<script>alert('Invalid image format. Only JPG, PNG, GIF allowed.');</script>";
        }
    }

    // ---------- Insert into DB ----------
    $sql = "INSERT INTO events 
            (title, start_date, start_time, end_date, end_time, location, description, category, target_audience, created_by, image) 
            VALUES 
            ('$event_name', '$start_date', '$start_time', '$end_date', '$end_time', '$event_venue', '$event_description', '$event_category', '$target_audience', '$created_by', '$event_image')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Event added successfully!');</script>";
        echo "<script>window.location.href = '../dashboard/events.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";      
    }    
}

// Add ministry
if (isset($_POST['add-ministry'])) {    
    $ministry_name        = $_POST['ministry_name'];
    //$ministry_type        = $_POST['ministry_type'];
    //$relevant_image       = $_POST['relevant_image'];
    $day                  = $_POST['day'];
    $time                 = $_POST['time'];
    $age_group            = $_POST['age_group'];
    $leader               = $_POST['leader'];
    $description          = $_POST['description'];
    $created_by           = 'Admin'; // Placeholder, replace with actual user info
    

    // ---------- Handle image upload ----------
    //$relevant_image = "";
    //if (isset($_FILES['relevant_image']) && $_FILES['relevant_image']['error'] == 0) {
        //$targetDir  = "../uploads/ministries/"; 
        //$fileName   = time() . "_" . basename($_FILES["relevant_image"]["name"]);
        //$targetFile = $targetDir . $fileName;

        // Optional: validate file type
        //$allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        //$fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        //if (in_array($fileType, $allowedTypes)) {
            //if (move_uploaded_file($_FILES["relevant_image"]["tmp_name"], $targetFile)) {
                //$relevant_image = $fileName; // 
            //} else {
                //echo "<script>alert('Failed to upload image.');</script>";
            //}
        //} //else {
            //echo "<script>alert('Invalid image format. Only JPG, PNG, GIF allowed.');</script>";
        //}
    //}

    // ---------- Insert into DB ----------
    $sql = "INSERT INTO ministries 
            (name,day,time,age_range, leader,description,created_by) 
            VALUES 
            ('$ministry_name', '$day', '$time', '$age_group', '$leader', '$description', '$created_by')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Ministry added successfully!');</script>";
        echo "<script>window.location.href = '../dashboard/ministries.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";      
    }    
}
//take attendance
if (isset($_POST['take_attendance'])) {
    $member_ids = $_POST['members'];
    $attendance_date = $_POST['date'];
    $service = $_POST['service'];
    $event = 'service';
     // Clear previous attendance for the same date to avoid duplicates
    $sql = "DELETE FROM attendance WHERE date = '$attendance_date'";
    $conn->query($sql);
    foreach ($member_ids as $member_id) {
        $sql = "INSERT INTO attendance (member_id, date, attended, service) VALUES ('$member_id', '$attendance_date', '$event', '$service')";
        $conn->query($sql);
    }
    echo "<script>alert('Attendance recorded successfully!');</script>";
    echo "<script>window.location.href = '../dashboard/attendance.php';</script>";
}
// Record offering
if (isset($_POST['record_offering'])) {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $member_id = $_POST['member'] ? $_POST['member'] : NULL; // Allow NULL for non-members

    $sql = "INSERT INTO contributions (contribution_date, contribution_type, amount, member_id, recorded_by) VALUES ('$date', '$type', '$amount', " . ($member_id ? "'$member_id'" : "NULL") . ", 'Admin')";
    if ($conn->query($sql) === TRUE) {
        //activity log
        $member_info = "SELECT first_name, last_name FROM members WHERE id = '$member_id'";
        $result = $conn->query($member_info);
        $member = $result->fetch_assoc();
        $member_name = $member['first_name'] . ' ' . $member['last_name'];  
        $activity = "$member_name Recorded $type of amount UGX $amount";
        $sql = "INSERT INTO activity_log (action, created_by,date) VALUES ('$activity', 'Admin', NOW())";
        $conn->query($sql);
        echo "<script>alert('Contribution recorded successfully!');</script>";
        echo "<script>window.location.href = '../dashboard/contributions.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
// Send announcement
if (isset($_POST['send_announcement'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $send_to = implode(", ", $_POST['send_to']); // Array of groups to send to
    //$channel = $_POST['channel']; // email, sms, both
    $sql = "INSERT INTO communications (title, message, sent_by,audience,sent_at, is_read, is_trash,is_draft) VALUES ('$title', '$message', 'You','$send_to', NOW(), 0, 0, 0)";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!');</script>";
        echo "<script>window.location.href = '../dashboard/communications.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}   // End of send announcement

// send message
if (isset($_POST['send_message'])) {
    $title = $_POST['title'];
    $message = $_POST['message'];
    $send_to = mysqli_real_escape_string($conn, str_replace(array(',', '\\'), array('\,', '\\\\'), $_POST['send_to']));
    //$channel = $_POST['channel']; // email, sms, both
 $sql = "INSERT INTO communications (title, message, sent_by, audience, sent_at, is_read, is_trash, is_draft)  VALUES ('$title', '$message', 'You', '$send_to', NOW(), 0, 0, 0)";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Message sent successfully!')</script>";
        echo "<script>window.location.href = '../dashboard/communications.php'</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
} 

// save sacrament
if (isset($_POST['save-sacrament'])) {
    $sacrament_type = $_POST['sacrament_type'];
    $sacrament_date = $_POST['sacrament_date'];
    $member_id = $_POST['member_id'];
    $officiant = $_POST['officiant'];
    $location = $_POST['location'];
    $notes = $_POST['notes'];

    $sql = "INSERT INTO sacraments (type, date, member_id, officiated_by, location, notes, recorded_by) VALUES ('$sacrament_type', '$sacrament_date', '$member_id', '$officiant', '$location', '$notes', 'Admin')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Sacrament record saved successfully!');</script>";
        echo "<script>window.location.href = '../dashboard/sacraments.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
//fetch ministries
if (isset($_GET['action']) && $_GET['action'] == 'getAll') {
    $ministries = [];
    $sql = "SELECT * FROM ministries";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ministries[] = $row;
        }
    }
    header('Content-Type: application/json');
    echo json_encode($ministries);
    exit;
}
//add participant to ministry
if (isset($_POST['add-participant'])) {
    $ministry_id = $_POST['ministry_id'];
    $participant_id = $_POST['member_id'];
    $join_date = $_POST['join_date'];
    $sql = "INSERT INTO participants (activity_id, participant_id, join_date) VALUES ('$ministry_id', '$participant_id', '$join_date')";
    $participantcount = "UPDATE ministries SET participantCount = participantCount + 1 WHERE id = '$ministry_id'";
    $conn->query($participantcount);
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Participant added to ministry successfully!');</script>";
        echo "<script>window.location.href = '../dashboard/ministries.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
//fetch participants for a ministry
if (isset($_GET['action']) && $_GET['action'] === 'getParticipants' && isset($_GET['ministryId'])) {
    $ministryId = $_GET['ministryId'];
    $participants = [];

    $sql = "SELECT p.id, m.first_name, m.last_name, p.join_date, m.email, m.phone
            FROM participants p 
            JOIN members m ON p.participant_id = m.id 
            WHERE p.activity_id = '$ministryId'";  

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $participants[] = [
                "id"        => $row['id'],
                "firstName" => $row['first_name'],
                "lastName"  => $row['last_name'],
                "email"     => $row['email'] ?? "",
                "phone"     => $row['phone'] ?? "",
                "joinDate"  => $row['join_date']
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($participants);
    exit;
}
