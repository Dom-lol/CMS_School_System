<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_img'])) {
    $user_id = $_SESSION['user_id'];
    $file = $_FILES['profile_img'];

    // កំណត់ឈ្មោះហ្វាយថ្មីដើម្បីកុំឱ្យជាន់គ្នា
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_name = "profile_" . $user_id . "_" . time() . "." . $ext;
    $upload_path = "../../assets/uploads/profiles/" . $new_name;

    // ពិនិត្យមើលថាតើជាប្រភេទរូបភាពមែនឬទេ
    $allowed_types = ['jpg', 'jpeg', 'png'];
    if (in_array(strtolower($ext), $allowed_types)) {
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            // Update ឈ្មោះរូបភាពទៅក្នុង Table users
            $sql = "UPDATE users SET profile_img = '$new_name' WHERE id = '$user_id'";
            if (mysqli_query($conn, $sql)) {
                header("Location: ../../views/student/dashboard.php?upload=success");
            }
        }
    } else {
        header("Location: ../../views/student/dashboard.php?error=invalid_type");
    }
}