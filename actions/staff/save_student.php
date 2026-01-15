<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ចាប់យកតម្លៃពី Form
    $s_id       = mysqli_real_escape_string($conn, $_POST['student_id']);
    $password   = mysqli_real_escape_string($conn, $_POST['password']); 
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender     = mysqli_real_escape_string($conn, $_POST['gender']);
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $status     = mysqli_real_escape_string($conn, $_POST['status']);

    // ជំហានទី ១: បង្កើត Account ក្នុងតារាង users
    $sql_user = "INSERT INTO users (username, password, full_name, role) 
                 VALUES ('$s_id', '$password', '$full_name', 'student')";

    if (mysqli_query($conn, $sql_user)) {
        // យក ID ដែលទើបតែបង្កើតក្នុង users
        $user_id = mysqli_insert_id($conn);

        // ជំហានទី ២: បញ្ចូលព័ត៌មានទៅក្នុងតារាង students
        $sql_student = "INSERT INTO students (student_id, user_id, full_name, gender, class_name, status) 
                        VALUES ('$s_id', '$user_id', '$full_name', '$gender', '$class_name', '$status')";

        if (mysqli_query($conn, $sql_student)) {
            // បើជោគជ័យ បញ្ជូនទៅកាន់បញ្ជីសិស្ស
            header("Location: ../../views/staff/student_list.php?msg=added");
            exit();
        } else {
            // បើខុសត្រង់នេះ បង្ហាញ Error ច្បាស់ៗ
            echo "កំហុសពេលបញ្ចូលក្នុងតារាង students: " . mysqli_error($conn);
        }
    } else {
        // បើខុសត្រង់នេះ ប្រហែលមកពី student_id មានរួចហើយ
        echo "កំហុសពេលបង្កើត Account ក្នុងតារាង users: " . mysqli_error($conn);
    }
}
?>