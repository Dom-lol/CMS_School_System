<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_data'])) {
    $file = $_FILES['excel_data']['tmp_name'];
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        $conn->begin_transaction();

        try {
            fgetcsv($handle); // រំលង Header
            $success_count = 0;
            
            // Query នេះត្រូវតាម Column ក្នុង Database របស់អ្នក
            $sql = "INSERT INTO students (
                full_name, full_name_en, gender, dob, pob, 
                address, father_name, mother_name, father_job, mother_job, 
                father_phone, mother_phone, student_id, stream, class_name, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active')";

            $sql_u = "INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, 'student')";
            $stmt_u = $conn->prepare($sql_u);
            $stmt_u->bind_param("sss", $_POST['student_id'], $_POST['student_id'], $_POST['full_name']);
            $stmt_u->execute();
            $u_id = $conn->insert_id;

            $stmt = $conn->prepare($sql);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // ចាប់យកទិន្នន័យតាមលំដាប់ជួរក្នុង Excel (ចាប់រាប់ពី 0 មក)
                $stmt->bind_param("sssssssssssssss", 
                    $data[0],  // full_name_kh
                    $data[1],  // full_name_en
                    $data[2],  // gender
                    $data[3],  // date_of_birth
                    $data[4],  // place_of_birth
                    $data[5],  // address
                    $data[6],  // father_name
                    $data[7],  // mother_name
                    $data[8],  // father_job
                    $data[9],  // mother_job
                    $data[10], // father_phone
                    $data[11], // mother_phone
                    $data[12], // student_id
                    $data[13], // stream
                    $data[14]  // grade (បញ្ចូលក្នុង class_name)
                );

                if ($stmt->execute()) {
                    $success_count++;
                }
            }

            $conn->commit();
            fclose($handle);
            header("Location: ../../views/staff/student_list.php?import_success=$success_count");
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            fclose($handle);
            die("បញ្ហាទិន្នន័យ៖ " . $e->getMessage());
        }
    }
}