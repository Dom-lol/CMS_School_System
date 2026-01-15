<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t_id   = $_POST['teacher_id']; // ឧទាហរណ៍: T001
    $name   = $_POST['full_name'];
    $major  = $_POST['major'];     // ឯកទេស (គណិត, រូប...)
    $phone  = $_POST['phone'];

    // ១. បង្កើត Account ឱ្យគ្រូ (Password default: 123456)
    $pass = password_hash('123456', PASSWORD_DEFAULT);
    $sql_user = "INSERT INTO users (username, password, full_name, role) VALUES ('$t_id', '$pass', '$name', 'teacher')";
    
    if (mysqli_query($conn, $sql_user)) {
        $user_id = mysqli_insert_id($conn);
        
        // ២. បញ្ចូលព័ត៌មានលម្អិតក្នុង table teachers
        $sql_teacher = "INSERT INTO teachers (teacher_id, user_id, major, phone) 
                        VALUES ('$t_id', '$user_id', '$major', '$phone')";
        
        if (mysqli_query($conn, $sql_teacher)) {
            header("Location: ../../views/admin/teachers_list.php?msg=added");
        }
    }
}
?>