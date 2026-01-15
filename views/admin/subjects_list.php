<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in(); // ពិនិត្យសិទ្ធិ Admin

include '../../includes/header.php';
include '../../includes/sidebar_admin.php'; 

// ទាញទិន្នន័យមុខវិជ្ជាទាំងអស់
$sql = "SELECT * FROM subjects ORDER BY id DESC";
$subjects = mysqli_query($conn, $sql);
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">គ្រប់គ្រងមុខវិជ្ជា</h1>
            <p class="text-slate-500 mt-1">បញ្ជីមុខវិជ្ជាសិក្សាទាំងអស់នៅក្នុងប្រព័ន្ធ</p>
        </div>
        <a href="add_subject.php" class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg shadow-red-200 transition flex items-center">
            <i class="fas fa-plus mr-2 text-sm"></i> បន្ថែមមុខវិជ្ជា
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs w-20 text-center">ល.រ</th>
                    <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs">ឈ្មោះមុខវិជ្ជា</th>
                    <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs">កូដមុខវិជ្ជា</th>
                    <th class="px-6 py-4 text-slate-600 font-bold uppercase text-xs text-center">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                <?php 
                $i = 1;
                if (mysqli_num_rows($subjects) > 0):
                    while($row = mysqli_fetch_assoc($subjects)): 
                ?>
                <tr class="hover:bg-slate-50/50 transition">
                    <td class="px-6 py-4 text-center text-slate-500 font-medium"><?php echo $i++; ?></td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-slate-800 text-base"><?php echo $row['subject_name']; ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-blue-50 text-blue-700 px-3 py-1 rounded-md text-xs font-mono font-bold border border-blue-100">
                            <?php echo $row['subject_code'] ?? 'SUB-'.$row['id']; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="edit_subject.php?id=<?php echo $row['id']; ?>" class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition" title="កែសម្រួល">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                            <a href="../../actions/admin/delete_subject.php?id=<?php echo $row['id']; ?>" 
                               onclick="return confirm('តើអ្នកពិតជាចង់លុបមុខវិជ្ជានេះមែនទេ?')"
                               class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition" title="លុប">
                                <i class="fas fa-trash text-xs"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php 
                    endwhile; 
                else:
                ?>
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                        មិនទាន់មានមុខវិជ្ជាក្នុងប្រព័ន្ធនៅឡើយទេ។
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>