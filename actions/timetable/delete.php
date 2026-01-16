<?php
require_once '../../config/db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM timetables WHERE id = '$id'");
    header("Location: ../../views/staff/timetable.php?status=deleted");
}