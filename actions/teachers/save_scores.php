<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ឆែកសិទ្ធិចូលប្រើប្រាស់
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ២. ទទួលទិន្នន័យពី Form
    $class_id   = mysqli_real_escape_string($conn, $_POST['class_id']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $grades     = $_POST['grade']; // ទទួល Array [student_id => score] ពី input_grades.php

    foreach ($grades as $student_db_id => $score) {
        // បង្ការ SQL Injection
        $student_db_id = mysqli_real_escape_string($conn, $student_db_id);
        $score = mysqli_real_escape_string($conn, $score);

        // គណនា Grade (A-F) តាមពិន្ទុដែលលោកគ្រូចង់បាន
        if ($score >= 90) $grade_letter = 'A';
        elseif ($score >= 80) $grade_letter = 'B';
        elseif ($score >= 70) $grade_letter = 'C';
        elseif ($score >= 60) $grade_letter = 'D';
        elseif ($score >= 50) $grade_letter = 'E';
        else $grade_letter = 'F';

        // ៣. ឆែកមើលថា តើមានពិន្ទុសិស្សម្នាក់នេះក្នុងមុខវិជ្ជានេះរួចហើយឬនៅ?
        $check_sql = "SELECT id FROM scores WHERE student_id = '$student_db_id' AND subject_id = '$subject_id'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            // បើមានរួចហើយ ធ្វើបច្ចុប្បន្នភាព (Update)
            $sql = "UPDATE scores SET 
                    score = '$score', 
                    grade = '$grade_letter',
                    updated_at = NOW()
                    WHERE student_id = '$student_db_id' AND subject_id = '$subject_id'";
        } else {
            // បើមិនទាន់មាន បញ្ចូលថ្មី (Insert)
            $sql = "INSERT INTO scores (student_id, subject_id, class_id, score, grade, created_at) 
                    VALUES ('$student_db_id', '$subject_id', '$class_id', '$score', '$grade_letter', NOW())";
        }

        mysqli_query($conn, $sql);
    }

    // ៤. បញ្ជូនត្រឡប់ទៅទំព័រដើមវិញ ជាមួយ Status Success
    header("Location: input_grades.php?class_id=$class_id&subject_id=$subject_id&status=success");
    exit();
}
?>