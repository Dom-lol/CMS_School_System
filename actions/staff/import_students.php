<?php
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['excel_data'])) {
    $file = $_FILES['excel_data']['tmp_name'];
    
    if (($handle = fopen($file, "r")) !== FALSE) {
        $conn->begin_transaction();

        try {
            fgetcsv($handle); // រំលង Header ជួរទី១ ក្នុង Excel
            $success_count = 0;
            
            // ១. រៀបចំ Query សម្រាប់បញ្ចូលទៅក្នុងតារាង users
            $sql_user = "INSERT INTO users (username, password, full_name, role) VALUES (?, ?, ?, 'student')";
            $stmt_user = $conn->prepare($sql_user);

            // ២. រៀបចំ Query សម្រាប់បញ្ចូលទៅក្នុងតារាង students (តាមរូបភាព Database របស់លោកគ្រូ)
            $sql_student = "INSERT INTO students (
                user_id, student_id, full_name, full_name_en, gender, dob, pob, 
                address, father_name, mother_name, father_job, mother_job, 
                father_phone, mother_phone, stream, class_name, class_id, status, photo
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Active', 'default.png')";
            $stmt_student = $conn->prepare($sql_student);

            // តារាងបំប្លែង class_name ទៅជា class_id
            $gradeMap = ["7" => 1, "8" => 2, "9" => 3, "10" => 4, "11" => 5, "12" => 6];

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // រំលងបើគ្មាន Student ID (ជួរទី ១២) ឬ ឈ្មោះ (ជួរទី ០)
                if (empty($data[0]) || empty($data[12])) continue;

                $s_id      = $data[12]; // student_id
                $s_name_kh = $data[0];  // full_name
                $c_name    = $data[14]; // class_name (ពី Excel ជួរទី ១៤)
                
                // កំណត់ class_id ស្វ័យប្រវត្តិ (បើក្នុង Excel ដាក់ "7" វានឹងឱ្យ id=1)
                $c_id = isset($gradeMap[$c_name]) ? $gradeMap[$c_name] : NULL;

                // --- ក. បង្កើត User Account (ID ជា Password) ---
                $stmt_user->bind_param("sss", $s_id, $s_id, $s_name_kh);
                $stmt_user->execute();
                $new_user_id = $conn->insert_id;

                // --- ខ. បញ្ចូលព័ត៌មានសិស្ស ---
                // លំដាប់៖ isssssssssssssssi (i=int, s=string)
                $stmt_student->bind_param("isssssssssssssssi", 
                    $new_user_id, // user_id
                    $s_id,        // student_id
                    $data[0],     // full_name (KH)
                    $data[1],     // full_name_en
                    $data[2],     // gender
                    $data[3],     // dob (6/12/2014)
                    $data[4],     // pob
                    $data[5],     // address
                    $data[6],     // father_name
                    $data[7],     // mother_name
                    $data[8],     // father_job
                    $data[9],     // mother_job
                    $data[10],    // father_phone
                    $data[11],    // mother_phone
                    $data[13],    // stream
                    $c_name,      // class_name (ឧ. 7)
                    $c_id         // class_id (ឧ. 1)
                );

                if ($stmt_student->execute()) {
                    $success_count++;
                }
            }

            $conn->commit();
            fclose($handle);
            // ត្រឡប់ទៅកាន់បញ្ជីសិស្សវិញជាមួយសារជោគជ័យ
            header("Location: ../../views/staff/student_list.php?import_success=$success_count");
            exit();

        } catch (Exception $e) {
            $conn->rollback();
            if (isset($handle)) fclose($handle);
            die("បញ្ហាទិន្នន័យ៖ " . $e->getMessage());
        }
    }
}