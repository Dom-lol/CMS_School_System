<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // លុបពី table users (វានឹងលុបក្នុង table teachers ដែរដោយសារ CASCADE)
    $sql = "DELETE FROM users WHERE id = '$user_id' AND role = 'teacher'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/admin/teachers_list.php?msg=deleted");
    }
}
?>