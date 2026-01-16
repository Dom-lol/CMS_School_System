<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id           = $_POST['id'];
    $teacher_id   = $_POST['teacher_id'];
    $subject      = mysqli_real_escape_string($conn, $_POST['subject_name']);
    $class        = mysqli_real_escape_string($conn, $_POST['class_name']);
    $day          = $_POST['day_of_week'];
    $start        = $_POST['start_time'];
    $end          = $_POST['end_time'];
    $room         = mysqli_real_escape_string($conn, $_POST['room_number']);

    if ($id) {
        // កូដសម្រាប់ Update
        $sql = "UPDATE timetables SET teacher_id='$teacher_id', subject_name='$subject', class_name='$class', 
                day_of_week='$day', start_time='$start', end_time='$end', room_number='$room' WHERE id='$id'";
    } else {
        // កូដសម្រាប់ Insert ថ្មី
        $sql = "INSERT INTO timetables (teacher_id, subject_name, class_name, day_of_week, start_time, end_time, room_number) 
                VALUES ('$teacher_id', '$subject', '$class', '$day', '$start', '$end', '$room')";
    }

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/staff/timetable.php?status=success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}