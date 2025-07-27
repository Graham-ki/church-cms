<?php
session_start();
if (isset($_POST['login'])) {
    $role = $_POST['role'];
    if ($role == 'member') {
        $_SESSION = ['admin' => false, 'member' => true, 'guest' => false];
        echo "<script>window.location.href = '../home';</script>";
    } elseif ($role == 'clergy') {
        $_SESSION = ['admin' => false, 'member' => false, 'clergy' => true];
        echo "<script>window.location.href = '../home';</script>";
    } elseif ($role == 'admin') {
        $_SESSION = ['admin' => true, 'member' => false, 'clergy' => false];
        echo "<script>window.location.href = '../home';</script>";
    } else {
        echo "<script>alert('No such role!');</script>";
    }
    
}