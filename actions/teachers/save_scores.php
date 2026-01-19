<?php
require_once '../../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $student_ids = $_POST['student_id'];
    $m_scores = $_POST['monthly_score'];
    $e_scores = $_POST['exam_score'];

    foreach ($student_ids as $key => $st_id) {
        $m = (float)$m_scores[$key];
        $e = (float)$e_scores[$key];
        $total = $m + $e;

        // Logic និទ្ទេស
        $grade = ($total >= 45) ? 'A' : (($total >= 40) ? 'B' : (($total >= 35) ? 'C' : (($total >= 25) ? 'D' : 'E')));

        $sql = "INSERT INTO scores (student_id, subject_id, monthly_score, exam_score, total_score, grade, created_at) 
                VALUES ('$st_id', '$subject_id', '$m', '$e', '$total', '$grade', NOW())
                ON DUPLICATE KEY UPDATE monthly_score='$m', exam_score='$e', total_score='$total', grade='$grade'";
        mysqli_query($conn, $sql);
    }
    echo json_encode(['status' => 'success']);
}