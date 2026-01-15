<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ប្រសិនបើកូដក្នុង config/session.php មិនទាន់មាន session_start()
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ពិនិត្យសិទ្ធិចូលប្រើ
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized");
    exit();
}

include '../../includes/header.php';
include '../../includes/sidebar_student.php';

// ទាញយក Username ពី Session (ការពារ Error ដោយប្រើ ?? '')
$s_id = $_SESSION['username'] ?? '';
$display_name = $_SESSION['full_name'] ?? $s_id;

// ទាញព័ត៌មានលម្អិតពី Table students ដោយប្រើ student_id
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$s_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

// បង្កើត Variable បម្រុង បើរកក្នុង DB មិនឃើញ (Null Coalescing Operator)
$class_name = $student_info['class_name'] ?? "មិនទាន់មានថ្នាក់";
$status = $student_info['status'] ?? "មិនទាន់បញ្ជាក់";
$academic_year = $student_info['academic_year'] ?? "2023-2024";
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 font-['Kantumruy_Pro']">សួស្ដី, <?php echo $display_name; ?>!</h1>
        <p class="text-slate-500 mt-1">នេះគឺជាសេចក្ដីសង្ខេបនៃការសិក្សារបស់អ្នកក្នុងថ្នាក់ <span class="font-semibold text-blue-600"><?php echo $class_name; ?></span></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-6 rounded-2xl text-white shadow-lg">
            <p class="opacity-80 text-sm">ស្ថានភាពសិក្សា</p>
            <h3 class="text-2xl font-bold mt-1"><?php echo $status; ?></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-purple-500 border border-gray-100">
            <p class="text-slate-500 text-sm font-medium">ឆ្នាំសិក្សា</p>
            <h3 class="text-2xl font-bold text-slate-800"><?php echo $academic_year; ?></h3>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500 border border-gray-100">
            <p class="text-slate-500 text-sm font-medium">វត្តមានសរុប</p>
            <h3 class="text-2xl font-bold text-slate-800">100%</h3>
        </div>
    </div>

    <?php if (!$student_info): ?>
    <div class="bg-orange-50 border-l-4 border-orange-500 p-4 text-orange-700 mb-8 rounded-r-xl">
        <p class="font-bold">បញ្ជាក់៖</p>
        <p>រកមិនឃើញទិន្នន័យក្នុងតារាងសិស្សសម្រាប់អត្តលេខ <strong><?php echo $s_id; ?></strong> ទេ។ សូមទាក់ទងការិយាល័យសិក្សា។</p>
    </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">ព័ត៌មានផ្ទាល់ខ្លួន</h2>
            <ul class="space-y-4 text-sm">
                <li class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-slate-500">អត្តលេខ៖</span> 
                    <strong class="text-blue-600"><?php echo $s_id; ?></strong>
                </li>
                <li class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-slate-500">ភេទ៖</span> 
                    <strong><?php echo $student_info['gender'] ?? '---'; ?></strong>
                </li>
                <li class="flex justify-between border-b border-gray-50 pb-2">
                    <span class="text-slate-500">លេខទូរស័ព្ទ៖</span> 
                    <strong><?php echo $student_info['phone'] ?? '---'; ?></strong>
                </li>
            </ul>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>