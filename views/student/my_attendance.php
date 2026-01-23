<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ទាញយក student_id របស់សិស្សដែលកំពុង Login
$u_id = $_SESSION['user_id'];
$st_query = mysqli_query($conn, "SELECT id, full_name, class_id FROM students WHERE user_id = '$u_id' LIMIT 1");
$st_data = mysqli_fetch_assoc($st_query);
$real_st_id = $st_data['id'] ?? 0;
$class_id = $st_data['class_id'] ?? 0;

// ២. ទាញយកស្ថិតិវត្តមានសរុប (Present, Absent, Permission)
$stats_query = mysqli_query($conn, "
    SELECT 
        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as total_present,
        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as total_absent,
        SUM(CASE WHEN status = 'permission' THEN 1 ELSE 0 END) as total_permission
    FROM attendance 
    WHERE student_id = '$real_st_id'
");
$stats = mysqli_fetch_assoc($stats_query);

// ៣. ទាញយកបញ្ជីវត្តមានលម្អិត រៀបតាមថ្ងៃខែថ្មីបំផុតមកមុន
$att_list = mysqli_query($conn, "
    SELECT attendance_date, status 
    FROM attendance 
    WHERE student_id = '$real_st_id' 
    ORDER BY attendance_date DESC
");

include '../../includes/header.php'; 
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    <?php include '../../includes/sidebar_student.php'; ?>
    
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-10 shrink-0">
            <h2 class="text-xl font-black text-slate-800 uppercase italic">វត្តមានរបស់ខ្ញុំ</h2>
            <div class="text-right">
                <p class="text-xs font-bold text-slate-400 uppercase leading-none">សិស្ស៖ <?= $st_data['full_name'] ?></p>
                <p class="text-[10px] text-blue-500 font-black mt-1 uppercase">ID: #<?= $real_st_id ?></p>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-[2rem] border-2 border-green-100 shadow-sm">
                    <p class="text-[10px] font-black text-green-500 uppercase">វត្តមានសរុប</p>
                    <h3 class="text-4xl font-black text-slate-800"><?= $stats['total_present'] ?? 0 ?></h3>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border-2 border-red-100 shadow-sm">
                    <p class="text-[10px] font-black text-red-500 uppercase">អវត្តមានសរុប</p>
                    <h3 class="text-4xl font-black text-slate-800"><?= $stats['total_absent'] ?? 0 ?></h3>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border-2 border-orange-100 shadow-sm">
                    <p class="text-[10px] font-black text-orange-500 uppercase">ច្បាប់សរុប</p>
                    <h3 class="text-4xl font-black text-slate-800"><?= $stats['total_permission'] ?? 0 ?></h3>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th class="p-6 text-[10px] font-bold uppercase tracking-widest">ថ្ងៃខែឆ្នាំ</th>
                            <th class="p-6 text-[10px] font-bold uppercase tracking-widest text-center">ស្ថានភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (mysqli_num_rows($att_list) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($att_list)): ?>
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="p-6 font-bold text-slate-700">
                                    <?= date('d-M-Y', strtotime($row['attendance_date'])) ?>
                                </td>
                                <td class="p-6">
                                    <div class="flex justify-center">
                                        <?php if($row['status'] == 'present'): ?>
                                            <span class="bg-green-100 text-green-600 px-4 py-1 rounded-full text-[10px] font-black uppercase italic">មក</span>
                                        <?php elseif($row['status'] == 'absent'): ?>
                                            <span class="bg-red-100 text-red-600 px-4 py-1 rounded-full text-[10px] font-black uppercase italic">អវត្តមាន</span>
                                        <?php else: ?>
                                            <span class="bg-orange-100 text-orange-600 px-4 py-1 rounded-full text-[10px] font-black uppercase italic">ច្បាប់</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="p-20 text-center opacity-40">
                                    <i class="fas fa-folder-open text-4xl mb-4 text-slate-200"></i>
                                    <p class="text-slate-400 font-black uppercase italic">មិនទាន់មានទិន្នន័យវត្តមាន</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>