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

// បន្ថែម Query ដើម្បីទាញមុខវិជ្ជាមកបង្ហាញក្នុង Header (ដើម្បីកុំឱ្យ Error)
$sub_query = mysqli_query($conn, "SELECT DISTINCT s.subject_name FROM timetable t 
                                  INNER JOIN subjects s ON t.subject_id = s.id 
                                  WHERE t.teacher_id = '$real_t_id' LIMIT 1");
$sub_data = mysqli_fetch_assoc($sub_query);
$display_subject = $sub_data['subject_name'] ?? 'មិនទាន់កំណត់';

$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;

// តារាង Mapping id ទៅជាឈ្មោះថ្នាក់
$grade_map = [1 => "7", 2 => "8", 3 => "9", 4 => "10", 5 => "11", 6 => "12"];
$current_grade = $grade_map[$class_id] ?? '---';

// ២. ទាញបញ្ជីថ្នាក់បង្រៀន
$classes_query = mysqli_query($conn, "SELECT DISTINCT class_id FROM timetable WHERE teacher_id = '$real_t_id' AND is_deleted = 0");

$students = null; 
if ($class_id > 0) {
    // ៣. ទាញឈ្មោះសិស្ស
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
        
       <header class="bg-white border-b-2 border-slate-100 h-20 flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl">
                    <i class="fas fa-bars text-xl"></i>
                </button>
              
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[18px] md:text-[20px] font-black text-slate-900 leading-tight">
                        <?= htmlspecialchars($display_name); ?>
                    </p>
                    <p class="text-[11px] md:text-[12px] text-blue-600 font-bold uppercase">
                         មុខវិជ្ជា: <span class="text-slate-500"><?= htmlspecialchars($display_subject) ?></span>
                    </p>
                </div>
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-slate-100 shadow-sm bg-slate-50">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        $img = (!empty($db_profile_img) && file_exists($path . $db_profile_img)) ? $path . $db_profile_img : $path . 'default_user.png';
                    ?>
                    <img src="<?= $img ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

       <main class="flex-1 overflow-y-auto p-4 md:p-10 custom-scrollbar bg-[#f8fafc]">
    
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-6 md:p-8 rounded-[2rem] text-white shadow-xl flex-1 border-b-4 md:border-b-8 border-blue-600">
            <h1 class="text-xl md:text-3xl "><span class="text-white">ថ្នាក់ទី <?= $current_grade ?></span><h1>
            <p class="text-slate-400 font-bold text-[12px] md:text-sm mt-2 uppercase tracking-widest">
                <i class="far fa-calendar-alt mr-2"></i> <?= date('D, d M Y') ?>
            </p>
        </div>

        <form method="GET" class="w-full md:w-auto">
            <select name="class_id" onchange="this.form.submit()" 
                    class="w-full md:w-72 bg-white border-2 border-slate-100 rounded-2xl md:rounded-[1.5rem] px-6 py-4 md:py-5 font-bold outline-none shadow-sm focus:border-blue-500 transition-all cursor-pointer text-slate-700 text-sm md:text-base">
                <option value="">--- ជ្រើសរើសថ្នាក់ ---</option>
                <?php if($classes_query): mysqli_data_seek($classes_query, 0); while($c = mysqli_fetch_assoc($classes_query)): 
                    $id = $c['class_id'];
                    $label = $grade_map[$id] ?? $id;
                ?>
                    <option value="<?= $id ?>" <?= $class_id == $id ? 'selected' : '' ?>>ថ្នាក់ទី <?= $label ?></option>
                <?php endwhile; endif; ?>
            </select>
        </form>
    </div>

    <?php if ($class_id > 0 && $students && mysqli_num_rows($students) > 0): ?>
        
        <div class="hidden md:block bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-8 font-black uppercase text-[17px] text-slate-500 ">អត្តលេខ</th>
                        <th class="p-8 font-black uppercase text-[17px] text-slate-500 ">ឈ្មោះសិស្ស</th>
                        <th class="p-8 font-black uppercase text-[17px] text-slate-500 text-center">ភេទ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php mysqli_data_seek($students, 0); while($row = mysqli_fetch_assoc($students)): ?>
                    <tr class="hover:bg-blue-50/40 transition-all group">
                        <td class="p-8 text-sm font-black text-blue-600 ">#<?= htmlspecialchars($row['student_id']) ?></td>
                        <td class="p-8 font-bold text-slate-800 uppercase "><?= htmlspecialchars($row['full_name']) ?></td>
                        <td class="p-8 text-center text-xs font-bold text-slate-500 uppercase"><span class="px-4 py-1 bg-slate-100 rounded-full"><?= $row['gender'] ?></span></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-3 pb-10">
            <?php mysqli_data_seek($students, 0); while($row = mysqli_fetch_assoc($students)): ?>
            <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-3">
                   
                    <div>
                        <h4 class="font-bold text-slate-800 text-[16px] uppercase"><?= htmlspecialchars($row['full_name']) ?></h4>
                        <p class="text-[12px] text-blue-500 font-bold uppercase">ID: <?= $row['student_id'] ?></p>
                    </div>
                </div>
                <div class="text-[13px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">
                    <?= $row['gender'] ?>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

    <?php else: ?>
        <div class="h-64 md:h-80 flex flex-col items-center justify-center border-2 md:border-4 border-dashed border-slate-100 rounded-[2rem] md:rounded-[4rem] p-6 bg-white/50">
            <i class="fas fa-users text-3xl text-slate-200 mb-4"></i>
            <h3 class="text-[10px] md:text-sm font-black text-slate-300 uppercase italic tracking-widest text-center">
                <?= $class_id > 0 ? 'មិនមានសិស្សក្នុងថ្នាក់នេះទេ' : 'សូមជ្រើសរើសថ្នាក់ ដើម្បីបង្ហាញបញ្ជីសិស្ស' ?>
            </h3>
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