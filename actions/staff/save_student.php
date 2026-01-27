<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['full_name'])) {
    
    // ចងក្រងអាសយដ្ឋាន
    $pob = implode(', ', array_filter([$_POST['pob_v'], $_POST['pob_c'], $_POST['pob_d'], $_POST['pob_p']]));
    $address = implode(', ', array_filter([$_POST['addr_v'], $_POST['addr_c'], $_POST['addr_d'], $_POST['addr_p']]));

    // --- ផ្នែកទី ១៖ កែបញ្ហា Upload (បង្កើត Folder បើមិនទាន់មាន) ---
    $photo_name = "default.png";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $upload_dir = "../../uploads/students/";
        
        // បង្កើត Folder ស្វ័យប្រវត្តិ និងផ្តល់សិទ្ធិ (Fix Warning)
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo_name = "STU_" . time() . "_" . uniqid() . "." . $ext;
        move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $photo_name);
    }

    $conn->begin_transaction();
    try {
        // ១. បង្កើតគណនី
        $sql_u = "INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, 'student')";
        $stmt_u = $conn->prepare($sql_u);
        $stmt_u->bind_param("sss", $_POST['student_id'], $_POST['student_id'], $_POST['full_name']);
        $stmt_u->execute();
        $u_id = $conn->insert_id;

        // ២. បញ្ចូលព័ត៌មានសិស្ស (Fix Fatal Error)
        // លោកគ្រូត្រូវប្រាកដថា Column ក្នុង DB មានគ្រប់ ១៧ នេះ
        $sql_s = "INSERT INTO students (
            user_id, student_id, full_name, full_name_en, gender, dob, pob, 
            address, father_name, father_job, mother_name, mother_job, 
            class_name, class_id, academic_year, photo, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')";

        $stmt_s = $conn->prepare($sql_s);

        // បើ prepare បរាជ័យ បង្ហាញ Error ថាខ្វះ Column អី
        if (!$stmt_s) {
            throw new Exception("SQL Error: " . $conn->error);
        }

        // types: i + s x 15 = "isssssssssssssss" (សរុប ១៦ តួ)
        $stmt_s->bind_param("isssssssssssssss", 
            $u_id, 
            $_POST['student_id'], 
            $_POST['full_name'], 
            $_POST['full_name_en'], 
            $_POST['gender'], 
            $_POST['dob'], 
            $pob, 
            $address, 
            $_POST['father_name'], 
            $_POST['father_job'], 
            $_POST['mother_name'], 
            $_POST['mother_job'], 
            $_POST['class_name'], 
            $_POST['class_id'], 
            $_POST['academic_year'], 
            $photo_name
        );

        $stmt_s->execute();
        $conn->commit();
        header("Location: ../../views/staff/student_list.php?save_success=1");

    } catch (Exception $e) {
        $conn->rollback();
        die("<div style='color:red; font-family:sans-serif; padding:20px; background:#fff5f5;'>
                <h2>កំហុសប្រព័ន្ធ!</h2>
                <p><b>មូលហេតុ៖</b> " . $e->getMessage() . "</p>
                <p>សូមពិនិត្យមើល Table students ក្នុង Database ថាមាន Column គ្រប់ឬនៅ?</p>
                <a href='../../views/staff/register_student.php'>ត្រឡប់ក្រោយ</a>
            </div>");
    }
}