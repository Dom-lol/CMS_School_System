<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if (isset($_FILES['excel_data']['name'])) {
    $filename = $_FILES['excel_data']['tmp_name'];

    if ($_FILES['excel_data']['size'] > 0) {
        $file = fopen($filename, "r");
        fgetcsv($file); // រំលងជួរ Header

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            
            // --- ចាប់ផ្ដើមការកែសម្រួលលេខជួរឱ្យត្រូវតាម Excel បង (Column A=0, B=1, ...) ---
            $full_name      = mysqli_real_escape_string($conn, $column[0]);  // Column A
            $full_name_en   = mysqli_real_escape_string($conn, $column[1]);  // Column B
            $gender         = mysqli_real_escape_string($conn, $column[2]);  // Column C
            $dob            = mysqli_real_escape_string($conn, $column[3]);  // Column D
            $pob            = mysqli_real_escape_string($conn, $column[4]);  // Column E (ទីកន្លែងកំណើត)
            $address        = mysqli_real_escape_string($conn, $column[5]);  // Column F (អាសយដ្ឋាន)
            $student_id     = mysqli_real_escape_string($conn, $column[8]);  // Column I (ID សិស្ស)
            $stream         = mysqli_real_escape_string($conn, $column[9]);  // Column J (វិទ្យាសាស្ត្រ/សង្គម)
            $class_name     = mysqli_real_escape_string($conn, $column[10]); // Column K (Grade 12 - នេះជាចំណុចសំខាន់!)

            // ឆែកមើលជួរឪពុកម្តាយ (បើក្នុង Excel បងមានជួរទាំងនេះ សូមប្តូរលេខ index ឱ្យត្រូវ)
            $father_name    = mysqli_real_escape_string($conn, $column[6] ?? ''); 
            $mother_name    = mysqli_real_escape_string($conn, $column[7] ?? ''); 

            // កំណត់យក ID ធ្វើជា Password
            $password_plain = $student_id; 

            $check_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$student_id'");

            if (mysqli_num_rows($check_user) == 0) {
                // បញ្ចូលទៅក្នុង Table users
                $user_sql = "INSERT INTO users (username, password, role) VALUES ('$student_id', '$password_plain', 'student')";
                
                if (mysqli_query($conn, $user_sql)) {
                    $user_id = mysqli_insert_id($conn);

                    // បញ្ចូលទៅក្នុង Table students ដោយប្រើ class_name ពីជួរទី ១០ (Column K)
                    $student_sql = "INSERT INTO students (student_id, user_id, full_name, full_name_en, gender, dob, pob, address, stream, class_name, father_name, mother_name, status) 
                                    VALUES ('$student_id', '$user_id', '$full_name', '$full_name_en', '$gender', '$dob', '$pob', '$address', '$stream', '$class_name', '$father_name', '$mother_name', 'Active')";
                    
                    mysqli_query($conn, $student_sql);
                }
            }
        }
        fclose($file);
        header("Location: ../../views/staff/student_list.php?import_success=1");
        exit();
    }
}
?>