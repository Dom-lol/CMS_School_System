<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if (isset($_FILES['excel_data']['name'])) {
    $filename = $_FILES['excel_data']['tmp_name'];

    if ($_FILES['excel_data']['size'] > 0) {
        $file = fopen($filename, "r");
        fgetcsv($file); // រំលងជួរ Header

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            
            $student_id   = mysqli_real_escape_string($conn, $column[0]);
            $full_name    = mysqli_real_escape_string($conn, $column[1]);
            $full_name_en = mysqli_real_escape_string($conn, $column[2]);
            $gender       = mysqli_real_escape_string($conn, $column[3]);
            $dob          = mysqli_real_escape_string($conn, $column[4]);
            $class_name   = mysqli_real_escape_string($conn, $column[5]);
            $father_name  = mysqli_real_escape_string($conn, $column[6]);
            $mother_name  = mysqli_real_escape_string($conn, $column[7]);
            $address      = mysqli_real_escape_string($conn, $column[8]);

            // កំណត់យក ID ធ្វើជា Password ដោយផ្ទាល់ (មិនប្រើ Hash)
            $password_plain = $student_id; 

            $check_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$student_id'");

            if (mysqli_num_rows($check_user) == 0) {
                // បញ្ចូលទៅក្នុង Table users ដោយបង្ហាញ Password ច្បាស់ៗ
                $user_sql = "INSERT INTO users (username, password, role) VALUES ('$student_id', '$password_plain', 'student')";
                
                if (mysqli_query($conn, $user_sql)) {
                    $user_id = mysqli_insert_id($conn);

                    $student_sql = "INSERT INTO students (student_id, user_id, full_name, full_name_en, gender, dob, class_name, father_name, mother_name, address, status) 
                                    VALUES ('$student_id', '$user_id', '$full_name', '$full_name_en', '$gender', '$dob', '$class_name', '$father_name', '$mother_name', '$address', 'Active')";
                    
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