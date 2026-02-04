<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // = SQL Injection
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $subjects   = mysqli_real_escape_string($conn, $_POST['subjects']); // ឈ្មោះមុខវិជ្ជា
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);
    
    //  "123456"
    $password   = password_hash("123456", PASSWORD_DEFAULT);

    // រៀបចំការ Upload រូបថត
    $profile_image = "default_user.png";
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $new_name = "T_" . $teacher_id . "_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], "../../public/uploads/teachers/" . $new_name)) {
            $profile_image = $new_name;
        }
    }

    // ចាប់ផ្ដើម Transaction ដើម្បីសុវត្ថិភាព
    mysqli_begin_transaction($conn);

    try {
        // ១. បង្កើតគណនីក្នុងតារាង users
        $sql_user = "INSERT INTO users (username, password, role) VALUES ('$teacher_id', '$password', 'teacher')";
        if (!mysqli_query($conn, $sql_user)) throw new Exception(mysqli_error($conn));
        
        $new_user_id = mysqli_insert_id($conn); // ទាញយក ID ដែលទើបនឹងបង្កើត

        // ២. បញ្ចូលព័ត៌មានគ្រូក្នុងតារាង teachers (យោងតាមរូបភាពទី ៣ របស់លោកឪ)
        $sql_teacher = "INSERT INTO teachers (teacher_id, user_id, full_name, subjects, phone, profile_image) 
                        VALUES ('$teacher_id', '$new_user_id', '$full_name', '$subjects', '$phone', '$profile_image')";
        
        if (!mysqli_query($conn, $sql_teacher)) throw new Exception(mysqli_error($conn));

        mysqli_commit($conn);
        header("Location: ../../views/staff/teachers_list.php?msg=success");
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("ទិន្នន័យមិនចូល Database៖ " . $e->getMessage());
    }
}