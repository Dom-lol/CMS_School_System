<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

// ទាញទិន្នន័យសម្រាប់ Dropdown
$subjects = mysqli_query($conn, "SELECT * FROM subjects");
$classes  = mysqli_query($conn, "SELECT * FROM classes");
// JOIN users និង teachers ដើម្បីយក ID, Teacher_ID (អត្តលេខ) និង ឈ្មោះ
$teachers = mysqli_query($conn, "SELECT t.id, t.teacher_id, u.full_name 
                                 FROM teachers t 
                                 JOIN users u ON t.user_id = u.id");
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen font-['Kantumruy_Pro']">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white p-10 rounded-3xl shadow-sm border border-slate-100">
            <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-3">
                <i class="fas fa-calendar-plus text-blue-600"></i> បន្ថែមម៉ោងបង្រៀនថ្មី
            </h2>

            <form action="../../actions/timetable/create.php" method="POST" class="space-y-5">
                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ថ្ងៃបង្រៀន</label>
                        <select name="day_of_week" required class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="ច័ន្ទ">ច័ន្ទ</option>
                            <option value="អង្គារ">អង្គារ</option>
                            <option value="ពុធ">ពុធ</option>
                            <option value="ព្រហស្បតិ៍">ព្រហស្បតិ៍</option>
                            <option value="សុក្រ">សុក្រ</option>
                            <option value="សៅរ៍">សៅរ៍</option>
                            <option value="អាទិត្យ">អាទិត្យ</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ថ្នាក់រៀន</label>
                        <select name="class_id" required class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                            <?php while($c = mysqli_fetch_assoc($classes)): ?>
                                <option value="<?= $c['id'] ?>"><?= $c['class_name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2 italic">វាយបញ្ចូលអត្តលេខគ្រូ (Teacher ID)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-id-card"></i>
                        </span>
                        <input type="text" name="teacher_custom_id" required 
                            placeholder="ឧទាហរណ៍៖ T001" 
                            class="w-full pl-10 p-3 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 font-bold text-blue-600 uppercase">
                    </div>
                    
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ម៉ោងចាប់ផ្ដើម</label>
                        <input type="time" name="start_time" required class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">ម៉ោងបញ្ចប់</label>
                        <input type="time" name="end_time" required class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">មុខវិជ្ជា</label>
                        <select name="subject_id" required class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                            <?php while($s = mysqli_fetch_assoc($subjects)): ?>
                                <option value="<?= $s['id'] ?>"><?= $s['subject_name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">លេខបន្ទប់</label>
                        <input type="text" name="room_number" placeholder="A101" class="w-full p-3 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 shadow-lg transition duration-200">
                    <i class="fas fa-save mr-2"></i> រក្សាទុកកាលវិភាគ
                </button>
            </form>
        </div>
    </div>
</main>