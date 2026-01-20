<?php 
// ១. បើក Session នៅជួរទី១ បំផុត
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ២. ភ្ជាប់ទៅកាន់ Database
require_once '../../config/db.php'; 

// ៣. ឆែកមើល Login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

// ៤. ទាញយកព័ត៌មានគ្រូដែលកំពុង Login
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_data = mysqli_fetch_assoc($teacher_query);
$t_id = $teacher_data['teacher_id'] ?? 0;
$t_name = $teacher_data['full_name'] ?? 'Unknown Teacher';

include '../../includes/header.php';
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    <?php include '../../includes/sidebar_teacher.php'; ?>

    <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0">
            <h2 class="text-lg font-bold text-slate-800 uppercase italic tracking-tight">ថ្នាក់រៀនរបស់ខ្ញុំ</h2>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-base font-black text-slate-900 leading-tight"><?= htmlspecialchars($t_name) ?></p>
                    <p class="text-[11px] text-blue-500 font-bold uppercase italic">Teacher ID: <?= $t_id ?></p>
                </div>
                <div class="w-12 h-12 bg-slate-200 rounded-full border-2 border-white shadow-sm overflow-hidden">
                    <img src="../../assets/img/default_user.png" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-10">
            <div class="relative w-full h-64 bg-gradient-to-br from-[#0f172a] to-[#334155] rounded-[3rem] p-12 overflow-hidden shadow-2xl mb-12">
                <div class="relative z-10">
                    <h1 class="text-6xl font-black text-white italic tracking-tighter mb-4 uppercase">My Classes</h1>
                    <p class="text-slate-300 font-medium text-lg italic">គ្រប់គ្រងបញ្ជីឈ្មោះសិស្ស និងការបញ្ចូលពិន្ទុតាមថ្នាក់រៀន</p>
                </div>
                <div class="absolute right-[-20px] bottom-[-40px] opacity-10">
                    <i class="fas fa-graduation-cap text-[280px] text-white rotate-12"></i>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php 
                // ៥. SQL Query កែសម្រួលថ្មី៖ Join ជាមួយ classes ដើម្បីយក class_name ពិតប្រាកដ
                // ប្រើ DISTINCT ដើម្បីកុំឱ្យជាន់ថ្នាក់ និងមុខវិជ្ជាដដែល
                $sql = "SELECT DISTINCT c.id as class_id, c.class_name, s.subject_name, s.id as sub_id
                        FROM timetable t 
                        INNER JOIN classes c ON t.class_id = c.id 
                        INNER JOIN subjects s ON t.subject_id = s.id 
                        WHERE t.teacher_id = '$t_id' AND t.is_deleted = 0";
                
                $class_result = mysqli_query($conn, $sql);

                if ($class_result && mysqli_num_rows($class_result) > 0): 
                    while ($row = mysqli_fetch_assoc($class_result)): ?>
                    
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col items-center text-center transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
                        <div class="w-20 h-20 bg-blue-50 rounded-3xl flex items-center justify-center mb-6">
                            <i class="fas fa-users text-3xl text-blue-600"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-1 italic">
                            ថ្នាក់: <?= htmlspecialchars($row['class_name']) ?>
                        </h3>
                        <p class="text-slate-400 font-bold text-[11px] uppercase tracking-[0.2em] mb-8 italic">
                            <?= htmlspecialchars($row['subject_name']) ?>
                        </p>
                        
                        <a href="view_students.php?class_id=<?= $row['class_id'] ?>&subject_id=<?= $row['sub_id'] ?>" 
                           class="w-full bg-[#0f172a] text-white py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-blue-600 transition-all shadow-lg shadow-slate-200">
                            VIEW STUDENTS <i class="fas fa-arrow-right text-[8px]"></i>
                        </a>
                    </div>

                <?php endwhile; else: ?>
                    <div class="col-span-full flex flex-col items-center justify-center py-20 opacity-40">
                        <div class="w-32 h-32 bg-slate-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-folder-open text-5xl text-slate-300"></i>
                        </div>
                        <h2 class="text-2xl font-black text-slate-800 italic mb-2">មិនទាន់មានទិន្នន័យបង្រៀន</h2>
                        <p class="text-slate-500 font-medium italic text-center">
                            លោកគ្រូមិនទាន់មានកាលវិភាគបង្រៀនក្នុងប្រព័ន្ធនៅឡើយទេ។<br>
                            សូមទាក់ទងរដ្ឋបាលដើម្បី Import កាលវិភាគចូល។
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>