<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username  = mysqli_real_escape_string($conn, $_POST['email']); // ប្រើ email ជា username
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $subjects  = mysqli_real_escape_string($conn, $_POST['subjects']); 
    $phone     = mysqli_real_escape_string($conn, $_POST['phone']);

    // ១. ឆែកមើលថា តើមាន Username នេះក្នុង Database ហើយឬនៅ?
    $check_user = mysqli_query($conn, "SELECT username FROM users WHERE username = '$username'");

    if (mysqli_num_rows($check_user) > 0) {
        // បើមានរួចហើយ បញ្ជូនត្រឡប់ទៅវិញជាមួយសារប្រាប់ថា "ឈ្មោះនេះមានរួចហើយ"
        header("Location: ../../views/staff/add_teacher.php?error=duplicate");
        exit();
    }

    // ២. បញ្ចូលទៅតារាង users បើមិនទាន់មានស្ទួន
    $user_sql = "INSERT INTO users (full_name, username, password, role) 
                 VALUES ('$full_name', '$username', '$password', 'teacher')";
    
    if (mysqli_query($conn, $user_sql)) {
        $new_user_id = mysqli_insert_id($conn);

        // ៣. បញ្ចូលទៅតារាង teachers
        $teacher_sql = "INSERT INTO teachers (user_id, subjects, phone) 
                        VALUES ('$new_user_id', '$subjects', '$phone')";
        
        if (mysqli_query($conn, $teacher_sql)) {
            header("Location: ../../views/staff/teachers_list.php?status=success");
            exit();
        }
    } else {
        die("Error: " . mysqli_error($conn));
    }
}