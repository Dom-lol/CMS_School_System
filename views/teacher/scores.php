<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ឆែកសិទ្ធិចូលប្រើប្រាស់
if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

// ២. ទាញយកទិន្នន័យគ្រូ (កែសម្រួល៖ ប្រើ teacher_id ជំនួស id ដែលបាត់)
$u_id = $_SESSION['user_id'];
// កែត្រង់នេះ៖ ដកពាក្យ id ចេញ ទុកតែ teacher_id
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");

if (!$teacher_query) {
    die("Database Error (Teacher): " . mysqli_error($conn));
}

$teacher_info = mysqli_fetch_assoc($teacher_query);

if (!$teacher_info) {
    die("រកមិនឃើញព័ត៌មានគ្រូក្នុងប្រព័ន្ធឡើយ។");
}

// កំណត់ Variable សម្រាប់បង្ហាញក្នុង Header និង Query
$real_t_id  = $teacher_info['teacher_id']; // ប្រើ teacher_id ជាអត្តលេខផង ជា Key សម្រាប់ Join ផង [cite: 2026-01-20]
$full_name  = $teacher_info['full_name'];
$db_img     = $teacher_info['profile_image'];

// រៀបចំ Path រូបភាព Profile
$path = "../../assets/uploads/teachers/";
$profile_img = (!empty($db_img) && file_exists($path . $db_img)) 
               ? $path . $db_img . "?v=" . time() 
               : "../../assets/img/default_user.png";

// ៣. ទាញយកបញ្ជីថ្នាក់រៀនពី Timetable
// ប្រាកដថា Column teacher_id ក្នុងតារាង timetable ផ្ទុកតម្លៃដូច teacher_id ក្នុងតារាង teachers [cite: 2026-01-20]
$sql = "SELECT DISTINCT t.class_id, t.subject_id, c.class_name, s.subject_name 
        FROM timetable t
        INNER JOIN classes c ON t.class_id = c.id
        INNER JOIN subjects s ON t.subject_id = s.id
        WHERE t.teacher_id = '$real_t_id' AND t.is_deleted = 0";

$res = mysqli_query($conn, $sql);

if (!$res) {
    die("Database Error (Timetable): " . mysqli_error($conn));
}
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
    <style> body { font-family: 'Kantumruy Pro', sans-serif; } </style>
</head>
<body class="bg-[#f8fafc] overflow-hidden">

<div class="flex h-screen w-full">
    <?php include '../../includes/sidebar_teacher.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 shrink-0 z-50 shadow-sm">
            <h2 class="text-xl font-black text-slate-800 uppercase italic">Scores System</h2>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[18px] font-black text-slate-900 leading-tight"><?= htmlspecialchars($full_name) ?></p>
                    <p class="text-[11px] text-blue-500 font-bold uppercase italic tracking-widest">ID: <?= $real_t_id ?></p>
                </div>
                <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-white shadow-lg bg-indigo-600 flex items-center justify-center">
                    <img src="<?= $profile_img ?>" class="w-full h-full object-cover">
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php if (mysqli_num_rows($res) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($res)): ?>
                        <a href="input_grades.php?class_id=<?= $row['class_id'] ?>&subject_id=<?= $row['subject_id'] ?>" 
                           class="group bg-white p-10 rounded-[3rem] border-2 border-slate-50 hover:border-blue-600 transition-all text-center relative shadow-sm hover:shadow-2xl hover:-translate-y-2">
                            <h2 class="text-5xl font-black text-slate-800 mb-2 italic"><?= $row['class_name'] ?></h2>
                            <p class="text-blue-600 font-black uppercase italic text-xs mb-8 tracking-widest"><?= $row['subject_name'] ?></p>
                            <div class="bg-slate-100 rounded-2xl py-3 text-[10px] font-black uppercase text-slate-500 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                ចុចដើម្បីបញ្ចូលពិន្ទុ
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-full bg-white rounded-[3rem] border-4 border-dashed border-slate-100 p-24 text-center">
                        <i class="fas fa-search text-6xl text-slate-200 mb-6 block"></i>
                        <h3 class="text-xl font-black text-slate-400 italic">មិនមានទិន្នន័យថ្នាក់បង្រៀន</h3>
                        <p class="text-slate-400 mt-2 text-sm italic">សូមឆែកក្នុង Database ថាគ្រូ ID <b><?= $real_t_id ?></b> មានក្នុងតារាង <b>timetable</b> ដែរឬទេ?</p>
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

</body>
</html>