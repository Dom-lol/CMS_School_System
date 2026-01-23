<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ទាញយក teacher_id (លេខ 1) ពី user_id (លេខ 13)
$u_id = $_SESSION['user_id'];
$t_query = mysqli_query($conn, "SELECT teacher_id FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$t_data = mysqli_fetch_assoc($t_query);
$real_t_id = $t_data['teacher_id'] ?? 1;

$class_id = $_GET['class_id'] ?? 0;
$date = $_GET['date'] ?? date('Y-m-d');

// ២. ទាញបញ្ជីថ្នាក់បង្រៀនសម្រាប់លោកគ្រូ (ដើម្បីបង្ហាញក្នុង Select Option)
$query = "SELECT DISTINCT t.class_id, c.class_name 
          FROM timetable t 
          INNER JOIN classes c ON t.class_id = c.id 
          WHERE t.teacher_id = '$real_t_id'";
$classes_query = mysqli_query($conn, $query);

$students = null; 
if ($class_id > 0) {
    // ៣. ទាញឈ្មោះសិស្ស (ប្រើ full_name និង student_id ឱ្យត្រូវតាមរូបភាព)
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
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0">
            <h2 class="text-xl font-black text-slate-800 uppercase italic">ស្រង់វត្តមានសិស្ស ថ្នាក់ទី 7</h2>
            
            <form method="GET" class="flex items-center gap-3">
                <select name="class_id" onchange="this.form.submit()" class="bg-slate-100 border-none rounded-xl px-4 py-2 font-bold outline-none">
                    <option value="">--- ជ្រើសរើសថ្នាក់ ---</option>
                    <?php while($c = mysqli_fetch_assoc($classes_query)): ?>
                        <option value="<?= $c['class_id'] ?>" <?= $class_id == $c['class_id'] ? 'selected' : '' ?>>
                            ថ្នាក់ទី <?= $c['class_name'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <input type="date" name="date" value="<?= $date ?>" onchange="this.form.submit()" class="bg-slate-100 border-none rounded-xl px-4 py-2 font-bold outline-none">
            </form>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <?php if ($class_id > 0 && $students && mysqli_num_rows($students) > 0): ?>
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-900 text-white">
                            <tr>
                                <th class="p-6 font-bold uppercase text-[10px] tracking-widest">ឈ្មោះសិស្ស (Full Name)</th>
                                <th class="p-6 font-bold uppercase text-[10px] tracking-widest">ភេទ</th>
                                <th class="p-6 font-bold uppercase text-[10px] tracking-widest text-center"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php while($row = mysqli_fetch_assoc($students)): ?>
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="p-6">
                                    <div class="font-bold text-slate-800 uppercase"><?= $row['full_name'] ?></div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase">ID: <?= $row['student_id'] ?></div>
                                </td>
                                <td class="p-6 text-xs font-bold text-slate-500 uppercase italic">
                                    <?= $row['gender'] ?>
                                </td>
                               <!-- dob -->
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="h-full flex flex-col items-center justify-center border-4 border-dashed border-slate-100 rounded-[3rem] p-20 opacity-60">
                    <i class="fas fa-users text-6xl text-slate-200 mb-4"></i>
                    <h3 class="text-xl font-black text-slate-400 uppercase italic">មិនទាន់មានសិស្សក្នុងថ្នាក់នេះ</h3>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>