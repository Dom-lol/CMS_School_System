<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t_id       = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $subjects   = mysqli_real_escape_string($conn, $_POST['subjects']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    $password   = '123'; // Password លំនាំដើម [cite: 2026-01-20]

    // ១. ចាត់ចែងការ Upload រូបភាព [cite: 2026-01-20]
    $image_name = 'default_user.png';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $image_name = 'T_' . $t_id . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['profile_image']['tmp_name'], "../../assets/uploads/teachers/" . $image_name);
    }

    mysqli_begin_transaction($conn);
    try {
        // ២. បញ្ចូលទៅ Table Users [cite: 2026-01-20]
        $sql1 = "INSERT INTO users (username, password, full_name, role) VALUES ('$t_id', '$password', '$full_name', 'teacher')";
        mysqli_query($conn, $sql1);
        $new_user_id = mysqli_insert_id($conn);

        // ៣. បញ្ចូលទៅ Table Teachers [cite: 2026-01-20]
        $sql2 = "INSERT INTO teachers (teacher_id, user_id, full_name, subjects, phone, profile_image) 
                 VALUES ('$t_id', '$new_user_id', '$full_name', '$subjects', '$phone', '$image_name')";
        mysqli_query($conn, $sql2);

        mysqli_commit($conn);
        header("Location: ../../views/staff/teachers_list.php?status=success");
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Error: " . $e->getMessage());
    }
}