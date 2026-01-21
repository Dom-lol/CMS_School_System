<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ត្រួតពិនិត្យ Parameter (បើអត់មាន ID ឱ្យទៅ scores.php វិញ ដើម្បីកុំឱ្យ Loop)
$class_id = $_GET['class_id'] ?? 0;
$subject_id = $_GET['subject_id'] ?? 0;
$month = $_GET['month'] ?? date('n');

if (!$class_id || !$subject_id) {
    header("Location: scores.php"); 
    exit();
}

// ២. ទាញព័ត៌មានថ្នាក់ និងមុខវិជ្ជា
$info_q = mysqli_query($conn, "SELECT c.class_name, s.subject_name FROM classes c, subjects s WHERE c.id = '$class_id' AND s.id = '$subject_id'");
$info = mysqli_fetch_assoc($info_q);

// ៣. ទាញបញ្ជីឈ្មោះសិស្ស និងពិន្ទុពីតារាង scores (តាម DB របស់លោកគ្រូ)
$st_query = "SELECT s.id, s.full_name, sc.score_value 
             FROM students s 
             LEFT JOIN scores sc ON s.id = sc.student_id AND sc.subject_id = '$subject_id' AND sc.month = '$month'
             WHERE s.class_id = '$class_id' ORDER BY s.full_name ASC";
$students = mysqli_query($conn, $st_query);

include '../../includes/header.php';
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    <?php include '../../includes/sidebar_teacher.php'; ?>
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <a href="scores.php" class="p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200"><i class="fas fa-arrow-left"></i></a>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 uppercase italic">បញ្ចូលពិន្ទុ៖ <?= $info['subject_name'] ?? 'N/A' ?></h2>
                    <p class="text-xs text-blue-500 font-bold uppercase">ថ្នាក់៖ <?= $info['class_name'] ?? 'N/A' ?></p>
                </div>
            </div>
            <form method="GET" class="flex items-center gap-3">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                <select name="month" onchange="this.form.submit()" class="bg-slate-100 border-none rounded-xl px-4 py-2 font-bold text-slate-700 outline-none focus:ring-2 focus:ring-blue-500">
                    <?php 
                    $months_kh = ["មករា", "កុម្ភៈ", "មីនា", "មេសា", "ឧសភា", "មិថុនា", "កក្កដា", "សីហា", "កញ្ញា", "តុលា", "វិច្ឆិកា", "ធ្នូ"];
                    for($i=1; $i<=12; $i++): ?>
                        <option value="<?= $i ?>" <?= ($month == $i) ? 'selected' : '' ?>>ខែ <?= $months_kh[$i-1] ?></option>
                    <?php endfor; ?>
                </select>
            </form>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <div class="max-w-5xl mx-auto">
                <form action="../../backend/teacher/save_action.php" method="POST">
                    <input type="hidden" name="class_id" value="<?= $class_id ?>">
                    <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                    <input type="hidden" name="month" value="<?= $month ?>">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <table class="w-full text-left">
                            <thead class="bg-slate-900 text-white">
                                <tr>
                                    <th class="p-6 font-bold uppercase text-[10px] tracking-widest">ឈ្មោះសិស្ស</th>
                                    <th class="p-6 font-bold uppercase text-[10px] tracking-widest text-center w-40">ពិន្ទុ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php while($row = mysqli_fetch_assoc($students)): ?>
                                <tr class="hover:bg-blue-50/50 transition-all">
                                    <td class="p-6">
                                        <div class="font-bold text-slate-800"><?= $row['full_name'] ?></div>
                                        <div class="text-[10px] text-slate-400 font-bold uppercase">ID: #<?= $row['id'] ?></div>
                                    </td>
                                    <td class="p-6 text-center">
                                        <input type="number" name="scores[<?= $row['id'] ?>]" value="<?= $row['score_value'] ?>" min="0" max="100" step="0.1" class="w-24 p-3 rounded-2xl border-2 border-slate-100 text-center font-black text-blue-600 focus:border-blue-500 outline-none transition-all" placeholder="0.0">
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-10 py-4 rounded-[2rem] font-black uppercase text-xs tracking-[0.2em] shadow-xl hover:bg-slate-900 transition-all active:scale-95">
                            <i class="fas fa-save mr-2"></i> រក្សាទុកពិន្ទុទាំងអស់
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>