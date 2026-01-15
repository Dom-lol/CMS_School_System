<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in(); // ពិនិត្យសិទ្ធិ Admin

include '../../includes/header.php';
// កែពី sidebar.php ទៅជា sidebar_admin.php
include '../../includes/sidebar_admin.php'; 

// ទាញទិន្នន័យគ្រូទាំងអស់ពី Database
$sql = "SELECT t.*, u.full_name, u.username 
        FROM teachers t 
        JOIN users u ON t.user_id = u.id";
$teachers = mysqli_query($conn, $sql);
?>

<main class="flex-1 p-8 bg-gray-50">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">គ្រប់គ្រងគ្រូបង្រៀន</h1>
            <p class="text-slate-500 mt-1">អ្នកអាចបន្ថែម កែសម្រួល ឬលុបព័ត៌មានគ្រូបង្រៀនបាននៅទីនេះ</p>
        </div>
        <a href="add_teacher.php" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-red-200 transition flex items-center">
            <i class="fas fa-plus mr-2 text-sm"></i> បន្ថែមគ្រូថ្មី
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs">អត្តលេខ</th>
                    <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs">ឈ្មោះគ្រូ</th>
                    <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs">ឯកទេស</th>
                    <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <?php while($row = mysqli_fetch_assoc($teachers)): ?>
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 font-mono font-bold text-blue-600"><?php echo $row['teacher_id']; ?></td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800"><?php echo $row['full_name']; ?></div>
                        <div class="text-xs text-slate-400">@<?php echo $row['username']; ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md text-xs font-medium border border-indigo-100">
                            <?php echo $row['specialization'] ?? 'មិនទាន់បញ្ជាក់'; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="edit_teacher.php?id=<?php echo $row['teacher_id']; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <a href="../../actions/admin/delete_teacher.php?id=<?php echo $row['teacher_id']; ?>" 
                               onclick="return confirm('តើអ្នកពិតជាចង់លុបគ្រូនេះមែនទេ?')"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition">
                                <i class="fas fa-trash text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>