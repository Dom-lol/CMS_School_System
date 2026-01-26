<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ១. ឆែកសិទ្ធិចូលប្រើ (សម្រាប់តែសិស្ស)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

// ២. ទាញយកព័ត៌មានសិស្ស
$s_id = $_SESSION['username'] ?? '';
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$s_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

$display_name = $student_info['full_name'] ?? ($_SESSION['full_name'] ?? $s_id);

// ៣. រៀបចំ Path រូបភាព Profile
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
                ? $profile_path . $student_info['profile_img'] . "?v=" . time() 
                : null;

// ៤. កំណត់ខែ និងឆ្នាំសម្រាប់ Filter
$selected_month = $_GET['month'] ?? date('m');
$selected_year = $_GET['year'] ?? date('Y');
$months = [
    "01"=>"មករា", "02"=>"កុម្ភៈ", "03"=>"មីនា", "04"=>"មេសា", 
    "05"=>"ឧសភា", "06"=>"មិថុនា", "07"=>"កក្កដា", "08"=>"សីហា", 
    "09"=>"កញ្ញា", "10"=>"តុលា", "11"=>"វិច្ឆិកា", "12"=>"ធ្នូ"
];
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>លទ្ធផលសិក្សា - <?= htmlspecialchars($display_name) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; }
        ::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-[#f8fafc] flex h-screen overflow-hidden">

    <?php include '../../includes/sidebar_student.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">

        <!-- ===== Header profile img ===== -->
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right ">
                    <p class="text-[20px] font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
                    <p class="text-[12px] text-gray-500 font-bold uppercase tracking-[0.2em]">អត្តលេខ: <?php echo $s_id; ?></p>
                </div>
                
                <div class="relative group">
                    <div class="w-16 h-16 rounded-full border-4 border-white shadow-lg overflow-hidden bg-blue-600 flex items-center justify-center">
                        <?php if($current_img): ?>
                            <img src="<?php echo $current_img; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-white text-xl font-bold"><?php echo mb_substr($display_name, 0, 1); ?></span>
                        <?php endif; ?>
                    </div>
                    <form action="../../actions/uploads/profiles" method="POST" enctype="multipart/form-data" class="absolute -bottom-1 -right-1">
                        <label class="w-7 h-7 bg-white text-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-md border border-slate-100 hover:bg-blue-50 transition-all">
                            <i class="fas fa-camera text-[10px]"></i>
                            <input type="file" name="profile_img" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>
            </div>
        </header>
        <main class="flex-1 overflow-y-auto p-6 md:p-10">
            <div class="max-w-6xl mx-auto">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 italic uppercase">Report Card</h2>
                        <p class="text-slate-500 font-medium">តាមដានលទ្ធផលសិក្សារបស់អ្នកប្រចាំខែ</p>
                    </div>
                    
                    <form action="" method="GET" class="flex gap-3 bg-white p-3 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100">
                        <div class="flex items-center px-4 gap-2 border-r border-slate-100">
                            <i class="fas fa-calendar-alt text-blue-500 text-sm"></i>
                            <select name="month" onchange="this.form.submit()" class="bg-transparent outline-none font-black text-xs uppercase italic cursor-pointer">
                                <?php foreach ($months as $num => $kh): ?>
                                    <option value="<?= $num ?>" <?= ($num == $selected_month) ? 'selected' : '' ?>><?= $kh ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-center px-4 gap-2">
                            <select name="year" onchange="this.form.submit()" class="bg-transparent outline-none font-black text-xs uppercase italic cursor-pointer">
                                <?php for ($y = date('Y'); $y >= 2024; $y--): ?>
                                    <option value="<?= $y ?>" <?= ($y == $selected_year) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php 
                    $list_q = mysqli_query($conn, "SELECT s.*, sub.subject_name 
                                                   FROM scores s 
                                                   JOIN subjects sub ON s.subject_id = sub.id 
                                                   WHERE s.student_id = '$s_id' 
                                                   AND MONTH(s.created_at) = '$selected_month' 
                                                   AND YEAR(s.created_at) = '$selected_year'
                                                   ORDER BY s.total_score DESC");
                    
                    if(mysqli_num_rows($list_q) > 0):
                        while($row = mysqli_fetch_assoc($list_q)):
                            // កំណត់ពណ៌តាម Grade
                            $gradeColor = "bg-slate-100 text-slate-600";
                            if($row['grade'] == 'A') $gradeColor = "bg-green-100 text-green-600";
                            elseif($row['grade'] == 'B') $gradeColor = "bg-blue-100 text-blue-600";
                            elseif($row['grade'] == 'F') $gradeColor = "bg-red-100 text-red-600";
                    ?>
                    <div class="group bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 hover:shadow-2xl hover:shadow-blue-200/40 hover:-translate-y-2 transition-all duration-500">
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-600 rounded-2xl flex items-center justify-center font-black italic shadow-inner group-hover:scale-110 transition-transform">
                                <?= mb_substr($row['subject_name'], 0, 1) ?>
                            </div>
                            <div class="text-right">
                                <span class="text-[9px] font-black text-slate-400 uppercase italic tracking-widest">Total</span>
                                <p class="text-4xl font-black text-slate-900 leading-none"><?= (int)$row['total_score'] ?></p>
                                <span class="inline-block mt-2 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-tighter <?= $gradeColor ?>">
                                    Grade: <?= $row['grade'] ?>
                                </span>
                            </div>
                        </div>

                        <h4 class="text-xl font-black text-slate-800 mb-6 uppercase italic truncate border-b border-slate-50 pb-4"><?= $row['subject_name'] ?></h4>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100 group-hover:bg-blue-50/50 transition-colors">
                                <span class="text-[9px] text-slate-400 font-black uppercase block mb-1">Monthly</span>
                                <p class="font-black text-slate-700 italic"><?= (int)$row['monthly_score'] ?></p>
                            </div>
                            <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100 group-hover:bg-indigo-50/50 transition-colors">
                                <span class="text-[9px] text-slate-400 font-black uppercase block mb-1">Exam</span>
                                <p class="font-black text-slate-700 italic"><?= (int)$row['exam_score'] ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; else: ?>
                        <div class="col-span-full py-32 text-center bg-white rounded-[4rem] border-4 border-dashed border-slate-100 shadow-inner">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fas fa-folder-open text-slate-200 text-4xl"></i>
                            </div>
                            <p class="text-slate-400 font-black italic uppercase tracking-widest">មិនទាន់មានទិន្នន័យសម្រាប់ខែនេះទេ</p>
                            <p class="text-slate-300 text-xs mt-2 font-medium">No results found for <?= $months[$selected_month] ?> <?= $selected_year ?></p>
                        </div>
                    <?php endif; ?>
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