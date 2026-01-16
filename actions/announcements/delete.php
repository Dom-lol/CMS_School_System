<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

// ឆែកមើល ID ដែលបានផ្ញើមកតាម URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $sql = "DELETE FROM announcements WHERE id = '$id'";
    
    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/staff/announcements.php?status=deleted");
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    header("Location: ../../views/staff/announcements.php");
}