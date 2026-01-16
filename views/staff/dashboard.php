<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

// ឆែកថាជា Staff ពិតមែនឬអត់
if ($_SESSION['role'] !== 'staff') {
    header("Location: ../../index.php?error=unauthorized");
    exit();
}

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ទាញទិន្នន័យសង្ខេបសម្រាប់ Staff
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM students"))['total'];
$total_absent_today = 5; // ឧទាហរណ៍៖ ទាញពី table attendance
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 tracking-tight">រដ្ឋបាលសាលា</h1>
        <p class="text-slate-500 mt-1 italic italic">សូមស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រងរដ្ឋបាលសាលារៀន</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <a href="student_list.php">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">សិស្សសរុប</p>
            <h3 class="text-3xl font-black text-slate-800 mt-2"><?php echo $total_students; ?></h3>
        </div>
        </a>
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200 border-l-4 border-l-blue-500">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">អវត្តមានថ្ងៃនេះ</p>
            <h3 class="text-3xl font-black text-blue-600 mt-2"><?php echo $total_absent_today; ?> នាក់</h3>
        </div>
        <a href="add_student.php">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">បន្ថែមគ្រូ</p>
                <h3 class="text-3xl font-black text-slate-800 mt-2"><?php echo $total_students; ?></h3>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
            <h2 class="font-bold text-slate-800 text-lg mb-4 flex items-center">
                <i class="fas fa-tasks mr-2 text-orange-500"></i> ការងារដែលត្រូវធ្វើ
            </h2>
            <ul class="space-y-3">
                <li class="flex items-center p-3 bg-slate-50 rounded-xl text-sm text-slate-600">
                    <i class="far fa-circle mr-3"></i> ពិនិត្យវត្តមានសិស្សថ្នាក់ទី ១២
                </li>
                <li class="flex items-center p-3 bg-slate-50 rounded-xl text-sm text-slate-600">
                    <i class="far fa-circle mr-3"></i> បោះពុម្ពកាតសិស្សថ្មី
                </li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
            <h2 class="font-bold text-slate-800 text-lg mb-4 flex items-center">
                <i class="fas fa-bell mr-2 text-blue-500"></i> សេចក្ដីជូនដំណឹងផ្ទៃក្នុង
            </h2>
            <div class="p-4 bg-blue-50 text-blue-700 rounded-2xl text-sm">
                <p><strong>ប្រជុំបុគ្គលិក៖</strong> ថ្ងៃសុក្រ ម៉ោង ៤ រសៀល នៅបន្ទប់ប្រជុំធំ។</p>
            </div>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>