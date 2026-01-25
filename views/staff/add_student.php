

<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">ចុះឈ្មោះសិស្សថ្មី</h1>
                <p class="text-slate-500 mt-1">សូមបញ្ចូលព័ត៌មានឱ្យបានត្រឹមត្រូវតាមប្រព័ន្ធគ្រប់គ្រង</p>
            </div>
            <div class="flex gap-2">
                <button type="button" onclick="document.getElementById('excel_file').click()" class="bg-emerald-600 text-white px-4 py-2 rounded-xl font-medium hover:bg-emerald-700 transition flex items-center">
                    <i class="fas fa-file-excel mr-2"></i> Import Excel
                </button>
                <a href="student_list.php" class="text-slate-500 hover:text-slate-800 font-medium self-center ml-4">
                    <i class="fas fa-arrow-left mr-1"></i> ត្រឡប់ក្រោយ
                </a>
            </div>
        </div>

        <form id="importForm" action="../../actions/staff/import_students.php" method="POST" enctype="multipart/form-data" class="hidden">
            <input type="file" id="excel_file" name="excel_data" accept=".csv" onchange="document.getElementById('importForm').submit()">
        </form>

        <form action="../../actions/staff/save_student.php" method="POST" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="md:col-span-2 border-b border-slate-100 pb-2 mb-2">
                    <h2 class=" text-blue-700 uppercase text-x tracking-widest flex items-center gap-2">
                        <i class="fas fa-key"></i> ព័ត៌មានគណនី
                    </h2>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">អត្តលេខសិស្ស​ ID</label>
                    <input type="text" name="student_id" required placeholder="ID: S2026001" 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">លេខសម្ងាត់ Password</label>
                    <input type="password" name="password" required placeholder="Password" 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="md:col-span-2 border-b border-slate-100 pb-2 mt-4 mb-2">
                    <h2 class=" text-blue-700 uppercase text-x tracking-widest flex items-center gap-2">
                        <i class="fas fa-user"></i> ព័ត៌មានផ្ទាល់ខ្លួន
                    </h2>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះពេញ</label>
                    <input type="text" name="full_name" required placeholder="ឈ្មោះជាភាសាខ្មែរ" 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះពេញ (ឡាតាំង)</label>
                    <input type="text" name="full_name_en" required placeholder="NAME IN LATIN" 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none uppercase">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ភេទ</label>
                    <select name="gender" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="ប្រុស">ប្រុស</option>
                        <option value="ស្រី">ស្រី</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ថ្ងៃខែឆ្នាំកំណើត</label>
                    <input type="date" name="dob" required 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">ទីកន្លែងកំណើត</label>
                    <input type="text" name="pob" placeholder="ភូមិ/ឃុំ/ស្រុក/ខេត្ត..." 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="md:col-span-2 border-b border-slate-100 pb-2 mt-4 mb-2">
                    <h2 class=" text-blue-700 uppercase text-x tracking-widest flex items-center gap-2">
                        <i class="fas fa-users"></i> ព័ត៌មានការសិក្សា និងគ្រួសារ
                    </h2>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ថ្នាក់រៀន</label>
                    <input type="text" name="class_name" required placeholder="ឧទាហរណ៍: 12-A" 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ស្ថានភាព</label>
                    <select name="status" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="Active">កំពុងសិក្សា (Active)</option>
                        <option value="Inactive">ឈប់សិក្សា (Inactive)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះឪពុក</label>
                    <input type="text" name="father_name" placeholder="ឈ្មោះឪពុក" 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះម្តាយ</label>
                    <input type="text" name="mother_name" placeholder="ឈ្មោះម្តាយ" 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">អាសយដ្ឋានបច្ចុប្បន្ន</label>
                    <textarea name="address" rows="2" placeholder="ផ្ទះលេខ... ផ្លូវលេខ..." 
                              class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
                </div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end gap-3">
                <button type="reset" class="px-6 py-3 rounded-xl font-bold text-slate-400 hover:text-slate-600 transition">
                    សម្អាត
                </button>
                <button type="submit" class="bg-blue-600 text-white px-10 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                    <i class="fas fa-save mr-2"></i> រក្សាទុកទិន្នន័យ
                </button>
            </div>
        </form>
    </div>
</main>



<?php include '../../includes/footer.php'; ?>