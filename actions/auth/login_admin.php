<?php
session_start();
require_once '../../config/db.php'; // ថយក្រោយ ២ ថ្នាក់ដើម្បីចូល config

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password']; 
    $role     = mysqli_real_escape_string($conn, $_POST['role']);

    // ឆែកទិន្នន័យក្នុងតារាង users
    $query = "SELECT * FROM users WHERE username = '$username' AND role = '$role' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($password === $row['password']) { // បើប្រើ password_hash ត្រូវប្ដូរប្រើ password_verify
            $_SESSION['user_id']   = $row['id'];
            $_SESSION['role']      = $row['role'];
            $_SESSION['full_name'] = $row['full_name'];

            // បែងចែកផ្លូវតាមរូបភាព Folder views របស់អ្នក
            if ($row['role'] === 'admin') {
                header("Location: ../../views/admin/dashboard.php");
            } else {
                header("Location: ../../views/staff/dashboard.php");
            }
            exit();
        }
    }
    header("Location: ../../index.php?error=invalid");
    exit();
}