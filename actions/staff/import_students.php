<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../config/db.php';
require_once '../../config/session.php';

if (isset($_FILES['excel_data']['name'])) {
    $filename = $_FILES['excel_data']['tmp_name'];

    if ($_FILES['excel_data']['size'] > 0) {
        $file = fopen($filename, "r");

        // រំលង Header
        fgets($file); 

        $count = 0;
        // ប្រើ "\t" ដើម្បីអាន Tab Delimiter (ព្រោះទិន្នន័យបង Copy មកមាន Tab)
        while (($column = fgetcsv($file, 10000, "\t")) !== FALSE) {
            
            // ប្រសិនបើអាន Tab មិនដាច់ សាកល្បងអានក្បៀសវិញ
            if (count($column) < 2) {
                rewind($file); // ត្រឡប់ទៅដើមវិញដើម្បីសាកក្បៀស
                fgets($file);
                while(($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                    processRow($conn, $column, $count);
                }
                break;
            }
            processRow($conn, $column, $count);
        }
        fclose($file);
        header("Location: ../../views/staff/student_list.php?import_success=$count");
        exit();
    }
}

// Function សម្រាប់រៀបចំទិន្នន័យចូល Database
function processRow($conn, $column, &$count) {
    if (!isset($column[8]) || empty(trim($column[8]))) return;

    $full_name_kh = mysqli_real_escape_string($conn, $column[0]);
    $full_name_en = mysqli_real_escape_string($conn, $column[1]);
    $gender       = mysqli_real_escape_string($conn, $column[2]);
    $dob          = mysqli_real_escape_string($conn, $column[3]);
    $pob          = mysqli_real_escape_string($conn, $column[4]);
    $address      = mysqli_real_escape_string($conn, $column[5]);
    $father_name  = mysqli_real_escape_string($conn, $column[6]);
    $mother_name  = mysqli_real_escape_string($conn, $column[7]);
    $student_id   = mysqli_real_escape_string($conn, trim($column[8]));
    $stream       = mysqli_real_escape_string($conn, $column[9]);
    $grade        = mysqli_real_escape_string($conn, $column[10]);

    $check = mysqli_query($conn, "SELECT id FROM students WHERE student_id = '$student_id'");

    if (mysqli_num_rows($check) > 0) {
        // UPDATE ព័ត៌មានទាំងអស់ (ឈប់ឱ្យ NULL)
        $sql = "UPDATE students SET 
                full_name = '$full_name_kh', full_name_en = '$full_name_en', 
                gender = '$gender', dob = '$dob', pob = '$pob', 
                address = '$address', father_name = '$father_name', 
                mother_name = '$mother_name', stream = '$stream', 
                class_name = '$grade' 
                WHERE student_id = '$student_id'";
    } else {
        // INSERT ថ្មី
        mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$student_id', '$student_id', 'student')");
        $user_id = mysqli_insert_id($conn);
        $sql = "INSERT INTO students (student_id, user_id, full_name, full_name_en, gender, dob, pob, address, father_name, mother_name, stream, class_name, status) 
                VALUES ('$student_id', '$user_id', '$full_name_kh', '$full_name_en', '$gender', '$dob', '$pob', '$address', '$father_name', '$mother_name', '$stream', '$grade', 'Active')";
    }

    if (mysqli_query($conn, $sql)) {
        $count++;
    }
}
?>