<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

// ឆែកសិទ្ធិថាជា Staff ឬ Admin
staff_or_admin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ចាប់យកទិន្នន័យពី Form
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']); // ប្រើជា Username
    $password   = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $gender     = mysqli_real_escape_string($conn, $_POST['gender']);
    $class_name = mysqli_real_escape_string($conn, $_POST['class_name']);
    $status     = mysqli_real_escape_string($conn, $_POST['status']);
    $role       = 'student';

    // ១. ពិនិត្យមើល Username (student_id) កុំឱ្យស្ទួន
    $check_user = mysqli_query($conn, "SELECT id FROM users WHERE username = '$student_id' LIMIT 1");
    
    if (mysqli_num_rows($check_user) > 0) {
        echo "<script>
                alert('កំហុស៖ អត្តលេខ $student_id មានក្នុងប្រព័ន្ធរួចហើយ!');
                window.history.back();
              </script>";
        exit();
    }

    // ២. បញ្ចូលទៅក្នុងតារាង users
    $sql_user = "INSERT INTO users (username, password, full_name, role) 
                 VALUES ('$student_id', '$password', '$full_name', '$role')";

    if (mysqli_query($conn, $sql_user)) {
        // ៣. យក ID ដែលទើបបង្កើតថ្មីៗ
        $new_user_id = mysqli_insert_id($conn);

        // ៤. បញ្ចូលទៅក្នុងតារាង students (ព័ត៌មានលម្អិត)
        $sql_student = "INSERT INTO students (user_id, student_id, gender, class_name, status) 
                        VALUES ('$new_user_id', '$student_id', '$gender', '$class_name', '$status')";
        
        if (mysqli_query($conn, $sql_student)) {
            // ជោគជ័យ៖ រុញទៅកាន់បញ្ជីឈ្មោះសិស្ស (មិនមែន login.php ទេ)
            header("Location: ../../views/staff/student_list.php?status=created");
            exit();
        } else {
            echo "Error Student Table: " . mysqli_error($conn);
        }
    } else {
        echo "Error User Table: " . mysqli_error($conn);
    }
} else {
    header("Location: ../../views/staff/add_student.php");
    exit();
}