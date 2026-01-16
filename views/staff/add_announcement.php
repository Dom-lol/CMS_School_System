<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php';
?>

<main class="flex-1 p-8 bg-gray-50">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">បង្កើតសេចក្ដីជូនដំណឹងថ្មី</h2>
        
        <form action="../../actions/announcements/create.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ចំណងជើង</label>
                <input type="text" name="title" required class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">ខ្លឹមសារព័ត៌មាន</label>
                <textarea name="content" rows="6" required class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"></textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-700 transition">បង្ហោះព័ត៌មាន</button>
                <a href="announcements.php" class="bg-slate-100 text-slate-600 px-6 py-2 rounded-xl font-bold hover:bg-slate-200 transition">បោះបង់</a>
            </div>
        </form>
    </div>
</main>