<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ឆែកសិទ្ធិចូលប្រើប្រាស់
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

// ២. ទាញទិន្នន័យគ្រូដើម្បីយក teacher_id (អត្តលេខ)
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);

$real_t_id = $teacher_info['teacher_id'] ?? 'N/A';
$t_name = $teacher_info['full_name'] ?? $_SESSION['full_name'];
$db_profile_img = $teacher_info['profile_image'] ?? ''; 

include '../../includes/header.php'; 
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    
    <?php include '../../includes/sidebar_teacher.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-black text-slate-800 italic uppercase tracking-tighter">My Classes</h2>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[18px] font-black text-slate-900 leading-tight"><?= htmlspecialchars($t_name) ?></p>
                    <p class="text-[11px] text-blue-500 font-bold uppercase tracking-widest">ID: <?= $real_t_id ?></p>
                </div>
                <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-white shadow-lg bg-indigo-600 flex items-center justify-center rotate-3">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        $display_img = (!empty($db_profile_img) && file_exists($path . $db_profile_img)) ? $path . $db_profile_img . "?v=" . time() : $path . 'default_user.png';
                    ?>
                    <img src="<?= $display_img ?>" class="w-full h-full object-cover -rotate-3">
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            <div class="max-w-7xl mx-auto space-y-10">
                
                <div class="w-full bg-gradient-to-r from-blue-900 to-indigo-800 rounded-[3rem] p-12 text-white shadow-xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h1 class="text-5xl font-black italic mb-2 uppercase">បញ្ជីថ្នាក់រៀន</h1>
                        <p class="text-blue-100 text-lg">គ្រប់គ្រងសិស្ស និងតាមដានសកម្មភាពបង្រៀនតាមថ្នាក់នីមួយៗ</p>
                    </div>
                    <i class="fas fa-layer-group absolute right-10 top-1/2 -translate-y-1/2 text-[15rem] text-white/5 transform rotate-12"></i>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php 
                    // ៣. Query ទាញយកថ្នាក់ដែលគ្រូបង្រៀន (Join classes & subjects)
                    $sql = "SELECT DISTINCT c.id as class_id, c.class_name, s.subject_name, s.id as sub_id
                            FROM timetable t 
                            INNER JOIN classes c ON t.class_id = c.id 
                            INNER JOIN subjects s ON t.subject_id = s.id 
                            WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0";
                    
                    $result = mysqli_query($conn, $sql);

                    if ($result && mysqli_num_rows($result) > 0): 
                        while ($row = mysqli_fetch_assoc($result)): ?>
                        
                        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 flex flex-col items-center text-center transition-all duration-500 hover:shadow-2xl hover:-translate-y-3 group shadow-sm">
                            <div class="w-20 h-20 bg-indigo-50 group-hover:bg-indigo-600 rounded-3xl flex items-center justify-center mb-6 transition-colors duration-300">
                                <i class="fas fa-users text-3xl text-indigo-600 group-hover:text-white transition-colors"></i>
                            </div>
                            
                            <h3 class="text-2xl font-black text-slate-800 mb-1 italic">
                                ថ្នាក់: <?= htmlspecialchars($row['class_name']) ?>
                            </h3>
                            <p class="text-slate-400 font-bold text-[11px] uppercase tracking-[0.2em] mb-8 italic">
                                <?= htmlspecialchars($row['subject_name']) ?>
                            </p>
                            
                            <a href="view_students.php?class_id=<?= $row['class_id'] ?>&subject_id=<?= $row['sub_id'] ?>" 
                               class="w-full bg-[#0f172a] text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-indigo-600 transition-all shadow-lg">
                                VIEW STUDENTS <i class="fas fa-arrow-right text-[8px]"></i>
                            </a>
                        </div>

                    <?php endwhile; else: ?>
                        <div class="col-span-full flex flex-col items-center justify-center py-20 opacity-30">
                            <i class="fas fa-folder-open text-6xl mb-4"></i>
                            <p class="text-xl font-bold italic">មិនទាន់មានទិន្នន័យថ្នាក់រៀននៅឡើយទេ</p>
                        </div>
                    <?php endif; ?>
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