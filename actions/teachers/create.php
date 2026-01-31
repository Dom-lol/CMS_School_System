<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t_id       = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $subjects   = mysqli_real_escape_string($conn, $_POST['subjects']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Username និង Password យកតាម ID ទាំងអស់
    $password   = $t_id; 

    // ចាត់ចែងការ Upload រូបភាព
    $image_name = 'default_user.png';
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $image_name = 'T_' . $t_id . '_' . time() . '.' . $ext;
        move_uploaded_file($_FILES['profile_image']['tmp_name'], "../../assets/uploads/teachers/" . $image_name);
    }

    mysqli_begin_transaction($conn);
    try {
        // បញ្ចូលទៅ Table Users
        $sql1 = "INSERT INTO users (username, password, full_name, role) 
                 VALUES ('$t_id', '$password', '$full_name', 'teacher')";
        mysqli_query($conn, $sql1);
        $new_user_id = mysqli_insert_id($conn);

        // បញ្ចូលទៅ Table Teachers
        $sql2 = "INSERT INTO teachers (teacher_id, user_id, full_name, subjects, phone, profile_image) 
                 VALUES ('$t_id', '$new_user_id', '$full_name', '$subjects', '$phone', '$image_name')";
        mysqli_query($conn, $sql2);

        mysqli_commit($conn);

        // កែសម្រួល URL ខាងក្រោមនេះឱ្យត្រូវនឹងឈ្មោះ File ក្នុង views/staff/ របស់បង
        header("Location: ../../views/staff/teachers_list.php?status=success");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Error: " . $e->getMessage());
    }
}