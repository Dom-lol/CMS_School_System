<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id         = $_POST['id'];
    $day        = $_POST['day_of_week'];
    $time       = $_POST['time_slot'];
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_POST['teacher_id'];

    $sql = "UPDATE timetable SET 
            day_of_week = '$day', 
            time_slot = '$time', 
            subject_id = '$subject_id', 
            teacher_id = '$teacher_id' 
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/admin/timetable.php?msg=updated");
    }
}
?>