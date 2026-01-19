<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_img'])) {
    $u_id = $_SESSION['user_id'];
    $file = $_FILES['profile_img'];
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $new_name = "profile_" . $u_id . "_" . time() . "." . $ext;
    $target = "../../assets/uploads/profiles/" . $new_name;

    if (move_uploaded_file($file['tmp_name'], $target)) {
        mysqli_query($conn, "UPDATE students SET profile_img = '$new_name' WHERE user_id = '$u_id'");
    }
}
header("Location: " . $_SERVER['HTTP_REFERER']);
exit();