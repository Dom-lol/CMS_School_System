<?php
require_once '../../config/db.php';
$teacher_sql = "INSERT INTO teachers (teacher_id, user_id, subjects, phone) 
                VALUES ('$teacher_id', '$new_user_id', '$subjects', '$phone')";
mysqli_query($conn, $teacher_sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ១. ទទួលទិន្នន័យពី Form (ថែម teacher_id)
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username   = mysqli_real_escape_string($conn, $_POST['email']); 
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $subjects   = mysqli_real_escape_string($conn, $_POST['subjects']); 
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);

    // ២. ចាប់ផ្ដើម Transaction
    mysqli_begin_transaction($conn);
    try {
        // បញ្ចូលទៅតារាង users
        $user_sql = "INSERT INTO users (full_name, username, password, role) 
                     VALUES ('$full_name', '$username', '$password', 'teacher')";
        mysqli_query($conn, $user_sql);
        $new_user_id = mysqli_insert_id($conn);

        // ៣. បញ្ចូលទៅតារាង teachers (ត្រូវថែម teacher_id ទៅក្នុង SQL នេះ)
        $teacher_sql = "INSERT INTO teachers (teacher_id, user_id, subjects, phone) 
                        VALUES ('$teacher_id', '$new_user_id', '$subjects', '$phone')";
        mysqli_query($conn, $teacher_sql);

        mysqli_commit($conn);
        header("Location: ../../views/staff/teachers_list.php?status=success");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Error: " . $e->getMessage());
    }
}// ផ្នែកមួយនៃ create.php
