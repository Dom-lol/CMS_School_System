<?php
require_once '../../config/db.php';

if (isset($_POST['btn_save'])) {
    $day = $_POST['day_of_week'];
    $room = $_POST['room'];
    $sid = $_POST['subject_id'];
    $cid = $_POST['class_id'];
    
    // បំបែកម៉ោង "07:00-07:50" ទៅជា $start និង $end
    $time = explode('-', $_POST['study_time']);
    $start = $time[0];
    $end = $time[1];

    $sql = "INSERT INTO timetable (day_of_week, start_time, end_time, subject_id, class_id, room) 
            VALUES ('$day', '$start', '$end', '$sid', '$cid', '$room')";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/staff/timetable.php?class_id=$cid&status=success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}