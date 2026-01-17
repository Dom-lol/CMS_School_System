<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ចាប់យកទិន្នន័យពី Form
    $s_id    = mysqli_real_escape_string($conn, $_POST['student_id']);
    $name    = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender  = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob     = mysqli_real_escape_string($conn, $_POST['dob']);
    $class   = mysqli_real_escape_string($conn, $_POST['class_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    // ប្រសិនបើក្នុង Form បងអត់មានបញ្ចូល Phone ទេ កូដនឹងដាក់ NULL ឬ ទទេ
    $phone   = isset($_POST['phone']) ? mysqli_real_escape_string($conn, $_POST['phone']) : '';

    // បិទការឆែក Foreign Key ជាបណ្តោះអាសន្ន ដើម្បីការពារ Error #1452
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

    // ១. បង្កើត Account ឱ្យសិស្សក្នុង Table users
    // បងគួរប្រើ password_hash ដើម្បីសុវត្ថិភាព ប៉ុន្តែបងអាចប្រើ '123456' ធម្មតាតាមកូដចាស់បងក៏បាន
    $pass = password_hash('123456', PASSWORD_DEFAULT); 
    
    // ឆែកមើលថា តើមាន Username (student_id) នេះហើយឬនៅ?
    $check_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$s_id'");
    
    if (mysqli_num_rows($check_user) > 0) {
        // បើមានហើយ យក ID ចាស់មកប្រើ
        $u_row = mysqli_fetch_assoc($check_user);
        $user_id = $u_row['id'];
    } else {
        // បើអត់ទាន់មាន បង្កើតថ្មី (ប្រើ Column username តាម image_901342.png)
        $sql_user = "INSERT INTO users (username, password, full_name, role) 
                     VALUES ('$s_id', '$pass', '$name', 'student')";
        
        if (!mysqli_query($conn, $sql_user)) {
            die("Error Table Users: " . mysqli_error($conn));
        }
        $user_id = mysqli_insert_id($conn);
    }

    // ២. បញ្ចូលព័ត៌មានលម្អិតក្នុង table students
    // ប្រើ INSERT ... ON DUPLICATE KEY UPDATE ដើម្បីកុំឱ្យជាន់ Student ID
    $sql_student = "INSERT INTO students (student_id, user_id, full_name, gender, dob, address, phone, class_name, status) 
                    VALUES ('$s_id', '$user_id', '$name', '$gender', '$dob', '$address', '$phone', '$class', 'Active')
                    ON DUPLICATE KEY UPDATE full_name = '$name', class_name = '$class'";

    if (mysqli_query($conn, $sql_student)) {
        // បើកការឆែក Foreign Key វិញ
        mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");
        
        header("Location: ../../views/staff/student_list.php?msg=added");
        exit();
    } else {
        die("Error Table Students: " . mysqli_error($conn));
    }
}
?>