<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; 
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    // ១. ស្វែងរក Username និង Role ឱ្យចំ
    $query = "SELECT * FROM users WHERE username = '$username' AND role = '$role' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // ២. ផ្ទៀងផ្ទាត់ Password ត្រង់ៗ (ID === ID)
        if ($password === $row['password']) {
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['role']      = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['username']  = $row['username'];

            // ៣. បែងចែកផ្លូវតាម Role
            if ($row['role'] === 'teacher') {
                header("Location: ../../views/teacher/dashboard.php");
            } elseif ($row['role'] === 'student') {
                header("Location: ../../views/student/dashboard.php");
            } else {
                header("Location: ../../views/admin/dashboard.php");
            }
            exit();
        }
    }
    
    // បើមកដល់ត្រង់នេះ មានន័យថាទិន្នន័យខុស
    header("Location: ../../index.php?error=1");
    exit();
}