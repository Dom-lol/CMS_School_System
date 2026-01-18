<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../../config/db.php';
require_once '../../config/session.php';

// ឆែកមើលថាតើមានការ Upload File ដែរឬទេ
if (isset($_FILES['excel_data']['name'])) {
    $filename = $_FILES['excel_data']['tmp_name'];

    if ($_FILES['excel_data']['size'] > 0) {
        $file = fopen($filename, "r");

        // ១. រំលងជួរ Header (ចំណងជើង column ក្នុង Excel)
        fgets($file); 

        $count = 0;
        
        // ២. អានទិន្នន័យពី File (គាំទ្រទាំងប្រភេទ Tab-Separated និង Comma-Separated)
        while (($column = fgetcsv($file, 10000, "\t")) !== FALSE) {
            
            // ប្រសិនបើអាន Tab មិនដាច់ (ករណី File ជា CSV ធម្មតា) សាកល្បងអានដោយប្រើក្បៀស (,) វិញ
            if (count($column) < 2) {
                rewind($file); 
                fgets($file); // រំលង header ម្តងទៀត
                while(($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                    processRow($conn, $column, $count);
                }
                break;
            }
            processRow($conn, $column, $count);
        }
        
        fclose($file);
        
        // បញ្ជូនទៅកាន់ទំព័របញ្ជីឈ្មោះសិស្សវិញ ជាមួយចំនួនសិស្សដែលបញ្ចូលជោគជ័យ
        header("Location: ../../views/staff/student_list.php?import_success=$count");
        exit();
    }
}

/**
 * Function សម្រាប់រៀបចំ និងបញ្ចូលទិន្នន័យទៅក្នុង Database
 */
function processRow($conn, $column, &$count) {
    // ប្រសិនបើ Column Student ID (លេខរៀងទី ៩) ទទេ មិនបាច់ធ្វើការទេ
    if (!isset($column[8]) || empty(trim($column[8]))) return;

    // សម្អាតទិន្នន័យមុនបញ្ចូលការពារ SQL Injection
    $full_name_kh = mysqli_real_escape_string($conn, $column[0]);
    $full_name_en = mysqli_real_escape_string($conn, $column[1]);
    $gender       = mysqli_real_escape_string($conn, $column[2]);
    $dob          = mysqli_real_escape_string($conn, $column[3]);
    $pob          = mysqli_real_escape_string($conn, $column[4]);
    $address      = mysqli_real_escape_string($conn, $column[5]);
    $father_name  = mysqli_real_escape_string($conn, $column[6]);
    $mother_name  = mysqli_real_escape_string($conn, $column[7]);
    $student_id   = mysqli_real_escape_string($conn, trim($column[8])); // លេខសម្គាល់សិស្ស
    $stream       = mysqli_real_escape_string($conn, $column[9]);
    $grade_text   = mysqli_real_escape_string($conn, $column[10]); // ឈ្មោះថ្នាក់ (ឧ: "ថ្នាក់ទី 9")

    // --- ផ្នែកសំខាន់បំផុត៖ បំប្លែងអក្សរថ្នាក់ឱ្យទៅជាលេខ ID ---
    // ប្រើ regex ដើម្បីទាញយកតែលេខពីអត្ថបទ (ឧទាហរណ៍៖ "ថ្នាក់ទី 9" វានឹងយកលេខ 9)
    $class_id = preg_replace('/[^0-9]/', '', $grade_text); 
    if(empty($class_id)) $class_id = 0; 

    // ៣. ឆែកមើលតើមានសិស្សនេះក្នុងប្រព័ន្ធហើយឬនៅ
    $check = mysqli_query($conn, "SELECT id FROM students WHERE student_id = '$student_id'");

    if (mysqli_num_rows($check) > 0) {
        // ករណីមានរួចហើយ៖ ធ្វើការ UPDATE ព័ត៌មាន និង class_id ឱ្យត្រឹមត្រូវ
        $sql = "UPDATE students SET 
                full_name = '$full_name_kh', 
                full_name_en = '$full_name_en', 
                gender = '$gender', 
                dob = '$dob', 
                pob = '$pob', 
                address = '$address', 
                father_name = '$father_name', 
                mother_name = '$mother_name', 
                stream = '$stream', 
                class_name = '$grade_text', 
                class_id = '$class_id' 
                WHERE student_id = '$student_id'";
    } else {
        // ករណីថ្មី៖ បង្កើត Account ក្នុងតារាង users ជាមុនសិន
        // ប្រើ student_id ជា Username និង Password សម្រាប់សិស្ស Login
        mysqli_query($conn, "INSERT INTO users (username, password, role) VALUES ('$student_id', '$student_id', 'student')");
        $user_id = mysqli_insert_id($conn);

        // បន្ទាប់មកបញ្ចូលទិន្នន័យក្នុងតារាង students
        $sql = "INSERT INTO students (student_id, user_id, full_name, full_name_en, gender, dob, pob, address, father_name, mother_name, stream, class_name, class_id, status) 
                VALUES ('$student_id', '$user_id', '$full_name_kh', '$full_name_en', '$gender', '$dob', '$pob', '$address', '$father_name', '$mother_name', '$stream', '$grade_text', '$class_id', 'Active')";
    }

    // ប្រសិនបើការបញ្ចូលជោគជ័យ បូកចំនួនសិស្សបន្ថែម
    if (mysqli_query($conn, $sql)) {
        $count++;
    }
}
?>