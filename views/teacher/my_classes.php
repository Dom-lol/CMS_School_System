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
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);

$real_t_id = $teacher_info['teacher_id'] ?? 'N/A';
$t_name = $teacher_info['full_name'] ?? $_SESSION['full_name'];
$db_profile_img = $teacher_info['profile_image'] ?? ''; 

// ៣. ទាញយកឈ្មោះមុខវិជ្ជាដំបូងគេដើម្បីបង្ហាញក្នុង Header (ជំនួស ID)
$subject_header_query = mysqli_query($conn, "SELECT DISTINCT s.subject_name 
                                             FROM timetable t 
                                             INNER JOIN subjects s ON t.subject_id = s.id 
                                             WHERE t.teacher_id = '$real_t_id' LIMIT 1");
$subject_data = mysqli_fetch_assoc($subject_header_query);
$display_subject = $subject_data['subject_name'] ?? 'គ្រូបង្រៀន';

include '../../includes/header.php'; 
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="flex h-screen w-full overflow-hidden">
    
    <div id="sidebar-container" class="transition-all duration-300">
        <?php include '../../includes/sidebar_teacher.php'; ?>
    </div>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
            <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="hidden md:block text-xl font-black text-slate-800 italic uppercase">Dashboard</h2>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[18px] md:text-[20px] font-black text-slate-900 leading-tight">
                        <?= htmlspecialchars($_SESSION['full_name']); ?>
                    </p>
                    <p class="text-[11px] md:text-[12px] text-blue-600 font-bold uppercase italic tracking-widest">
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


        <main class="flex-1 overflow-y-auto p-4 md:p-10 custom-scrollbar">
            <div class="max-w-7xl mx-auto space-y-6 md:space-y-10">
                
                <div class="w-full bg-gradient-to-r from-blue-900 to-indigo-800 rounded-[2rem] md:rounded-[3rem] p-8 md:p-12 text-white shadow-xl relative overflow-hidden border-b-8 border-indigo-500">
                    <div class="relative z-10">
                        <h1 class="text-3xl md:text-5xl font-black italic mb-2 uppercase">បញ្ជីថ្នាក់រៀន</h1>
                        <p class="text-blue-100 text-sm md:text-lg opacity-80">គ្រប់គ្រងសិស្ស និងតាមដានសកម្មភាពបង្រៀន</p>
                    </div>
                    <i class="fas fa-layer-group absolute right-4 md:right-10 top-1/2 -translate-y-1/2 text-7xl md:text-[15rem] text-white/5 transform rotate-12"></i>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-8">
                    <?php 
                    $sql = "SELECT DISTINCT c.id as class_id, c.class_name, s.subject_name, s.id as sub_id
                            FROM timetable t 
                            INNER JOIN classes c ON t.class_id = c.id 
                            INNER JOIN subjects s ON t.subject_id = s.id 
                            WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0";
                    
                    $result = mysqli_query($conn, $sql);

                    if ($result && mysqli_num_rows($result) > 0): 
                        while ($row = mysqli_fetch_assoc($result)): ?>
                        
                        <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] border border-slate-100 p-6 md:p-8 flex flex-col items-center text-center transition-all duration-300 hover:shadow-xl group">
                            <div class="w-16 h-16 md:w-20 md:h-20 bg-slate-50 group-hover:bg-indigo-600 rounded-[1.5rem] md:rounded-3xl flex items-center justify-center mb-4 md:mb-6 transition-all duration-300 group-hover:rotate-6">
                                <i class="fas fa-users text-2xl md:text-3xl text-indigo-600 group-hover:text-white"></i>
                            </div>
                            
                            <h3 class="text-xl md:text-2xl font-black text-slate-800 mb-1 italic">
                                 <?= htmlspecialchars($row['class_name']) ?>
                            </h3>
                            <p class="text-slate-400 font-bold text-[10px] md:text-[11px] uppercase tracking-[0.1em] mb-6 md:mb-8 italic">
                                <?= htmlspecialchars($row['subject_name']) ?>
                            </p>
                            
                            <a href="view_student.php?class_id=<?= $row['class_id'] ?>&subject_id=<?= $row['sub_id'] ?>" 
                               class="w-full bg-slate-900 text-white py-3 md:py-4 rounded-xl md:rounded-2xl font-black text-[9px] md:text-[10px] uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-blue-600 transition-all shadow-lg active:scale-95">
                                VIEW STUDENTS <i class="fas fa-arrow-right text-[8px]"></i>
                            </a>
                        </div>

                    <?php endwhile; else: ?>
                        <div class="col-span-full flex flex-col items-center justify-center py-16 md:py-24 opacity-20">
                            <i class="fas fa-folder-open text-5xl md:text-7xl mb-4"></i>
                            <p class="text-lg md:text-xl font-bold italic uppercase tracking-widest">No Classes Found</p>
                        </div>
                    <?php endif; ?>
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