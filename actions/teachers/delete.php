<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if (isset($_GET['id'])) {
    $user_id = mysqli_real_escape_string($conn, $_GET['id']);

    // ចាប់ផ្ដើម Transaction ដើម្បីការពារកំហុស
    mysqli_begin_transaction($conn);

    try {
        // ១. លុបក្នុងតារាង teachers ជាមុន
        $del_teacher = "DELETE FROM teachers WHERE user_id = '$user_id'";
        mysqli_query($conn, $del_teacher);

        // ២. លុបក្នុងតារាង users តាមក្រោយ
        $del_user = "DELETE FROM users WHERE id = '$user_id'";
        mysqli_query($conn, $del_user);

        // បើជោគជ័យទាំងពីរ
        mysqli_commit($conn);
        header("Location: ../../views/staff/teachers_list.php?status=deleted");
        exit();

    } catch (Exception $e) {
        // បើមានបញ្ហា មិនអនុញ្ញាតឱ្យលុប
        mysqli_rollback($conn);
        die("Error deleting record: " . $e->getMessage());
    }
} else {
    header("Location: ../../views/staff/teachers_list.php");
    exit();
}