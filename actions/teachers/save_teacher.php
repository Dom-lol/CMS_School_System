<?php
require_once '../../config/db.php';

$teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
$full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);

$sql = "INSERT INTO teachers (teacher_id, full_name, ...) VALUES ('$teacher_id', '$full_name', ...)";

if (isset($_POST['btn_save_teacher'])) {
    // ទទួលទិន្នន័យ និងការពារ SQL Injection
    $t_id   = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $name   = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender = $_POST['gender'];
    $phone  = mysqli_real_escape_string($conn, $_POST['phone']);
    $subject = $_POST['subject_id'];

    $sql = "INSERT INTO teachers (teacher_id, full_name, gender, phone, subject_specialty) 
            VALUES ('$t_id', '$name', '$gender', '$phone', '$subject')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/staff/teachers_list.php?status=success");
    } else {
        // បង្ហាញ Error ពិតប្រាកដដើម្បីងាយស្រួល Fix
        die("Error Inserting: " . mysqli_error($conn));
    }
}