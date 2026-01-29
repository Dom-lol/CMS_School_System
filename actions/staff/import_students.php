<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_data'])) {
    $file = $_FILES['excel_data']['tmp_name'];
    
    // កំណត់ឆ្នាំសិក្សា
    $academic_year = "2025-2026"; 

    if (($handle = fopen($file, "r")) !== FALSE) {
        $conn->begin_transaction();

        try {
            fgetcsv($handle); // រំលង Header
            $success_count = 0;
            
            // ១. Prepare User Query - ប្រើ INSERT IGNORE ដើម្បីការពារ Error បើមាន User ID ជាន់គ្នា
            $sql_user = "INSERT IGNORE INTO users (username, password, full_name, role) VALUES (?, ?, ?, 'student')";
            $stmt_user = $conn->prepare($sql_user);

            // ២. Prepare Student Query
            $sql_student = "INSERT INTO students (
                user_id, student_id, full_name, full_name_en, gender, dob, pob, 
                address, father_name, mother_name, father_job, mother_job, 
                father_phone, mother_phone, stream, class_name, class_id, 
                academic_year, status, photo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', 'default.png')
            ON DUPLICATE KEY UPDATE 
                full_name = VALUES(full_name),
                class_id = VALUES(class_id),
                academic_year = VALUES(academic_year)"; // បើមាន ID រួចហើយ វានឹង Update ជំនួសវិញ
            
            $stmt_student = $conn->prepare($sql_student);

            $gradeMap = ["7" => 1, "8" => 2, "9" => 3, "10" => 4, "11" => 5, "12" => 6];

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // ត្រួតពិនិត្យទិន្នន័យចាំបាច់
                if (empty($data[0]) || empty($data[12])) continue;

                $s_id      = trim($data[12]); 
                $s_name_kh = trim($data[0]);  
                $c_name    = trim($data[14]); 
                $c_id      = isset($gradeMap[$c_name]) ? $gradeMap[$c_name] : NULL;

                // --- ក. បង្កើត User Account ---
                $stmt_user->bind_param("sss", $s_id, $s_id, $s_name_kh);
                $stmt_user->execute();
                
                // ទាញយក user_id (បើទើបបង្កើតថ្មី ឬមានស្រាប់)
                $res_user = $conn->query("SELECT id FROM users WHERE username = '$s_id'");
                $user_data = $res_user->fetch_assoc();
                $current_user_id = $user_data['id'];

                // --- ខ. បញ្ចូលព័ត៌មានសិស្ស ---
                $stmt_student->bind_param("isssssssssssssssss", 
                    $current_user_id, 
                    $s_id, 
                    $data[0], $data[1], $data[2], $data[3], $data[4], $data[5], 
                    $data[6], $data[7], $data[8], $data[9], $data[10], $data[11], 
                    $data[13], $c_name, $c_id, $academic_year
                );

                if ($stmt_student->execute()) {
                    $success_count++;
                }
            }

            $conn->commit();
            fclose($handle);
            header("Location: ../../views/staff/student_list.php?import_success=$success_count");
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            if (isset($handle)) fclose($handle);
            die("<div style='color:red; font-family:khmer os battambang;'>កំហុស៖ " . $e->getMessage() . "</div>");
        }
    }
}