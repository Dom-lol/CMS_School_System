<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$current_page = 'add_teacher.php';
include '../../includes/header.php';
include '../../includes/sidebar_staff.php';
?>
<?php if (isset($_GET['error']) && $_GET['error'] == 'duplicate'): ?>
    <div class="bg-red-100 text-red-700 p-4 rounded-2xl mb-4 text-sm font-medium flex items-center gap-2">
        <i class="fas fa-exclamation-triangle"></i>
        សូមទោស! Email ឬ Username នេះមានអ្នកប្រើប្រាស់រួចហើយ។
    </div>
<?php endif; ?>
<main class="flex-1 p-8 bg-gray-50 min-h-screen font-['Kantumruy_Pro']">
    <div class="max-w-xl mx-auto mb-4">
        <a href="teachers_list.php" class="text-slate-500 hover:text-blue-600 transition flex items-center gap-2 text-sm font-medium">
            <i class="fas fa-arrow-left"></i> ត្រឡប់ទៅបញ្ជីគ្រូ
        </a>
    </div>

    <div class="max-w-xl mx-auto bg-white p-10 rounded-3xl shadow-sm border border-slate-100">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-4">
                <i class="fas fa-user-plus"></i>
            </div>
            <h2 class="text-2xl font-bold text-slate-800">បន្ថែមព័ត៌មានគ្រូបង្រៀន</h2>
            <p class="text-slate-500 text-sm mt-1">បំពេញព័ត៌មានខាងក្រោមដើម្បីចុះឈ្មោះគ្រូថ្មី</p>
        </div>
        
            <form action="../../actions/teachers/create.php" method="POST" class="space-y-4">
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium mb-1 italic">ឈ្មោះពេញ (Full Name)</label>
            <input type="text" name="full_name" required placeholder="ឈ្មោះគ្រូ" class="w-full px-4 py-2 border rounded-xl shadow-sm outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium mb-1 italic">Email</label>
            <input type="email" name="email" required placeholder="teacher@gmail.com" class="w-full px-4 py-2 border rounded-xl shadow-sm outline-none focus:ring-2 focus:ring-blue-500">
        </div>
    </div>

    <div>
        <label class="block text-sm font-medium mb-1 italic">Password</label>
        <input type="password" name="password" required placeholder="កំណត់លេខសម្ងាត់" class="w-full px-4 py-2 border rounded-xl shadow-sm outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <hr class="my-4 border-slate-100">

    <div>
        <label class="block text-sm font-medium mb-1 italic">មុខវិជ្ជាបង្រៀន (Subjects)</label>
        <input type="text" name="subjects" required placeholder="ឧទាហរណ៍៖ គណិតវិទ្យា, រូបវិទ្យា" class="w-full px-4 py-2 border rounded-xl shadow-sm outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1 italic">លេខទូរស័ព្ទ (Phone)</label>
        <input type="text" name="phone" required placeholder="012 345 678" class="w-full px-4 py-2 border rounded-xl shadow-sm outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
        <i class="fas fa-save mr-2"></i> បង្កើតគណនីគ្រូថ្មី
    </button>
</form> 
    </div>
</main>

<?php include '../../includes/footer.php'; ?>