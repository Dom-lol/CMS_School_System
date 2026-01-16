<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    
    // កំណត់ថា "រដ្ឋបាល" ជាអ្នកបង្ហោះ
    $posted_by = "រដ្ឋបាល"; 

    $sql = "INSERT INTO announcements (title, content, posted_by) VALUES ('$title', '$content', '$posted_by')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/staff/announcements.php?status=success");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}