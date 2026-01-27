<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

$u_id = $_SESSION['user_id'];
$s_id = $_SESSION['username'] ?? '';

// ទាញព័ត៌មានសិស្ស
$student_info_query = mysqli_query($conn, "SELECT full_name, profile_img FROM students WHERE user_id = '$u_id' LIMIT 1");

if ($student_info_query && mysqli_num_rows($student_info_query) > 0) {
    $student_data = mysqli_fetch_assoc($student_info_query);
    $display_name = $student_data['full_name']; 
    $profile_img_name = $student_data['profile_img'];
} else {
    $display_name = $s_id; 
    $profile_img_name = null;
}

// path profile img
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($profile_img_name) && file_exists($profile_path . $profile_img_name)) 
                ? $profile_path . $profile_img_name . "?v=" . time() 
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
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#f8fafc] flex h-screen overflow-hidden">

    <?php include '../../includes/sidebar_student.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        
    <!-- ===== header profile img ===== -->
         <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right">
                    <p class="text-[18px] font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
                    <p class="text-[11px] text-gray-400 font-bold uppercase tracking-widest">អត្តលេខ: <?php echo $s_id; ?></p>
                </div>
                
                <div class="relative group">
                    <div class="w-14 h-14 rounded-full border-4 border-white shadow-md overflow-hidden bg-blue-600 flex items-center justify-center">
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
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900">សេចក្ដីជូនដំណឹង</h1>
                    <p class="text-slate-500 mt-2 text-lg italic">តាមដានព័ត៌មាន និងការប្រកាសថ្មីៗពីសាលារៀន</p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <?php 
                    $query = "SELECT * FROM announcements ORDER BY created_at DESC";
                    $result = mysqli_query($conn, $query);
                    
                    if($result && mysqli_num_rows($result) > 0):
                        while($row = mysqli_fetch_assoc($result)): 
                    ?>
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
                            <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8">
                                
                                <div class="flex-shrink-0 flex gap-3 md:flex-col items-center justify-center w-full md:w-28 h-auto md:h-28 bg-blue-600 text-white rounded-[2rem] shadow-xl shadow-blue-100 p-4">
                                    <span class="text-3xl md:text-4xl font-black italic"><?php echo date('d', strtotime($row['created_at'])); ?></span>
                                    <span class="text-[15px] md:text-xs font-bold uppercase tracking-wider mt-1 opacity-80"><?php echo date('M Y', strtotime($row['created_at'])); ?></span>
                                </div>

                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <div class="flex items-center justify-between mb-3">
                                        <h2 class="text-xl md:text-2xl font-bold text-slate-800 truncate">
                                            <?php echo htmlspecialchars($row['title']); ?>
                                        </h2>
                                        <?php if (strtotime($row['created_at']) > strtotime('-3 days')): ?>
                                            <span class="bg-emerald-500 text-[10px] text-white px-3 py-1 rounded-full font-bold uppercase animate-pulse">New</span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="text-slate-600 text-base md:text-lg leading-relaxed mb-6 italic line-clamp-2">
                                        <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                                    </div>

                                    <div class="flex items-center justify-between pt-6 border-t border-slate-50 text-sm">
                                        <div class="flex items-center gap-6">
                                            <span class="flex items-center gap-2 text-slate-400 font-medium">
                                                <i class="far fa-user-circle text-blue-500 text-lg"></i>
                                                ដោយ៖ <span class="text-slate-800 font-bold"><?php echo htmlspecialchars($row['posted_by']); ?></span>
                                            </span>
                                            <span class="hidden md:flex items-center gap-2 text-slate-400 font-medium uppercase">
                                                <i class="far fa-clock text-blue-500 text-lg"></i>
                                                <?php echo date('h:i A', strtotime($row['created_at'])); ?>
                                            </span>
                                        </div>
                                        
                                        <button onclick="viewDetail('<?= addslashes($row['title']) ?>', '<?= addslashes(str_replace(["\r", "\n"], ' ', $row['content'])) ?>', '<?= $row['posted_by'] ?>', '<?= date('d M Y | h:i A', strtotime($row['created_at'])) ?>')" 
                                                class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-blue-600 transition-all shadow-lg shadow-slate-100 text-sm">
                                            មើលលម្អិត
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; else: ?>
                        <div class="bg-white rounded-[2.5rem] p-20 text-center border-2 border-dashed border-slate-100">
                            <i class="fas fa-bullhorn text-6xl text-slate-200 mb-4"></i>
                            <p class="text-slate-400 font-bold italic text-xl">មិនទាន់មានការប្រកាសថ្មីៗនៅឡើយទេ</p>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="h-20"></div> 
            </div>
        </main>
    </div>

    <div id="detailModal" class="fixed inset-0 z-[60] hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div onclick="closeModal()" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="p-8 md:p-12">
                    <div class="flex justify-between items-start mb-6">
                        <div id="modalDate" class="text-blue-600 font-bold text-sm uppercase tracking-widest"></div>
                        <button onclick="closeModal()" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fas fa-times text-2xl"></i>
                        </button>
                    </div>
                    <h2 id="modalTitle" class="text-2xl md:text-3xl font-black text-slate-900 mb-6 leading-tight"></h2>
                    <div id="modalContent" class="text-slate-600 text-lg leading-relaxed mb-10 whitespace-pre-line"></div>
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
                            បិទផ្ទាំងនេះ
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden backdrop-blur-sm"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
            if (overlay) {
                overlay.classList.toggle('hidden');
            }
        }

        function viewDetail(title, content, author, date) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalContent').innerText = content;
            document.getElementById('modalAuthor').innerText = author;
            document.getElementById('modalDate').innerText = date;
            
            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden'; 
        }

        function closeModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto'; 
        }

        // បិទ Modal ពេលចុច Esc
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>

    
</body>
</html>