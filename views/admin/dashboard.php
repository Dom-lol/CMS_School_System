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

        <div class="bg-white rounded-[3rem] p-10 shadow-sm border border-slate-100">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-black text-slate-800 uppercase italic">Student Management</h2>
                <a href="add_student.php" class="bg-blue-600 hover:bg-slate-900 text-white px-8 py-3 rounded-2xl font-bold text-sm transition-all flex items-center gap-2 shadow-lg shadow-blue-100">
                    <i class="fas fa-plus"></i> Add Student
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-50">
                            <th class="pb-6 pr-4">ID</th>
                            <th class="pb-6 pr-4">Name</th>
                            <th class="pb-6 pr-4 text-center">Gender</th>
                            <th class="pb-6 pr-4 text-center">Class</th>
                            <th class="pb-6 pr-4 text-center">Status</th>
                            <th class="pb-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php while($row = mysqli_fetch_assoc($students)): ?>
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="py-6 font-bold text-blue-600 text-sm italic">#<?= $row['student_id'] ?></td>
                            <td class="py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-100">
                                        <img src="../../assets/upload/profiles/<?= $row['profile_image'] ?: 'default.png' ?>" class="w-full h-full object-cover">
                                    </div>
                                    <span class="font-bold text-slate-700"><?= $row['full_name'] ?></span>
                                </div>
                            </td>
                            <td class="py-6 text-center">
                                <span class="px-4 py-1.5 rounded-xl text-[10px] font-black uppercase <?= $row['gender'] == 'ស្រី' ? 'bg-pink-50 text-pink-500' : 'bg-blue-50 text-blue-500' ?>">
                                    <i class="fas <?= $row['gender'] == 'ស្រី' ? 'fa-female' : 'fa-male' ?> mr-1"></i> <?= $row['gender'] ?>
                                </span>
                            </td>
                            <td class="py-6 text-center text-sm font-bold text-slate-500 italic">Grade <?= $row['class_name'] ?></td>
                            <td class="py-6 text-center">
                                <span class="px-4 py-1.5 bg-green-50 text-green-500 rounded-xl text-[10px] font-black uppercase">Active</span>
                            </td>
                            <td class="py-6">
                                <div class="flex justify-end gap-2">
                                    <button class="w-9 h-9 flex items-center justify-center bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all"><i class="fas fa-eye text-xs"></i></button>
                                    <button class="w-9 h-9 flex items-center justify-center bg-orange-50 text-orange-500 rounded-xl hover:bg-orange-500 hover:text-white transition-all"><i class="fas fa-edit text-xs"></i></button>
                                    <button class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-500 rounded-xl hover:bg-red-500 hover:text-white transition-all"><i class="fas fa-trash text-xs"></i></button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>