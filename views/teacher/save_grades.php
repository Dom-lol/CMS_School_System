<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['grade'])) {
    $class_id   = mysqli_real_escape_string($conn, $_POST['class_id']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $grades     = $_POST['grade'];

    foreach ($grades as $student_id => $score) {
        $student_id = mysqli_real_escape_string($conn, $student_id);
        $score = mysqli_real_escape_string($conn, $score);

        // កំណត់ Grade (A-F)
        if ($score >= 90) $grade = 'A';
        elseif ($score >= 80) $grade = 'B';
        elseif ($score >= 70) $grade = 'C';
        elseif ($score >= 60) $grade = 'D';
        elseif ($score >= 50) $grade = 'E';
        else $grade = 'F';

        // Update បើមានរួច ឬ Insert បើមិនទាន់មាន
        $sql = "INSERT INTO scores (student_id, subject_id, class_id, score, grade, created_at) 
                VALUES ('$student_id', '$subject_id', '$class_id', '$score', '$grade', NOW())
                ON DUPLICATE KEY UPDATE 
                score = '$score', grade = '$grade', updated_at = NOW()";
        
        mysqli_query($conn, $sql);
    }

    // ត្រឡប់ទៅវិញ
    header("Location: input_grades.php?class_id=$class_id&subject_id=$subject_id&status=success");
    exit();
}