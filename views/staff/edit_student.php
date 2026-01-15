<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ទាញយក ID សិស្សពី URL
$id = mysqli_real_escape_string($conn, $_GET['id']);

// ទាញទិន្នន័យរួមគ្នារវាង users និង students
$sql = "SELECT s.*, u.full_name FROM students s 
        JOIN users u ON s.student_id = u.username 
        WHERE s.student_id = '$id'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("រកមិនឃើញទិន្នន័យសិស្សឡើយ!");
}
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">កែប្រែព័ត៌មានសិស្ស</h1>
                <p class="text-slate-500 mt-1 font-mono text-sm">អត្តលេខ: <?php echo $data['student_id']; ?></p>
            </div>
            <a href="student_list.php" class="text-slate-500 hover:text-slate-800 font-medium">
                <i class="fas fa-times mr-1"></i> បោះបង់
            </a>
        </div>

        <form action="../../actions/staff/update_student.php" method="POST" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <input type="hidden" name="old_student_id" value="<?php echo $data['student_id']; ?>">

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះពេញ</label>
                    <input type="text" name="full_name" value="<?php echo $data['full_name']; ?>" required 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ភេទ</label>
                    <select name="gender" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none">
                        <option value="ប្រុស" <?php if($data['gender'] == 'ប្រុស') echo 'selected'; ?>>ប្រុស</option>
                        <option value="ស្រី" <?php if($data['gender'] == 'ស្រី') echo 'selected'; ?>>ស្រី</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ថ្នាក់រៀន</label>
                    <input type="text" name="class_name" value="<?php echo $data['class_name']; ?>" required 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ស្ថានភាពសិក្សា</label>
                    <select name="status" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500 outline-none">
                        <option value="Active" <?php if($data['status'] == 'Active') echo 'selected'; ?>>Active</option>
                        <option value="Inactive" <?php if($data['status'] == 'Inactive') echo 'selected'; ?>>Inactive</option>
                    </select>
                </div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="submit" class="bg-slate-800 text-white px-10 py-3 rounded-xl font-bold shadow-lg hover:bg-orange-600 transition">
                    <i class="fas fa-check mr-2"></i> រក្សាទុកការផ្លាស់ប្តូរ
                </button>
            </div>
        </form>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>