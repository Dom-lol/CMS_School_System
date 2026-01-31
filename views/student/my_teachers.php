<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
 include '../../includes/header.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

// database
$s_id = $_SESSION['username'] ?? '';
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$s_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

$display_name = $student_info['full_name'] ?? ($_SESSION['full_name'] ?? $s_id);

// Logic change class_d
$cid = $student_info['class_id'] ?? 0;
$grades = [1 => "៧", 2 => "៨", 3 => "៩", 4 => "១០", 5 => "១១", 6 => "១២"];
$class_name_display = isset($grades[$cid]) ? $grades[$cid] : "---";

$status = $student_info['status'] ?? "Active";
$academic_year = $student_info['academic_year'] ?? "2025-2026";

// Path img
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
                ? $profile_path . $student_info['profile_img'] . "?v=" . time() 
                : null;

// logic class_id
$active_grade_id = $student_info['class_id'] ?? ''; 
$active_grade    = $student_info['class_name'] ?? 'N/A';                 
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
<body class="bg-[#f8fafc] h-screen overflow-hidden">

    <?php include '../../includes/sidebar_student.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">

        <header class="bg-white border-b-2 border-slate-100 h-20 flex items-center justify-between px-6 md:px-10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <div class="flex items-center gap-5">
               
                <div class="text-right ">
                    <p class="text-[18px] font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
                    <p class="text-[12px] text-gray-500 font-bold uppercase ">អត្តលេខ: <?php echo $s_id; ?></p>
                </div>
                <div class="relative group cursor-pointer">
                    <div onclick="openInfoModal()"  class="w-16 h-16 rounded-full border-4 border-white shadow-md overflow-hidden bg-blue-600 flex items-center justify-center">
                        <?php if($current_img): ?>
                            <img src="<?php echo $current_img; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-white text-xl font-bold"><?php echo mb_substr($display_name, 0, 1); ?></span>
                        <?php endif; ?>
                    </div>
                    <form action="../../actions/students/upload_profile.php" method="POST" enctype="multipart/form-data" id="profileForm">
                        <label class="absolute -bottom-1 -right-1 w-7 h-7 bg-white text-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-md border border-slate-100 hover:bg-blue-600 hover:text-white transition-all">
                            <i class="fas fa-camera text-[10px]"></i>
                            <input type="file" name="profile_img" class="hidden" accept="image/*" onchange="document.getElementById('profileForm').submit()">
                        </label>
                    </form>
                </div>
                
               
            </div>
        </header>
        
<div class="flex-1 h-screen overflow-y-auto bg-slate-50">
    <section class="w-full px-4 py-8">
    
        <h1 class="text-center font-bold text-2xl md:text-3xl mb-2 text-slate-800">
            បញ្ជីឈ្មោះគ្រូបង្រៀន
        </h1>

        <div class="max-w-4xl mx-auto space-y-2 w-full h-auto">

            <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-lg hover:border-blue-100 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <img src="https://i.pravatar.cc/150?img=11" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                       
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[17px] md:text-base transition-colors ">ជា ឧត្តម</p>
                        <p class="text-[14px] md:text-sm text-slate-500">អក្សរសាស្រ្តខ្មែរ</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 md:gap-8">
                    <div class="font-mono font-semibold text-slate-600 text-xs md:text-base">096 826 3627</div>
                    <a href="https://t.me/+855968263627" class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white active:scale-95 transition-all shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-lg hover:border-blue-100 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <img src="https://i.pravatar.cc/150?img=11" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                      
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[17px] md:text-base transition-colors">ចាន់ ថា</p>
                        <p class="text-[14px]  md:text-sm text-slate-500">គណិតវិទ្យា</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 md:gap-8">
                    <div class="font-mono font-semibold text-slate-600 text-xs md:text-base">012 345 678</div>
                    <a href="https://t.me/+85566686543" class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white active:scale-95 transition-all shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-lg hover:border-blue-100 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <img src="https://i.pravatar.cc/150?img=11" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                      
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[17px] md:text-base transition-colors">ហេង ឡុង</p>
                        <p class="text-[14px]  md:text-sm text-slate-500">រូបវិទ្យា</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 md:gap-8">
                    <div class="font-mono font-semibold text-slate-600 text-xs md:text-base">011 223 344</div>
                    <a href="https://t.me/+855968263627" class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white active:scale-95 transition-all shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-lg hover:border-blue-100 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <img src="https://i.pravatar.cc/150?img=11" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                       
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[17px] md:text-base transition-colors"> ម៉ារី យ៉ា</p>
                        <p class="text-[14px]  md:text-sm text-slate-500">គីមីវិទ្យា</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 md:gap-8">
                    <div class="font-mono font-semibold text-slate-600 text-xs md:text-base">088 777 6655</div>
                    <a href="https://t.me/+855968263627" class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white active:scale-95 transition-all shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-lg hover:border-blue-100 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <img src="https://i.pravatar.cc/150?img=11" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                        
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[17px] md:text-base  transition-colors"> វណ្ណ ឌី</p>
                        <p class="text-[14px]  md:text-sm text-slate-500">ជីវវិទ្យា</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 md:gap-8">
                    <div class="font-mono font-semibold text-slate-600 text-xs md:text-base">015 999 000</div>
                    <a href="https://t.me/+855968263627" class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white active:scale-95 transition-all shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-lg hover:border-blue-100 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <img src="https://i.pravatar.cc/150?img=11" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                       
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[17px] md:text-base transition-colors">ស្រីមុំ</p>
                        <p class="text-[14px]  md:text-sm text-slate-500">ប្រវត្តិវិទ្យា</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 md:gap-8">
                    <div class="font-mono font-semibold text-slate-600 text-xs md:text-base">097 555 4433</div>
                    <a href="https://t.me/+855968263627" class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white active:scale-95 transition-all shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-lg hover:border-blue-100 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <img src="https://i.pravatar.cc/150?img=11" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                       
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[17px] md:text-base transition-colors">ញឹប កុសល</p>
                        <p class="text-[14px]  md:text-sm text-slate-500">ផែនដីវិទ្យា</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 md:gap-8">
                    <div class="font-mono font-semibold text-slate-600 text-xs md:text-base">010 444 3322</div>
                    <a href="https://t.me/+855968263627" class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white active:scale-95 transition-all shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>

        </div>
    </section>
</div>

        
    </div>
   
    <script>
        function toggleSidebar() { document.getElementById('sidebar').classList.toggle('-translate-x-full'); }
        function openInfoModal() {
            const modal = document.getElementById('infoModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
      
    </script>
</body>
</html>