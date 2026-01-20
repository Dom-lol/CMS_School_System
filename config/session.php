<?php
// ១. កំណត់រយៈពេល Session ឱ្យនៅបានយូរ (ឧទាហរណ៍៖ ១០ ម៉ោង = ៣៦០០០ វិនាទី)
$session_lifetime = 36000; 
ini_set('session.gc_maxlifetime', $session_lifetime);
session_set_cookie_params($session_lifetime, '/');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ២. មុខងារឆែកថា តើបាន Login ហើយឬនៅ?
function is_logged_in() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../../index.php"); 
        exit();
    }
}

// ៣. មុខងារការពារទំព័រ Admin (ឱ្យតែ Admin ទើបចូលបាន)
function admin_only() {
    is_logged_in(); // ឆែក login សិន
    if ($_SESSION['role'] !== 'admin') {
        if ($_SESSION['role'] == 'staff') {
            header("Location: ../../views/staff/dashboard.php?error=no_permission");
        } else {
            header("Location: ../../views/student/dashboard.php?error=no_permission");
        }
        exit();
    }
}

// ៤. មុខងារការពារទំព័រ Staff (ឱ្យតែ Admin ឬ Staff ទើបចូលបាន)
function staff_or_admin() {
    is_logged_in(); // ឆែក login សិន
    if ($_SESSION['role'] !== 'staff' && $_SESSION['role'] !== 'admin') {
        header("Location: ../../index.php?error=no_permission");
        exit();
    }
}
?>