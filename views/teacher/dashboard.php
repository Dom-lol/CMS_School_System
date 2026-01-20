<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ឆែកសិទ្ធិចូលប្រើប្រាស់៖ អនុញ្ញាតតែ teacher និង admin ប៉ុណ្ណោះ
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

// ២. ទាញទិន្នន័យគ្រូ និងរូបភាពពីតារាង teachers ដោយប្រើ user_id ពី Session
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);

$real_t_id = $teacher_info['teacher_id'] ?? 'N/A';
$db_profile_img = $teacher_info['profile_image'] ?? ''; 

// ៣. ទាញស្ថិតិ៖ ចំនួនសិស្សសរុប និងចំនួនថ្នាក់ដែលគ្រូបង្រៀន
$count_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM students"))['total'];
$count_classes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT class_id) as total FROM timetable WHERE teacher_id = '$real_t_id' AND is_deleted = 0"))['total'] ?? 0;

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
                <h2 class="text-lg font-bold text-slate-800 italic uppercase tracking-tight">Teacher Portal</h2>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right hidden sm:block">
                    <p class="text-base font-black text-slate-900 leading-tight"><?php echo htmlspecialchars($_SESSION['full_name']); ?></p>
                    <p class="text-[11px] text-blue-500 font-bold uppercase italic">
                        Teacher ID: <?php echo $real_t_id; ?>
                    </p>
                </div>

                <div class="relative group">
                    <div class="w-16 h-16 rounded-2xl overflow-hidden border-2 border-slate-100 shadow-sm group-hover:border-blue-500 transition-all bg-slate-100 flex items-center justify-center">
                        <?php 
                            $path = "../../assets/uploads/teachers/";
                            // ប្រើ time() query string ដើម្បីឱ្យ Browser Update រូបភាពថ្មីភ្លាមៗ
                            if (!empty($db_profile_img) && file_exists($path . $db_profile_img)) {
                                $display_img = $path . $db_profile_img . "?v=" . time();
                            } else {
                                $display_img = $path . 'default_user.png';
                            }
                        ?>
                        <img src="<?= $display_img ?>" class="w-full h-full object-cover" onerror="this.src='../../assets/uploads/teachers/default_user.png'">
                    </div>
                    
                    <form action="../../actions/teachers/upload_profile.php" method="POST" enctype="multipart/form-data" id="teacherUploadForm" class="absolute -bottom-1 -right-1">
                        <label class="w-7 h-7 bg-blue-600 text-white rounded-lg flex items-center justify-center cursor-pointer shadow-lg border-2 border-white hover:bg-slate-900 transition-all">
                            <i class="fas fa-camera text-[10px]"></i>
                            <input type="file" name="profile_img" class="hidden" accept="image/*" onchange="document.getElementById('teacherUploadForm').submit()">
                        </label>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 bg-[#f8fafc] custom-scrollbar">
            <div class="w-full space-y-10">
                
                <div class="w-full bg-gradient-to-br from-slate-900 via-slate-800 to-blue-900 rounded-[3rem] p-10 md:p-16 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10">
                        <h2 class="text-4xl md:text-6xl font-black italic uppercase tracking-wider mb-4">សួស្តី លោកគ្រូ!</h2>
                        <p class="text-blue-200 text-lg md:text-2xl font-medium opacity-90">រីករាយដែលបានជួបលោកអ្នកត្រឡប់មកវិញ</p>
                    </div>
                    <i class="fas fa-chalkboard-teacher absolute -right-10 -bottom-10 text-[18rem] text-white/5 transform -rotate-12"></i>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 w-full">
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[10px] border-l-blue-600 hover:scale-[1.02] transition-all duration-300">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest">ថ្នាក់បង្រៀន</p>
                        <h3 class="text-4xl font-black text-slate-800 mt-3 italic"><?= str_pad($count_classes, 2, '0', STR_PAD_LEFT) ?> ថ្នាក់</h3>
                    </div>
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[10px] border-l-purple-500 hover:scale-[1.02] transition-all duration-300">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest">សិស្សសរុប</p>
                        <h3 class="text-4xl font-black text-slate-800 mt-3 italic"><?= $count_students ?> នាក់</h3>
                    </div>
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[10px] border-l-emerald-500 hover:scale-[1.02] transition-all duration-300">
                        <p class="text-slate-400 text-xs font-black uppercase tracking-widest">វត្តមានថ្ងៃនេះ</p>
                        <h3 class="text-4xl font-black text-slate-800 mt-3 italic">៩៨.៥%</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 w-full">
                    <div class="lg:col-span-2 bg-white rounded-[3rem] shadow-sm border border-slate-100 p-10">
                         <h3 class="font-black text-slate-800 text-2xl uppercase italic mb-6">សេចក្តីជូនដំណឹង</h3>
                         <p class="text-slate-400 italic">មិនទាន់មានការជូនដំណឹងថ្មីសម្រាប់ថ្ងៃនេះទេ...</p>
                    </div>
                    
                    <div class="bg-slate-900 rounded-[3rem] shadow-xl p-10 flex flex-col items-center justify-center text-center text-white relative overflow-hidden group">
                         <div class="w-20 h-20 bg-blue-500/20 rounded-3xl flex items-center justify-center text-blue-400 text-3xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all">
                             <i class="fas fa-star"></i>
                         </div>
                         <h3 class="font-black text-xl uppercase italic mb-2 tracking-wide">បញ្ចូលពិន្ទុសិស្ស</h3>
                         <p class="text-slate-400 text-sm mb-8 px-4">គ្រប់គ្រង និងបញ្ចូលពិន្ទុប្រចាំខែសម្រាប់សិស្ស</p>
                         <a href="my_classes.php" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg active:scale-95">ជ្រើសរើសថ្នាក់</a>
                    </div>
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