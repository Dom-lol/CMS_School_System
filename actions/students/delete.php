<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    mysqli_query($conn, "DELETE FROM users WHERE students_id = $id");
    mysqli_query($conn, "DELETE FROM students WHERE student_id = $id");
    
        header("Location: ../../views/staff/student_list.php?msg=deleted");
        exit;
    }
