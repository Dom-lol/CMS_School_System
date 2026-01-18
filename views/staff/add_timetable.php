<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ១. ដំណើរការនៅពេល Staff ចុចប៊ូតុង Save
if (isset($_POST['btn_save'])) {
    // ចាប់យកទិន្នន័យ និងការពារ SQL Injection
    $day        = mysqli_real_escape_string($conn, $_POST['day_of_week']);
    $start_time = mysqli_real_escape_string($conn, $_POST['start_time']);
    $end_time   = mysqli_real_escape_string($conn, $_POST['end_time']);
    $subject_id = mysqli_real_escape_string($conn, $_POST['subject_id']);
    $teacher_id = mysqli_real_escape_string($conn, $_POST['teacher_id']);
    $room       = mysqli_real_escape_string($conn, $_POST['room_number']);
    $class_id   = mysqli_real_escape_string($conn, $_POST['class_id']);

    // ឆែកមើលថាតើម៉ោងបញ្ចប់តូចជាងម៉ោងចាប់ផ្ដើមឬអត់
    if (strtotime($end_time) <= strtotime($start_time)) {
        $error = "កំហុស៖ ម៉ោងបញ្ចប់មិនអាចតូចជាង ឬស្មើម៉ោងចាប់ផ្ដើមឡើយ!";
    } else {
        $sql = "INSERT INTO timetable (day_of_week, start_time, end_time, subject_id, teacher_id, room_number, class_id) 
                VALUES ('$day', '$start_time', '$end_time', '$subject_id', '$teacher_id', '$room', '$class_id')";

        if (mysqli_query($conn, $sql)) {
            echo "<script>
                alert('បញ្ចូលកាលវិភាគជោគជ័យ!'); 
                window.location='timetable.php';
            </script>";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}

// ២. ទាញបញ្ជីមុខវិជ្ជា និង គ្រូ ដើម្បីបង្ហាញក្នុង Dropdown
$subjects_query = mysqli_query($conn, "SELECT * FROM subjects ORDER BY subject_name ASC");
$teachers_query = mysqli_query($conn, "SELECT * FROM teachers WHERE is_deleted = 0 ORDER BY full_name ASC");
?>

<main class="flex-1 p-6 bg-[#f8fafc] min-h-screen">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
        
        <div class="mb-8">
            <h2 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                <i class="fas fa-calendar-plus text-blue-600"></i> បន្ថែមកាលវិភាគថ្មី
            </h2>
            <p class="text-slate-400 text-sm mt-1 font-medium">សូមបំពេញព័ត៌មានកាលវិភាគសិក្សាខាងក្រោម</p>
        </div>

        <?php if(isset($error)): ?>
            <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 font-bold text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">ថ្ងៃសិក្សា</label>
                    <select name="day_of_week" required class="w-full p-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-blue-500 focus:bg-white transition-all font-bold text-slate-700 outline-none">
                        <option value="ច័ន្ទ">ច័ន្ទ</option>
                        <option value="អង្គារ">អង្គារ</option>
                        <option value="ពុធ">ពុធ</option>
                        <option value="ព្រហស្បតិ៍">ព្រហស្បតិ៍</option>
                        <option value="សុក្រ">សុក្រ</option>
                        <option value="សៅរ៍">សៅរ៍</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">ថ្នាក់រៀន</label>
                    <input type="text" name="class_id" required placeholder="ឧទាហរណ៍: 12A" class="w-full p-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-blue-500 focus:bg-white transition-all font-bold outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">ម៉ោងចាប់ផ្ដើម</label>
                    <input type="time" name="start_time" required class="w-full p-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-blue-500 focus:bg-white transition-all font-bold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">ម៉ោងបញ្ចប់</label>
                    <input type="time" name="end_time" required class="w-full p-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-blue-500 focus:bg-white transition-all font-bold outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">មុខវិជ្ជាសិក្សា</label>
                <select name="subject_id" required class="w-full p-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-blue-500 focus:bg-white transition-all font-bold text-slate-700 outline-none">
                    <option value="">--- ជ្រើសរើសមុខវិជ្ជា ---</option>
                    <?php if(mysqli_num_rows($subjects_query) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($subjects_query)): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['subject_name']; ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">គ្រូបង្រៀន</label>
                <select name="teacher_id" required class="w-full p-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-blue-500 focus:bg-white transition-all font-bold text-slate-700 outline-none">
                    <option value="">--- ជ្រើសរើសគ្រូ ---</option>
                    <?php if(mysqli_num_rows($teachers_query) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($teachers_query)): ?>
                            <option value="<?php echo $row['id']; ?>"><?php echo $row['full_name']; ?></option>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <option value="" disabled>មិនទាន់មានឈ្មោះគ្រូក្នុងប្រព័ន្ធ</option>
                    <?php endif; ?>
                </select>
            </div>

            <div>
                <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-2 ml-1">លេខបន្ទប់ / អាគារ</label>
                <input type="text" name="room_number" placeholder="ឧទាហរណ៍: Room 201" class="w-full p-4 bg-slate-50 border-2 border-transparent rounded-2xl focus:border-blue-500 focus:bg-white transition-all font-bold outline-none">
            </div>

            <div class="pt-6 flex flex-col md:flex-row gap-4">
                <button type="submit" name="btn_save" class="flex-1 bg-blue-600 text-white p-4 rounded-2xl font-black shadow-xl shadow-blue-100 hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-save mr-2"></i> រក្សាទុកកាលវិភាគ
                </button>
                <a href="timetable.php" class="px-8 bg-slate-100 text-slate-500 p-4 rounded-2xl font-black hover:bg-slate-200 transition-all text-center">
                    បោះបង់
                </a>
            </div>
        </form>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>