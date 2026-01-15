<?php
session_start();

// មុខងារឆែកថា តើបាន Login ហើយឬនៅ?
function is_logged_in() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php"); 
        exit();
    }
}

// មុខងារការពារទំព័រ Admin (ឱ្យតែ Admin ទើបចូលបាន)
function admin_only() {
    if ($_SESSION['role'] !== 'admin') {
        // បើមិនមែន admin ទេ រុញទៅទំព័រ dashboard រៀងៗខ្លួនវិញ
        if ($_SESSION['role'] == 'staff') {
            header("Location: ../../views/staff/dashboard.php?error=no_permission");
        } else {
            header("Location: ../../views/student/dashboard.php?error=no_permission");
        }
        exit();
    }
}

// មុខងារការពារទំព័រ Staff (ឱ្យតែ Admin ឬ Staff ទើបចូលបាន)
function staff_or_admin() {
    if ($_SESSION['role'] !== 'staff' && $_SESSION['role'] !== 'admin') {
        header("Location: ../../index.php?error=no_permission");
        exit();
    }
}
?>