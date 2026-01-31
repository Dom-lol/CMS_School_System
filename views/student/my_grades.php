<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }
 include '../../includes/header.php'; 

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

// ទាញយកព័ត៌មានសិស្សពី Database ផ្ទាល់
$s_id = $_SESSION['username'] ?? '';
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$s_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

$display_name = $student_info['full_name'] ?? ($_SESSION['full_name'] ?? $s_id);

// Logic ប្តូរ class_id ទៅជាឈ្មោះថ្នាក់ (1=7, 2=8, ...)
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
        
    <div class="bg-white l shadow-sm border border-slate-200 overflow-hidden w-full ">
        <div class="overflow-x-auto lg:overflow-x-auto">
            <table class="w-full text-left lg:w-full items-center">
                <thead>
                    <tr class="bg-gray-100 border-b border-slate-100​
                    ">
                        <th class="px-6 py-4 text-black font-bold text-[14px] uppercase">លរ</th>
                        <th class="px-3 py-4 text-black font-bold text-[14px] uppercase">មុខវិជ្ជា</th>
                        <th class="px-3 py-4 text-black font-bold text-[14px] uppercase text-center">ពិន្ទុ</th>
                        <th class="px-3 py-4 text-black font-bold text-[14px] uppercase">និទ្ទិស</th>
                        <th class="px-5 py-4 text-black font-bold text-[14px] uppercase">ចំណាត់ថ្នាក់</th>
                    </tr>
                </thead>
                <tbody class=" divide-slate-100​">
                   
                        <tr class="hover:bg-blue-50/40 transition-colors group  ">
                            <td class="px-6 py-4 font-mono font-bold ">1</td>
                            <td class=" py-4 ">អក្សរសាស្រ្តខ្មែរ</td>
                            <td class=" py-4 text-center">99/125</td>
                            <td class="px-6 py-4 text-green-700 text-bold font-bold ">A</td>
                            <td class="px-9 py-4 text-[15px] text-red-600 max-w-[200px] truncate font-bold">5/30</td>
                
                        </t r>
                      
                </tbody>`
                <tbody class=" divide-slate-100">
                   
                        <tr class="hover:bg-blue-50/40 transition-colors group ">
                            <td class="px-6 py-4 font-mono font-bold ">2</td>
                            <td class=" py-4">គណិតវិទ្យា</td>
                            <td class=" py-4 text-center">113/125</td>
                            <td class="px-6 py-4 text-red-500 text-bold font-bold">B</td>
                            <td class="px-9 py-4 text-[15px] text-red-600 max-w-[200px] truncate font-bold">10/30</td>
                
                        </tr>                 
                </tbody>
                <tbody class=" divide-slate-100">
                   
                        <tr class="hover:bg-blue-50/40 transition-colors group ">
                            <td class="px-6 py-4 font-mono font-bold ">3</td>
                            <td class=" py-4">រូបវិទ្យា</td>
                            <td class=" py-4 text-center">67/100</td>
                            <td class="px-6  py-4 text-orange-500 text-bold font-bold ">C</td>
                            <td class="px-9 py-4 text-[15px] text-red-600 max-w-[200px] truncate font-bold">16/30</td>
                
                        </tr>
                      
                </tbody>
                <tbody class=" divide-slate-100">
                   
                        <tr class="hover:bg-blue-50/40 transition-colors group ">
                            <td class="px-6 py-4 font-mono font-bold ">4</td>
                            <td class=" py-4">គីមីវិទ្យា</td>
                            <td class=" py-4 text-center">90/100</td>
                            <td class="px-6  py-4 text-green-700 text-bold font-bold ">A</td>
                            <td class="px-9 py-4 text-[15px] text-red-600 max-w-[200px] truncate font-bold">5/30</td>
                
                        </tr>
                      
                </tbody>
                <tbody class=" divide-slate-100">
                   
                        <tr class="hover:bg-blue-50/40 transition-colors group ">
                            <td class="px-6 py-4 font-mono font-bold ">5</td>
                            <td class=" py-4">ជីវវិទ្យា</td>
                            <td class=" py-4 text-center">89/100</td>
                            <td class="px-6  py-4 text-green-700 text-bold font-bold ">A</td>
                            <td class="px-9 py-4 text-[15px] text-red-600 max-w-[200px] truncate font-bold">3/30</td>
                
                        </tr>
                      
                </tbody>
                <tbody class=" divide-slate-100">
                   
                        <tr class="hover:bg-blue-50/40 transition-colors group ">
                            <td class="px-6 py-4 font-mono font-bold ">6</td>
                            <td class=" py-4">ប្រវត្តិវិទ្យា</td>
                            <td class=" py-4 text-center">70/100</td>
                            <td class="px-6  py-4 text-orange-500 text-bold font-bold ">C</td>
                            <td class="px-9 py-4 text-[15px] text-red-600 max-w-[200px] truncate font-bold">12/30</td>
                
                        </tr>
                      
                </tbody>
                <tbody class=" divide-slate-100">
                   
                        <tr class="hover:bg-blue-50/40 transition-colors group ">
                            <td class="px-6 py-4 font-mono font-bold ">7</td>
                            <td class=" py-4">ផែនដីវិទ្យា</td>
                            <td class=" py-4 text-center">65/100</td>
                            <td class="px-6  py-4 text-orange-500 text-bold font-bold ">C</td>
                            <td class="px-9 py-4 text-[15px] text-red-600 max-w-[200px] truncate font-bold">22/30</td>
                
                        </tr>
                      
                </tbody>
            </table>
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
      
    </script>
</body>
</html>