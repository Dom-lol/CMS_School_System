<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ចាប់យកទិន្នន័យទាំងអស់ពី Form (ប្រើ mysqli_real_escape_string ដើម្បីសុវត្ថិភាព)
    $old_id      = mysqli_real_escape_string($conn, $_POST['old_student_id']);
    $full_name   = mysqli_real_escape_string($conn, $_POST['full_name']);
    $full_name_en = mysqli_real_escape_string($conn, $_POST['full_name_en'] ?? '');
    $gender      = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob         = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');
    $pob         = mysqli_real_escape_string($conn, $_POST['pob'] ?? '');
    $address     = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $stream      = mysqli_real_escape_string($conn, $_POST['stream'] ?? '');
    $class_name  = mysqli_real_escape_string($conn, $_POST['class_name']);
    $father_name = mysqli_real_escape_string($conn, $_POST['father_name'] ?? '');
    $mother_name = mysqli_real_escape_string($conn, $_POST['mother_name'] ?? '');
    $status      = mysqli_real_escape_string($conn, $_POST['status']);

    $img_update = "";

    // ត្រួតពិនិត្យ និង Upload រូបភាព
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0) {
        $file = $_FILES['profile_img'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_name = "profile_" . $old_id . "_" . time() . "." . $ext;
        $upload_dir = "../../assets/uploads/profiles/";

        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
            $img_update = ", profile_img = '$new_name'";
        }
    }

    // ១. Update ក្នុង Table students (គ្រប់ Column ទាំងអស់)
    $sql_students = "UPDATE students SET 
                    full_name    = '$full_name',
                    full_name_en = '$full_name_en',
                    gender       = '$gender', 
                    dob          = '$dob',
                    pob          = '$pob',
                    address      = '$address',
                    stream       = '$stream',
                    class_name   = '$class_name', 
                    father_name  = '$father_name',
                    mother_name  = '$mother_name',
                    status       = '$status' 
                    $img_update 
                    WHERE student_id = '$old_id'";

    // ២. Update ឈ្មោះក្នុង Table users ឱ្យដូចគ្នា
    $sql_users = "UPDATE users SET full_name = '$full_name' WHERE username = '$old_id'";

    if (mysqli_query($conn, $sql_students) && mysqli_query($conn, $sql_users)) {
        header("Location: ../../views/staff/student_list.php?msg=updated");
        exit();
    } else {
        die("Database Error: " . mysqli_error($conn));
    }
}