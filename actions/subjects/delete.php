<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $sql = "DELETE FROM subjects WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/admin/subjects_list.php?msg=deleted");
    }
}
?>