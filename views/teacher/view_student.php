<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ចាប់យក class_id ពី URL
$class_id = $_GET['class_id'] ?? 0;
$subject_id = $_GET['subject_id'] ?? 0;

// ២. ទាញព័ត៌មានគ្រូ
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);
$real_t_id = $teacher_info['teacher_id'] ?? 'N/A';
$display_name = $teacher_info['full_name'] ?? 'គ្រូបង្រៀន';

// ៣. ទាញឈ្មោះថ្នាក់ និងមុខវិជ្ជា
$info_sql = "SELECT c.class_name, s.subject_name FROM classes c, subjects s WHERE c.id = '$class_id' AND s.id = '$subject_id' LIMIT 1";
$info = mysqli_fetch_assoc(mysqli_query($conn, $info_sql));

// ៤. ទាញបញ្ជីសិស្ស (មិនយក academic_year មកបង្ហាញក្នុងតារាងទេ)
$st_query = "SELECT student_id, full_name, full_name_en, gender FROM students WHERE class_id = '$class_id' AND status = 'Active' ORDER BY full_name ASC";
$students = mysqli_query($conn, $st_query);

include '../../includes/header.php'; 
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8fafc; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="flex flex-col lg:flex-row h-screen w-full overflow-hidden">
    
    <div class="hidden lg:block">
        <?php include '../../includes/sidebar_teacher.php'; ?>
    </div>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
        <header class="bg-white border-b border-slate-100 h-20 md:h-24 flex items-center justify-between px-4 md:px-10 shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-3">
                <a href="my_classes.php" class="p-2 md:p-3 bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-all">
                    <i class="fas fa-arrow-left text-lg"></i>
                </a>
                <h2 class="text-base md:text-xl font-black text-slate-800 uppercase italic tracking-tighter truncate">Student List</h2>
            </div>

            <div class="flex items-center gap-3 md:gap-5">
                <div class="text-right hidden sm:block">
                    <p class="text-sm md:text-lg font-black text-slate-900 leading-tight truncate"><?= htmlspecialchars($display_name) ?></p>
                    <p class="text-[10px] md:text-[11px] text-blue-500 font-bold uppercase italic">ID: <?= $real_t_id ?></p>
                </div>
                <div class="w-10 h-10 md:w-14 md:h-14 rounded-full overflow-hidden border-2 border-slate-100 shadow-sm shrink-0">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        $img = (!empty($teacher_info['profile_image']) && file_exists($path . $teacher_info['profile_image'])) ? $path . $teacher_info['profile_image'] : $path . 'default_user.png';
                    ?>
                    <img src="<?= $img ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-10 custom-scrollbar">
            
            <div class="mb-6 md:mb-10 bg-gradient-to-r from-slate-900 to-slate-800 p-6 md:p-10 rounded-[2rem] md:rounded-[3rem] text-white shadow-xl relative overflow-hidden border-b-4 md:border-b-8 border-blue-600">
                <div class="relative z-10">
                    <p class="text-blue-400 font-black uppercase italic tracking-[0.1em] text-[9px] md:text-[10px] mb-1">កម្រិតថ្នាក់បង្រៀន</p>
                    <h1 class="text-xl md:text-3xl font-black italic uppercase">
                        ថ្នាក់ទី <?= $info['class_name'] ?? '---' ?> 
                        <span class="text-slate-500 mx-1 md:mx-2">|</span> 
                        <span class="text-blue-400"><?= $info['subject_name'] ?? '---' ?></span>
                    </h1>
                </div>
                <i class="fas fa-user-graduate absolute right-4 md:right-10 top-1/2 -translate-y-1/2 text-5xl md:text-8xl text-white/5 rotate-12"></i>
            </div>

            <div class="bg-white rounded-[1.5rem] md:rounded-[3rem] shadow-md border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="p-6 md:p-8 font-black uppercase text-[10px] md:text-[11px] text-slate-400 tracking-widest w-1/4">អត្តលេខ</th>
                                <th class="p-6 md:p-8 font-black uppercase text-[10px] md:text-[11px] text-slate-400 tracking-widest w-1/2">ឈ្មោះសិស្ស</th>
                                <th class="p-6 md:p-8 font-black uppercase text-[10px] md:text-[11px] text-slate-400 tracking-widest text-right">ភេទ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php if ($students && mysqli_num_rows($students) > 0): ?>
                                <?php while($row = mysqli_fetch_assoc($students)): ?>
                                <tr class="hover:bg-blue-50/40 transition-all">
                                    <td class="p-6 md:p-8 text-xs md:text-sm font-black text-blue-600 italic">
                                        #<?= htmlspecialchars($row['student_id']) ?>
                                    </td>
                                    <td class="p-6 md:p-8">
                                        <div class="font-bold text-slate-800 uppercase tracking-tight text-sm md:text-base"><?= htmlspecialchars($row['full_name']) ?></div>
                                        <div class="text-[9px] md:text-[10px] text-slate-400 font-bold uppercase italic"><?= htmlspecialchars($row['full_name_en']) ?></div>
                                    </td>
                                    <td class="p-6 md:p-8 text-right">
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] md:text-xs font-bold"><?= $row['gender'] ?></span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="p-20 text-center">
                                        <div class="flex flex-col items-center opacity-20">
                                            <i class="fas fa-folder-open text-4xl mb-4"></i>
                                            <p class="font-black uppercase italic tracking-widest text-xs">មិនទាន់មានទិន្នន័យ</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="h-10 lg:hidden"></div>
        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>