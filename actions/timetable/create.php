<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teacher_custom_id = mysqli_real_escape_string($conn, $_POST['teacher_custom_id']);
    
    // កែត្រង់នេះ៖ ទាញយក user_id ជំនួសឱ្យ id ព្រោះតារាង teachers ប្រើ user_id ជា Primary Key
    $find_teacher = mysqli_query($conn, "SELECT user_id FROM teachers WHERE teacher_id = '$teacher_custom_id' LIMIT 1");
    
    if (mysqli_num_rows($find_teacher) > 0) {
        $t_data = mysqli_fetch_assoc($find_teacher);
        $real_teacher_id = $t_data['user_id']; // យក user_id ទៅប្រើ
        
        $day_of_week = mysqli_real_escape_string($conn, $_POST['day_of_week']);
        $class_id    = mysqli_real_escape_string($conn, $_POST['class_id']);
        $subject_id  = mysqli_real_escape_string($conn, $_POST['subject_id']);
        $start_time  = mysqli_real_escape_string($conn, $_POST['start_time']);
        $end_time    = mysqli_real_escape_string($conn, $_POST['end_time']);
        $room_number = mysqli_real_escape_string($conn, $_POST['room_number']);

        $sql = "INSERT INTO timetable (day_of_week, teacher_id, class_id, subject_id, start_time, end_time, room_number) 
                VALUES ('$day_of_week', '$real_teacher_id', '$class_id', '$subject_id', '$start_time', '$end_time', '$room_number')";

        if (mysqli_query($conn, $sql)) {
            header("Location: ../../views/staff/timetable.php?status=success");
            exit();
        } else {
            die("SQL Error: " . mysqli_error($conn));
        }
    } else {
        echo "<script>alert('រកមិនឃើញគ្រូដែលមានអត្តលេខ $teacher_custom_id ទេ!'); window.history.back();</script>";
    }
}