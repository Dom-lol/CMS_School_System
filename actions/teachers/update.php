<?php
require_once '../../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t_id  = $_POST['teacher_id'];
    $major = mysqli_real_escape_string($conn, $_POST['major']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $sql = "UPDATE teachers SET major='$major', phone='$phone' WHERE teacher_id='$t_id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/staff/teachers_list.php?status=updated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}