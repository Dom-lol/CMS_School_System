<?php 
// ១. ហៅ Session និង DB Connection ឱ្យបានត្រឹមត្រូវ [cite: 2026-01-20]
require_once '../../config/session.php'; 
require_once '../../config/db.php'; 

$u_id = $_SESSION['user_id'] ?? 0;
if ($u_id == 0) {
    header("Location: ../../login.php");
    exit();
}

// ២. ទាញយកព័ត៌មានគ្រូ [cite: 2026-01-20]
$teacher_res = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_data = mysqli_fetch_assoc($teacher_res);
$t_id = $teacher_data['teacher_id'] ?? 0;
$t_profile = $teacher_data['profile_image'] ?? '';

// ៣. ទទួលយក Class ID ពី Dropdown (Default យកថ្នាក់ទី ១)
$target_class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 1; 

// ៤. ទាញយកបញ្ជីថ្នាក់ទាំងអស់ដែលគ្រូបង្រៀន (Distinct Classes)
$all_classes_res = mysqli_query($conn, "SELECT DISTINCT c.id, c.class_name 
                                        FROM timetable t 
                                        INNER JOIN classes c ON t.class_id = c.id 
                                        WHERE t.teacher_id = '$t_id' AND t.is_deleted = 0");

// ៥. ទាញយកឈ្មោះថ្នាក់ដែលកំពុងជ្រើសរើស
$current_class_res = mysqli_query($conn, "SELECT class_name FROM classes WHERE id = '$target_class_id' LIMIT 1");
$class_info = mysqli_fetch_assoc($current_class_res);
$display_class_name = $class_info['class_name'] ?? 'មិនស្គាល់';

include '../../includes/header.php';

$time_slots = ['07:00 - 07:50', '08:00 - 08:50', '09:00 - 09:50', '10:00 - 10:50'];
$days_kh = ['ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍'];
?>

<div class="flex h-screen w-full bg-slate-100 font-['Kantumruy_Pro'] overflow-hidden">
    <?php include '../../includes/sidebar_teacher.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-hidden">
        
        <header class="bg-white border-b-4 border-blue-600 shadow-md px-4 md:px-10 py-4 shrink-0">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <button onclick="toggleSidebar()" class="lg:hidden p-2 bg-slate-100 rounded-lg">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="w-16 h-16 rounded-full border-4 border-blue-100 shadow-sm overflow-hidden bg-slate-200">
                        <?php 
                            $path = "../../assets/uploads/teachers/";
                            $display_img = (!empty($t_profile) && file_exists($path . $t_profile)) ? $path . $t_profile : $path . 'default_user.png';
                        ?>
                        <img src="<?= $display_img ?>" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-blue-700 leading-tight"><?= htmlspecialchars($teacher_data['full_name']) ?></h2>
                        <p class="text-sm font-bold text-slate-400">អត្តលេខគ្រូ: #<?= $t_id ?></p>
                    </div>
                </div>

                <div class="w-full md:w-auto">
                    <form method="GET" class="flex items-center justify-center gap-3">
                        <label class="hidden sm:block text-lg font-bold text-slate-600">ជ្រើសរើសថ្នាក់៖</label>
                        <select name="class_id" onchange="this.form.submit()" 
                                class="w-full md:w-48 bg-blue-600 text-white text-xl font-black rounded-2xl px-5 py-3 shadow-lg outline-none cursor-pointer hover:bg-blue-700 transition-all">
                            <?php while($class = mysqli_fetch_assoc($all_classes_res)): ?>
                                <option value="<?= $class['id'] ?>" <?= ($target_class_id == $class['id']) ? 'selected' : '' ?>>
                                    ថ្នាក់ទី <?= htmlspecialchars($class['class_name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </form>
                </div>

            </div>
        </header>

        <div class="flex-1 overflow-auto p-4 md:p-8">
            <div class="mb-6 text-center">
                <h1 class="text-3xl md:text-4xl font-black text-slate-800 underline decoration-blue-200 underline-offset-8 italic uppercase">
                    កាលវិភាគសិក្សាថ្នាក់ទី <?= htmlspecialchars($display_class_name) ?>
                </h1>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl border-2 border-slate-300 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white border-b-4 border-blue-600">
                                <th class="p-6 border-r-2 border-slate-700 text-xl font-black italic text-center">ម៉ោងសិក្សា</th>
                                <?php foreach ($days_kh as $day): ?>
                                    <th class="p-6 border-r-2 border-slate-700 text-xl font-black italic text-center"><?= $day ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-100">
                            <?php foreach ($time_slots as $slot): ?>
                            <tr>
                                <td class="p-6 border-r-2 border-slate-200 bg-slate-50 text-center text-lg font-black italic text-slate-700 whitespace-nowrap">
                                    <?= $slot ?>
                                </td>
                                <?php foreach ($days_kh as $day): 
                                    list($start, $end) = explode(' - ', $slot);
                                    // SQL Query តាមគ្រូ និងថ្នាក់ដែលបានរើស [cite: 2026-01-20]
                                    $sql = "SELECT s.subject_name, t.room_number FROM timetable t 
                                            INNER JOIN subjects s ON t.subject_id = s.id 
                                            WHERE t.teacher_id = '$t_id' AND t.class_id = '$target_class_id' 
                                            AND t.day_of_week = '$day' AND DATE_FORMAT(t.start_time, '%H:%i') = '$start'
                                            AND t.is_deleted = 0 LIMIT 1";
                                    $res = mysqli_query($conn, $sql);
                                    $data = mysqli_fetch_assoc($res);
                                ?>
                                <td class="p-4 border-r-2 border-slate-50 text-center min-w-[160px]">
                                    <?php if ($data): ?>
                                        <div class="text-2xl font-black text-slate-800"><?= htmlspecialchars($data['subject_name']) ?></div>
                                        <div class="text-[12px] font-bold text-blue-600 mt-2 italic uppercase bg-blue-50 py-1 rounded-lg">
                                            Room: <?= htmlspecialchars($data['room_number']) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-200">---</span>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar'); 
        if(sidebar) sidebar.classList.toggle('-translate-x-full');
    }
</script>

<?php include '../../includes/footer.php'; ?>