<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $posted_by = $_SESSION['full_name'];

    $sql = "INSERT INTO announcements (title, content, posted_by) VALUES ('$title', '$content', '$posted_by')";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/admin/manage_announcements.php?success=1");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>