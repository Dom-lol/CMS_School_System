<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $day        = $_POST['day_of_week']; // ឧ: Monday
    $time       = $_POST['time_slot'];   // ឧ: 08:00 AM - 09:00 AM
    $class      = $_POST['class_name'];  // ឧ: 12A
    $subject_id = $_POST['subject_id'];
    $teacher_id = $_POST['teacher_id'];

    // ឆែកមើលថាតើមានការជាន់ម៉ោងគ្នា (Conflict) ឬទេ
    $check = "SELECT id FROM timetable WHERE day_of_week = '$day' AND time_slot = '$time' AND class_name = '$class'";
    $res = mysqli_query($conn, $check);

    if (mysqli_num_rows($res) > 0) {
        header("Location: ../../views/admin/timetable.php?error=conflict");
    } else {
        $sql = "INSERT INTO timetable (day_of_week, time_slot, class_name, subject_id, teacher_id) 
                VALUES ('$day', '$time', '$class', '$subject_id', '$teacher_id')";
        
        if (mysqli_query($conn, $sql)) {
            header("Location: ../../views/admin/timetable.php?msg=added");
        }
    }
}
?>