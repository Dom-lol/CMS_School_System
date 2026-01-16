<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ពិនិត្យសិទ្ធិ (ប្រាកដថា role ក្នុង session.php គឺជា admin)
is_logged_in(); 

include '../../includes/header.php';
include '../../includes/sidebar_admin.php'; 

// ទាញទិន្នន័យសិស្ស ដោយ JOIN ជាមួយ Table Users ដើម្បីយកឈ្មោះពេញ
$sql = "SELECT s.*, u.full_name, u.username 
        FROM students s 
        JOIN users u ON s.student_id = u.username";
$students = mysqli_query($conn, $sql);
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">គ្រប់គ្រងសិស្ស</h1>
            <p class="text-slate-500 mt-1">អ្នកអាចគ្រប់គ្រងព័ត៌មាន និងពិនិត្យមើលស្ថានភាពសិស្សទាំងអស់</p>
        </div>
        <a href="add_student.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-200 transition flex items-center">
            <i class="fas fa-user-plus mr-2 text-sm"></i> បន្ថែមសិស្សថ្មី
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs">អត្តលេខ</th>
                        <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs">ឈ្មោះសិស្ស</th>
                        <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs text-center">ភេទ</th>
                        <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs">ថ្នាក់រៀន</th>
                        <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs">ស្ថានភាព</th>
                        <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs text-center">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    <?php if (mysqli_num_rows($students) > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($students)): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-mono font-bold text-blue-600"><?php echo $row['student_id']; ?></td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800"><?php echo $row['full_name']; ?></div>
                               
                            </td>
                            <td class="px-6 py-4 text-center"><?php echo $row['gender']; ?></td>
                            <td class="px-6 py-4">
                                <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-100">
                                    <?php echo $row['class_name']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php 
                                    $status_class = ($row['status'] == 'Active') ? 'text-green-600 bg-green-50 border-green-100' : 'text-red-600 bg-red-50 border-red-100';
                                ?>
                                <span class="px-2 py-1 rounded text-[10px] font-black uppercase border <?php echo $status_class; ?>">
                                    <?php echo $row['status'] ?? 'Active'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <a href="../../actions/admin/delete_student.php?id=<?php echo $row['student_id']; ?>" 
                                       onclick="return confirm('តើអ្នកពិតជាចង់លុបសិស្សនេះមែនទេ?')"
                                       class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition">
                                        <i class="fas fa-trash text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                មិនទាន់មានទិន្នន័យសិស្សក្នុងប្រព័ន្ធឡើយ។
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>