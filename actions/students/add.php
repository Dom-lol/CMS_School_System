<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $s_id    = mysqli_real_escape_string($conn, $_POST['student_id']);
    $name    = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender  = $_POST['gender'];
    $dob     = $_POST['dob'];
    $phone   = $_POST['phone'];
    $class   = $_POST['class_name'];
    $address = $_POST['address'];

    // ១. បង្កើត Account ឱ្យសិស្ស (Password ធម្មតា: 123456)
    $pass = '123456'; 
    $sql_user = "INSERT INTO users (username, password, full_name, role) 
                 VALUES ('$s_id', '$pass', '$name', 'student')";
    
    if (mysqli_query($conn, $sql_user)) {
        $user_id = mysqli_insert_id($conn);
        
        // ២. បញ្ចូលព័ត៌មានលម្អិតក្នុង table students
        $sql_student = "INSERT INTO students (student_id, user_id, gender, dob, address, phone, class_name, status) 
                        VALUES ('$s_id', '$user_id', '$gender', '$dob', '$address', '$phone', '$class', 'Active')";
        
        if (mysqli_query($conn, $sql_student)) {
            header("Location: ../../views/staff/students_list.php?msg=added");
            exit();
        }
    }
}
?>