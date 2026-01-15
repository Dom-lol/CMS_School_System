<?php
session_start();
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['status'])) {
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $attendance_date = date('Y-m-d'); // កត់ថ្ងៃនេះ
    $recorded_by = $_SESSION['username']; // ឈ្មោះបុគ្គលិកដែលកត់

    foreach ($_POST['status'] as $student_id => $status) {
        $student_id = mysqli_real_escape_string($conn, $student_id);
        $status = mysqli_real_escape_string($conn, $status);

        // ១. ពិនិត្យមើលថា តើថ្ងៃនេះសិស្សម្នាក់នេះមានទិន្នន័យវត្តមានហើយឬនៅ?
        $check_sql = "SELECT id FROM attendance 
                      WHERE student_id = '$student_id' 
                      AND attendance_date = '$attendance_date'";
        $check_result = mysqli_query($conn, $check_sql);

        if (mysqli_num_rows($check_result) > 0) {
            // ២. បើមានហើយ ធ្វើការ Update ស្ថានភាពថ្មី
            $sql = "UPDATE attendance SET status = '$status', recorded_by = '$recorded_by' 
                    WHERE student_id = '$student_id' AND attendance_date = '$attendance_date'";
        } else {
            // ៣. បើមិនទាន់មាន ធ្វើការ Insert ចូលថ្មី
            $sql = "INSERT INTO attendance (student_id, status, attendance_date, class_name, recorded_by) 
                    VALUES ('$student_id', '$status', '$attendance_date', '$class_name', '$recorded_by')";
        }

        mysqli_query($conn, $sql);
    }

    // បញ្ជូនត្រឡប់ទៅទំព័រដើមវិញ ជាមួយសារជោគជ័យ
    header("Location: ../../views/staff/attendance_list.php?class=$class_name&status=success");
    exit();
} else {
    header("Location: ../../views/staff/attendance_list.php?status=error");
    exit();
}
?>