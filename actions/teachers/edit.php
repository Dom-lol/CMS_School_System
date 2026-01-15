<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t_id   = $_POST['teacher_id'];
    $name   = $_POST['full_name'];
    $major  = $_POST['major'];
    $phone  = $_POST['phone'];

    // Update ឈ្មោះក្នុង Table users
    $u_id = $_POST['user_id'];
    mysqli_query($conn, "UPDATE users SET full_name = '$name' WHERE id = '$u_id'");

    // Update ព័ត៌មានក្នុង Table teachers
    $sql = "UPDATE teachers SET major = '$major', phone = '$phone' WHERE teacher_id = '$t_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/admin/teachers_list.php?msg=updated");
    }
}
?>