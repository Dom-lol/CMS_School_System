<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ឆែកសិទ្ធិចូលប្រើប្រាស់
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

// ២. ទាញយកព័ត៌មានគ្រូ
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);
$real_t_id = $teacher_info['teacher_id'] ?? 'N/A';
$t_full_name = $teacher_info['full_name'] ?? $_SESSION['full_name'];

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

// ៥. ទាញយកបញ្ជីសិស្ស
$student_sql = "SELECT id, student_id, full_name, gender FROM students 
                WHERE class_id = '$class_id' AND status = 'Active' 
                ORDER BY gender DESC, full_name ASC";
$students = mysqli_query($conn, $student_sql);

// ៦. បញ្ជីខែខ្មែរ
$months_kh = ["01"=>"មករា", "02"=>"កុម្ភៈ", "03"=>"មីនា", "04"=>"មេសា", "05"=>"ឧសភា", "06"=>"មិថុនា", "07"=>"កក្កដា", "08"=>"សីហា", "09"=>"កញ្ញា", "10"=>"តុលា", "11"=>"វិច្ឆិកា", "12"=>"ធ្នូ"];
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Input Point - Adaptive UI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; -webkit-tap-highlight-color: transparent; }
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] h-screen flex overflow-hidden">

    <div class="hidden lg:block w-72 h-full bg-slate-900 shrink-0">
        <?php include '../../includes/sidebar_teacher.php'; ?>
    </div>

    <div class="flex-1 flex flex-col min-w-0 h-full relative">
        
        <header class="bg-white border-b border-slate-100 h-14 md:h-24 flex items-center justify-between px-4 md:px-10 shrink-0 z-40">
            <div class="flex items-center gap-2 md:gap-4">
                <a href="scores.php" class="w-8 h-8 md:w-12 md:h-12 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                    <i class="fas fa-chevron-left text-xs md:text-lg"></i>
                </a>
                <div class="leading-tight">
                    <h2 class="text-[11px] md:text-xl font-black text-slate-800 uppercase italic">Input Point</h2>
                    <p class="text-[8px] md:text-sm text-blue-600 font-bold uppercase italic">Class: <?= $c_name ?></p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right leading-none">
                    <p class="text-[12px] md:text-[20px] font-black text-slate-900"><?= htmlspecialchars($t_full_name); ?></p>
                    <p class="text-[9px] md:text-[12px] text-slate-400 font-bold italic mt-1 uppercase"><?= htmlspecialchars($subject_display) ?></p>
                </div>
                <div class="w-9 h-9 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-slate-50 bg-slate-100 shrink-0 shadow-sm">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        $img = $teacher_info['profile_image'];
                        $display_img = (!empty($img) && file_exists($path . $img)) ? $path . $img : "../../assets/img/default_user.png";
                    ?>
                    <img src="<?= $display_img ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-3 md:p-10 custom-scrollbar">
            
            <div class="bg-slate-900 p-5 md:p-12 rounded-2xl md:rounded-[3rem] text-white shadow-xl mb-4 md:mb-8 relative overflow-hidden border-b-4 md:border-b-8 border-blue-600">
                <div class="relative z-10">
                    <p class="text-[8px] md:text-[12px] opacity-50 uppercase font-black tracking-[0.2em] mb-1">Manual Entry Mode</p>
                    <h1 class="text-xl md:text-5xl font-black italic uppercase leading-none mb-2">ថ្នាក់ទី <?= $c_name ?></h1>
                    <div class="flex items-center gap-2">
                        <span class="bg-blue-600 text-[8px] md:text-[11px] px-2 py-0.5 rounded-full font-black uppercase">Subject</span>
                        <p class="text-blue-400 font-bold uppercase italic text-[10px] md:text-lg"><?= $subject_display ?></p>
                    </div>
                </div>
                <i class="fas fa-edit absolute -right-5 -bottom-5 text-6xl md:text-9xl opacity-10 -rotate-12"></i>
            </div>

            <form action="save_grades.php" method="POST">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                <input type="hidden" name="subject_id" value="<?= $subject_id ?>">

                <div class="flex flex-wrap gap-2 md:gap-4 mb-4 md:mb-8 bg-white p-3 md:p-6 rounded-xl md:rounded-[2rem] border border-slate-100 shadow-sm">
                    <select name="input_month" required class="flex-1 md:flex-none bg-slate-50 border-none rounded-lg md:rounded-xl px-4 md:px-8 py-2 md:py-4 text-[10px] md:text-sm font-black uppercase italic outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer">
                        <?php foreach ($months_kh as $m_num => $m_kh): ?>
                            <option value="<?= $m_num ?>" <?= ($m_num == date('m')) ? 'selected' : '' ?>>ខែ <?= $m_kh ?></option>
                        <?php endforeach; ?>
                    </select>
                    <select name="input_year" required class="w-24 md:w-40 bg-slate-50 border-none rounded-lg md:rounded-xl px-4 md:px-8 py-2 md:py-4 text-[10px] md:text-sm font-black uppercase italic outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer">
                        <option value="<?= date('Y') ?>"><?= date('Y') ?></option>
                        <option value="<?= date('Y')-1 ?>"><?= date('Y')-1 ?></option>
                    </select>
                </div>

                <div class="space-y-1.5 md:space-y-4 mb-28">
                    <?php if($students && mysqli_num_rows($students) > 0): $i=1; ?>
                        <?php while($row = mysqli_fetch_assoc($students)): ?>
                        <div class="bg-white px-3 py-2 md:px-8 md:py-6 rounded-lg md:rounded-[2.5rem] border border-slate-50 md:border-slate-100 shadow-sm flex items-center justify-between gap-3 hover:border-blue-200 transition-all group">
                            
                            <div class="flex items-center gap-2 md:gap-8 overflow-hidden">
                                <span class="text-[15px] md:text-2xl font-black text-slate-500 italic shrink-0 w-5 md:w-10 ">
                                    <?= sprintf("%02d", $i++) ?>
                                </span>
                                
                                <div class="truncate">
                                    <div class="text-[15px] md:text-2xl font-black text-slate-800 uppercase italic leading-tight truncate">
                                        <?= htmlspecialchars($row['full_name']) ?>
                                    </div>
                                    <div class="flex items-center gap-2 mt-0.5 md:mt-1">
                                        <span class="text-[12px] md:text-[11px] text-blue-500 font-bold uppercase tracking-tighter">ID: <?= $row['student_id'] ?></span>
                                        <span class="text-[12px] md:text-[10px] px-1.5 py-0.5 md:px-3 md:py-1 rounded-[4px] md:rounded-lg font-black uppercase italic <?= ($row['gender'] == 'Female' || $row['gender'] == 'ស្រី') ? 'bg-pink-50 text-pink-500' : 'bg-indigo-50 text-indigo-500' ?>">
                                            <?= $row['gender'] ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0">
                                <input type="number" name="grade[<?= $row['student_id'] ?>]" 
                                       min="0" max="100" step="0.1" required placeholder="PT"
                                       class="w-16 md:w-48 bg-slate-50 border-2 border-transparent focus:border-blue-600 focus:bg-white rounded-lg md:rounded-[1.8rem] py-2 md:py-5 text-center text-[15px] md:text-3xl font-black text-slate-800 outline-none transition-all shadow-inner">
                            </div>

                        </div>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </div>

                <div class="fixed bottom-4 left-4 right-4 md:bottom-10 md:right-10 md:left-auto z-50">
                    <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-8 py-4 md:px-14 md:py-6 rounded-xl md:rounded-full font-black uppercase text-[10px] md:text-xs tracking-[0.2em] shadow-2xl hover:bg-slate-900 transition-all active:scale-95 flex items-center justify-center gap-3">
                        <i class="fas fa-save text-lg"></i>
                        <span>រក្សាទុកទិន្នន័យ</span>
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

</body>
</html>