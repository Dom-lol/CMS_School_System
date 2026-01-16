<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$current_page = 'teachers_list.php';
include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

// ទាញយកព័ត៌មានគ្រូ ដោយភ្ជាប់ជាមួយឈ្មោះចេញពីតារាង users
$query = "SELECT t.teacher_id, u.full_name, t.major, t.phone, u.email 
          FROM teachers t 
          JOIN users u ON t.user_id = u.id 
          ORDER BY t.teacher_id DESC";

$query = "SELECT t.*, u.full_name 
          FROM teachers t 
          INNER JOIN users u ON t.user_id = u.id";
$result = mysqli_query($conn, $query);
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen font-['Kantumruy_Pro']">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">បញ្ជីគ្រូបង្រៀន</h1>
            <p class="text-slate-500 mt-1">គ្រប់គ្រង និងមើលព័ត៌មានលម្អិតរបស់គ្រូបង្រៀនទាំងអស់</p>
        </div>
        <a href="add_teacher.php" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200 flex items-center gap-2">
            <i class="fas fa-plus"></i> បន្ថែមគ្រូថ្មី
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 text-slate-600 uppercase text-xs tracking-wider">
                    <th class="p-5 font-semibold">Teacher ID</th>
                    <th class="p-5 font-semibold">ឈ្មោះពេញ</th>
                    <th class="p-5 font-semibold">ជំនាញ</th>
                    <th class="p-5 font-semibold">លេខទូរស័ព្ទ</th>
                    <th class="p-5 font-semibold text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                <?php if(mysqli_num_rows($result) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr class="hover:bg-blue-50/30 transition group">
                        <td class="p-5">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg font-bold text-sm">
                                #<?php echo $row['teacher_id']; ?>
                            </span>
                        </td>
                        <td class="p-5">
                            <div class="font-bold text-slate-800"><?php echo $row['full_name']; ?></div>
                            <div class="text-xs text-slate-400"><?php echo $row['email']; ?></div>
                        </td>
                        <td class="p-5 text-slate-600 font-medium"><?php echo $row['major']; ?></td>
                        <td class="p-5 text-slate-600 italic"><?php echo $row['phone']; ?></td>
                        <td class="p-5">
                            <div class="flex justify-center gap-3">
                                <a href="edit_teacher.php?id=<?php echo $row['teacher_id']; ?>" 
                                   class="w-9 h-9 flex items-center justify-center rounded-xl bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="../../actions/teachers/delete.php?id=<?php echo $row['teacher_id']; ?>" 
                                   onclick="return confirm('តើអ្នកពិតជាចង់លុបគ្រូនេះមែនទេ?')"
                                   class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="p-10 text-center text-slate-400 italic">មិនទាន់មានទិន្នន័យគ្រូបង្រៀននៅឡើយទេ</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>