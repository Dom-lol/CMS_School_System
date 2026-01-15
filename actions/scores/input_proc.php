<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in(); // ឆែកថាតើបាន Login ឬនៅ

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ទទួលទិន្នន័យជា Array ពី Form (ព្រោះគ្រូបញ្ចូលពិន្ទុច្រើននាក់ក្នុងពេលតែមួយ)
    $student_ids = $_POST['student_ids'];
    $subject_id  = $_POST['subject_id'];
    $monthly_scores = $_POST['monthly_scores'];
    $exam_scores    = $_POST['exam_scores'];

    foreach ($student_ids as $index => $student_id) {
        $m_score = $monthly_scores[$index];
        $e_score = $exam_scores[$index];
        
        // គណនាពិន្ទុសរុប (មធ្យមភាគ)
        $total = ($m_score + $e_score) / 2;

        // កំណត់ Grade (A-F) តាមស្ដង់ដារ
        if ($total >= 90) $grade = 'A';
        elseif ($total >= 80) $grade = 'B';
        elseif ($total >= 70) $grade = 'C';
        elseif ($total >= 60) $grade = 'D';
        elseif ($total >= 50) $grade = 'E';
        else $grade = 'F';

        // ឆែកមើលថា តើមានពិន្ទុសម្រាប់សិស្សម្នាក់នេះក្នុងមុខវិជ្ជានេះរួចហើយឬនៅ?
        $check_sql = "SELECT id FROM scores WHERE student_id = '$student_id' AND subject_id = '$subject_id'";
        $check_res = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_res) > 0) {
            // បើមានរួចហើយ ធ្វើបច្ចុប្បន្នភាព (Update)
            $sql = "UPDATE scores SET 
                    monthly_score = '$m_score', 
                    exam_score = '$e_score', 
                    total_score = '$total', 
                    grade = '$grade' 
                    WHERE student_id = '$student_id' AND subject_id = '$subject_id'";
        } else {
            // បើមិនទាន់មាន បញ្ចូលថ្មី (Insert)
            $sql = "INSERT INTO scores (student_id, subject_id, monthly_score, exam_score, total_score, grade) 
                    VALUES ('$student_id', '$subject_id', '$m_score', '$e_score', '$total', '$grade')";
        }

        mysqli_query($conn, $sql);
    }

    // បញ្ជូនត្រឡប់ទៅទំព័របញ្ចូលពិន្ទុវិញ ជាមួយសារជោគជ័យ
    header("Location: ../../views/teacher/input_scores.php?status=success");
}
?>