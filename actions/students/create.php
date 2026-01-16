<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

// ឆែកសិទ្ធិ (ឱ្យតែ Staff ឬ Admin ទើបអាចបង្កើតសិស្សបាន)
staff_or_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username  = mysqli_real_escape_string($conn, $_POST['username']);
    $password  = password_hash($_POST['password'], PASSWORD_DEFAULT); // ដាក់លេខសម្ងាត់ឱ្យមានសុវត្ថិភាព
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $class_id  = mysqli_real_escape_string($conn, $_POST['class_id']);
    $role      = 'student';

    // ១. ឆែកមើល Username ស្ទួន
    $check_username = mysqli_query($conn, "SELECT id FROM users WHERE username = '$username' LIMIT 1");
    if (mysqli_num_rows($check_username) > 0) {
        echo "<script>alert('កំហុស៖ Username $username មានគេប្រើរួចហើយ!'); window.history.back();</script>";
        exit();
    }

    // ២. បញ្ចូលទៅក្នុងតារាង users
    $sql_user = "INSERT INTO users (username, password, full_name, role) 
                 VALUES ('$username', '$password', '$full_name', '$role')";

    if (mysqli_query($conn, $sql_user)) {
        // ៣. យក ID ដែលទើបតែបង្កើតក្នុងតារាង users
        $new_user_id = mysqli_insert_id($conn);

        // ៤. បញ្ចូលទៅក្នុងតារាង students ដើម្បីភ្ជាប់ជាមួយថ្នាក់ (class_id)
        $sql_student = "INSERT INTO students (user_id, class_id) VALUES ('$new_user_id', '$class_id')";
        
        if (mysqli_query($conn, $sql_student)) {
            // បញ្ចូលជោគជ័យ រុញទៅទំព័របញ្ជីសិស្ស (មិនមែន login.php ទេ)
            header("Location: ../../views/staff/manage_students.php?status=success");
            exit();
        } else {
            die("Error in students table: " . mysqli_error($conn));
        }
    } else {
        die("Error in users table: " . mysqli_error($conn));
    }
}