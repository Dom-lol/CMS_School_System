<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

/**
 * ២. កំណត់យក teacher_id ដោយផ្ទាល់ (ឧទាហរណ៍៖ លេខ 1)
 * ប្រសិនបើលោកគ្រូទុក teacher_id ក្នុង Session ពេល Login គឺប្រើ $_SESSION['teacher_id']
 */
$real_t_id = $_SESSION['teacher_id'] ?? 1; 

// ៣. ទាញយកមុខវិជ្ជាពី timetable ដោយប្រើ teacher_id ផ្ទាល់
$sql = "SELECT DISTINCT 
            t.class_id, 
            t.subject_id, 
            c.class_name, 
            s.subject_name 
        FROM timetable t
        INNER JOIN classes c ON t.class_id = c.id
        INNER JOIN subjects s ON t.subject_id = s.id
        WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0";

$res = mysqli_query($conn, $sql);

include '../../includes/header.php'; 
?>

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

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (mysqli_num_rows($res) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($res)): ?>
                        <a href="enter_scores.php?class_id=<?= $row['class_id'] ?>&subject_id=<?= $row['subject_id'] ?>" 
                           class="group bg-white p-10 rounded-[2.5rem] border-2 border-slate-100 hover:border-blue-600 transition-all text-center relative shadow-sm">
                            <h2 class="text-5xl font-black text-slate-800 mb-2"><?= $row['class_name'] ?></h2>
                            <p class="text-blue-600 font-bold uppercase italic text-xs mb-6"><?= $row['subject_name'] ?></p>
                            
                            <div class="bg-slate-50 rounded-full py-2 text-[10px] font-black uppercase text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                ចុចដើម្បីបញ្ចូលពិន្ទុ
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-full bg-white rounded-[3rem] border-4 border-dashed border-slate-100 p-24 text-center">
                        <i class="fas fa-search text-5xl text-slate-200 mb-6"></i>
                        <h3 class="text-xl font-black text-slate-400 italic uppercase">រកមិនឃើញមុខវិជ្ជា</h3>
                        <p class="text-slate-400 mt-4 text-sm max-w-md mx-auto">
                            សូមពិនិត្យក្នុងតារាង <strong class="text-blue-600">timetable</strong> ថាមាន <strong class="text-slate-800">teacher_id = <?= $real_t_id ?></strong> ដែរឬទេ?
                        </p>
                    </div>
                <?php endif; ?>
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