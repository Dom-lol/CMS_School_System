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
                        <img src="https://cdn-icons-png.flaticon.com/512/219/219983.png" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                       
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
                        <img src="data:image/jpeg;base64,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" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                      
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-[17px] md:text-base transition-colors">ចាន់ ថា</p>
                        <p class="text-[14px]  md:text-sm text-slate-500">គណិតវិទ្យា</p>
                    </div>
                </div>
                <div class="flex items-center gap-4 md:gap-8">
                    <div class="font-mono font-semibold text-slate-600 text-xs md:text-base">096 404 4728</div>
                    <a href="https://t.me/+855964044728" class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-green-50 text-green-600 flex items-center justify-center hover:bg-green-600 hover:text-white active:scale-95 transition-all shadow-sm">
                        <i class="fa-solid fa-phone"></i>
                    </a>
                </div>
            </div>

            <div class="bg-white border border-slate-100 rounded-2xl p-4 flex items-center justify-between hover:shadow-lg hover:border-blue-100 transition-all duration-300 group">
                <div class="flex items-center gap-4">
                    <div class="relative flex-shrink-0">
                        <img src="https://cdn-icons-png.flaticon.com/512/219/219983.png" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                      
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
                        <img src="data:image/jpeg;base64,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" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                       
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
                        <img src="https://cdn-icons-png.flaticon.com/512/219/219983.png" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                        
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
                        <img src="data:image/jpeg;base64,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" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                       
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
                        <img src="https://cdn-icons-png.flaticon.com/512/219/219983.png" class="w-15 h-15 md:w-14 md:h-14 rounded-full object-cover border-2 border-slate-50 shadow-sm">
                       
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