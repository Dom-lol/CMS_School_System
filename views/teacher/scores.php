<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
include '../../includes/header.php';

$teacher_id = $_SESSION['user_id'] ?? 0;

// ទាញយកមុខវិជ្ជា និងថ្នាក់ដែលគ្រូត្រូវបង្រៀន
$query = "SELECT c.class_name, s.subject_name, c.id as class_id, s.id as subject_id 
          FROM teacher_assignments ta
          JOIN classes c ON ta.class_id = c.id
          JOIN subjects s ON ta.subject_id = s.id
          WHERE ta.teacher_id = '$teacher_id'";
$result = mysqli_query($conn, $query);
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    <?php include '../../includes/sidebar_teacher.php'; ?>
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center px-10 shrink-0 shadow-sm">
            <h2 class="text-xl font-black text-slate-800 uppercase italic">ជ្រើសរើសមុខវិជ្ជាដើម្បីបញ្ចូលពិន្ទុ</h2>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="bg-white p-8 rounded-[2.5rem] border-2 border-slate-100 shadow-sm hover:border-blue-500 transition-all group">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 text-2xl group-hover:scale-110 transition-transform">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-xl mb-2"><?= $row['subject_name'] ?></h3>
                        <p class="text-sm text-blue-500 font-bold uppercase tracking-wider mb-8">ថ្នាក់រៀន៖ <?= $row['class_name'] ?></p>
                        
                        <a href="input_grades.php?class_id=<?= $row['class_id'] ?>&subject_id=<?= $row['subject_id'] ?>" 
                           class="block text-center bg-slate-900 text-white py-4 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-blue-600 transition-all shadow-lg active:scale-95">
                           បញ្ចូលពិន្ទុឥឡូវនេះ
                        </a>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-full text-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                        <p class="text-slate-400 font-bold italic">មិនទាន់មានមុខវិជ្ជាចាត់តាំងសម្រាប់លោកគ្រូនៅឡើយទេ</p>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
</div>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar'); 
        if(sidebar) sidebar.classList.toggle('-translate-x-full');
    }
</script>