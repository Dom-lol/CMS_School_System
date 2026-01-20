<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t_id   = mysqli_real_escape_string($conn, $_POST['teacher_id']); // ប្រើ ID ជា Username
    $name   = mysqli_real_escape_string($conn, $_POST['full_name']);
    $major  = mysqli_real_escape_string($conn, $_POST['major']);
    $phone  = mysqli_real_escape_string($conn, $_POST['phone']);

    // ១. កំណត់ Password ជា 123 ធម្មតា (មិន Hash តាមសំណូមពរ) [cite: 2026-01-20]
    $pass = '123'; 
    
    // ២. បញ្ចូលទៅ Table users មុនគេ (ប្រើ ID ជា Username) [cite: 2026-01-20]
    $sql_user = "INSERT INTO users (username, password, full_name, role) 
                 VALUES ('$t_id', '$pass', '$name', 'teacher')";
    
    if (mysqli_query($conn, $sql_user)) {
        $user_id = mysqli_insert_id($conn); // ចាប់យក ID អូតូពី table users
        
        // ៣. បញ្ចូលទៅ Table teachers ដោយភ្ជាប់ user_id ឱ្យត្រូវគ្នា [cite: 2026-01-20]
        $sql_teacher = "INSERT INTO teachers (teacher_id, user_id, full_name, subjects, phone) 
                        VALUES ('$t_id', '$user_id', '$name', '$major', '$phone')";
        
        if (mysqli_query($conn, $sql_teacher)) {
            header("Location: ../../views/staff/teachers_list.php?msg=success");
            exit();
        }
    }
}
?>