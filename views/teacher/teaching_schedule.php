<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ឆែកសិទ្ធិចូលប្រើប្រាស់
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

// ២. ទាញទិន្នន័យគ្រូ
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);

$real_t_id = $teacher_info['teacher_id'] ?? 'N/A';
$t_full_name = $teacher_info['full_name'] ?? $_SESSION['full_name'];
$db_profile_img = $teacher_info['profile_image'] ?? ''; 

// ៣. ទាញយកឈ្មោះមុខវិជ្ជាដំបូងគេដើម្បីបង្ហាញក្នុង Header
$subject_header_query = mysqli_query($conn, "SELECT DISTINCT s.subject_name 
                                             FROM timetable t 
                                             INNER JOIN subjects s ON t.subject_id = s.id 
                                             WHERE t.teacher_id = '$real_t_id' LIMIT 1");
$subject_data = mysqli_fetch_assoc($subject_header_query);
$display_subject = $subject_data['subject_name'] ?? 'គ្រូបង្រៀន';

// ៤. ទាញយក Class ID សម្រាប់ Filter (កាលវិភាគ)
$target_class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 1; 

// ទាញយកបញ្ជីថ្នាក់ដែលគ្រូបង្រៀន
$all_classes_res = mysqli_query($conn, "SELECT DISTINCT c.id, c.class_name 
                                        FROM timetable t 
                                        INNER JOIN classes c ON t.class_id = c.id 
                                        WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0");

$current_class_res = mysqli_query($conn, "SELECT class_name FROM classes WHERE id = '$target_class_id' LIMIT 1");
$class_info = mysqli_fetch_assoc($current_class_res);
$display_class_name = $class_info['class_name'] ?? 'មិនស្គាល់';

$time_slots = ['07:00 - 07:50', '08:00 - 08:50', '09:00 - 09:50', '10:00 - 10:50'];
$days_kh = ['ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍'];
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Timetable | <?= htmlspecialchars($t_full_name) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; -webkit-tap-highlight-color: transparent; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] h-screen flex overflow-hidden">

    <?php include '../../includes/sidebar_teacher.php'; ?>

    <main class="flex-1 flex flex-col min-w-0 h-full relative">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="hidden md:block text-xl font-black text-slate-800 italic uppercase">Dashboard</h2>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[18px] md:text-[20px] font-black text-slate-900 leading-tight">
                        <?= htmlspecialchars($_SESSION['full_name']); ?>
                    </p>
                    <p class="text-[11px] md:text-[12px] text-blue-600 font-bold uppercase italic tracking-widest">
                         មុខវិជ្ជា: <span class="text-slate-500"><?= htmlspecialchars($display_subject) ?></span>
                    </p>
                </div>
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-slate-100 shadow-sm bg-slate-50">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        $img = (!empty($db_profile_img) && file_exists($path . $db_profile_img)) ? $path . $db_profile_img : $path . 'default_user.png';
                    ?>
                    <img src="<?= $img ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-4 md:p-10 custom-scrollbar">
            
            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h1 class="text-xl md:text-4xl font-black text-slate-800 italic uppercase">
                    កាលវិភាគ <?= htmlspecialchars($display_class_name) ?></span>
                </h1>
                <form method="GET" class="w-full md:w-auto">
                    <select name="class_id" onchange="this.form.submit()" 
                            class="w-full md:w-64 bg-white border-2 border-slate-100 text-slate-700 text-sm md:text-lg font-black rounded-2xl px-5 py-3.5 shadow-sm outline-none cursor-pointer focus:border-blue-600 transition-all">
                        <?php mysqli_data_seek($all_classes_res, 0); while($class = mysqli_fetch_assoc($all_classes_res)): ?>
                            <option value="<?= $class['id'] ?>" <?= ($target_class_id == $class['id']) ? 'selected' : '' ?>>
                                 <?= htmlspecialchars($class['class_name']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>
            </div>

            <div class="block md:hidden space-y-6">
                <?php foreach ($days_kh as $day): ?>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <span class="bg-slate-900 text-white px-4 py-1 rounded-full text-[10px] font-black italic uppercase tracking-widest"><?= $day ?></span>
                            <div class="h-px bg-slate-200 flex-1"></div>
                        </div>
                        <div class="grid gap-2">
                            <?php foreach ($time_slots as $slot): 
                                list($start, $end) = explode(' - ', $slot);
                                $sql = "SELECT s.subject_name, t.room_number, t.id FROM timetable t 
                                        INNER JOIN subjects s ON t.subject_id = s.id 
                                        WHERE t.teacher_id = '$real_t_id' AND t.class_id = '$target_class_id' 
                                        AND t.day_of_week = '$day' AND DATE_FORMAT(t.start_time, '%H:%i') = '$start'
                                        AND t.is_deleted = 0 LIMIT 1";
                                $res = mysqli_query($conn, $sql);
                                $data = mysqli_fetch_assoc($res);
                            ?>
                                <?php if($data): ?>
                                <div class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm flex items-center justify-between border-l-4 border-l-blue-600">
                                    <div class="flex items-center gap-4">
                                        <div class="text-[10px] font-black text-slate-400 italic leading-none border-r pr-4 border-slate-100"><?= $start ?></div>
                                        <div>
                                            <div class="text-[14px] font-black text-slate-800 uppercase italic"><?= htmlspecialchars($data['subject_name']) ?></div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[8px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded font-bold uppercase">Room: <?= htmlspecialchars($data['room_number']) ?></span>
                                                <span class="text-[8px] text-slate-300 font-bold uppercase">#ID: <?= $data['id'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                    <i class="fas fa-bookmark text-blue-50 text-xl"></i>
                                </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="hidden md:block bg-white rounded-[2.5rem] shadow-xl border-2 border-slate-100 overflow-hidden mb-10">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse">
                        <thead>
                            <tr class="bg-slate-900 text-white border-b-4 border-blue-600">
                                <th class="p-6 border-r border-slate-800 text-xl font-black italic text-center uppercase tracking-widest">Time</th>
                                <?php foreach ($days_kh as $day): ?>
                                    <th class="p-6 border-r border-slate-800 text-xl font-black italic text-center uppercase"><?= $day ?></th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody class="divide-y-2 divide-slate-50">
                            <?php foreach ($time_slots as $slot): ?>
                            <tr>
                                <td class="p-6 border-r-2 border-slate-100 bg-slate-50 text-center text-lg font-black italic text-slate-600"><?= $slot ?></td>
                                <?php foreach ($days_kh as $day): 
                                    list($start, $end) = explode(' - ', $slot);
                                    $sql = "SELECT s.subject_name, t.room_number, t.id FROM timetable t 
                                            INNER JOIN subjects s ON t.subject_id = s.id 
                                            WHERE t.teacher_id = '$real_t_id' AND t.class_id = '$target_class_id' 
                                            AND t.day_of_week = '$day' AND DATE_FORMAT(t.start_time, '%H:%i') = '$start'
                                            AND t.is_deleted = 0 LIMIT 1";
                                    $res = mysqli_query($conn, $sql);
                                    $data = mysqli_fetch_assoc($res);
                                ?>
                                <td class="p-5 text-center min-w-[150px]">
                                    <?php if ($data): ?>
                                        <div class="text-2xl font-black text-slate-800 italic uppercase"><?= htmlspecialchars($data['subject_name']) ?></div>
                                        <div class="mt-2 flex flex-col items-center gap-1">
                                            <span class="inline-block bg-blue-50 text-blue-600 px-4 py-1 rounded-full text-[10px] font-black uppercase italic border border-blue-100">Room: <?= htmlspecialchars($data['room_number']) ?></span>
                                            <span class="text-[9px] text-slate-300 font-bold uppercase tracking-widest">Timetable ID: #<?= $data['id'] ?></span>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-slate-100">---</span>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar'); 
        if(sidebar) sidebar.classList.toggle('-translate-x-full');
    }
</script>

</body>
</html>