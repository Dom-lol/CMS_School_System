<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ១. ឆែកសិទ្ធិ និងទាញព័ត៌មានគ្រូ (សម្រាប់ Header)
$u_id = $_SESSION['user_id'] ?? 0;
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);

$real_t_id      = $teacher_info['teacher_id'] ?? 'N/A';
$display_name   = $teacher_info['full_name'] ?? ($_SESSION['full_name'] ?? 'គ្រូបង្រៀន');
$db_profile_img = $teacher_info['profile_image'] ?? ''; 

$class_id = $_GET['class_id'] ?? 0;

// តារាង Mapping id ទៅជាឈ្មោះថ្នាក់
$grade_map = [1 => "7", 2 => "8", 3 => "9", 4 => "10", 5 => "11", 6 => "12"];
$current_grade = $grade_map[$class_id] ?? '---';

// ២. ទាញបញ្ជីថ្នាក់បង្រៀន
$classes_query = mysqli_query($conn, "SELECT DISTINCT class_id FROM timetable WHERE teacher_id = '$real_t_id' AND is_deleted = 0");

$students = null; 
if ($class_id > 0) {
    // ៣. ទាញឈ្មោះសិស្ស (គ្មានរូបថត គ្មាន detail តាមសំណើ)
    $st_query = "SELECT student_id, full_name, gender FROM students 
                 WHERE class_id = '$class_id' AND status = 'Active' 
                 ORDER BY full_name ASC";
    $students = mysqli_query($conn, $st_query);
}

include '../../includes/header.php'; 
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; overflow: hidden; width: 100%; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    
    <?php include '../../includes/sidebar_teacher.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full">
        
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-all">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="hidden md:block text-xl font-black text-slate-800 uppercase italic tracking-tighter">បញ្ជីឈ្មោះសិស្ស</h2>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[18px] md:text-[20px] font-black text-slate-900 leading-tight"><?php echo htmlspecialchars($display_name); ?></p>
                    <p class="text-[11px] md:text-[12px] text-blue-500 font-bold uppercase italic">Teacher ID: <?php echo $real_t_id; ?></p>
                </div>
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-slate-100 bg-slate-100 shadow-sm">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        $display_img = (!empty($db_profile_img) && file_exists($path . $db_profile_img)) ? $path . $db_profile_img . "?v=" . time() : $path . 'default_user.png';
                    ?>
                    <img src="<?= $display_img ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            
            <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-8 rounded-[2.5rem] text-white shadow-xl flex-1 border-b-8 border-blue-600">
                   
                    <h1 class="text-3xl text-white italic"><span class="text-white">ថ្នាក់ទី <?= $current_grade ?></span></h1>
                </div>

                <form method="GET" class="w-full md:w-auto">
                    <select name="class_id" onchange="this.form.submit()" class="w-full md:w-72 bg-white border-2 border-slate-100 rounded-[1.5rem] px-6 py-5 font-bold outline-none shadow-sm focus:border-blue-500 transition-all cursor-pointer text-slate-700">
                        <option value="">--- ជ្រើសរើសថ្នាក់ ---</option>
                        <?php if($classes_query): ?>
                            <?php while($c = mysqli_fetch_assoc($classes_query)): 
                                $id = $c['class_id'];
                                $label = $grade_map[$id] ?? $id;
                            ?>
                                <option value="<?= $id ?>" <?= $class_id == $id ? 'selected' : '' ?>>
                                    ថ្នាក់ទី <?= $label ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </form>
            </div>

            <?php if ($class_id > 0 && $students && mysqli_num_rows($students) > 0): ?>
                <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="p-8 font-black uppercase text-[11px] text-slate-400 tracking-widest">អត្តលេខសិស្ស</th>
                                    <th class="p-8 font-black uppercase text-[11px] text-slate-400 tracking-widest">ឈ្មោះសិស្ស</th>
                                    <th class="p-8 font-black uppercase text-[11px] text-slate-400 tracking-widest text-center">ភេទ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php while($row = mysqli_fetch_assoc($students)): ?>
                                <tr class="hover:bg-blue-50/40 transition-all group">
                                    <td class="p-8 text-sm font-black text-blue-600 italic">
                                        #<?= htmlspecialchars($row['student_id']) ?>
                                    </td>
                                    <td class="p-8">
                                        <div class="font-bold text-slate-800 uppercase tracking-tight"><?= htmlspecialchars($row['full_name']) ?></div>
                                    </td>
                                    <td class="p-8 text-xs font-bold text-slate-500 uppercase italic text-center">
                                        <span class="px-4 py-1 bg-slate-100 rounded-full"><?= $row['gender'] ?></span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="h-80 flex flex-col items-center justify-center border-4 border-dashed border-slate-100 rounded-[4rem] p-10 bg-white/50 shadow-inner">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-users text-3xl text-slate-300"></i>
                    </div>
                    <h3 class="text-sm font-black text-slate-400 uppercase italic tracking-[0.2em]">សូមជ្រើសរើសថ្នាក់ ដើម្បីបង្ហាញបញ្ជីសិស្ស</h3>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar'); 
        if(sidebar) sidebar.classList.toggle('-translate-x-full');
    }
</script>

<?php include '../../includes/footer.php'; ?>