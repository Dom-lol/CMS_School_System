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

// ៣. ទាញយកឈ្មោះមុខវិជ្ជាដំបូងគេដើម្បីបង្ហាញក្នុង Header
$subject_header_query = mysqli_query($conn, "SELECT DISTINCT s.subject_name 
                                             FROM timetable t 
                                             INNER JOIN subjects s ON t.subject_id = s.id 
                                             WHERE t.teacher_id = '$real_t_id' LIMIT 1");
$subject_data = mysqli_fetch_assoc($subject_header_query);
$display_subject = $subject_data['subject_name'] ?? 'គ្រូបង្រៀន';

// ៤. ទាញស្ថិតិ (Statistics)
// ក. បញ្ជីមុខវិជ្ជាទាំងអស់
$sql_all_subjects = "SELECT DISTINCT s.subject_name FROM timetable t 
                     INNER JOIN subjects s ON t.subject_id = s.id 
                     WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0";
$res_subjects = mysqli_query($conn, $sql_all_subjects);

// ខ. រាប់ចំនួនសិស្សសរុប
$sql_count_students = "SELECT COUNT(DISTINCT s.id) as total FROM students s
                       INNER JOIN timetable t ON s.class_id = t.class_id
                       WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0";
$count_students = mysqli_fetch_assoc(mysqli_query($conn, $sql_count_students))['total'] ?? 0;

// គ. ភាគរយវត្តមានថ្ងៃនេះ
$today = date('Y-m-d');
$sql_att = "SELECT (SUM(CASE WHEN a.status = 'present' THEN 1 ELSE 0 END) / COUNT(*)) * 100 as rate 
            FROM attendance a
            INNER JOIN students s ON a.student_id = s.id
            INNER JOIN timetable t ON s.class_id = t.class_id
            WHERE t.teacher_id = '$real_t_id' AND a.attendance_date = '$today'";
$att_rate = mysqli_fetch_assoc(mysqli_query($conn, $sql_att))['rate'] ?? 0;

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
                        <?= htmlspecialchars($_SESSION['full_name']); ?>
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

        <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            <div class="max-w-7xl mx-auto space-y-10">
                
                <!-- <div class="w-full bg-gradient-to-br from-slate-900 via-slate-800 to-blue-900 rounded-[3rem] p-10 md:p-16 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-4xl md:text-6xl font-black italic uppercase mb-2">សួស្តី លោកគ្រូ!</h2>
                        <p class="text-blue-200 text-lg md:text-2xl font-medium italic">Welcome back to your dashboard</p>
                    </div>
                    <i class="fas fa-graduation-cap absolute -right-10 -bottom-10 text-[15rem] md:text-[20rem] text-white/5 transform -rotate-12"></i>
                </div> -->

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[10px] border-l-blue-600 hover:shadow-xl transition-all">
                        <p class="text-slate-500 text-[15px] font-black uppercase  mb-4">មុខវិជ្ជាបង្រៀន</p>
                        <div class="flex flex-wrap gap-2">
                            <?php if (mysqli_num_rows($res_subjects) > 0): ?>
                                <?php while($s = mysqli_fetch_assoc($res_subjects)): ?>
                                    <span class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl font-black text-sm uppercase ">
                                        <?= htmlspecialchars($s['subject_name']) ?>
                                    </span>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <span class="text-slate-300 font-bold ">មិនទាន់មានទិន្នន័យ</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[10px] border-l-purple-500 hover:shadow-xl transition-all">
                        <p class="text-slate-500 text-[15px] font-black uppercase ">សិស្សសរុប</p>
                        <h3 class="text-4xl font-black text-slate-800 mt-4 "><?= $count_students ?> <span class="text-lg text-slate-400">នាក់</span></h3>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[10px] border-l-emerald-500 hover:shadow-xl transition-all">
                        <p class="text-slate-500 text-[15px]  font-black uppercase est">វត្តមានថ្ងៃនេះ</p>
                        <h3 class="text-4xl font-black text-slate-800 mt-4 "><?= number_format($att_rate, 1) ?>%</h3>
                    </div>

                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    
                    <div class="bg-slate-900 rounded-[3rem] shadow-2xl p-10 flex flex-col items-center justify-center text-center text-white group overflow-hidden relative">
                         <div class="relative z-10 w-full">
                            <h3 class="font-black text-xl uppercasemb-6 ">ស្រង់វត្តមានសិស្ស</h3>
                            <br>
                            <a href="attendance.php" class="block w-full py-4 bg-blue-600 text-white rounded-2xl font-black uppercase  hover:bg-blue-700 transition-all shadow-lg active:scale-95 text-[15px]">
                                ចាប់ផ្តើមឥឡូវនេះ
                            </a>
                         </div>
                         <i class="fas fa-clipboard-check absolute -right-5 -bottom-5 text-8xl text-white/5 group-hover:scale-110 transition-transform"></i>
                    </div>
                </div>

            </div>
            <div class="h-10 lg:hidden"></div>
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar'); 
        if(sidebar) {
            sidebar.classList.toggle('-translate-x-full');
        }
    }
</script>

<?php include '../../includes/footer.php'; ?>