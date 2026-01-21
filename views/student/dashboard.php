<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

// ទាញយកព័ត៌មានសិស្សពី Database ផ្ទាល់
$s_id = $_SESSION['username'] ?? '';
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$s_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

$display_name = $student_info['full_name'] ?? ($_SESSION['full_name'] ?? $s_id);
$class_id = $student_info['class_id'] ?? "មិនទាន់មានថ្នាក់";
$status = $student_info['status'] ?? "Active";
$academic_year = $student_info['academic_year'] ?? "2024-2025";


// កំណត់ Path រូបភាព
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
               ? $profile_path . $student_info['profile_img'] . "?v=" . time() 
               : null;
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
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right ">
                    <p class="text-[25px] font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
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
                    <form action="../../actions/students/upload_profile.php" method="POST" enctype="multipart/form-data" class="absolute -bottom-1 -right-1">
                        <label class="w-7 h-7 bg-white text-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-md border border-slate-100 hover:bg-blue-50 transition-all">
                            <i class="fas fa-camera text-[10px]"></i>
                            <input type="file" name="profile_img" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto overflow-hidden p-6 md:p-10">
            <div class="max-w-7xl mx-auto">
                
                <div class="mb-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900">សួស្ដី, <?php echo $display_name; ?>!</h1>
                    <p class="text-slate-500 mt-2 text-lg italic uppercase">សូមស្វាគមន៍មកកាន់វិទ្យាល័យលំដាប់ពិភពលោក</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
                    <div class="bg-blue-600 p-8 rounded-[2.5rem] text-white shadow-2xl shadow-blue-200 relative overflow-hidden group">
                        <i class="fas fa-graduation-cap absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
                        <p class="opacity-80 text-sm font-bold uppercase tracking-wider text-white/80">ស្ថានភាពសិក្សា</p>
                        <h3 class="text-4xl font-bold mt-4 italic"><?php echo $status; ?></h3>
                    </div>
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[6px] border-l-purple-500 flex flex-col justify-center">
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">ឆ្នាំសិក្សា</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-3"><?php echo $academic_year; ?></h3>
                    </div>
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 border-l-[6px] border-l-emerald-500 flex flex-col justify-center">
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">រៀនថ្នាក់ទី</p>
                        <h3 class="text-3xl font-bold text-slate-800 mt-3"><?php echo $class_id; ?></h3>
                    </div>
                </div>

                <div class="grid grid-cols-1  gap-8">
                    <div onclick="openInfoModal()" class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 md:p-10 cursor-pointer hover:border-blue-300 transition-all group relative">
                        <div class="flex items-center justify-between border-b border-slate-50 ">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <i class="fas fa-id-card text-xl"></i>
                                </div>
                                <h2 class="text-xl font-bold text-slate-800 italic uppercase">ព័ត៌មានលម្អិតផ្ទាល់ខ្លួន</h2>
                            </div>
                            <span class="text-blue-500 text-xs font-bold uppercase italic">បង្ហាញលម្អិត <i class="fas fa-chevron-right "></i></span>
                        </div>
                        
                       
                    </div>

                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 flex flex-col justify-between">
                        <div>
                            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-6">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 italic uppercase">កាលវិភាគសិក្សា</h3>
                        </div>
                        <div class="mt-8 flex items-baseline gap-2">
                            <span class="text-6xl font-black text-slate-900 leading-none italic">?</span>
                            <span class="text-xl font-bold text-slate-400">ម៉ោង</span>
                        </div>
                        <a href="my_timetable.php" class="mt-8 w-full py-4 bg-slate-900 text-white rounded-[1.5rem] text-center font-bold hover:bg-slate-800 transition shadow-lg">
                            មើលកាលវិភាគលម្អិត
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>



    <!-- Modal student -->
    <div id="infoModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/60 backdrop-blur-md p-4">
        <div class="bg-white w-full max-w-2xl  shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-8 border-b flex justify-between items-center bg-slate-50/50">
                <h3 class="font-black text-slate-800 italic uppercase tracking-wider">ព័ត៌មានលម្អិតទាំងស្រុង</h3>
                <button onclick="closeInfoModal()" class="w-10 h-10 bg-white rounded-2xl shadow-sm text-slate-400 hover:text-red-500 transition-all"><i class="fas fa-times"></i></button>
            </div>
            
            <div class="p-8 overflow-y-auto space-y-6">
                <div class="flex items-center gap-6 p-6 text-white justify-center">
                    <img src="<?php echo $current_img ?? '../../assets/img/default.png'; ?>" class="w-[150px] h-[150px] rounded-[50%] object-cover border-2 border-slate-700">
                    <div>
                    </div>
                </div>

                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100"> 
                    <h4 class="text-2xl font-black italic uppercase text-blue-400"><?php echo $display_name; ?></h4>
                        <p class="text-slate-400 text-sm font-bold uppercase tracking-widest">លេខសម្គាល់: <?php echo $s_id; ?></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-slate-400 text-[10px] font-black uppercase italic">ថ្ងៃខែឆ្នាំកំណើត</span>
                        <p class="text-slate-800 font-bold italic"><?php echo $student_info['dob'] ?? '---'; ?></p>
                    </div>
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-slate-400 text-[10px] font-black uppercase italic text-sm">ទីកន្លែងកំណើត</span>
                        <p class="text-slate-800 font-bold italic"><?php echo $student_info['pob'] ?? '---'; ?></p>
                    </div>
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-slate-400 text-[10px] font-black uppercase italic">ឈ្មោះឪពុក</span>
                        <p class="text-slate-800 font-bold italic"><?php echo $student_info['father_name'] ?? '---'; ?></p>
                    </div>
                    <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100">
                        <span class="text-slate-400 text-[10px] font-black uppercase italic">ឈ្មោះម្ដាយ</span>
                        <p class="text-slate-800 font-bold italic"><?php echo $student_info['mother_name'] ?? '---'; ?></p>
                    </div>
                    <div class="md:col-span-2 p-5 bg-blue-50/50 rounded-2xl border border-blue-100">
                        <span class="text-blue-400 text-[10px] font-black uppercase italic">អាសយដ្ឋានបច្ចុប្បន្ន</span>
                        <p class="text-slate-800 font-bold italic leading-relaxed text-sm"><?php echo $student_info['address'] ?? 'មិនទាន់មានទិន្នន័យ'; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); }
        function openInfoModal() {
            const modal = document.getElementById('infoModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        function closeInfoModal() {
            const modal = document.getElementById('infoModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        window.onclick = function(event) { if (event.target == document.getElementById('infoModal')) closeInfoModal(); }
    </script>
</body>
</html>