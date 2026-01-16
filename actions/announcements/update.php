<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $sql = "UPDATE announcements SET title='$title', content='$content' WHERE id='$id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/staff/announcements.php?status=updated");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: ../../views/staff/announcements.php");
}