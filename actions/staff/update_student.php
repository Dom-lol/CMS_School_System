<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ចាប់យកទិន្នន័យពី Form
    $old_id       = mysqli_real_escape_string($conn, $_POST['old_student_id']);
    $student_id   = mysqli_real_escape_string($conn, $_POST['student_id']); // អត្តលេខថ្មី (ករណីចង់ប្តូរលេខ ID)
    $full_name    = mysqli_real_escape_string($conn, $_POST['full_name']);
    $full_name_en = mysqli_real_escape_string($conn, $_POST['full_name_en'] ?? '');
    $gender       = mysqli_real_escape_string($conn, $_POST['gender']);
    $dob          = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');
    $pob          = mysqli_real_escape_string($conn, $_POST['pob'] ?? '');
    $address      = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $class_name   = mysqli_real_escape_string($conn, $_POST['class_name']);
    $academic_year = mysqli_real_escape_string($conn, $_POST['academic_year'] ?? '');
    
    // ព័ត៌មានបន្ថែមសម្រាប់ Full Form
    $father_name  = mysqli_real_escape_string($conn, $_POST['father_name'] ?? '');
    $father_job   = mysqli_real_escape_string($conn, $_POST['father_job'] ?? '');
    $mother_name  = mysqli_real_escape_string($conn, $_POST['mother_name'] ?? '');
    $mother_job   = mysqli_real_escape_string($conn, $_POST['mother_job'] ?? '');
    $phone        = mysqli_real_escape_string($conn, $_POST['phone'] ?? '');
    $parent_phone = mysqli_real_escape_string($conn, $_POST['parent_phone'] ?? '');
   
    $status       = mysqli_real_escape_string($conn, $_POST['status']);

    $img_update = "";

    // ត្រួតពិនិត្យ និង Upload រូបភាព
    if (isset($_FILES['profile_img']) && $_FILES['profile_img']['error'] == 0) {
        $file = $_FILES['profile_img'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $new_name = "profile_" . $student_id . "_" . time() . "." . $ext;
        $upload_dir = "../../assets/uploads/profiles/";

        if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

        if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_name)) {
            $img_update = ", profile_img = '$new_name'";
        }
    }

    // ១. Update ក្នុង Table students
    $sql_students = "UPDATE students SET 
                    student_id   = '$student_id',
                    full_name    = '$full_name',
                    full_name_en = '$full_name_en',
                    gender       = '$gender', 
                    dob          = '$dob',
                    pob          = '$pob',
                    address      = '$address',
                    class_name   = '$class_name', 
                    academic_year = '$academic_year',
                    father_name  = '$father_name',
                    father_job   = '$father_job',
                    mother_name  = '$mother_name',
                    mother_job   = '$mother_job',
                    phone        = '$phone',
                    parent_phone = '$parent_phone',
               
                    status       = '$status' 
                    $img_update 
                    WHERE student_id = '$old_id'";

    // ២. Update ក្នុង Table users (Update ទាំងឈ្មោះ និង Username ករណី ID ត្រូវបានប្តូរ)
    $sql_users = "UPDATE users SET 
                  full_name = '$full_name', 
                  username = '$student_id' 
                  WHERE username = '$old_id'";

    if (mysqli_query($conn, $sql_students)) {
        // បើ Update student ជោគជ័យ ទើប update user តាមក្រោយ
        mysqli_query($conn, $sql_users);
        
        header("Location: ../../views/staff/student_list.php?msg=updated");
        exit();
    } else {
        die("Database Error: " . mysqli_error($conn));
    }
}