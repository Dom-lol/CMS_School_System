<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

$s_id = $_SESSION['username'] ?? '';
$display_name = $_SESSION['full_name'] ?? $s_id;

$student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$s_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

$class_name = $student_info['class_name'] ?? "មិនទាន់មានថ្នាក់";
$status = $student_info['status'] ?? "Active";
$academic_year = $student_info['academic_year'] ?? "2023-2024";
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style> body { font-family: 'Kantumruy Pro', sans-serif; } </style>
</head>
<body class="bg-[#f8fafc] flex h-screen overflow-hidden">

    <?php include '../../includes/sidebar_student.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
     <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 flex-shrink-0">
    <div class="flex items-center gap-4">
        <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-colors">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <h1 class="text-xl font-bold text-slate-800 hidden md:block uppercase tracking-tight">Dashboard</h1>
    </div>

    <div class="flex items-center gap-5">
        <div class="text-right hidden sm:block">
            <p class="text-base font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
            <p class="text-[11px] text-blue-500 font-bold uppercase tracking-[0.2em]">Student Portal</p>
        </div>

        <div class="w-[80px] h-[80px] bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-xl shadow-blue-200 border-4 border-white">
            <?php echo mb_substr($display_name, 0, 1); ?>
        </div>
    </div>
</header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10">
            <div class="max-w-7xl mx-auto">
                
                <div class="mb-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900">សួស្ដី, <?php echo $display_name; ?>!</h1>
                    <p class="text-slate-500 mt-2 text-lg italic">នេះគឺជាសេចក្ដីសង្ខេបនៃការសិក្សារបស់អ្នកក្នុងថ្នាក់ <span class="text-blue-600 font-bold not-italic"><?php echo $class_name; ?></span></p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
                    <div class="bg-blue-600 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-blue-200 relative overflow-hidden group">
                        <i class="fas fa-check-circle absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
                        <p class="opacity-80 text-sm font-bold uppercase tracking-wider">ស្ថានភាពសិក្សា</p>
                        <h3 class="text-4xl font-bold mt-4"><?php echo $status; ?></h3>
                    </div>
                    
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[6px] border-l-purple-500 flex flex-col justify-center">
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">ឆ្នាំសិក្សា</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-3"><?php echo $academic_year; ?></h3>
                    </div>

                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[6px] border-l-emerald-500 flex flex-col justify-center">
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">វត្តមានសរុប</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-3">100%</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-10">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-id-card text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold text-slate-800">ព័ត៌មានផ្ទាល់ខ្លួន</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-6">
                            <div class="flex flex-col gap-1 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <span class="text-slate-400 text-xs font-bold uppercase">អត្តលេខសិស្ស</span>
                                <span class="text-blue-600 font-bold text-lg"><?php echo $s_id; ?></span>
                            </div>
                            <div class="flex flex-col gap-1 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <span class="text-slate-400 text-xs font-bold uppercase">ភេទ</span>
                                <span class="text-slate-800 font-bold text-lg"><?php echo $student_info['gender'] ?? '---'; ?></span>
                            </div>
                            <div class="flex flex-col gap-1 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <span class="text-slate-400 text-xs font-bold uppercase">ថ្ងៃខែឆ្នាំកំណើត</span>
                                <span class="text-slate-800 font-bold text-lg"><?php echo $student_info['dob'] ?? '---'; ?></span>
                            </div>
                            <div class="flex flex-col gap-1 p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <span class="text-slate-400 text-xs font-bold uppercase">ថ្នាក់សិក្សា</span>
                                <span class="text-slate-800 font-bold text-lg"><?php echo $class_name; ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800">ម៉ោងសិក្សាក្នុងថ្ងៃនេះ</h3>
                            <p class="text-slate-400 text-sm mt-1 italic">ពិនិត្យកាលវិភាគប្រចាំថ្ងៃ</p>
                        </div>
                        <div class="mt-8 flex items-baseline gap-2">
                            <span class="text-6xl font-black text-slate-900 leading-none">2</span>
                            <span class="text-xl font-bold text-slate-400">ម៉ោង</span>
                        </div>
                        <a href="my_timetable.php" class="mt-8 w-full py-4 bg-slate-900 text-white rounded-2xl text-center font-bold hover:bg-slate-800 transition shadow-lg shadow-slate-200">
                            មើលកាលវិភាគលម្អិត
                        </a>
                    </div>
                </div>

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