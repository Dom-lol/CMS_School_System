<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. បម្លែងពី user_id ទៅជា teacher_id ឱ្យបានត្រឹមត្រូវ [cite: 2026-01-20]
$u_id = $_SESSION['user_id'];
$t_query = mysqli_query($conn, "SELECT teacher_id FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$t_data = mysqli_fetch_assoc($t_query);
$real_t_id = $t_data['teacher_id'] ?? 0;

// ២. ទទួលយក class_id និង date ពីការជ្រើសរើស (Default គឺថ្ងៃនេះ) [cite: 2026-01-20]
$class_id = $_GET['class_id'] ?? 0;
$date = $_GET['date'] ?? date('Y-m-d');

// ៣. ទាញយកបញ្ជីថ្នាក់ដែលគ្រូម្នាក់នេះត្រូវបង្រៀន [cite: 2026-01-20]
$query = "SELECT DISTINCT t.class_id, c.class_name 
          FROM timetable t 
          INNER JOIN classes c ON t.class_id = c.id 
          WHERE t.teacher_id = '$real_t_id'";
$classes_query = mysqli_query($conn, $query);

// ៤. ទាញយកបញ្ជីឈ្មោះសិស្សក្នុងថ្នាក់ដែលបានជ្រើសរើស [cite: 2026-01-20]
$students = null; 
if ($class_id > 0) {
    $st_query = "SELECT id, full_name, student_id, gender FROM students 
                 WHERE class_id = '$class_id' AND status = 'Active' 
                 ORDER BY full_name ASC";
    $students = mysqli_query($conn, $st_query);
}

include '../../includes/header.php'; 
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    <?php include '../../includes/sidebar_teacher.php'; ?>
    
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
        <header class="bg-white border-b-2 border-slate-100 min-h-24 py-4 flex flex-col md:flex-row items-center justify-between px-6 md:px-10 shrink-0 shadow-sm gap-4">
            <div class="text-center md:text-left">
                <h2 class="text-xl font-black text-slate-800 uppercase italic leading-none">ស្រង់វត្តមានសិស្ស</h2>
                <p class="text-[10px] text-blue-500 font-bold uppercase mt-1">គ្រូ ID: <?= $real_t_id ?> | ថ្ងៃទី: <?= date('d-M-Y', strtotime($date)) ?></p>
            </div>

            <form method="GET" class="flex flex-wrap justify-center items-center gap-2">
                <select name="class_id" onchange="this.form.submit()" class="bg-slate-100 border-none rounded-xl px-4 py-2 font-bold text-sm outline-none cursor-pointer hover:bg-slate-200 transition-all">
                    <option value="">--- ជ្រើសរើសថ្នាក់ ---</option>
                    <?php mysqli_data_seek($classes_query, 0); while($c = mysqli_fetch_assoc($classes_query)): ?>
                        <option value="<?= $c['class_id'] ?>" <?= $class_id == $c['class_id'] ? 'selected' : '' ?>>
                            ថ្នាក់ទី <?= $c['class_name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="date" name="date" value="<?= $date ?>" onchange="this.form.submit()" class="bg-slate-100 border-none rounded-xl px-4 py-2 font-bold text-sm outline-none cursor-pointer hover:bg-slate-200 transition-all">
            </form>
        </header>

        <main class="flex-1 overflow-y-auto p-4 md:p-10 custom-scrollbar">
            <?php if ($class_id > 0 && $students && mysqli_num_rows($students) > 0): ?>
            
            <form action="../../actions/teachers/save_attendance.php" method="POST">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                <input type="hidden" name="date" value="<?= $date ?>">

                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mb-20">
                    <div class="overflow-x-auto"> <table class="w-full text-left min-w-[500px]">
                            <thead class="bg-slate-900 text-white">
                                <tr>
                                    <th class="p-6 font-bold uppercase text-[10px] tracking-widest">ឈ្មោះសិស្ស & ID</th>
                                    <th class="p-6 font-bold uppercase text-[10px] tracking-widest text-center">ស្ថានភាពវត្តមាន (<?= $date ?>)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php while($row = mysqli_fetch_assoc($students)): ?>
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="p-6">
                                        <div class="font-bold text-slate-800 uppercase italic leading-tight"><?= $row['full_name'] ?></div>
                                        <div class="text-[9px] text-slate-400 font-black uppercase mt-1">Student ID: <?= $row['student_id'] ?> (<?= $row['gender'] ?>)</div>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex justify-center items-center gap-4 md:gap-8">
                                            <label class="flex flex-col md:flex-row items-center gap-1 md:gap-2 cursor-pointer group">
                                                <input type="radio" name="att[<?= $row['id'] ?>]" value="present" checked class="w-5 h-5 accent-green-500 cursor-pointer">
                                                <span class="text-[9px] font-black text-slate-500 uppercase italic group-hover:text-green-600">មក</span>
                                            </label>
                                            <label class="flex flex-col md:flex-row items-center gap-1 md:gap-2 cursor-pointer group">
                                                <input type="radio" name="att[<?= $row['id'] ?>]" value="absent" class="w-5 h-5 accent-red-500 cursor-pointer">
                                                <span class="text-[9px] font-black text-slate-500 uppercase italic group-hover:text-red-600">អវត្តមាន</span>
                                            </label>
                                            <label class="flex flex-col md:flex-row items-center gap-1 md:gap-2 cursor-pointer group">
                                                <input type="radio" name="att[<?= $row['id'] ?>]" value="permission" class="w-5 h-5 accent-orange-500 cursor-pointer">
                                                <span class="text-[9px] font-black text-slate-500 uppercase italic group-hover:text-orange-600">ច្បាប់</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="fixed bottom-10 right-10 z-50">
                    <button type="submit" class="bg-blue-600 text-white px-10 py-5 rounded-full font-black uppercase text-xs tracking-widest shadow-2xl hover:bg-slate-900 transition-all hover:-translate-y-1 active:scale-95 flex items-center gap-3">
                        <i class="fas fa-save text-lg"></i>
                        <span class="hidden md:inline">រក្សាទុកវត្តមានថ្ងៃទី <?= $date ?></span>
                    </button>
                </div>
            </form>

            <?php else: ?>
                <div class="h-full flex flex-col items-center justify-center border-4 border-dashed border-slate-100 rounded-[3rem] p-20 opacity-60">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-calendar-day text-4xl text-slate-200"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-300 uppercase italic text-center">
                        <?= ($class_id > 0) ? "មិនទាន់មានសិស្សក្នុងថ្នាក់នេះ" : "សូមជ្រើសរើសថ្នាក់ដើម្បីស្រង់វត្តមាន"; ?>
                    </h3>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>