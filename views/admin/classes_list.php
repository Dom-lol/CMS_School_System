<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// កំណត់ទំព័របច្ចុប្បន្នសម្រាប់ Sidebar
$current_page = basename($_SERVER['PHP_SELF']);

include '../../includes/header.php'; 
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden font-['Kantumruy_Pro']">
    <?php include '../../includes/sidebar_admin.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0">
            <div>
                <h2 class="text-xl font-bold text-slate-800 italic uppercase">គ្រប់គ្រងថ្នាក់រៀន</h2>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">បញ្ជីថ្នាក់ និងការគ្រប់គ្រងសិស្សតាមថ្នាក់</p>
            </div>
            <a href="add_class.php" class="bg-blue-600 text-white px-8 py-4 rounded-2xl font-bold hover:bg-slate-900 shadow-lg shadow-blue-100 transition-all flex items-center gap-3">
                <i class="fas fa-plus"></i> បង្កើតថ្នាក់ថ្មី
            </a>
        </header>

        <main class="flex-1 overflow-y-auto p-10 bg-[#f3f4f9] custom-scrollbar">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                            <th class="p-8">ឈ្មោះថ្នាក់រៀន</th>
                            <th class="p-8">ឆ្នាំសិក្សា</th>
                            <th class="p-8">ស្ថានភាព</th>
                            <th class="p-8 text-right">សកម្មភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php 
                        // ទាញទិន្នន័យឱ្យត្រូវតាម Column ក្នុង Database របស់លោកគ្រូ (id, class_name)
                        $sql = "SELECT * FROM classes ORDER BY id DESC";
                        $res = mysqli_query($conn, $sql);

                        if($res && mysqli_num_rows($res) > 0):
                            while($row = mysqli_fetch_assoc($res)): 
                        ?>
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="p-8">
                                <div class="flex items-center gap-4">
                                   
                                    <div>
                                        <div class="font-black  text-slate-800 text-[18px] italic uppercase tracking-tight">
                                            <?= $row['class_name'] ?>
                                        </div>
                                        
                                    </div>
                                </div>
                            </td>
                            <td class="p-8">
                                <span class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl text-[11px] font-black italic">
                                    <?= !empty($row['academic_year']) ? $row['academic_year'] : '2025-2026' ?>
                                </span>
                            </td>
                            <td class="p-8 text-sm font-bold text-green-500 italic">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                    Active Now
                                </span>
                            </td>
                            <td class="p-8">
                                <div class="flex justify-end gap-3">
                                    <a href="class_student_details.php?class_id=<?= $row['id'] ?>" 
                                       class="flex items-center gap-2 px-4 py-2.5 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all font-bold text-xs shadow-sm border border-blue-100">
                                        <i class="fas fa-users-viewfinder"></i>
                                        បញ្ជីសិស្ស
                                    </a>

                                    <a href="edit_class.php?id=<?= $row['id'] ?>" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-orange-500 hover:text-white transition-all shadow-sm border border-slate-100">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>

                                    <button onclick="confirmDelete(<?= $row['id'] ?>)" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-red-500 hover:text-white transition-all shadow-sm border border-slate-100">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="4" class="p-24 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <i class="fas fa-layer-group text-7xl mb-6"></i>
                                    <p class="text-xl font-black italic uppercase tracking-widest">មិនទាន់មានទិន្នន័យថ្នាក់រៀន</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
function confirmDelete(id) {
    if(confirm('តើលោកគ្រូពិតជាចង់លុបទិន្នន័យថ្នាក់រៀននេះមែនទេ?')) {
        window.location.href = '../../actions/admin/delete_class.php?id=' + id;
    }
}
</script>

<?php include '../../includes/footer.php'; ?>