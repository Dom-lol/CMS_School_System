<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ទាញយកបញ្ជីថ្នាក់រៀនសម្រាប់ជ្រើសរើស (Option)
$classes = mysqli_query($conn, "SELECT DISTINCT class_name FROM students");
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">ចុះឈ្មោះសិស្សថ្មី</h1>
                <p class="text-slate-500 mt-1">សូមបញ្ចូលព័ត៌មានឱ្យបានត្រឹមត្រូវដើម្បីបង្កើតគណនីសិស្ស</p>
            </div>
            <a href="student_list.php" class="text-slate-500 hover:text-slate-800 font-medium">
                <i class="fas fa-arrow-left mr-1"></i> ត្រឡប់ក្រោយ
            </a>
        </div>

        <form action="../../actions/staff/save_student.php" method="POST" class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="md:col-span-2 border-b border-slate-100 pb-4 mb-2">
                    <h2 class="font-bold text-blue-700 uppercase text-sm tracking-widest">ព័ត៌មានគណនី</h2>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">អត្តលេខសិស្ស</label>
                    <input type="text" name="student_id" required placeholder="Example: B20234888" 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">លេខសម្តាត់</label>
                    <input type="password" name="password" required placeholder="Password" 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div class="md:col-span-2 border-b border-slate-100 pb-4 mt-4 mb-2">
                    <h2 class="font-bold text-blue-700 uppercase text-sm tracking-widest">ព័ត៌មានផ្ទាល់ខ្លួន</h2>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះពេញ</label>
                    <input type="text" name="full_name" required placeholder="បញ្ចូលឈ្មោះសិស្ស..." 
                           class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ភេទ</label>
                    <select name="gender" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 ring-orange-500 outline-none">
                        <option value="ប្រុស">ប្រុស</option>
                        <option value="ស្រី">ស្រី</option>
                    </select>
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
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-10 py-3 rounded-xl font-bold shadow-blue-200 hover:bg-blue-700​ ​​transition">
                    <i class="fas fa-save mr-2"></i> បង្កើតសិស្សថ្មី
                </button>
            </div>
        </form>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>