<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_data'])) {
    $file = $_FILES['excel_data']['tmp_name'];
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

        $line = fgets($handle);
        $sep = (strpos($line, ';') !== false) ? ';' : ',';
        rewind($handle);

        $header = true;
        $success_count = 0;

        while (($data = fgetcsv($handle, 1000, $sep)) !== FALSE) {
            if ($header) { $header = false; continue; }
            
            // Student ID (Index 8 ក្នុង Excel)
            $sid = mysqli_real_escape_string($conn, $data[8]);
            if (empty($sid)) continue; 

            // ទិន្នន័យទូទៅ
            $name_kh = mysqli_real_escape_string($conn, $data[0]); 
            $name_en = mysqli_real_escape_string($conn, $data[1]); 
            $gender  = mysqli_real_escape_string($conn, $data[2]); 
            
            // បំប្លែងថ្ងៃខែ (Index 3)
            $raw_dob = $data[3]; 
            if (!empty($raw_dob) && $raw_dob != '#######') {
                $time = strtotime(str_replace('/', '-', $raw_dob));
                $dob = ($time) ? date('Y-m-d', $time) : 'NULL';
            } else {
                $dob = 'NULL';
            }

            $address = mysqli_real_escape_string($conn, $data[5]); 
            
            // បន្ថែម Father Name និង Mother Name (Index 6 និង 7)
            $father_name = mysqli_real_escape_string($conn, $data[6]); 
            $mother_name = mysqli_real_escape_string($conn, $data[7]); 
            
            $stream = mysqli_real_escape_string($conn, $data[9]); 

            // ១. បង្កើត User
            $pass = password_hash("123456", PASSWORD_DEFAULT);
            $check_u = mysqli_query($conn, "SELECT id FROM users WHERE username = '$sid'");
            
            if (mysqli_num_rows($check_u) > 0) {
                $u_row = mysqli_fetch_assoc($check_u);
                $user_id = $u_row['id'];
            } else {
                $sql_u = "INSERT INTO users (username, password, full_name, role) 
                          VALUES ('$sid', '$pass', '$name_kh', 'student')";
                mysqli_query($conn, $sql_u);
                $user_id = mysqli_insert_id($conn);
            }

            // ២. បញ្ចូលក្នុង Table 'students' (រួមទាំងឈ្មោះឪពុកម្តាយ)
            $sql_s = "INSERT INTO students 
                      (student_id, user_id, full_name, full_name_en, gender, dob, address, father_name, mother_name, class_name, academic_year, status) 
                      VALUES 
                      ('$sid', '$user_id', '$name_kh', '$name_en', '$gender', ".($dob == 'NULL' ? "NULL" : "'$dob'").", '$address', '$father_name', '$mother_name', '$stream', '2025-2026', 'Active')
                      ON DUPLICATE KEY UPDATE 
                      full_name = VALUES(full_name), 
                      father_name = VALUES(father_name), 
                      mother_name = VALUES(mother_name),
                      dob = VALUES(dob)";
            
            if (mysqli_query($conn, $sql_s)) { $success_count++; }
        }

        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
        fclose($handle);
        header("Location: ../../views/staff/student_list.php?msg=success&count=$success_count");
        exit();
    }
}
?>