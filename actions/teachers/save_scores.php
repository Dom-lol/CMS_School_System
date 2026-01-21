<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = $_POST['class_id'];
    $subject_id = $_POST['subject_id'];
    $month = $_POST['month'];
    $scores = $_POST['scores'] ?? [];

    foreach ($scores as $student_id => $val) {
        if ($val === '') continue; 

        // ពិនិត្យមើលពិន្ទុក្នុងតារាង scores
        $check = mysqli_query($conn, "SELECT id FROM scores WHERE student_id='$student_id' AND subject_id='$subject_id' AND month='$month'");
        
        if (mysqli_num_rows($check) > 0) {
            // បើមានហើយ UPDATE
            mysqli_query($conn, "UPDATE scores SET score_value='$val' WHERE student_id='$student_id' AND subject_id='$subject_id' AND month='$month'");
        } else {
            // បើមិនទាន់មាន INSERT
            mysqli_query($conn, "INSERT INTO scores (student_id, subject_id, class_id, month, score_value) VALUES ('$student_id', '$subject_id', '$class_id', '$month', '$val')");
        }
    }

    // រុញត្រឡប់ទៅ Views Folder វិញ
    header("Location: ../../views/teacher/input_grades.php?class_id=$class_id&subject_id=$subject_id&month=$month&status=success");
    exit();
}