<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_CMS_School";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// បន្ថែមជួរកូដខាងក្រោមនេះ ដើម្បីឱ្យ Support អក្សរខ្មែរ
mysqli_set_charset($conn, "utf8mb4");
?>