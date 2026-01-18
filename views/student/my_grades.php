<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

$s_id = $_SESSION['username'] ?? '';
$display_name = $_SESSION['full_name'] ?? $s_id;
$current_page = 'my_grades.php';

// Query ទាញយកពិន្ទុ
$query = "SELECT s.subject_name, sc.monthly_score, sc.exam_score, sc.total_score, sc.grade 
          FROM scores sc 
          JOIN subjects s ON sc.subject_id = s.id 
          WHERE sc.student_id = '$s_id'";
$grades = mysqli_query($conn, $query);

// Query គណនាមធ្យមភាគ
$avg_result = mysqli_query($conn, "SELECT AVG(total_score) as avg_score FROM scores WHERE student_id = '$s_id'");
$avg_row = mysqli_fetch_assoc($avg_result);
$overall_avg = number_format($avg_row['avg_score'] ?? 0, 2);
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>លទ្ធផលសិក្សា | Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Kantumruy Pro', sans-serif; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="bg-[#f8fafc] flex h-screen overflow-hidden">

    <?php include '../../includes/sidebar_student.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 flex-shrink-0 no-print">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-slate-800 hidden md:block uppercase tracking-tight italic">My Grades</h1>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right hidden sm:block">
                    <p class="text-base font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
                    <p class="text-[11px] text-blue-500 font-bold uppercase tracking-[0.2em]">Student Portal</p>
                </div>
                <div class="w-[60px] h-[60px] bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-xl shadow-blue-200 border-4 border-white">
                    <?php echo mb_substr($display_name, 0, 1); ?>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10">
            <div class="max-w-7xl mx-auto">
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10 no-print">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-bold text-slate-900">លទ្ធផលសិក្សា</h1>
                        <p class="text-slate-500 mt-2 text-lg italic">របាយការណ៍ពិន្ទុ និងការវាយតម្លៃការសិក្សា</p>
                    </div>
                    <button onclick="window.print()" class="bg-white border-2 border-slate-100 px-6 py-3 rounded-2xl text-slate-700 font-bold hover:bg-slate-50 transition flex items-center gap-3 shadow-sm">
                        <i class="fas fa-print text-blue-600"></i>
                        <span>បោះពុម្ពរបាយការណ៍</span>
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                    <div class="bg-blue-600 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-blue-200 flex flex-col justify-between relative overflow-hidden group">
                        <i class="fas fa-award absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:rotate-12 transition-transform"></i>
                        <p class="opacity-80 text-sm font-bold uppercase tracking-wider">មធ្យមភាគសរុប</p>
                        <h3 class="text-5xl font-black mt-4 italic"><?php echo $overall_avg; ?></h3>
                    </div>
                    </div>

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="p-8 text-sm font-bold text-slate-400 uppercase tracking-widest">មុខវិជ្ជា</th>
                                    <th class="p-8 text-sm font-bold text-slate-400 uppercase tracking-widest text-center">ពិន្ទុប្រចាំខែ</th>
                                    <th class="p-8 text-sm font-bold text-slate-400 uppercase tracking-widest text-center">ពិន្ទុប្រលង</th>
                                    <th class="p-8 text-sm font-bold text-slate-400 uppercase tracking-widest text-center">សរុប</th>
                                    <th class="p-8 text-sm font-bold text-slate-400 uppercase tracking-widest text-center">និទ្ទេស</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php if(mysqli_num_rows($grades) > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($grades)): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="p-8">
                                            <span class="text-lg font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                                                <?php echo $row['subject_name']; ?>
                                            </span>
                                        </td>
                                        <td class="p-8 text-center text-slate-600 font-medium">
                                            <?php echo number_format($row['monthly_score'], 2); ?>
                                        </td>
                                        <td class="p-8 text-center text-slate-600 font-medium">
                                            <?php echo number_format($row['exam_score'], 2); ?>
                                        </td>
                                        <td class="p-8 text-center">
                                            <span class="text-xl font-black text-slate-900 italic">
                                                <?php 
                                                    $total = ($row['total_score'] > 0) ? $row['total_score'] : ($row['monthly_score'] + $row['exam_score']);
                                                    echo number_format($total, 2);
                                                ?>
                                            </span>
                                        </td>
                                        <td class="p-8 text-center">
                                            <span class="inline-block px-5 py-2 rounded-2xl text-xs font-black tracking-widest shadow-sm
                                                <?php 
                                                    if($row['grade'] == 'A') echo 'bg-emerald-50 text-emerald-600 border border-emerald-100';
                                                    elseif($row['grade'] == 'F') echo 'bg-rose-50 text-rose-600 border border-rose-100';
                                                    else echo 'bg-amber-50 text-amber-600 border border-amber-100'; 
                                                ?>">
                                                <?php echo $row['grade']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="p-20 text-center text-slate-400 font-bold italic text-lg">មិនទាន់មានទិន្នន័យពិន្ទុនៅឡើយទេ</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <p class="mt-8 text-slate-300 text-[11px] uppercase tracking-[0.3em] text-center italic">
                    * ឯកសារនេះត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិតាមរយៈប្រព័ន្ធគ្រប់គ្រងសាលារៀន *
                </p>
                <div class="h-20"></div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
</body>
</html>