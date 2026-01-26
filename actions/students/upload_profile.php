<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_img'])) {
    $s_id = $_SESSION['username'];
    $file = $_FILES['profile_img'];
    
    // បង្កើតឈ្មោះថ្មីការពារជាន់គ្នា
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_file_name = $s_id . "_" . time() . "." . $ext;
    $upload_path = "../../assets/uploads/profiles/" . $new_file_name;

    if (move_uploaded_file($file['tmp_name'], $upload_path)) {
        // Update ក្នុង Database
        $update = "UPDATE students SET profile_img = '$new_file_name' WHERE student_id = '$s_id'";
        mysqli_query($conn, $update);
        
        // ត្រឡប់ទៅទំព័រដើមវិញ
        header("Location: " . $_SERVER['HTTP_REFERER']);
    }
}