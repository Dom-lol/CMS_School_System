<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ទាញយក ID សិស្សពី URL
$id = mysqli_real_escape_string($conn, $_GET['id']);

// ទាញទិន្នន័យរួមគ្នារវាង users និង students (ថែម profile_img)
$sql = "SELECT s.*, u.full_name FROM students s 
        JOIN users u ON s.student_id = u.username 
        WHERE s.student_id = '$id'";
$result = mysqli_query($conn, $sql);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("រកមិនឃើញទិន្នន័យសិស្សឡើយ!");
}

// កំណត់ Path រូបភាព
$img_path = "../../assets/uploads/profiles/" . $data['profile_img'];
$display_img = (!empty($data['profile_img']) && file_exists($img_path)) ? $img_path : null;
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">កែប្រែព័ត៌មានសិស្ស</h1>
                <p class="text-slate-500 mt-1 font-mono text-sm">អត្តលេខ: <?php echo $data['student_id']; ?></p>
            </div>
            <a href="student_list.php" class="text-slate-500 hover:text-slate-800 font-medium transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> ត្រឡប់ក្រោយ
            </a>
        </div>

        <form action="../../actions/staff/update_student.php" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <input type="hidden" name="old_student_id" value="<?php echo $data['student_id']; ?>">

            <div class="p-8 border-b border-slate-100 flex flex-col items-center bg-slate-50/50">
                <div class="relative group">
                    <div class="w-32 h-32 rounded-3xl border-4 border-white shadow-xl overflow-hidden bg-orange-100 flex items-center justify-center">
                        <?php if($display_img): ?>
                            <img src="<?php echo $display_img; ?>?v=<?php echo time(); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i class="fas fa-user-graduate text-5xl text-orange-400"></i>
                        <?php endif; ?>
                    </div>
                    <label class="absolute -bottom-2 -right-2 w-10 h-10 bg-orange-500 text-white rounded-xl flex items-center justify-center cursor-pointer shadow-lg hover:bg-slate-800 transition-all border-2 border-white">
                        <i class="fas fa-camera text-sm"></i>
                        <input type="file" name="profile_img" class="hidden" accept="image/*">
                    </label>
                </div>
                <p class="mt-4 text-xs text-slate-400 italic">ចុចលើរូបកាមេរ៉ាដើម្បីផ្លាស់ប្តូររូបថត</p>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះពេញ</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($data['full_name']); ?>" required 
                           class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ភេទ</label>
                    <select name="gender" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none cursor-pointer">
                        <option value="ប្រុស" <?php if($data['gender'] == 'ប្រុស') echo 'selected'; ?>>ប្រុស</option>
                        <option value="ស្រី" <?php if($data['gender'] == 'ស្រី') echo 'selected'; ?>>ស្រី</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ថ្នាក់រៀន</label>
                    <input type="text" name="class_name" value="<?php echo htmlspecialchars($data['class_name']); ?>" required 
                           class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ស្ថានភាពសិក្សា</label>
                    <select name="status" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-orange-500 outline-none cursor-pointer">
                        <option value="Active" class="text-green-600 font-bold" <?php if($data['status'] == 'Active') echo 'selected'; ?>>កំពុងរៀន (Active)</option>
                        <option value="Inactive" class="text-red-600 font-bold" <?php if($data['status'] == 'Inactive') echo 'selected'; ?>>ឈប់សិក្សា (Inactive)</option>
                    </select>
                </div>
                <div>
    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះជាឡាតាំង</label>
    <input type="text" name="full_name_en" value="<?php echo htmlspecialchars($data['full_name_en']); ?>" 
           class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
</div>

<div>
    <label class="block text-sm font-bold text-slate-700 mb-2">ថ្ងៃខែឆ្នាំកំណើត</label>
    <input type="date" name="dob" value="<?php echo $data['dob']; ?>" 
           class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
</div>

<div class="md:col-span-2">
    <label class="block text-sm font-bold text-slate-700 mb-2">ទីកន្លែងកំណើត</label>
    <textarea name="pob" class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"><?php echo htmlspecialchars($data['pob']); ?></textarea>
</div>

<div>
    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះឪពុក</label>
    <input type="text" name="father_name" value="<?php echo htmlspecialchars($data['father_name']); ?>" 
           class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
</div>

<div>
    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះម្ដាយ</label>
    <input type="text" name="mother_name" value="<?php echo htmlspecialchars($data['mother_name']); ?>" 
           class="w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
</div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="submit" class="bg-slate-800 text-white px-10 py-4 rounded-2xl font-bold shadow-xl hover:bg-orange-600 hover:-translate-y-1 active:translate-y-0 transition-all duration-300">
                    <i class="fas fa-save mr-2"></i> រក្សាទុកការផ្លាស់ប្តូរ
                </button>
            </div>
        </form>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>