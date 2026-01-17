<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

// ឆែកសិទ្ធិបុគ្គលិក
if ($_SESSION['role'] !== 'staff' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=unauthorized");
    exit();
}

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ស្វែងរកតាមឈ្មោះ ឬថ្នាក់
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$class_filter = isset($_GET['class']) ? mysqli_real_escape_string($conn, $_GET['class']) : '';

// Query ទាញយកទិន្នន័យ (កែសម្រួលដើម្បីកុំឱ្យបាត់ព័ត៌មាន)
$query = "SELECT s.*, u.username 
          FROM students s 
          LEFT JOIN users u ON s.user_id = u.id 
          WHERE 1=1";

if ($search) {
    $query .= " AND (s.full_name LIKE '%$search%' OR s.full_name_en LIKE '%$search%' OR s.student_id LIKE '%$search%')";
}

if ($class_filter) {
    $query .= " AND s.class_name = '$class_filter'";
}

$students = mysqli_query($conn, $query);
$classes = mysqli_query($conn, "SELECT DISTINCT class_name FROM students");
?> <main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">បញ្ជីឈ្មោះសិស្សសរុប</h1>
            <p class="text-slate-500 mt-1">គ្រប់គ្រង និងកែប្រែព័ត៌មានសិស្សានុសិស្ស</p>
        </div>
        <div class="flex gap-2">
             <a href="add_student.php" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-user-plus mr-2"></i> បន្ថែមសិស្ស
            </a>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">ស្វែងរក</label>
                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="ឈ្មោះ ឬអត្តលេខ..." 
                       class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
            </div>
            <div class="w-48">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">ថ្នាក់រៀន</label>
                <select name="class" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">ទាំងអស់</option>
                    <?php 
                    mysqli_data_seek($classes, 0);
                    while($c = mysqli_fetch_assoc($classes)): 
                    ?>
                        <option value="<?php echo $c['class_name']; ?>" <?php echo ($class_filter == $c['class_name']) ? 'selected' : ''; ?>>
                            ថ្នាក់ <?php echo $c['class_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="bg-slate-800 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-600 transition">
                <i class="fas fa-search mr-1"></i> Search
            </button>
            <a href="student_list.php" class="bg-slate-100 text-slate-500 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-4 text-slate-600 font-bold text-xs uppercase">អត្តលេខ</th>
                    <th class="px-6 py-4 text-slate-600 font-bold text-xs uppercase">ឈ្មោះសិស្ស</th>
                    <th class="px-6 py-4 text-slate-600 font-bold text-xs uppercase text-center">ភេទ</th>
                    <th class="px-6 py-4 text-slate-600 font-bold text-xs uppercase">ថ្ងៃខែឆ្នាំកំណើត</th>
                    <th class="px-6 py-4 text-slate-600 font-bold text-xs uppercase text-center">ថ្នាក់</th>
                    <th class="px-6 py-4 text-slate-600 font-bold text-xs uppercase text-right">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if(mysqli_num_rows($students) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($students)): ?>
                    <tr class="hover:bg-blue-50/30 transition">
                        <td class="px-6 py-4 font-mono font-bold text-blue-600"><?php echo $row['student_id']; ?></td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-700"><?php echo $row['full_name']; ?></div>
                            <div class="text-xs text-slate-400 uppercase"><?php echo $row['full_name_en']; ?></div>
                        </td>
                        <td class="px-6 py-4 text-center text-slate-600"><?php echo $row['gender']; ?></td>
                        <td class="px-6 py-4 text-slate-600 text-sm">
                            <?php echo ($row['dob'] != '0000-00-00') ? date('d-M-Y', strtotime($row['dob'])) : '---'; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-white border border-slate-200 text-slate-600 rounded-lg text-xs font-bold shadow-sm">
                                <?php echo $row['class_name']; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                <a href="view_student.php?id=<?php echo $row['student_id']; ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-green-50 text-green-600 hover:bg-green-600 hover:text-white transition-all">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="fas fa-user-edit"></i>
                                </a>
                                <button onclick="confirmDelete('<?php echo $row['student_id']; ?>', '<?php echo $row['full_name']; ?>')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">មិនមានទិន្នន័យសិស្សឡើយ</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
function confirmDelete(id, name) {
    if (confirm("តើអ្នកប្រាកដថាចង់លុបសិស្ស '" + name + "' នេះមែនទេ?")) {
        window.location.href = "../../actions/staff/delete_student.php?id=" + id;
    }
}
</script>

<?php include '../../includes/footer.php'; ?>