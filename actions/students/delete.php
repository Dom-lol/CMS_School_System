<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // ១. លុបក្នុង table students មុន
    $del_student = "DELETE FROM students WHERE student_id = '$id'";
    
    if (mysqli_query($conn, $del_student)) {
        // ២. លុបក្នុង table users តាមក្រោយ (ដោយប្រើ username ជា ID សិស្ស)
        $del_user = "DELETE FROM users WHERE username = '$id'";
        mysqli_query($conn, $del_user);
        
        header("Location: ../../views/staff/student_list.php?msg=deleted");
    }
}