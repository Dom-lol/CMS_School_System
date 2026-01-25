<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ទាញទិន្នន័យសរុបសម្រាប់បង្ហាញលើ Card [cite: 2026-01-20]
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM students"))['total'];
$total_teachers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(teacher_id) as total FROM teachers"))['total'];
$total_classes  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(id) as total FROM classes"))['total'];

// ២. ទាញបញ្ជីសិស្ស ៥ នាក់ចុងក្រោយ [cite: 2026-01-20]
$st_query = "SELECT s.*, c.class_name FROM students s 
             LEFT JOIN classes c ON s.class_id = c.id 
             ORDER BY s.id DESC LIMIT 5";
$students = mysqli_query($conn, $st_query);

include '../../includes/header.php'; 
?>

<div class="flex h-screen w-full bg-[#f3f4f9] overflow-hidden">
    <?php include '../../includes/sidebar_admin.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-y-auto custom-scrollbar p-10">
        
        <div class="flex justify-between items-center mb-10">
            <h1 class="text-3xl font-black text-slate-800 uppercase italic">Admin Dashboard</h1>
            <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-full shadow-sm border border-slate-100">
                <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white">
                    <i class="fas fa-user-shield"></i>
                </div>
                <span class="font-bold text-slate-700">Admin</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <div class="bg-gradient-to-br from-blue-600 to-blue-400 p-8 rounded-[2.5rem] text-white shadow-xl shadow-blue-200 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <i class="fas fa-user-graduate text-2xl opacity-80"></i>
                        <p class="font-bold text-xs uppercase tracking-widest">Total Students</p>
                    </div>
                    <h3 class="text-5xl font-black italic"><?= number_format($total_students) ?></h3>
                </div>
                <i class="fas fa-user-graduate absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
            </div>

            <div class="bg-gradient-to-br from-cyan-500 to-blue-400 p-8 rounded-[2.5rem] text-white shadow-xl shadow-cyan-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <i class="fas fa-chalkboard-teacher text-2xl opacity-80"></i>
                        <p class="font-bold text-xs uppercase tracking-widest">Total Teachers</p>
                    </div>
                    <h3 class="text-5xl font-black italic"><?= number_format($total_teachers) ?></h3>
                </div>
                <i class="fas fa-chalkboard-teacher absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
            </div>

            <div class="bg-gradient-to-br from-indigo-500 to-blue-400 p-8 rounded-[2.5rem] text-white shadow-xl shadow-indigo-100 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <i class="fas fa-school text-2xl opacity-80"></i>
                        <p class="font-bold text-xs uppercase tracking-widest">Total Classes</p>
                    </div>
                    <h3 class="text-5xl font-black italic"><?= number_format($total_classes) ?></h3>
                </div>
                <i class="fas fa-school absolute -right-4 -bottom-4 text-8xl opacity-10 group-hover:scale-110 transition-transform"></i>
            </div>
        </div>

       
    </div>
</div>