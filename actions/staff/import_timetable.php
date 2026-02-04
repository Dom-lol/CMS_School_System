<?php
session_start();
include_once '../../config/db.php'; // ភ្ជាប់ Database [cite: 2026-01-20]

if (isset($_POST['import_btn'])) {
    // ឆែកមើលឯកសារដែលបាន Upload [cite: 2026-01-20]
    $fileName = $_FILES['timetable_file']['tmp_name'];

    if ($_FILES['timetable_file']['size'] > 0) {
        $file = fopen($fileName, "r");
        
        // រំលងជួរទី ១ (Header នៃ CSV) [cite: 2026-01-20]
        fgetcsv($file);

        $success_count = 0;
        $error_count = 0;

        // អានទិន្នន័យពី CSV ម្តងមួយជួរ (Column A-G) [cite: 2026-01-20]
        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $day         = mysqli_real_escape_string($conn, $column[0]); // Column A
            $start_time  = mysqli_real_escape_string($conn, $column[1]); // Column B
            $end_time    = mysqli_real_escape_string($conn, $column[2]); // Column C
            $room        = mysqli_real_escape_string($conn, $column[3]); // Column D
            $subject_id  = mysqli_real_escape_string($conn, $column[4]); // Column E
            $teacher_id  = mysqli_real_escape_string($conn, $column[5]); // Column F
            $class_id    = mysqli_real_escape_string($conn, $column[6]); // Column G

            // បញ្ចូលទៅក្នុង Database [cite: 2026-01-20]
            $sql = "INSERT INTO timetable (day_of_week, start_time, end_time, room_number, subject_id, teacher_id, class_id, is_deleted) 
                    VALUES ('$day', '$start_time', '$end_time', '$room', '$subject_id', '$teacher_id', '$class_id', 0)";
            
            if (mysqli_query($conn, $sql)) {
                $success_count++;
            } else {
                $error_count++;
            }
        }
        
        fclose($file);
        // បញ្ជូនត្រឡប់ទៅទំព័រដើមវិញជាមួយលទ្ធផល [cite: 2026-01-20]
        header("Location: ../../views/staff/timetable.php?msg=success&count=$success_count");
        exit();
    }
}
?>