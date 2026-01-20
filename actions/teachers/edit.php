<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();
<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ១. ទទួលទិន្នន័យពី Form [cite: 2026-01-20]
    $t_id       = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $u_id       = mysqli_real_escape_string($conn, $_POST['user_id']);
    $full_name  = mysqli_real_escape_string($conn, $_POST['full_name']);
    $subjects   = mysqli_real_escape_string($conn, $_POST['subjects']); // ប្តូរតាមឈ្មោះ Column ក្នុង DB លោកគ្រូ [cite: 2026-01-20]
    $phone      = mysqli_real_escape_string($conn, $_POST['phone']);

    // ចាប់ផ្ដើម Transaction ដើម្បីធានាថាទិន្នន័យបានកែប្រែទាំង ២ តារាង [cite: 2026-01-20]
    mysqli_begin_transaction($conn);

    try {
        // ២. Update ឈ្មោះក្នុង Table users (សម្រាប់បង្ហាញពេល Login) [cite: 2026-01-20]
        $sql_user = "UPDATE users SET full_name = '$full_name' WHERE id = '$u_id'";
        mysqli_query($conn, $sql_user);

        // ៣. Update ព័ត៌មានក្នុង Table teachers (ប្រើ subjects និង phone) [cite: 2026-01-20]
        $sql_teacher = "UPDATE teachers SET 
                        full_name = '$full_name', 
                        subjects = '$subjects', 
                        phone = '$phone' 
                        WHERE teacher_id = '$t_id'";
        mysqli_query($conn, $sql_teacher);

        // បើជោគជ័យទាំងពីរ [cite: 2026-01-20]
        mysqli_commit($conn);
        
        // រុញត្រឡប់ទៅបញ្ជីគ្រូវិញ ជាមួយ Layout ពេញទទឹង (Full Width) ដដែល [cite: 2026-01-20]
        header("Location: ../../views/staff/teachers_list.php?status=updated");
        exit();

    } catch (Exception $e) {
        // បើមានបញ្ហា វានឹងមិនកែប្រែអ្វីទាំងអស់ [cite: 2026-01-20]
        mysqli_rollback($conn);
        die("Error updating: " . $e->getMessage());
    }
}
?>
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t_id   = $_POST['teacher_id'];
    $name   = $_POST['full_name'];
    $major  = $_POST['major'];
    $phone  = $_POST['phone'];

    // Update ឈ្មោះក្នុង Table users
    $u_id = $_POST['user_id'];
    mysqli_query($conn, "UPDATE users SET full_name = '$name' WHERE id = '$u_id'");

    // Update ព័ត៌មានក្នុង Table teachers
    $sql = "UPDATE teachers SET major = '$major', phone = '$phone' WHERE teacher_id = '$t_id'";

    if (mysqli_query($conn, $sql)) {
        header("Location: ../../views/admin/teachers_list.php?msg=updated");
    }
}
?>