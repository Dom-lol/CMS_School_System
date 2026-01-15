<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // លុបក្នុង students
    mysqli_query($conn, "DELETE FROM students WHERE student_id = '$id'");
    // លុបក្នុង users
    mysqli_query($conn, "DELETE FROM users WHERE username = '$id'");
    
    header("Location: ../../views/staff/student_list.php?msg=deleted");
    exit();
}
?>