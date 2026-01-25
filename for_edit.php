<!-- For edit  -->
<?php
    require_once 'config/db.php';
    require_once 'config/session.php';

    $sql = " SELECT * FROM students ";
    $resuilt = mysqli_query($conn, $sql);

    

?>

<table border=1>
    <tr>
        <td >Full Name Khmer</td>
        <td>Full Name English</td>
        <td>ID</td>
        <td>Gender</td>
        <td>dob</td>
        <td>address</td>
        <td>father Name</td>
        <td>Mother Name</td>
        <td>Stream </td>
        <td>Grade</td>
        <td>class ID</td>
        <td>Profile IMG</td>

    </tr>

    <?php
  while ($row = mysqli_fetch_assoc($resuilt)){
    echo "<tr>";
    echo "<td >" . $row['full_name'];
    echo "<td>" . $row['full_name_en'];
    echo "<td>" . $row['student_id'];
    echo "<td>" . $row['gender'];
    echo "<td>" . $row['dob'];
    echo "<td>" . $row['address'];
    echo "<td>" . $row['father_name'];
    echo "<td>" . $row['mother_name'];
    echo "<td>" . $row['stream'];
    echo "<td>" . $row['class_name'];
    echo "<td>" . $row['class_id']; 
    echo "<td>" . $row['profile_img'];


  }

    ?>
        
</table>