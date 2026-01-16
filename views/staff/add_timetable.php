<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ឆែកសិទ្ធិបុគ្គលិក
if ($_SESSION['role'] !== 'staff' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php");
    exit();
}

include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

// ទាញទិន្នន័យពី DB សម្រាប់ Dropdown
$subjects = mysqli_query($conn, "SELECT * FROM subjects ORDER BY subject_name ASC");
$classes  = mysqli_query($conn, "SELECT * FROM classes ORDER BY class_name ASC");
?>

<main class="flex-1 p-8 bg-gray-50 font-['Kantumruy_Pro']">
    <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-slate-800 p-6 text-white text-center">
            <h2 class="text-xl font-bold">បន្ថែមកាលវិភាគសិក្សា</h2>
        </div>

        <form action="../../actions/timetable/save_timetable.php" method="POST" class="p-8 space-y-5">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">ថ្ងៃសិក្សា</label>
                    <select name="day_of_week" required class="w-full p-3 border rounded-xl">
                        <option value="ច័ន្ទ">ច័ន្ទ</option>
                        <option value="អង្គារ">អង្គារ</option>
                        <option value="ពុធ">ពុធ</option>
                        <option value="ព្រហស្បតិ៍">ព្រហស្បតិ៍</option>
                        <option value="សុក្រ">សុក្រ</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">ថ្នាក់រៀន</label>
                    <select name="class_id" required class="w-full p-3 border rounded-xl">
                        <?php while($row = mysqli_fetch_assoc($classes)): ?>
                            <option value="<?= $row['id'] ?>"><?= $row['class_name'] ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">ជ្រើសរើសម៉ោងសិក្សា (៧-១២)</label>
                <select name="study_time" required class="w-full p-3 bg-gray-50 border border-gray-300 rounded-xl">
                    <optgroup label="វេនព្រឹក">
                        <option value="07:00-07:50">07:00 - 07:50</option>
                        <option value="08:00-08:50">08:00 - 08:50</option>
                        <option value="09:00-09:50">09:00 - 09:50</option>
                        <option value="10:00-10:50">10:00 - 10:50</option>
                    </optgroup>
                    <optgroup label="វេនរសៀល">
                        <option value="13:00-13:50">01:00 - 01:50</option>
                        <option value="14:00-14:50">02:00 - 02:50</option>
                        <option value="15:00-15:50">03:00 - 03:50</option>
                        <option value="16:00-16:50">04:00 - 04:50</option>
                    </optgroup>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">មុខវិជ្ជា</label>
                <select name="subject_id" required class="w-full p-3 border rounded-xl">
                    <?php while($row = mysqli_fetch_assoc($subjects)): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['subject_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">បន្ទប់</label>
                <input type="text" name="room" placeholder="A101" required class="w-full p-3 border rounded-xl">
            </div>

            <button type="submit" name="btn_save" class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition">
                រក្សាទុកទិន្នន័យ
            </button>
        </form>
    </div>
</main>