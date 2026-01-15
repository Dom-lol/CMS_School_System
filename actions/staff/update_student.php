<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $old_id     = mysqli_real_escape_string($conn, $_POST['old_student_id']);
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender     = mysqli_real_escape_string($conn, $_POST['gender']);
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $status     = mysqli_real_escape_string($conn, $_POST['status']);

    // ១. Update ឈ្មោះក្នុង Table users
    $sql_user = "UPDATE users SET full_name = '$full_name' WHERE username = '$old_id'";

    if (mysqli_query($conn, $sql_user)) {
        // ២. Update ព័ត៌មានសិក្សាក្នុង Table students
        $sql_student = "UPDATE students SET 
                        gender = '$gender', 
                        class_name = '$class_name', 
                        status = '$status' 
                        WHERE student_id = '$old_id'";
        
        if (mysqli_query($conn, $sql_student)) {
            header("Location: ../../views/staff/student_list.php?status=updated");
        } else {
            echo "Error Updating Student: " . mysqli_error($conn);
        }
    } else {
        echo "Error Updating User: " . mysqli_error($conn);
    }
    exit();
}