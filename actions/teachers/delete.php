<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

$u_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

if (!empty($u_id)) {
    $res = mysqli_query($conn, "SELECT profile_image FROM teachers WHERE user_id = '$u_id'");
    $row = mysqli_fetch_assoc($res);
    $img = $row['profile_image'];

    mysqli_begin_transaction($conn);
    try {
        mysqli_query($conn, "DELETE FROM teachers WHERE user_id = '$u_id'");
        mysqli_query($conn, "DELETE FROM users WHERE id = '$u_id'");
        
        if ($img && $img != 'default_user.png') {
            @unlink("../../assets/uploads/teachers/" . $img);
        }

        mysqli_commit($conn);
        header("Location: ../../views/staff/teachers_list.php?status=deleted");
    } catch (Exception $e) {
        mysqli_rollback($conn);
        die("Error: " . $e->getMessage());
    }
}