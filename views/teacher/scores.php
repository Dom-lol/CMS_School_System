<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// 
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

// 
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);

$real_t_id  = $teacher_info['teacher_id'] ?? 'N/A';
$full_name  = $teacher_info['full_name'] ?? $_SESSION['full_name'];
$db_profile_img = $teacher_info['profile_image'] ?? ''; 

// 
$subj_header_query = mysqli_query($conn, "SELECT DISTINCT s.subject_name 
                                          FROM timetable t 
                                          INNER JOIN subjects s ON t.subject_id = s.id 
                                          WHERE t.teacher_id = '$real_t_id' LIMIT 1");
$subj_data = mysqli_fetch_assoc($subj_header_query);
$display_subject = $subj_data['subject_name'] ?? 'គ្រូបង្រៀន';

// 
$sql = "SELECT DISTINCT t.class_id, t.subject_id, c.class_name, s.subject_name 
        FROM timetable t
        INNER JOIN classes c ON t.class_id = c.id
        INNER JOIN subjects s ON t.subject_id = s.id
        WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0";
$res = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scores Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700;900&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Kantumruy Pro', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] overflow-hidden">

<div class="flex h-screen w-full">
    
    <?php include '../../includes/sidebar_teacher.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
        <header class="bg-white border-b-2 border-slate-100 h-20 flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm z-20">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-all">
                    <i class="fas fa-bars text-xl"></i>
                </button>
               
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[18px] md:text-[20px] font-black text-slate-900 leading-tight">
                        <?= htmlspecialchars($full_name); ?>
                    </p>
                    <p class="text-[11px] md:text-[12px] text-blue-600 font-bold uppercase">
                         មុខវិជ្ជា: <span class="text-slate-500 font-bold"><?= htmlspecialchars($display_subject) ?></span>
                    </p>
                </div>
                
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full overflow-hidden border-2 border-slate-100 shadow-sm bg-slate-50 flex-shrink-0">
                    <?php 
                        $path = "../../assets/uploads/teachers/";
                        $display_img = (!empty($db_profile_img) && file_exists($path . $db_profile_img)) ? $path . $db_profile_img : $path . 'default_user.png';
                    ?>
                    <img src="<?= $display_img ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            
            <div class="mb-10 w-full bg-gradient-to-br from-slate-900 to-slate-800 rounded-[2rem] md:rounded-[3rem] p-8 md:p-12 text-white shadow-xl relative overflow-hidden">
                <div class="relative z-10">
                    <h1 class="text-2xl md:text-5xl font-black italic mb-2 uppercase tracking-tighter">បញ្ចូលពិន្ទុសិស្ស</h1>
                    <p class="text-slate-400 text-sm md:text-lg italic opacity-90">សូមជ្រើសរើសថ្នាក់ដែលលោកគ្រូត្រូវបញ្ចូលពិន្ទុសម្រាប់ឆមាសនេះ</p>
                </div>
                <i class="fas fa-award absolute right-4 md:right-10 top-1/2 -translate-y-1/2 text-7xl md:text-[12rem] text-white/5 transform rotate-12"></i>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <?php if ($res && mysqli_num_rows($res) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($res)): ?>
                        <a href="input_grades.php?class_id=<?= $row['class_id'] ?>&subject_id=<?= $row['subject_id'] ?>" 
                           class="group bg-white p-8 md:p-10 rounded-[2.5rem] md:rounded-[3rem] border-2  border-blue-600 transition-all text-center relative shadow-sm hover:shadow-2xl hover:-translate-y-2">
                            
                           

                            <h2 class="text-[25px] md:text-3xl font-black text-slate-800 mb-2 "><?= htmlspecialchars($row['class_name']) ?></h2>
                            <p class="text-blue-600 font-black uppercase text-[15px] md:text-xs mb-6 "><?= htmlspecialchars($row['subject_name']) ?></p>
                            
                            <div class=" rounded-2xl py-3 md:py-4 text-[12px] md:text-[15px] font-black uppercase  bg-blue-600 text-white transition-all shadow-sm er">
                                <i class="fas fa-edit mr-2"></i> ចុចដើម្បីបញ្ចូលពិន្ទុ
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-full bg-white rounded-[2rem] border-4 border-dashed border-slate-100 p-16 md:p-24 text-center">
                        <i class="fas fa-search text-5xl md:text-6xl text-slate-200 mb-6 block"></i>
                        <h3 class="text-lg md:text-xl font-black text-slate-400 italic uppercase">មិនមានទិន្នន័យថ្នាក់បង្រៀន</h3>
                        <p class="text-slate-400 mt-2 text-xs md:text-sm italic">សូមទាក់ទងអ្នកគ្រប់គ្រងដើម្បីពិនិត្យតារាងបង្រៀន (Timetable)</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="h-10 lg:hidden"></div>
        </main>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar'); 
        if(sidebar) {
            sidebar.classList.toggle('-translate-x-full');
        }
    }
</script>

</body>
</html>