<?php
session_start();
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
    $sql = "INSERT INTO members (first_name, last_name, email, phone, password, gender,  address, date_joined) VALUES ('$first_name', '$last_name', '$email', '$phone', '$password', '$gender', '$address', '$date_registered')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Registration successful!');</script>";
        echo "<script>window.location.href = '../login';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
// Simple role-based login simulation
if (isset($_POST['login'])) {
    $role = $_POST['role'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    if ($role == 'member') {
        $checkUser = "SELECT * FROM members WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($checkUser); 
        if ($result->num_rows == 0) {
            echo "<script>alert('Invalid email or password!');</script>";
            echo "<script>window.location.href = '../login';</script>";
            exit;
        }
        $_SESSION = ['admin' => false, 'member' => true, 'guest' => false];
        echo "<script>window.location.href = '../home';</script>";
    } elseif ($role == 'clergy') {
        $checkUser1 = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($checkUser1);
        if ($result->num_rows == 0) {
            echo "<script>alert('Invalid email or password!');</script>";
            echo "<script>window.location.href = '../login';</script>";
            exit;
        }
        $_SESSION = ['admin' => false, 'member' => false, 'clergy' => true];
        echo "<script>window.location.href = '../home';</script>";
    } elseif ($role == 'admin') {
        $checkUser2 = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
        $result = $conn->query($checkUser2);
        if ($result->num_rows == 0) {
            echo "<script>alert('Invalid email or password!');</script>";
            echo "<script>window.location.href = '../login';</script>";
            exit;
        }
        $_SESSION = ['admin' => true, 'member' => false, 'clergy' => false];
        echo "<script>window.location.href = '../home';</script>";
    } else {
        echo "<script>alert('No such role!');</script>";
    }
    
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
    $created_by        = 'Admin'; // Placeholder, replace with actual user info
    $target_audience   = implode(", ", $_POST['target_audience']); 

    // ---------- Handle image upload ----------
    $event_image = "";
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $targetDir  = "../uploads/events/"; // make sure this folder exists & is writable
        $fileName   = time() . "_" . basename($_FILES["event_image"]["name"]);
        $targetFile = $targetDir . $fileName;

        // Optional: validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $targetFile)) {
                $event_image = $fileName; // save only filename to DB
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
