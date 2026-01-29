<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

// ១. ទាញយកព័ត៌មានសិស្សតាម student_id (ដូច Dashboard ប្អូន)
$s_id = $_SESSION['username'] ?? '';
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$s_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

$display_name = $student_info['full_name'] ?? ($_SESSION['full_name'] ?? $s_id);

// ២. Path រូបភាព (Logic ដូច Dashboard បេះដាក់)
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
                ? $profile_path . $student_info['profile_img'] . "?v=" . time() 
                : null;

$current_page = 'announcements.php';
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>សេចក្ដីជូនដំណឹង | Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Kantumruy Pro', sans-serif; } 
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] flex h-screen overflow-hidden">

    <?php include '../../includes/sidebar_student.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
        <header class="bg-white border-b-2 border-slate-100 h-20 flex items-center justify-between px-6 md:px-10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[18px] font-bold text-slate-900 leading-tight"><?php echo htmlspecialchars($display_name); ?></p>
                    <p class="text-[12px] text-gray-500 font-bold uppercase">អត្តលេខ: <?php echo htmlspecialchars($s_id); ?></p>
                </div>

                <div class="relative group">
                    <div class="w-16 h-16 rounded-full border-4 border-white shadow-md overflow-hidden bg-blue-600 flex items-center justify-center cursor-pointer">
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

        <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            <div class="max-w-7xl mx-auto">
                
                <div class="mb-10 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900 uppercase">សេចក្ដីជូនដំណឹង</h1>
                    <p class="text-slate-500 mt-2 text-lg italic">ព័ត៌មាន និងការប្រកាសសំខាន់ៗពីសាលារៀន</p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <?php 
                    $query = "SELECT * FROM announcements ORDER BY created_at DESC";
                    $result = mysqli_query($conn, $query);
                    
                    if($result && mysqli_num_rows($result) > 0):
                        while($row = mysqli_fetch_assoc($result)): 
                            // រៀបចំ data សម្រាប់ JavaScript Modal
                            $title = addslashes($row['title']);
                            $content = addslashes(str_replace(["\r", "\n"], ' ', $row['content']));
                            $posted_by = addslashes($row['posted_by']);
                            $date = date('d M Y | h:i A', strtotime($row['created_at']));
                    ?>
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
                            <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8">
                                
                                <div class="flex-shrink-0 flex gap-3 md:flex-col items-center justify-center w-full md:w-28 h-auto md:h-28 bg-blue-600 text-white rounded-[2rem] shadow-xl shadow-blue-100 p-4">
                                    <span class="text-3xl md:text-4xl font-black italic"><?php echo date('d', strtotime($row['created_at'])); ?></span>
                                    <span class="text-[15px] md:text-xs font-bold uppercase tracking-wider mt-1 opacity-80"><?php echo date('M Y', strtotime($row['created_at'])); ?></span>
                                </div>

                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="text-xl md:text-2xl font-bold text-slate-800 truncate"><?php echo htmlspecialchars($row['title']); ?></h2>
                                        <?php if (strtotime($row['created_at']) > strtotime('-3 days')): ?>
                                            <span class="bg-emerald-500 text-[10px] text-white px-3 py-1 rounded-full font-bold uppercase animate-pulse">New</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <p class="text-slate-600 text-base md:text-lg italic line-clamp-2 mb-6">
                                        <?php echo htmlspecialchars($row['content']); ?>
                                    </p>

                                    <div class="flex items-center justify-between pt-6 border-t border-slate-50 text-sm">
                                        <div class="flex items-center gap-4 text-slate-400 font-medium">
                                            <span><i class="far fa-user-circle text-blue-500 mr-1"></i> ដោយ៖ <span class="text-slate-800"><?php echo htmlspecialchars($row['posted_by']); ?></span></span>
                                        </div>
                                        <button onclick="viewDetail('<?= $title ?>', '<?= $content ?>', '<?= $posted_by ?>', '<?= $date ?>')" 
                                                class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-blue-600 transition-all shadow-lg text-sm">
                                            មើលលម្អិត
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; else: ?>
                        <div class="bg-white rounded-[2.5rem] p-20 text-center border-2 border-dashed border-slate-100">
                            <i class="fas fa-bullhorn text-6xl text-slate-200 mb-4"></i>
                            <p class="text-slate-400 font-bold italic text-xl">មិនទាន់មានការប្រកាសនៅឡើយទេ</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="h-20"></div> 
            </div>
        </main>
    </div>

    <div id="detailModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl overflow-hidden transform transition-all">
            <div class="p-8 md:p-12">
                <div class="flex justify-between items-start mb-6">
                    <div id="modalDate" class="text-blue-600 font-bold text-sm uppercase tracking-widest"></div>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-red-500 transition-colors">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
                <h2 id="modalTitle" class="text-2xl md:text-3xl font-black text-slate-900 mb-6 leading-tight"></h2>
                <div id="modalContent" class="text-slate-600 text-lg leading-relaxed mb-10 whitespace-pre-line italic"></div>
                <div class="pt-8 border-t border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                            <i class="fas fa-user-shield text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase font-bold">ប្រកាសដោយ</p>
                            <p id="modalAuthor" class="font-bold text-slate-800"></p>
                        </div>
                    </div>
                    <button onclick="closeModal()" class="px-8 py-3 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                        បិទវិញ
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden backdrop-blur-sm"></div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar')?.classList.toggle('-translate-x-full');
            document.getElementById('sidebar-overlay')?.classList.toggle('hidden');
        }

        function viewDetail(title, content, author, date) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalContent').innerText = content;
            document.getElementById('modalAuthor').innerText = author;
            document.getElementById('modalDate').innerText = date;
            
            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden'; 
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto'; 
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('detailModal')) closeModal();
        }
    </script>
</body>
</html>