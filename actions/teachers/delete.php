<?php
require_once '../../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // លុបតែក្នុងតារាង teachers (គណនីក្នុង users នៅដដែល)
    $sql = "DELETE FROM teachers WHERE teacher_id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/staff/teachers_list.php?status=deleted");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}