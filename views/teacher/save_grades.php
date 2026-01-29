<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['grade'])) {
    $class_id    = mysqli_real_escape_string($conn, $_POST['class_id']);
    $subject_id  = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $input_month = mysqli_real_escape_string($conn, $_POST['input_month']); // ចាប់តម្លៃខែ
    $input_year  = mysqli_real_escape_string($conn, $_POST['input_year']);  // ចាប់តម្លៃឆ្នាំ
    $grades      = $_POST['grade'];

    foreach ($grades as $student_id => $score) {
        if ($score === '') continue; // បើមិនបានបញ្ចូលពិន្ទុ មិនបាច់រក្សាទុក

        $student_id = mysqli_real_escape_string($conn, $student_id);
        $score = mysqli_real_escape_string($conn, $score);

        // កំណត់ Grade (A-F)
        if ($score >= 90) $grade = 'A';
        elseif ($score >= 80) $grade = 'B';
        elseif ($score >= 70) $grade = 'C';
        elseif ($score >= 60) $grade = 'D';
        elseif ($score >= 50) $grade = 'E';
        else $grade = 'F';

        /**
         * ការប្រើ ON DUPLICATE KEY UPDATE នឹង Update ពិន្ទុចាស់ 
         * ប្រសិនបើ Student_ID, Subject_ID, Month, និង Year ដូចគ្នា។
         */
        $sql = "INSERT INTO scores (student_id, subject_id, total_score, grade, input_month, input_year, created_at) 
                VALUES ('$student_id', '$subject_id', '$score', '$grade', '$input_month', '$input_year', NOW())
                ON DUPLICATE KEY UPDATE 
                total_score = '$score', 
                grade = '$grade', 
                updated_at = NOW()";
        
        mysqli_query($conn, $sql);
    }

    // ត្រឡប់ទៅវិញជាមួយ Status Success
    header("Location: input_grades.php?class_id=$class_id&subject_id=$subject_id&status=success");
    exit();
}