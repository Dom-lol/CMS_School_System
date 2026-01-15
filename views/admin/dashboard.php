<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ឆែកមើលថាជា Admin ឬអត់
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=unauthorized");
    exit();
}

include '../../includes/header.php';
include '../../includes/sidebar_admin.php'; 

// ទាញទិន្នន័យគ្រូទាំងអស់ពី Database
$sql = "SELECT * FROM users WHERE role = 'teacher' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
$total_teachers = mysqli_num_rows($result);
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">គ្រប់គ្រងគ្រូបង្រៀន</h1>
            <p class="text-slate-500 text-sm">បង្ហាញបញ្ជីឈ្មោះគ្រូបង្រៀនទាំងអស់នៅក្នុងប្រព័ន្ធ</p>
        </div>
        
        <a href="../../actions/teachers/add.php" class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl transition shadow-md shadow-blue-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            បន្ថែមគ្រូថ្មី
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="text-slate-400 text-xs font-bold uppercase tracking-wider mb-1">គ្រូបង្រៀនសរុប</div>
            <div class="text-3xl font-black text-blue-600"><?php echo $total_teachers; ?> <span class="text-sm font-medium text-slate-400">នាក់</span></div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-slate-600 text-sm uppercase font-bold">
                        <th class="px-6 py-4">អត្តលេខ / Username</th>
                        <th class="px-6 py-4">ឈ្មោះពេញ</th>
                        <th class="px-6 py-4">តួនាទី</th>
                        <th class="px-6 py-4 text-right">សកម្មភាព</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-700">
                    <?php if ($total_teachers > 0): ?>
                        <?php while($row = mysqli_fetch_assoc($result)): ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-mono text-sm text-blue-600"><?php echo $row['username']; ?></td>
                            <td class="px-6 py-4 font-medium"><?php echo $row['full_name']; ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full font-bold">
                                    <?php echo strtoupper($row['role']); ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <a href="../../actions/teachers/edit.php?id=<?php echo $row['id']; ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="កែសម្រួល">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <a href="../../actions/teachers/delete.php?id=<?php echo $row['id']; ?>" 
                                   onclick="return confirm('តើអ្នកពិតជាចង់លុបគ្រូនេះមែនទេ?')"
                                   class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" title="លុបចេញ">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">មិនមានទិន្នន័យគ្រូបង្រៀនឡើយ</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>