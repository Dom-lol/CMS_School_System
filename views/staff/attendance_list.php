<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $attendance_date = date('Y-m-d'); // កាលបរិច្ឆេទថ្ងៃនេះ
    $statuses = $_POST['status']; // ចាប់យក Array នៃស្ថានភាពវត្តមាន [student_id => status]

    if (!empty($statuses)) {
        foreach ($statuses as $student_id => $status) {
            $student_id = mysqli_real_escape_string($conn, $student_id);
            $status = mysqli_real_escape_string($conn, $status);

            // ឆែកមើលថា តើថ្ងៃនេះបានកត់វត្តមានឱ្យសិស្សម្នាក់នេះរួចហើយឬនៅ? (ដើម្បីកុំឱ្យជាន់គ្នា)
            $check_query = "SELECT id FROM attendance 
                            WHERE student_id = '$student_id' 
                            AND attendance_date = '$attendance_date'";
            $check_result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                // បើមានរួចហើយ ធ្វើការ Update
                $sql = "UPDATE attendance SET status = '$status' 
                        WHERE student_id = '$student_id' 
                        AND attendance_date = '$attendance_date'";
            } else {
                // បើមិនទាន់មាន បញ្ចូលថ្មី
                $sql = "INSERT INTO attendance (student_id, class_name, status, attendance_date) 
                        VALUES ('$student_id', '$class_name', '$status', '$attendance_date')";
            }
            mysqli_query($conn, $sql);
        }

        // បញ្ជូនត្រឡប់ទៅវិញជាមួយសារជោគជ័យ
        header("Location: ../../views/staff/attendance.php?class=$class_name&status=success");
        exit();
    }
}
?>