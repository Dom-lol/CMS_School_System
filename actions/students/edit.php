<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $s_id    = $_POST['student_id'];
    $name    = $_POST['full_name'];
    $gender  = $_POST['gender'];
    $class   = $_POST['class_name'];
    $status  = $_POST['status']; // Learning, Suspended, Stopped

    $sql = "UPDATE students SET 
            gender = '$gender', 
            class_name = '$class', 
            status = '$status' 
            WHERE student_id = '$s_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/staff/students_list.php?msg=updated");
    }
}
?>