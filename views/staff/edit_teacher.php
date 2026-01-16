<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$current_page = 'teachers_list.php';
include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

// ចាប់យក ID របស់គ្រូដែលបានផ្ញើមកតាម Link
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// ទាញយកទិន្នន័យចាស់ពី Database
$query = "SELECT t.*, u.full_name FROM teachers t JOIN users u ON t.user_id = u.id WHERE t.teacher_id = '$id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// បើគ្មានទិន្នន័យទេ ឱ្យត្រឡប់ទៅបញ្ជីវិញ
if (!$row) {
    header("Location: teachers_list.php");
    exit();
}
?>

<main class="flex-1 p-8 bg-gray-50 font-['Kantumruy_Pro']">
    <div class="max-w-xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <h2 class="text-2xl font-bold text-slate-800 mb-2">កែប្រែព័ត៌មានគ្រូបង្រៀន</h2>
        <p class="text-slate-500 mb-6 italic">កែប្រែព័ត៌មានរបស់លោកគ្រូ/អ្នកគ្រូ៖ <span class="text-blue-600 font-bold"><?php echo $row['full_name']; ?></span></p>
        
        <form action="../../actions/teachers/update.php" method="POST" class="space-y-4">
            <input type="hidden" name="teacher_id" value="<?php echo $row['teacher_id']; ?>">
            
            <div>
                <label class="block text-sm font-medium mb-1">ឈ្មោះ (មិនអាចកែបាន)</label>
                <input type="text" value="<?php echo $row['full_name']; ?>" disabled 
                       class="w-full px-4 py-2 border rounded-xl bg-slate-50 text-slate-400 cursor-not-allowed">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700">ជំនាញ (Major)</label>
                <input type="text" name="major" value="<?php echo $row['major']; ?>" required 
                       class="w-full px-4 py-2 border rounded-xl outline-none focus:ring-2 focus:ring-amber-500 shadow-sm">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700">លេខទូរស័ព្ទ (Phone)</label>
                <input type="text" name="phone" value="<?php echo $row['phone']; ?>" required 
                       class="w-full px-4 py-2 border rounded-xl outline-none focus:ring-2 focus:ring-amber-500 shadow-sm">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-amber-500 text-white py-3 rounded-xl font-bold hover:bg-amber-600 transition shadow-lg shadow-amber-100">
                    <i class="fas fa-sync-alt mr-2"></i> ធ្វើបច្ចុប្បន្នភាព
                </button>
                <a href="teachers_list.php" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 transition">
                    បោះបង់
                </a>
            </div>
        </form>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>