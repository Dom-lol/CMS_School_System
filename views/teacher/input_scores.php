<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ឆែកសិទ្ធិចូលប្រើប្រាស់
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

include '../../includes/header.php';

// ២. ទាញយកព័ត៌មានគ្រូ និងរូបភាពពី Database
$u_id = $_SESSION['user_id'];
$teacher_res = mysqli_query($conn, "SELECT teacher_id, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_data = mysqli_fetch_assoc($teacher_res);

$t_id = $teacher_data['teacher_id'] ?? 0;
$db_profile_img = $teacher_data['profile_image'] ?? '';
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; overflow: hidden; width: 100%; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    
    <?php include '../../includes/sidebar_teacher.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-all">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-lg font-bold text-slate-800 italic uppercase tracking-tight">បញ្ចូលពិន្ទុសិស្ស</h2>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right hidden sm:block">
                    <p class="text-base font-black text-slate-900 leading-tight"><?php echo $_SESSION['full_name']; ?></p>
                    <p class="text-[11px] text-blue-500 font-bold uppercase italic">Teacher ID: <?php echo $t_id; ?></p>
                </div>
                
                <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm bg-slate-50">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        if (!empty($db_profile_img) && file_exists($path . $db_profile_img)) {
                            $display_img = $path . $db_profile_img . "?v=" . time();
                        } else {
                            $display_img = $path . 'default_user.png';
                        }
                    ?>
                    <img src="<?= $display_img ?>" class="w-full h-full object-cover" onerror="this.src='../../assets/uploads/teachers/default_user.png'">
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            <div class="w-full">
                <div class="mb-10 bg-gradient-to-br from-slate-900 to-blue-900 rounded-[3rem] p-10 md:p-14 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h1 class="text-4xl md:text-5xl font-black italic uppercase tracking-tighter">Grade Entry</h1>
                        <p class="text-blue-200 mt-4 font-medium opacity-80 italic">សូមជ្រើសរើសមុខវិជ្ជាខាងក្រោមដើម្បីបញ្ចូលពិន្ទុតាមថ្នាក់នីមួយៗ</p>
                    </div>
                    <i class="fas fa-edit absolute -right-10 -bottom-10 text-[18rem] text-white/5 transform -rotate-12"></i>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    // Query ទាញយកមុខវិជ្ជា និងថ្នាក់ពី Timetable
                    $query = "SELECT DISTINCT c.class_name, s.subject_name, s.id as subject_id, c.id as class_id
                              FROM timetable t 
                              JOIN classes c ON t.class_id = c.id 
                              JOIN subjects s ON t.subject_id = s.id 
                              WHERE t.teacher_id = '$t_id'";
                    
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0):
                        while ($row = mysqli_fetch_assoc($result)):
                    ?>
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl transition-all duration-500 group relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-blue-50/40 rounded-bl-full -mr-16 -mt-16 transition-all group-hover:bg-blue-100"></div>
                            
                            <div class="flex justify-between items-start mb-6">
                                <span class="px-4 py-1.5 bg-blue-50 text-blue-600 rounded-2xl text-[10px] font-black uppercase tracking-widest group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    ថ្នាក់: <?php echo $row['class_name']; ?>
                                </span>
                            </div>

                            <h3 class="text-2xl font-black text-slate-800 mb-8 italic tracking-tight leading-tight">
                                <?php echo $row['subject_name']; ?>
                            </h3>
                            
                            <a href="manage_grades.php?class_id=<?php echo $row['class_id']; ?>&subject_id=<?php echo $row['subject_id']; ?>" 
                               class="flex items-center justify-center gap-3 w-full py-5 bg-slate-900 text-white rounded-[1.8rem] font-black uppercase text-[10px] tracking-[0.2em] hover:bg-blue-600 shadow-xl transition-all active:scale-95">
                                <i class="fas fa-edit text-xs"></i> បញ្ចូលពិន្ទុឥឡូវនេះ
                            </a>
                        </div>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                        <div class="col-span-full bg-white p-24 rounded-[3rem] text-center border-4 border-dashed border-slate-100">
                            <i class="fas fa-book-reader text-6xl text-slate-200 mb-6"></i>
                            <h3 class="text-2xl font-black text-slate-800 uppercase italic">មិនទាន់មានមុខវិជ្ជាបង្រៀន</h3>
                            <p class="text-slate-400 mt-2 font-medium italic">លោកគ្រូមិនទាន់មានកាលវិភាគបង្រៀននៅក្នុងប្រព័ន្ធនៅឡើយទេ។</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden backdrop-blur-sm transition-opacity duration-300"></div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        if(sidebar) {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    }
</script>

<?php include '../../includes/footer.php'; ?>