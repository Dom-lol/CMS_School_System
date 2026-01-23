<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ចាប់យក ID ថ្នាក់ពី URL [cite: 2026-01-20]
$class_id = isset($_GET['class_id']) ? mysqli_real_escape_string($conn, $_GET['class_id']) : 0;

// ២. ទាញព័ត៌មានថ្នាក់រៀន [cite: 2026-01-20]
$class_query = mysqli_query($conn, "SELECT * FROM classes WHERE id = '$class_id'");
$class_data = mysqli_fetch_assoc($class_query);

if (!$class_data) {
    header("Location: classes_list.php");
    exit();
}

include '../../includes/header.php'; 
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden font-['Kantumruy_Pro']">
    <?php include '../../includes/sidebar_admin.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0">
            <div class="flex items-center gap-4">
                <a href="classes_list.php" class="w-10 h-10 flex items-center justify-center bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition-all">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 italic uppercase">បញ្ជីសិស្ស៖ <?= $class_data['class_name'] ?></h2>
                    <p class="text-[10px] text-blue-500 font-bold uppercase tracking-widest mt-0.5">ឆ្នាំសិក្សា៖ 2025-2026</p>
                </div>
            </div>
            <button onclick="window.print()" class="bg-[#1e293b] text-white px-8 py-3 rounded-2xl font-bold hover:bg-black shadow-lg flex items-center gap-3 transition-all">
                <i class="fas fa-print"></i> បោះពុម្ព
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-10 bg-[#f3f4f9] custom-scrollbar">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 text-slate-400 text-[10px] font-black uppercase tracking-widest border-b border-slate-100">
                            <th class="p-8 w-20">ល.រ</th>
                            <th class="p-8">ឈ្មោះសិស្ស</th>
                            <th class="p-8">ភេទ</th>
                            <th class="p-8">លេខទូរស័ព្ទ</th>
                            <th class="p-8 text-right">ស្ថានភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        <?php 
                        // ឆែកមើលឈ្មោះ Column ក្នុង Database (សាកល្បងប្រើ full_name ឬ name) [cite: 2026-01-20]
                        $student_sql = "SELECT * FROM students WHERE class_id = '$class_id' ORDER BY id ASC";
                        $student_res = mysqli_query($conn, $student_sql);
                        
                        $count = 1;
                        if(mysqli_num_rows($student_res) > 0):
                            while($st = mysqli_fetch_assoc($student_res)): 
                        ?>
                        <tr class="group hover:bg-slate-50/50 transition-all">
                            <td class="p-8 font-bold text-slate-400 text-sm"><?= $count++ ?></td>
                            <td class="p-8">
                                <div class="font-black text-slate-800 text-sm italic uppercase">
                                    <?php 
                                        // កែសម្រួលត្រង់នេះ៖ ប្រើឈ្មោះ Column ឱ្យត្រូវតាម Database (ឧទាហរណ៍៖ full_name) [cite: 2026-01-20]
                                        echo $st['full_name'] ?? $st['name'] ?? $st['name_kh'] ?? 'មិនទាន់មានឈ្មោះ'; 
                                    ?>
                                </div>
                            </td>
                            <td class="p-8 text-sm font-bold text-slate-500 italic">
                                <?= $st['gender'] ?? 'មិនបញ្ជាក់' ?>
                            </td>
                            <td class="p-8 font-black text-slate-700 text-sm italic">
                                <?= !empty($st['phone']) ? $st['phone'] : '---' ?>
                            </td>
                            <td class="p-8 text-right">
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[9px] font-black uppercase">Active</span>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="5" class="p-24 text-center opacity-20 italic font-bold">មិនទាន់មានសិស្សក្នុងថ្នាក់នេះ</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>