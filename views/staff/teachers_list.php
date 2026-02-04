<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

//  Logic Filter
$search  = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$subject_filter = isset($_GET['subject']) ? mysqli_real_escape_string($conn, $_GET['subject']) : '';

$page    = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit   = 10;
$offset  = ($page - 1) * $limit;

//
$where_clauses = ["(t.full_name LIKE '%$search%' OR t.teacher_id LIKE '%$search%')"];
if (!empty($subject_filter)) {
    $where_clauses[] = "t.subjects = '$subject_filter'";
}
$where_sql = implode(' AND ', $where_clauses);

// 
$query = "SELECT t.*, u.username FROM teachers t 
          JOIN users u ON t.user_id = u.id 
          WHERE $where_sql
          ORDER BY t.teacher_id DESC LIMIT $limit OFFSET $offset";

$res = mysqli_query($conn, $query);

// Dropdown Filter
$subjects_res = mysqli_query($conn, "SELECT DISTINCT subjects FROM teachers WHERE subjects != ''");

// 
$total_res = mysqli_query($conn, "SELECT COUNT(*) as total FROM teachers t WHERE $where_sql");
$total_data = mysqli_fetch_assoc($total_res)['total'];
$total_pages = ceil($total_data / $limit);

include '../../includes/header.php';
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden font-['Kantumruy_Pro']">
    <?php include '../../includes/sidebar_staff.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-28 flex items-center px-10 shrink-0 gap-6">
            

            <form action="" method="GET" class="flex-1 flex gap-4 items-center">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="<?php echo $search; ?>" 
                           placeholder="ស្វែងរកឈ្មោះ ឬ អត្តលេខ..." 
                           class="w-full pl-14 pr-5 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-blue-500 transition-all text-sm">
                </div>

                <select name="subject" onchange="this.form.submit()" 
                        class="w-48 px-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none focus:border-blue-500 text-sm font-bold text-slate-600">
                    <option value="">គ្រប់ឯកទេស</option>
                    <?php while($s = mysqli_fetch_assoc($subjects_res)): ?>
                        <option value="<?= $s['subjects'] ?>" <?= $subject_filter == $s['subjects'] ? 'selected' : '' ?>>
                            <?= $s['subjects'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <button type="submit" class="bg-slate-800 text-white px-6 py-3 rounded-2xl font-bold hover:bg-slate-700 transition shadow-sm cursor-pointer">
                    ស្វែងរក
                </button>
            </form>

            <a href="add_teacher.php" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-100 transition-all flex items-center gap-2 cursor-pointer">
                <i class="fas fa-plus"></i> បន្ថែមថ្មី
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            <div class="w-full bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 border-b-2 border-slate-100">
                        <tr>
                            <th class="p-6 text-[15px] font-black text-slate-700 uppercase">គ្រូបង្រៀន</th>
                            <th class=" text-[15px] font-black text-slate-700 uppercase">អត្តលេខ</th>
                            <th class="p-6 text-[15px] font-black text-slate-700 uppercase text-center">ឯកទេស</th>
                            <th class="p-6 text-[15px] font-black text-slate-700 uppercase text-center">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php while($row = mysqli_fetch_assoc($res)): ?>
                        <tr class="hover:bg-blue-50/30 transition-all group">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <img src="../../assets/uploads/teachers/<?= !empty($row['profile_image']) ? $row['profile_image'] : 'default_user.png' ?>" 
                                         class="w-22 h-22 rounded-[50%] object-cover shadow-sm ">
                                    <div>
                                        <div class="font-bold text-slate-800"><?= $row['full_name'] ?></div>
                                        <div class="text-[13px] text-slate-700"><?= $row['phone'] ?></div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                <span class="text-sm font-bold text-blue-600"><?= $row['teacher_id'] ?></span>
                            </td>
                            <td class="p-6 text-center">
                                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[13px] font-black uppercase">
                                    <?= $row['subjects'] ?>
                                </span>
                            </td>
                            <td class="p-6">
                                <div class="flex justify-center gap-2">
                                    <a href="edit_teacher.php?id=<?= $row['teacher_id'] ?>" class="w-9 h-9 flex items-center justify-center bg-slate-100 text-slate-400 rounded-xl hover:bg-amber-100 hover:text-amber-600 transition-all"><i class="fas fa-edit"></i></a>
                                    <a href="../../actions/teachers/delete.php?id=<?= $row['user_id'] ?>" onclick="return confirm('លុប?')" class="w-9 h-9 flex items-center justify-center bg-slate-100 text-slate-400 rounded-xl hover:bg-red-100 hover:text-red-600 transition-all"><i class="fas fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                
                </div>
        </main>
    </div>
</div>