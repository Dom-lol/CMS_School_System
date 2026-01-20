<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t_id       = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $u_id       = mysqli_real_escape_string($conn, $_POST['user_id']); 
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $subjects   = mysqli_real_escape_string($conn, $_POST['subjects']);
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);

    $image_sql = "";
    $new_img_name = "";

    // ចាត់ចែងការ Upload រូបភាពថ្មី
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $ext = pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION);
        $new_img_name = 'T_' . $t_id . '_' . time() . '.' . $ext;
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], "../../assets/uploads/teachers/" . $new_img_name)) {
            $image_sql = ", profile_image = '$new_img_name'";
        }
    }

    mysqli_begin_transaction($conn);
    try {
        // ១. Update ឈ្មោះក្នុង Table Users (ប្រើ id ពី Database របស់លោកគ្រូ)
        mysqli_query($conn, "UPDATE users SET full_name = '$full_name' WHERE id = '$u_id'");
        
        // ២. Update ព័ត៌មានក្នុង Table Teachers
        $sql = "UPDATE teachers SET full_name='$full_name', subjects='$subjects', phone='$phone' $image_sql WHERE teacher_id='$t_id'";
        mysqli_query($conn, $sql);

        // ៣. ចំណុចសំខាន់៖ Update Session ឱ្យ Header ប្តូរតាមភ្លាមៗ
        if ($_SESSION['user_id'] == $u_id) {
            $_SESSION['full_name'] = $full_name;
            if (!empty($new_img_name)) {
                $_SESSION['profile_image'] = $new_img_name;
            }
        }

        mysqli_commit($conn);
        header("Location: ../../views/staff/teachers_list.php?status=updated");
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("កំហុស៖ " . $e->getMessage());
    }
}