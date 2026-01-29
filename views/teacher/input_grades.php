<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. បញ្ជាក់សិទ្ធិចូលប្រើប្រាស់
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

// ២. ទាញយកព័ត៌មានគ្រូ
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);
$real_t_id = $teacher_info['teacher_id'] ?? 'N/A';
$t_full_name = $teacher_info['full_name'] ?? 'Teacher';

// ៣. ចាប់តម្លៃពី URL
$class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 0;
$subject_id = isset($_GET['subject_id']) ? (int)$_GET['subject_id'] : 0;

// ៤. ទាញយកឈ្មោះថ្នាក់ និងមុខវិជ្ជា
$info_q = mysqli_query($conn, "
    SELECT c.class_name, s.subject_name 
    FROM classes c, subjects s 
    WHERE c.id = '$class_id' AND s.id = '$subject_id'
");
$info = mysqli_fetch_assoc($info_q);
$c_name = $info['class_name'] ?? 'N/A';
$subject_display = $info['subject_name'] ?? 'N/A';

// ៥. ទាញយកបញ្ជីសិស្ស (ប្រើ TRIM ការពារបញ្ហា No Data)
$student_sql = "SELECT id, student_id, full_name, gender FROM students 
                WHERE (class_id = '$class_id' OR TRIM(class_name) = TRIM('$c_name'))
                AND status = 'Active' 
                ORDER BY gender DESC, full_name ASC";
$students = mysqli_query($conn, $student_sql);

// ៦. បញ្ជីខែខ្មែរ
$months_kh = [
    "01"=>"មករា", "02"=>"កុម្ភៈ", "03"=>"មីនា", "04"=>"មេសា", 
    "05"=>"ឧសភា", "06"=>"មិថុនា", "07"=>"កក្កដា", "08"=>"សីហា", 
    "09"=>"កញ្ញា", "10"=>"តុលា", "11"=>"វិច្ឆិកា", "12"=>"ធ្នូ"
];

// ៧. ទាញបញ្ជីថ្នាក់សម្រាប់ Dropdown ប្តូរថ្នាក់
$all_classes_res = mysqli_query($conn, "
    SELECT DISTINCT t.class_id, c.class_name 
    FROM timetable t 
    INNER JOIN classes c ON t.class_id = c.id 
    WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0
");
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>បញ្ចូលពិន្ទុ - <?= htmlspecialchars($c_name) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8fafc; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="h-screen overflow-hidden">

<div class="flex h-full w-full">
    <div class="hidden lg:block w-72 bg-slate-900 shadow-2xl z-50">
        <?php include '../../includes/sidebar_teacher.php'; ?>
    </div>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-white border-b-4 border-blue-600 shadow-md px-6 py-4 shrink-0 z-40">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full border-4 border-blue-50 overflow-hidden bg-slate-100">
                        <?php 
                            $path = "../../assets/uploads/teachers/";
                            $img = $teacher_info['profile_image'];
                            $display_img = (!empty($img) && file_exists($path . $img)) ? $path . $img : "../../assets/img/default_user.png";
                        ?>
                        <img src="<?= $display_img ?>" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-blue-800 italic uppercase"><?= htmlspecialchars($t_full_name) ?></h2>
                        <p class="text-[10px] font-bold text-slate-400 tracking-widest">ID: #<?= $real_t_id ?></p>
                    </div>
                </div>

                <form method="GET" class="flex items-center gap-2">
                    <input type="hidden" name="subject_id" value="<?= $subject_id ?>">
                    <select name="class_id" onchange="this.form.submit()" 
                            class="bg-blue-600 text-white font-black rounded-xl px-5 py-2.5 shadow-lg outline-none cursor-pointer text-sm">
                        <?php if($all_classes_res): mysqli_data_seek($all_classes_res, 0); ?>
                            <?php while($c = mysqli_fetch_assoc($all_classes_res)): ?>
                                <option value="<?= $c['class_id'] ?>" <?= $class_id == $c['class_id'] ? 'selected' : '' ?>>
                                    ថ្នាក់ទី <?= $c['class_name'] ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-xl flex items-center justify-between border-b-8 border-blue-600 mb-8 relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-[10px] opacity-50 uppercase font-black tracking-[0.2em]">Data Entry Mode</p>
                    <h1 class="text-4xl font-black italic uppercase">ថ្នាក់ទី <?= $c_name ?></h1>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="bg-blue-600 text-[10px] px-3 py-1 rounded-full font-black uppercase italic">មុខវិជ្ជា</span>
                        <p class="text-blue-400 font-bold uppercase italic tracking-wider"><?= $subject_display ?></p>
                    </div>
                </div>
                <i class="fas fa-edit text-8xl absolute -right-5 -bottom-5 opacity-10 -rotate-12"></i>
            </div>

            <?php if ($students && mysqli_num_rows($students) > 0): ?>
            <form action="save_grades.php" method="POST">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                <input type="hidden" name="subject_id" value="<?= $subject_id ?>">

                <div class="flex flex-wrap items-center gap-4 mb-8 bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100">
                    <div class="flex items-center gap-3 border-r pr-6 border-slate-100">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                        <span class="text-[10px] font-black uppercase text-slate-400 italic">បញ្ចូលសម្រាប់ខែ:</span>
                    </div>
                    
                    <select name="input_month" required class="bg-slate-50 border-none rounded-xl px-8 py-3 font-black text-xs uppercase italic outline-none focus:ring-4 focus:ring-blue-100 transition-all cursor-pointer">
                        <?php foreach ($months_kh as $m_num => $m_name): ?>
                            <option value="<?= $m_num ?>" <?= ($m_num == date('m')) ? 'selected' : '' ?>>
                                ខែ <?= $m_name ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select name="input_year" required class="bg-slate-50 border-none rounded-xl px-8 py-3 font-black text-xs uppercase italic outline-none focus:ring-4 focus:ring-blue-100 transition-all cursor-pointer">
                        <?php for($y = date('Y'); $y >= 2024; $y--): ?>
                            <option value="<?= $y ?>" <?= ($y == date('Y')) ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-24">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr class="text-[11px] font-black uppercase text-slate-400 tracking-widest">
                                <th class="p-8 text-center w-24">ល.រ</th>
                                <th class="p-8">ព័ត៌មានសិស្ស</th>
                                <th class="p-8 text-center">ភេទ</th>
                                <th class="p-8 text-center w-64">ពិន្ទុសរុប (100)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $i=1; while($row = mysqli_fetch_assoc($students)): ?>
                                <tr class="hover:bg-blue-50/40 transition-all group">
                                    <td class="p-6 text-center font-black text-slate-300 italic text-2xl group-hover:text-blue-600">
                                        <?= sprintf("%02d", $i++) ?>
                                    </td>
                                    <td class="p-6">
                                        <div class="text-xl font-black text-slate-800 uppercase italic leading-tight">
                                            <?= htmlspecialchars($row['full_name']) ?>
                                        </div>
                                        <div class="text-[10px] text-blue-500 font-bold italic mt-1 uppercase">ID: <?= $row['student_id'] ?></div>
                                    </td>
                                    <td class="p-6 text-center">
                                        <span class="px-5 py-2 rounded-xl text-[10px] font-black uppercase italic <?= ($row['gender'] == 'Female' || $row['gender'] == 'ស្រី') ? 'bg-pink-100 text-pink-600' : 'bg-indigo-100 text-indigo-600' ?>">
                                            <?= $row['gender'] ?>
                                        </span>
                                    </td>
                                    <td class="p-6">
                                        <input type="number" name="grade[<?= $row['student_id'] ?>]" min="0" max="100" step="0.1" required placeholder="0.0"
                                               class="w-full bg-slate-50 border-4 border-transparent rounded-[1.5rem] px-6 py-4 text-center text-2xl font-black text-slate-800 focus:bg-white focus:border-blue-600 outline-none transition-all shadow-inner focus:shadow-2xl">
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="fixed bottom-8 right-8 z-50">
                    <button type="submit" class="bg-blue-600 text-white px-12 py-5 rounded-full font-black uppercase text-xs tracking-[0.2em] shadow-2xl hover:bg-slate-900 transition-all hover:-translate-y-2 flex items-center gap-4 group">
                        <i class="fas fa-save text-xl group-hover:animate-bounce"></i>
                        <span>រក្សាទុកទិន្នន័យពិន្ទុ</span>
                    </button>
                </div>
            </form>
            <?php else: ?>
                <div class="bg-white rounded-[3rem] p-24 flex flex-col items-center justify-center border-4 border-dashed border-slate-100 opacity-80 text-center shadow-inner">
                    <div class="w-32 h-32 bg-slate-50 rounded-full flex items-center justify-center mb-8 shadow-sm">
                        <i class="fas fa-users-slash text-6xl text-slate-200"></i>
                    </div>
                    <h2 class="text-3xl font-black text-slate-400 italic uppercase tracking-tighter">រកមិនឃើញទិន្នន័យសិស្ស</h2>
                    <p class="text-slate-300 mt-3 text-lg font-medium italic">ថ្នាក់ទី <span class="text-blue-500 font-black"><?= htmlspecialchars($c_name) ?></span> មិនទាន់មានបញ្ជីសិស្សនៅឡើយ។</p>
                    <div class="mt-8 flex gap-4">
                        <a href="index.php" class="px-8 py-3 bg-slate-100 text-slate-500 rounded-2xl font-black text-[10px] uppercase hover:bg-slate-200 transition-all">ត្រឡប់ក្រោយ</a>
                        <button onclick="window.location.reload()" class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-black text-[10px] uppercase shadow-lg hover:bg-blue-700 transition-all">ព្យាយាមម្តងទៀត</button>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

</body>
</html>