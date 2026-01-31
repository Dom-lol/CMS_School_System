<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ទាញយកព័ត៌មានគ្រូ
$u_id = $_SESSION['user_id'];
$t_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$t_data = mysqli_fetch_assoc($t_query);

$real_t_id   = $t_data['teacher_id'] ?? 'N/A';
$t_full_name = $t_data['full_name'] ?? $_SESSION['full_name'];
$t_profile   = $t_data['profile_image'] ?? '';

// ២. ទាញយកឈ្មោះមុខវិជ្ជាដើម្បីបង្ហាញក្នុង Header (បន្ថែមដើម្បីកុំឱ្យ Error)
$sub_query = mysqli_query($conn, "SELECT DISTINCT s.subject_name FROM timetable t 
                                  INNER JOIN subjects s ON t.subject_id = s.id 
                                  WHERE t.teacher_id = '$real_t_id' LIMIT 1");
$sub_data = mysqli_fetch_assoc($sub_query);
$display_subject = $sub_data['subject_name'] ?? 'មិនទាន់កំណត់';

// ៣. ទទួលយក Class ID និង Date
$target_class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 1; 
$date = $_GET['date'] ?? date('Y-m-d');

$grade_map = [1 => "7", 2 => "8", 3 => "9", 4 => "10", 5 => "11", 6 => "12"];
$display_class_name = $grade_map[$target_class_id] ?? '---';

// ៤. ទាញបញ្ជីថ្នាក់ដែលគ្រូនេះមានបង្រៀន
$all_classes_res = mysqli_query($conn, "SELECT DISTINCT class_id FROM timetable WHERE teacher_id = '$real_t_id' AND is_deleted = 0");

// ៥. ទាញបញ្ជីសិស្ស
$st_query = "SELECT id, full_name, student_id, gender FROM students 
             WHERE class_id = '$target_class_id' AND status = 'Active' 
             ORDER BY full_name ASC";
$students = mysqli_query($conn, $st_query);
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Attendance | <?= htmlspecialchars($t_full_name) ?></title>
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

    <main class="flex-1 overflow-y-auto custom-scrollbar bg-[#f8fafc]">
    
        
        <header class="bg-white border-b-2 border-slate-100 h-20 flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-50 text-slate-500 rounded-2xl">
                    <i class="fas fa-bars text-xl"></i>
                </button>
               
            </div>

            <div class="flex items-center gap-5 ">
                <div class="text-right">
                    <p class="text-[18px] md:text-[20px] font-black text-slate-900 leading-tight">
                        <?= htmlspecialchars($t_full_name); ?>
                    </p>
                    <p class="text-[11px] md:text-[12px] text-blue-600 font-bold uppercase ">
                        មុខវិជ្ជា: <span class="text-slate-500"><?= htmlspecialchars($display_subject) ?></span>
                    </p>
                </div>
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-slate-100 shadow-sm bg-slate-50">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        $img = (!empty($t_profile) && file_exists($path . $t_profile)) ? $path . $t_profile : $path . 'default_user.png';
                    ?>
                    <img src="<?= $img ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

    
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6 p-4 ">
        <div>
            <p class="text-black font-bold text-xs md:text-sm mt-2 uppercase ">
                <i class="far fa-calendar-alt mr-2"></i> <?= date('D, d M Y', strtotime($date)) ?>
            </p>
        </div>

        <form id="filterForm" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row items-center gap-3 bg-white p-2 rounded-2xl md:rounded-[2rem] shadow-sm border border-slate-100">
            <div class="w-full sm:w-auto flex items-center gap-2 px-4 py-2 border-b sm:border-b-0 sm:border-r border-slate-100">
                <i class="far fa-calendar text-blue-600"></i>
                <input type="date" name="date" value="<?= $date ?>" onchange="this.form.submit()"
                       class="bg-transparent text-slate-700 font-black text-sm outline-none cursor-pointer w-full">
            </div>
            
            <select name="class_id" onchange="this.form.submit()" 
                    class="w-full sm:w-auto bg-transparent text-slate-700 font-black text-sm px-4 py-2 outline-none cursor-pointer">
                <option value="">-- រើសថ្នាក់ --</option>
                <?php if($all_classes_res): mysqli_data_seek($all_classes_res, 0); while($c = mysqli_fetch_assoc($all_classes_res)): 
                    $id = $c['class_id'];
                    $label = $grade_map[$id] ?? $id;
                ?>
                    <option value="<?= $id ?>" <?= $target_class_id == $id ? 'selected' : '' ?>>ថ្នាក់ទី <?= $label ?></option>
                <?php endwhile; endif; ?>
            </select>
        </form>
    </div>

    <?php if ($students && mysqli_num_rows($students) > 0): ?>
    <form action="../../actions/teachers/save_attendance.php" method="POST">
        <input type="hidden" name="class_id" value="<?= $target_class_id ?>">
        <input type="hidden" name="date" value="<?= $date ?>">

        <div class=" hidden md:block bg-white rounded-[1.5rem] shadow-xl border-2 border-slate-100 overflow-hidden ">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-900 text-white border-b-4 border-blue-600">
                        <th class="p-6 text-[16px] font-black uppercase">ព័ត៏មានសិស្ស</th>
                        <th class="p-6 text-[16px] font-black uppercase  text-center">ភេទ</th>
                        <th class="p-6 text-[16px]m font-black uppercase  text-center">វត្តមាន</th>
                    </tr>
                </thead>
                <tbody class="divide-y-2 divide-slate-50">
                    <?php mysqli_data_seek($students, 0); while($row = mysqli_fetch_assoc($students)): 
                        $s_id = $row['id'];
                        // ... your existing attendance check logic ...
                        $check_att = mysqli_query($conn, "SELECT status FROM attendance WHERE student_id='$s_id' AND attendance_date='$date' LIMIT 1");
                        $saved = mysqli_fetch_assoc($check_att);
                        $current_status = $saved['status'] ?? 'present';
                    ?>
                    <tr class="hover:bg-blue-50/50 transition-all">
                        <td class="p-6">
                            <div class="text-lg font-black text-slate-800 uppercase italic leading-none"><?= htmlspecialchars($row['full_name']) ?></div>
                            <div class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-tighter">ID: <?= $row['student_id'] ?></div>
                        </td>
                        <td class="p-6 text-center text-slate-500 font-bold"><?= $row['gender'] ?></td>
                        <td class="p-6">
                            <div class="flex justify-center items-center gap-8">
                                <?php foreach(['present'=>'មក', 'permission'=>'ច្បាប់', 'absent'=>'អវត្តមាន'] as $val => $lab): ?>
                                    <label class="flex flex-col items-center gap-1 cursor-pointer group">
                                        <input type="radio" name="att[<?= $s_id ?>]" value="<?= $val ?>" <?= $current_status == $val ? 'checked' : '' ?> 
                                               class="w-6 h-6 accent-blue-600">
                                        <span class="text-[10px] font-black text-slate-400 group-hover:text-blue-600 uppercase"><?= $lab ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-4 mb-32">
            <?php mysqli_data_seek($students, 0); while($row = mysqli_fetch_assoc($students)): 
                $s_id = $row['id'];
                $current_status = 'present'; // Re-run your check logic here or use a variable
            ?>
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="font-black text-slate-800 uppercase italic"><?= htmlspecialchars($row['full_name']) ?></h4>
                        <p class="text-[12px]  text-blue-600 font-bold">ID: <?= $row['student_id'] ?> | <?= $row['gender'] ?></p>
                    </div>
                </div>
                
                <div class="grid grid-cols-3 gap-2 border-t border-slate-50 pt-4">
                    <label class="flex flex-col items-center p-2 rounded-xl has-[:checked]:bg-green-50 transition-all">
                        <input type="radio" name="att[<?= $s_id ?>]" value="present" checked class="accent-green-500 w-5 h-5">
                        <span class="text-[10px] font-bold mt-1">មក</span>
                    </label>
                    <label class="flex flex-col items-center p-2 rounded-xl has-[:checked]:bg-orange-50 transition-all">
                        <input type="radio" name="att[<?= $s_id ?>]" value="permission" class="accent-orange-500 w-5 h-5">
                        <span class="text-[10px] font-bold mt-1">ច្បាប់</span>
                    </label>
                    <label class="flex flex-col items-center p-2 rounded-xl has-[:checked]:bg-red-50 transition-all">
                        <input type="radio" name="att[<?= $s_id ?>]" value="absent" class="accent-red-500 w-5 h-5">
                        <span class="text-[10px] font-bold mt-1">អវត្តមាន</span>
                    </label>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <div class="fixed bottom-6 right-6 left-6 md:left-auto md:bottom-10 md:right-10 z-50">
            <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-8 py-4 md:py-5 rounded-2xl md:rounded-full font-black uppercase text-xs tracking-widest shadow-2xl hover:bg-slate-900 transition-all flex items-center justify-center gap-4 active:scale-95">
                <i class="fas fa-save text-lg"></i>
                <span>រក្សាទុកទិន្នន័យ</span>
            </button>
        </div>
    </form>
    <?php else: ?>
        <div class="h-64 flex flex-col items-center justify-center bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
            <i class="fas fa-user-slash text-slate-200 text-4xl mb-4"></i>
            <p class="text-slate-400 font-bold italic">រកមិនឃើញសិស្សទេ</p>
        </div>
    <?php endif; ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar'); 
            if(sidebar) sidebar.classList.toggle('-translate-x-full');
        }
        // លុប Error Status Success ចាស់ចេញ បើមាន
    </script>
</body>
</html>