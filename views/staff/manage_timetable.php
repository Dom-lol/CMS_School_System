<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

$id = isset($_GET['id']) ? $_GET['id'] : '';
$row = ['teacher_id'=>'', 'subject_name'=>'', 'class_name'=>'', 'day_of_week'=>'', 'start_time'=>'', 'end_time'=>'', 'room_number'=>''];

if ($id) {
    $res = mysqli_query($conn, "SELECT * FROM timetables WHERE id = '$id'");
    $row = mysqli_fetch_assoc($res);
}
?>

<main class="flex-1 p-8 bg-gray-50 font-['Kantumruy_Pro']">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <h2 class="text-2xl font-bold text-slate-800 mb-6"><?php echo $id ? 'កែប្រែកាលវិភាគ' : 'បន្ថែមម៉ោងបង្រៀនថ្មី'; ?></h2>
        
        <form action="../../actions/timetable/save.php" method="POST" class="grid grid-cols-2 gap-4">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            
            <div>
                <label class="block text-sm font-medium mb-1 text-slate-700">លេខសម្គាល់គ្រូ (Teacher ID)</label>
                <input type="number" name="teacher_id" 
                    value="<?php echo isset($row['teacher_id']) ? $row['teacher_id'] : ''; ?>" 
                    required 
                    class="w-full px-4 py-2 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">មុខវិជ្ជា</label>
                <input type="text" name="subject_name" value="<?php echo $row['subject_name']; ?>" required class="w-full px-4 py-2 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">ថ្នាក់សិក្សា</label>
                <input type="text" name="class_name" value="<?php echo $row['class_name']; ?>" required class="w-full px-4 py-2 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">ថ្ងៃ</label>
                <select name="day_of_week" class="w-full px-4 py-2 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                    <?php 
                    $days = ['ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍', 'អាទិត្យ'];
                    foreach($days as $day) {
                        $selected = ($row['day_of_week'] == $day) ? 'selected' : '';
                        echo "<option value='$day' $selected>$day</option>";
                    }
                    ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">បន្ទប់</label>
                <input type="text" name="room_number" value="<?php echo $row['room_number']; ?>" class="w-full px-4 py-2 border rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">ម៉ោងចាប់ផ្ដើម</label>
                <input type="time" name="start_time" value="<?php echo $row['start_time']; ?>" required class="w-full px-4 py-2 border rounded-xl">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">ម៉ោងបញ្ចប់</label>
                <input type="time" name="end_time" value="<?php echo $row['end_time']; ?>" required class="w-full px-4 py-2 border rounded-xl">
            </div>

            <div class="col-span-2 pt-4 flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-700 transition">រក្សាទុក</button>
                <a href="timetable.php" class="bg-slate-100 text-slate-600 px-6 py-2 rounded-xl font-bold hover:bg-slate-200 transition">បោះបង់</a>
            </div>
        </form>
    </div>
</main>