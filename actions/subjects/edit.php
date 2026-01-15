<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id           = $_POST['id'];
    $subject_name = $_POST['subject_name'];
    $teacher_id   = $_POST['teacher_id'];

    $sql = "UPDATE subjects SET 
            subject_name = '$subject_name', 
            teacher_id = '$teacher_id' 
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/admin/subjects_list.php?msg=updated");
    }
}
?>