<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();
include '../../includes/header.php';

// ១. ចាប់យក teacher_id ពី URL
$t_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// ២. ទាញទិន្នន័យដោយ Join ជាមួយ Table Users ដើម្បីយកឈ្មោះពេញ និង user_id
$query = "SELECT t.*, u.full_name, u.id as u_id FROM teachers t 
          JOIN users u ON t.user_id = u.id 
          WHERE t.teacher_id = '$t_id'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// បើគ្មានទិន្នន័យគ្រូទេ ឱ្យត្រឡប់ទៅបញ្ជីវិញ
if (!$row) { 
    echo "<script>window.location='teachers_list.php';</script>"; 
    exit(); 
}
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden font-['Kantumruy_Pro']">
    <?php include '../../includes/sidebar_staff.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0">
            
            <a href="teachers_list.php" class="text-slate-500 hover:text-blue-600 font-bold transition flex items-center gap-2">
                <i class="fas fa-arrow-left"></i> ត្រឡប់ក្រោយ
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <div class="max-w-2xl mx-auto">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                    
                    <form action="../../actions/teachers/update.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        
                        <input type="hidden" name="teacher_id" value="<?= $row['teacher_id']; ?>">
                        <input type="hidden" name="user_id" value="<?= $row['u_id']; ?>">

                        <div class="flex flex-col items-center mb-8">
                            <div id="preview" class="w-32 h-32 rounded-[50%] border-4 border-white shadow-lg overflow-hidden mb-4 bg-slate-100 flex items-center justify-center">
                                <?php if(!empty($row['profile_image'])): ?>
                                    <img src="../../assets/uploads/teachers/<?= $row['profile_image'] ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <i class="fas fa-user-tie text-4xl text-slate-300"></i>
                                <?php endif; ?>
                            </div>
                            <label class="cursor-pointer bg-blue-50 text-blue-600 px-6 py-2 rounded-xl font-black text-[13px] uppercase hover:bg-blue-100 transition">
                                <i class="fas fa-camera mr-2"></i> ប្តូររូបថតថ្មី
                                <input type="file" name="profile_image" class="hidden" accept="image/*" onchange="showPreview(this)">
                            </label>
                        </div>

                        <div class="space-y-5">
                            <div>
                                <label class="block text-[13px] font-black text-slate-500 mb-2 uppercase ">ឈ្មោះពេញ </label>
                                <input type="text" name="full_name" value="<?= $row['full_name']; ?>" required 
                                       class="w-full p-4 bg-slate-50 border-none rounded-2xl font-bold focus:ring-2 focus:ring-blue-500 transition">
                            </div>

                            <div class="grid grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[13px] font-black text-slate-500 mb-2 uppercase ">ឯកទេស </label>
                                    <input type="text" name="subjects" value="<?= $row['subjects']; ?>" required 
                                           class="w-full p-4 bg-slate-50 border-none rounded-2xl font-bold focus:ring-2 focus:ring-blue-500 transition">
                                </div>
                                <div>
                                    <label class="block text-[13px] font-black text-slate-500 mb-2 uppercase ">លេខទូរស័ព្ទ</label>
                                    <input type="text" name="phone" value="<?= $row['phone']; ?>" required 
                                           class="w-full p-4 bg-slate-50 border-none rounded-2xl font-bold focus:ring-2 focus:ring-blue-500 transition">
                                </div>
                            </div>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-[1.5rem] font-black uppercase  hover:bg-blue-700 shadow-xl transition-all active:scale-95 cursor-pointer">
                                <i class="fas fa-save mr-2"></i> រក្សាទុកការផ្លាស់ប្តូរ
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </main>
    </div>
</div>

<script>
// មុខងារបង្ហាញរូបភាពភ្លាមៗនៅពេលជ្រើសរើស File
function showPreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '<img src="'+e.target.result+'" class="w-full h-full object-cover">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../../includes/footer.php'; ?>