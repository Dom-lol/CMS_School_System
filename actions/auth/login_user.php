<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; 
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    $query = "SELECT * FROM users WHERE username = '$username' AND role = '$role' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        // ផ្ទៀងផ្ទាត់ Password (បើប្រើ hash ត្រូវប្រើ password_verify)
        if ($password === $row['password']) {
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['role']      = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];
            $_SESSION['username']  = $row['username']; // បន្ថែមជួរនេះដើម្បីបាត់ Error "Undefined index"

            // ឆែកមើល Folder ក្នុង views (តាមរូបភាពរបស់អ្នកគឺគ្មានអក្សរ s ទេ)
            if ($row['role'] === 'teacher') {
                header("Location: ../../views/teacher/dashboard.php");
            } else {
                header("Location: ../../views/student/dashboard.php");
            }
            exit();
        }
    }
    header("Location: ../../index.php?error=invalid");
    exit();
}