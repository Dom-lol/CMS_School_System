<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();
include '../../includes/header.php';

// ១. ទាញយកមុខវិជ្ជាពី Database (យោងតាមរូបភាពទី ៥ របស់លោកឪ)
$subjects_query = "SELECT subject_name FROM subjects ORDER BY subject_name ASC";
$subjects_res = mysqli_query($conn, $subjects_query);
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden font-['Kantumruy_Pro']">
    <?php include '../../includes/sidebar_staff.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0">
            <a href="teachers_list.php" class="text-slate-500 hover:text-blue-600 font-bold transition flex items-center gap-2">
                <i class="fas fa-chevron-left"></i> ត្រឡប់ក្រោយ
            </a>
            <h1 class="text-xl font-black text-slate-800 uppercase">បន្ថែមគ្រូបង្រៀនថ្មី</h1>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                    
                    <form action="../../actions/teachers/create.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        
                        <div class="flex flex-col items-center mb-8">
                            <div id="preview" class="w-32 h-32 bg-slate-50 rounded-[50%] border-2 border-dashed border-slate-200 flex items-center justify-center overflow-hidden mb-4 transition-all">
                                <i class="fas fa-user-tie text-4xl text-slate-300"></i>
                            </div>
                            <label class="cursor-pointer bg-slate-900 text-white px-6 py-2 rounded-xl font-black text-[10px] uppercase tracking-wider hover:bg-blue-600 transition shadow-lg">
                                <i class="fas fa-camera mr-2"></i> ជ្រើសរើសរូបថត
                                <input type="file" name="profile_image" class="hidden" accept="image/*" onchange="showPreview(this)">
                            </label>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase ml-2">អត្តលេខគ្រូ (Username)</label>
                                <input type="text" name="teacher_id" placeholder="T2026001" required 
                                       class="w-full p-5 bg-slate-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl font-bold outline-none transition-all">
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase ml-2">ឈ្មោះពេញ</label>
                                <input type="text" name="full_name" placeholder="ឈ្មោះជាភាសាខ្មែរ" required 
                                       class="w-full p-5 bg-slate-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl font-bold outline-none transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase ml-2">ឯកទេស / មុខវិជ្ជា</label>
                                <div class="relative">
                                    <select name="subjects" required 
                                            class="w-full p-5 bg-slate-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl font-bold outline-none transition-all appearance-none cursor-pointer">
                                        <option value="" disabled selected>--- ជ្រើសរើសមុខវិជ្ជា ---</option>
                                        <?php 
                                        if ($subjects_res && mysqli_num_rows($subjects_res) > 0) {
                                            while($subj = mysqli_fetch_assoc($subjects_res)) {
                                                echo '<option value="'.$subj['subject_name'].'">'.$subj['subject_name'].'</option>';
                                            }
                                        }
                                        ?>
                                    </select>
                                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                        <i class="fas fa-chevron-down"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-xs font-black text-slate-400 uppercase ml-2">លេខទូរស័ព្ទ</label>
                                <input type="text" name="phone" placeholder="012 345 678" 
                                       class="w-full p-5 bg-slate-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl font-bold outline-none transition-all">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full py-6 bg-blue-600 text-white rounded-[1.8rem] font-black uppercase tracking-widest hover:bg-slate-900 transition-all shadow-xl shadow-blue-100">
                                <i class="fas fa-save mr-2"></i> បង្កើតគណនីគ្រូ
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </main>
    </div>
</div>

<script>
function showPreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            preview.innerHTML = '<img src="'+e.target.result+'" class="w-full h-full object-cover animate-pulse">';
            preview.classList.remove('border-dashed');
            preview.classList.add('border-solid', 'border-white', 'shadow-lg');
            setTimeout(() => {
                preview.querySelector('img').classList.remove('animate-pulse');
            }, 500);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include '../../includes/footer.php'; ?>