<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();
include '../../includes/header.php';
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden font-['Kantumruy_Pro']">
    <?php include '../../includes/sidebar_staff.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0">
            <h2 class="text-xl font-bold text-slate-800 italic uppercase">បន្ថែមគ្រូបង្រៀនថ្មី</h2>
        </header>

        <main class="flex-1 overflow-y-auto p-10">
            <div class="max-w-4xl mx-auto">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                    
                    <form action="../../actions/teachers/create.php" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <div class="flex flex-col items-center mb-8">
                            <div id="preview" class="w-32 h-32 bg-slate-100 rounded-[2rem] border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden mb-4">
                                <i class="fas fa-user-tie text-4xl text-slate-300"></i>
                            </div>
                            <label class="cursor-pointer bg-blue-50 text-blue-600 px-6 py-2 rounded-xl font-black text-xs">
                                <i class="fas fa-camera mr-2"></i> ជ្រើសរើសរូបថត
                                <input type="file" name="profile_image" class="hidden" accept="image/*" onchange="showPreview(this)">
                            </label>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <input type="text" name="teacher_id" placeholder="អត្តលេខគ្រូ" required class="w-full p-5 bg-slate-50 border-none rounded-2xl font-bold">
                            <input type="text" name="full_name" placeholder="ឈ្មោះពេញ" required class="w-full p-5 bg-slate-50 border-none rounded-2xl font-bold">
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <input type="text" name="subjects" placeholder="ឯកទេស" required class="w-full p-5 bg-slate-50 border-none rounded-2xl font-bold">
                            <input type="text" name="phone" placeholder="លេខទូរស័ព្ទ" class="w-full p-5 bg-slate-50 border-none rounded-2xl font-bold">
                        </div>

                        <button type="submit" class="w-full py-6 bg-slate-900 text-white rounded-[1.8rem] font-black uppercase tracking-widest hover:bg-blue-600 transition-all shadow-xl">
                            <i class="fas fa-save mr-2"></i> រក្សាទុក
                        </button>
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
            document.getElementById('preview').innerHTML = '<img src="'+e.target.result+'" class="w-full h-full object-cover">';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>