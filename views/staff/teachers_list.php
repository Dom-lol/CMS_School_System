<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();
include '../../includes/header.php';
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden font-['Kantumruy_Pro']">
    <?php include '../../includes/sidebar_staff.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0">
            <h2 class="text-xl font-bold text-slate-800 italic uppercase">បញ្ជីឈ្មោះគ្រូបង្រៀន</h2>
            <a href="add_teacher.php" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 shadow-lg transition-all">
                <i class="fas fa-plus mr-2"></i> បន្ថែមគ្រូថ្មី
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <div class="w-full">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b-2 border-slate-100">
                            <tr>
                                <th class="p-6 text-xs font-black text-slate-500 uppercase">គ្រូបង្រៀន</th>
                                <th class="p-6 text-xs font-black text-slate-500 uppercase">ឯកទេស</th>
                                <th class="p-6 text-xs font-black text-slate-500 uppercase">លេខទូរស័ព្ទ</th>
                                <th class="p-6 text-xs font-black text-slate-500 uppercase text-center">សកម្មភាព</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php 
                            $res = mysqli_query($conn, "SELECT t.*, u.username FROM teachers t JOIN users u ON t.user_id = u.id");
                            while($row = mysqli_fetch_assoc($res)): 
                            ?>
                            <tr class="hover:bg-blue-50/50 transition-all">
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <img src="../../assets/uploads/teachers/<?= !empty($row['profile_image']) ? $row['profile_image'] : 'default_user.png' ?>" 
                                             class="w-12 h-12 rounded-2xl object-cover border-2 border-white shadow-sm">
                                        <div>
                                            <div class="font-bold text-slate-800"><?= $row['full_name'] ?></div>
                                            <div class="text-[10px] text-blue-500 font-black italic">USER: <?= $row['username'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6 italic text-slate-500 font-medium"><?= $row['subjects'] ?></td>
                                <td class="p-6 font-bold text-slate-600"><?= $row['phone'] ?></td>
                                <td class="p-6 text-center">
                                    <a href="edit_teacher.php?id=<?= $row['teacher_id'] ?>" class="p-3 bg-slate-100 text-slate-600 rounded-xl hover:bg-amber-100 hover:text-amber-600 transition-all"><i class="fas fa-edit"></i></a>
                                    <a href="../../actions/teachers/delete.php?id=<?= $row['user_id'] ?>" onclick="return confirm('លុបគ្រូនេះ?')" class="p-3 bg-slate-100 text-slate-600 rounded-xl hover:bg-red-100 hover:text-red-600 transition-all ml-2"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>