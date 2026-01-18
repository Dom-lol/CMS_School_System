<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

$s_id = $_SESSION['username'] ?? '';
$display_name = $_SESSION['full_name'] ?? $s_id;
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
                <h1 class="text-xl font-bold text-slate-800 hidden md:block uppercase tracking-tight italic">Announcements</h1>
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
                
                <div class="mb-10">
                    <h1 class="text-3xl md:text-4xl font-bold text-slate-900">សេចក្ដីជូនដំណឹង</h1>
                    <p class="text-slate-500 mt-2 text-lg italic">តាមដានព័ត៌មាន និងការប្រកាសថ្មីៗពីសាលារៀន</p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <?php 
                    $query = "SELECT * FROM announcements ORDER BY created_at DESC";
                    $result = mysqli_query($conn, $query);
                    
                    if(mysqli_num_rows($result) > 0):
                        while($row = mysqli_fetch_assoc($result)): 
                    ?>
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
                            <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8">
                                
                                <div class="flex-shrink-0 flex md:flex-col items-center justify-center w-full md:w-28 h-auto md:h-28 bg-blue-600 text-white rounded-[2rem] shadow-xl shadow-blue-100 p-4">
                                    <span class="text-3xl md:text-4xl font-black italic"><?php echo date('d', strtotime($row['created_at'])); ?></span>
                                    <span class="text-[10px] md:text-xs font-bold uppercase tracking-wider mt-1 opacity-80"><?php echo date('M Y', strtotime($row['created_at'])); ?></span>
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
                                    
                                    <div class="text-slate-600 text-base md:text-lg leading-relaxed mb-6 italic line-clamp-3">
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
                                        <button class="px-6 py-2 bg-slate-900 text-white rounded-xl font-bold hover:bg-blue-600 transition-all shadow-lg shadow-slate-100 text-sm">
                                            មើលលម្អិត
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endwhile; 
                    else: 
                    ?>
                        <div class="bg-white rounded-[2.5rem] p-20 text-center border-2 border-dashed border-slate-100">
                            <i class="fas fa-bullhorn text-6xl text-slate-200 mb-4"></i>
                            <p class="text-slate-400 font-bold italic text-xl">មិនទាន់មានការប្រកាសថ្មីៗនៅឡើយទេ</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="h-20"></div> </div>
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