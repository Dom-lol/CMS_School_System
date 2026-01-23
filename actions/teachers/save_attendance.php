<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'];
    $date = $_POST['date'];
    $teacher_id = $_SESSION['user_id'] ?? 1;
    $attendance_data = $_POST['att'] ?? [];

    foreach ($attendance_data as $student_id => $status) {
        // ១. ឆែកមើលថាតើថ្ងៃហ្នឹងមានទិន្នន័យហើយឬនៅ
        $check = mysqli_query($conn, "SELECT id FROM attendance WHERE student_id='$student_id' AND attendance_date='$date'");
        
        if (mysqli_num_rows($check) > 0) {
            // ២. បើមានហើយ UPDATE
            mysqli_query($conn, "UPDATE attendance SET status='$status' WHERE student_id='$student_id' AND attendance_date='$date'");
        } else {
            // ៣. បើអត់ទាន់មាន INSERT
            mysqli_query($conn, "INSERT INTO attendance (student_id, class_id, teacher_id, status, attendance_date) 
                                VALUES ('$student_id', '$class_id', '$teacher_id', '$status', '$date')");
        }
    }

    // រុញត្រឡប់ទៅទំព័រស្រង់វត្តមានវិញ
    header("Location: ../../views/teacher/attendance.php?class_id=$class_id&date=$date&status=success");
    exit();
}