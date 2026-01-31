<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

// Check Role
if ($_SESSION['role'] !== 'staff' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=unauthorized");
    exit();
}

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// search class
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$class_filter = isset($_GET['class']) ? mysqli_real_escape_string($conn, $_GET['class']) : '';

// 
$query = "SELECT * FROM students WHERE 1=1";
if ($search) {
    $query .= " AND (full_name LIKE '%$search%' OR full_name_en LIKE '%$search%' OR student_id LIKE '%$search%')";
}
if ($class_filter) {
    $query .= " AND class_name = '$class_filter'";
}
$query .= " ORDER BY id DESC";
$students = mysqli_query($conn, $query);

// 
$total_students = mysqli_num_rows($students);
$male_count = 0;
$female_count = 0;

// 
$student_list = [];
while($row = mysqli_fetch_assoc($students)) {
    $student_list[] = $row;
    if($row['gender'] == 'ប្រុស') $male_count++;
    if($row['gender'] == 'ស្រី') $female_count++;
}

// 
$classes = mysqli_query($conn, "SELECT DISTINCT class_name FROM students WHERE class_name != '' ORDER BY class_name ASC");
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <?php if(isset($_GET['import_success'])): ?>
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl flex items-center shadow-sm">
            <i class="fas fa-check-circle mr-3 text-xl"></i>
            <div>
                <span class="font-bold">ជោគជ័យ!</span> 
                បានបញ្ចូលទិន្នន័យសិស្សចំនួន <?php echo (int)$_GET['import_success']; ?> នាក់ទៅក្នុងប្រព័ន្ធ។
            </div>
        </div>
    <?php endif; ?>

    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">គ្រប់គ្រងសិស្សានុសិស្ស</h1>
           
        </div>
        <div class="flex gap-3">
            
            <a href="add_student.php" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition flex items-center">
                <i class="fas fa-user-plus mr-2"></i> បន្ថែមសិស្ស
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 p-6 rounded-3xl shadow-lg shadow-blue-100 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-white text-[15px] font-bold uppercase tracking-wider">សិស្សសរុប</p>
                    <h3 class="text-4xl font-black mt-2"><?php echo $total_students; ?> <span class="text-lg font-normal">នាក់</span></h3>
                </div>
                <div class="bg-white/20 p-3 rounded-2xl"><i class="fas fa-users text-2xl"></i></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-[15px] font-bold uppercase tracking-wider">សិស្សប្រុស</p>
                    <h3 class="text-4xl font-black mt-2 text-slate-800"><?php echo $male_count; ?> <span class="text-lg font-normal text-slate-400">នាក់</span></h3>
                </div>
                <div class="bg-blue-50 p-3 rounded-2xl text-blue-600"><i class="fas fa-mars text-2xl"></i></div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-[15px] font-bold uppercase tracking-wider">សិស្សស្រី</p>
                    <h3 class="text-4xl font-black mt-2 text-slate-800"><?php echo $female_count; ?> <span class="text-lg font-normal text-slate-400">នាក់</span></h3>
                </div>
                <div class="bg-pink-50 p-3 rounded-2xl text-pink-600"><i class="fas fa-venus text-2xl"></i></div>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mb-8">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[250px]">
                <label class="block text-[15px] font-bold text-gray-500 uppercase mb-2 ml-1">ស្វែងរកសិស្ស</label>
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="ឈ្មោះ ឬអត្តលេខសិស្ស..." 
                           class="w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition">
                </div>
            </div>
            <div class="w-48">
                <label class="block text-[15px]  font-bold text-slate-400 uppercase mb-2 ml-1">ជ្រើសរើសថ្នាក់</label>
                <select name="class" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">ទាំងអស់</option>
                    <?php while($c = mysqli_fetch_assoc($classes)): ?>
                        <option value="<?php echo $c['class_name']; ?>" <?php echo ($class_filter == $c['class_name']) ? 'selected' : ''; ?>>
                            ថ្នាក់ទី <?php echo $c['class_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="bg-slate-800 text-white px-8 py-3 rounded-xl font-bold hover:bg-slate-700 transition">
                ស្វែងរក
            </button>
            <a href="student_list.php" class="bg-slate-100 text-slate-500 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition text-center">Reset</a>
        </form>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-100 border-b border-slate-100">
                        <th class="px-6 py-4 text-black font-bold text-[18px] uppercase">អត្តលេខ</th>
                        <th class="px-6 py-4 text-black font-bold text-[18px] uppercase">ឈ្មោះសិស្ស</th>
                        <th class="px-6 py-4 text-black font-bold text-[18px] uppercase text-center">ភេទ</th>
                        <th class="px-6 py-4 text-black font-bold text-[18px] uppercase">ថ្ងៃខែឆ្នាំកំណើត</th>
                        <th class="px-6 py-4 text-black font-bold text-[18px] uppercase">ទីកន្លែងកំណើត / អាសយដ្ឋាន</th>
                        <th class="px-6 py-4 text-black font-bold text-[18px] uppercase text-center">ថ្នាក់</th>
                        <th class="px-6 py-4 text-black font-bold text-[18px] uppercase text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php if(count($student_list) > 0): ?>
                        <?php foreach($student_list as $row): ?>
                        <tr class="hover:bg-blue-50/40 transition-colors group">
                            <td class="px-6 py-4 font-mono font-bold text-blue-600"><?php echo $row['student_id']; ?></td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-700"><?php echo $row['full_name']; ?></div>
                                <div class="text-xs text-gray-700 font-medium tracking-wide"><?php echo $row['full_name_en']; ?></div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo ($row['gender'] == 'ប្រុស') ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600'; ?>">
                                    <?php echo $row['gender']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700 text-sm italic"><?php echo $row['dob']; ?></td>
                            <td class="px-6 py-4 text-xs text-gray-700 max-w-[200px] truncate">
                                <span title="<?php echo $row['pob']; ?>">POB: <?php echo $row['pob']; ?></span><br>
                                <span title="<?php echo $row['address']; ?>" class="text-slate-400">Addr: <?php echo $row['address']; ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold">
                                    <?php echo $row['class_name']; ?>
                                </span>
                            </td>
                           <td class="px-6 py-4">
                                <div class="flex justify-end gap-2 transition-all">
                                    <a href="view_student.php?id=<?php echo $row['student_id']; ?>" 
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-all shadow-sm" 
                                    title="មើល">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" 
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-all shadow-sm" 
                                    title="កែប្រែ">
                                        <i class="fas fa-user-edit"></i>
                                    </a>
                                    
                                    <button onclick="confirmDelete('<?php echo $row['student_id']; ?>', '<?php echo $row['full_name']; ?>')" 
                                            class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-all shadow-sm" 
                                            title="លុប">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="px-6 py-16 text-center text-slate-400 italic">មិនមានទិន្នន័យសិស្សក្នុងប្រព័ន្ធឡើយ។</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
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