<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ឆែកសិទ្ធិចូលប្រើប្រាស់
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

// ២. ទាញទិន្នន័យគ្រូ
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);

$real_t_id = $teacher_info['teacher_id'] ?? 'N/A';
$db_profile_img = $teacher_info['profile_image'] ?? ''; 

// ៣. ទាញស្ថិតិ (Statistics) - កូដដែលដោះស្រាយបញ្ហា Undefined Variable

// ក. ទាញយក "ឈ្មោះថ្នាក់ពិតប្រាកដ" (Real Class Names)
$sql_class_names = "SELECT DISTINCT c.class_name 
                    FROM timetable t
                    INNER JOIN classes c ON t.class_id = c.id
                    WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0";
$res_class_names = mysqli_query($conn, $sql_class_names);

// ខ. រាប់ចំនួនថ្នាក់សរុប (សម្រាប់ប្រើក្នុង str_pad)
$count_classes = mysqli_num_rows($res_class_names);

// គ. រាប់ចំនួនសិស្សសរុប (រាប់តែសិស្សក្នុងថ្នាក់ដែលលោកគ្រូបង្រៀន)
$sql_count_students = "SELECT COUNT(DISTINCT s.id) as total 
                         FROM students s
                         INNER JOIN timetable t ON s.class_id = t.class_id
                         WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0";
$res_students = mysqli_query($conn, $sql_count_students);
$count_students = mysqli_fetch_assoc($res_students)['total'] ?? 0;

// ឃ. ភាគរយវត្តមានថ្ងៃនេះ
$today = date('Y-m-d');
$sql_attendance = "SELECT 
                    (SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) / COUNT(*)) * 100 as rate 
                  FROM attendance a
                  INNER JOIN students s ON a.student_id = s.id
                  INNER JOIN timetable t ON s.class_id = t.class_id
                  WHERE t.teacher_id = '$real_t_id' AND a.attendance_date = '$today'";
$res_attendance = mysqli_query($conn, $sql_attendance);
$att_rate = mysqli_fetch_assoc($res_attendance)['rate'] ?? 0;

include '../../includes/header.php'; 
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; overflow: hidden; width: 100%; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
</style>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    
    <?php include '../../includes/sidebar_teacher.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-all">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[20px] font-black text-slate-900 leading-tight"><?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
                    <p class="text-[12px] text-blue-500 font-bold uppercase italic">Teacher ID: <?php echo $real_t_id; ?></p>
                </div>
                <div class="w-16 h-16 rounded-full overflow-hidden border-2 border-slate-100 bg-slate-100">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        $display_img = (!empty($db_profile_img) && file_exists($path . $db_profile_img)) ? $path . $db_profile_img . "?v=" . time() : $path . 'default_user.png';
                    ?>
                    <img src="<?= $display_img ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 bg-[#f8fafc] custom-scrollbar">
            <div class="w-full space-y-10">
                <div class="w-full bg-gradient-to-br from-slate-900 via-slate-800 to-blue-900 rounded-[3rem] p-10 md:p-16 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-4xl md:text-6xl font-black italic uppercase mb-4">សួស្តី លោកគ្រូ!</h2>
                        <p class="text-blue-200 text-lg md:text-2xl font-medium">រីករាយដែលបានជួបលោកអ្នកត្រឡប់មកវិញ</p>
                    </div>
                    <i class="fas fa-chalkboard-teacher absolute -right-10 -bottom-10 text-[18rem] text-white/5 transform -rotate-12"></i>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 w-full">
                    
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[10px] border-l-blue-600 hover:scale-[1.02] transition-all duration-300">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest">ថ្នាក់បង្រៀនពិតប្រាកដ</p>
                        <div class="mt-3">
                            <?php if (mysqli_num_rows($res_class_names) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($res_class_names)): ?>
                                    <h3 class="text-4xl font-black text-slate-800 mt-1 italic uppercase">
                                        <?= htmlspecialchars($row['class_name']) ?>
                                    </h3>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <h3 class="text-4xl font-black text-slate-300 mt-1 italic">00 ថ្នាក់</h3>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[10px] border-l-purple-500 hover:scale-[1.02] transition-all duration-300">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest">សិស្សសរុប</p>
                        <h3 class="text-4xl font-black text-slate-800 mt-3 italic"><?= $count_students ?> នាក់</h3>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[10px] border-l-emerald-500 hover:scale-[1.02] transition-all duration-300">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest">វត្តមានថ្ងៃនេះ</p>
                        <h3 class="text-4xl font-black text-slate-800 mt-3 italic"><?= number_format($att_rate, 1) ?>%</h3>
                    </div>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 w-full">
                    <div class="lg:col-span-2 bg-white rounded-[3rem] shadow-sm border border-slate-100 p-10 flex flex-col justify-center">
                         <h3 class="font-black text-slate-800 text-2xl uppercase italic mb-4">សេចក្តីជូនដំណឹង</h3>
                         <p class="text-slate-400 italic">មិនទាន់មានការជូនដំណឹងថ្មីសម្រាប់ថ្ងៃនេះទេ...</p>
                    </div>
                    
                    <div class="bg-slate-900 rounded-[3rem] shadow-xl p-10 flex flex-col items-center justify-center text-center text-white">
                         <h3 class="font-black text-xl uppercase italic mb-6">ស្រង់វត្តមានសិស្ស</h3>
                         <a href="attendance.php" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg active:scale-95">ជ្រើសរើសថ្នាក់</a>
                    </div>
                </div>
            </div>
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