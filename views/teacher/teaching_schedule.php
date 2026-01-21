<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../../config/db.php'; 


$u_id = $_SESSION['user_id'] ?? 0;
if ($u_id == 0) {
    header("Location: ../../login.php");
    exit();
}

$teacher_res = mysqli_query($conn, "SELECT teacher_id, full_name FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_data = mysqli_fetch_assoc($teacher_res);
$t_id = $teacher_data['teacher_id'] ?? 0;

include '../../includes/header.php';

$days_kh = [
    'Monday'    => 'ច័ន្ទ',
    'Tuesday'   => 'អង្គារ',
    'Wednesday' => 'ពុធ',
    'Thursday'  => 'ព្រហស្បតិ៍',
    'Friday'    => 'សុក្រ',
    'Saturday'  => 'សៅរ៍'
];
?>

<div class="flex h-screen w-full bg-slate-50 overflow-hidden">
    <?php include '../../includes/sidebar_teacher.php'; ?>

    <main class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <header class="bg-white border-b-2 h-24 flex items-center justify-between px-10 shrink-0">
            <div>
                <h2 class="text-xl font-black text-slate-800 uppercase italic">កាលវិភាគបង្រៀន</h2>
                <p class="text-[10px] text-blue-500 font-bold uppercase">Teacher ID: <?= $t_id ?></p>
            </div>
            <div class="text-right">
                <p class="text-base font-black text-slate-900 leading-tight"><?= htmlspecialchars($teacher_data['full_name']) ?></p>
                <p class="text-[10px] text-slate-400 font-bold italic uppercase">សាលារៀនជំនាន់ថ្មី</p>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-10">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-slate-900 text-white font-black italic uppercase text-[11px]">
                        <tr>
                            <th class="px-8 py-6 w-40 text-center border-r border-slate-800">ថ្ងៃបង្រៀន</th>
                            <th class="px-8 py-6">ព័ត៌មានលម្អិត (ម៉ោង - មុខវិជ្ជា - ថ្នាក់)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach ($days_kh as $day_en => $day_kh_name): 
                            // ៤. SQL Query ទាញយកកាលវិភាគដោយ Join តារាង subjects និង classes [cite: 2026-01-20]
                            $sql = "SELECT t.*, s.subject_name, c.class_name 
                                    FROM timetable t 
                                    INNER JOIN subjects s ON t.subject_id = s.id 
                                    INNER JOIN classes c ON t.class_id = c.id
                                    WHERE t.teacher_id = '$t_id' 
                                    AND t.day_of_week = '$day_kh_name' 
                                    AND t.is_deleted = 0
                                    ORDER BY t.start_time ASC";
                            
                            $res = mysqli_query($conn, $sql);
                        ?>
                        <tr class="hover:bg-blue-50/30 transition-all border-b border-slate-100 last:border-0">
                            <td class="px-8 py-10 font-black text-slate-800 bg-slate-50/50 border-r border-slate-100 text-center italic text-xl">
                                <?= $day_kh_name ?>
                            </td>
                            
                            <td class="px-8 py-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-5">
                                    <?php if ($res && mysqli_num_rows($res) > 0): 
                                        while ($row = mysqli_fetch_assoc($res)): ?>
                                        
                                        <div class="bg-white border-l-[6px] border-blue-600 p-6 rounded-3xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                                            <div class="text-blue-600 mb-3 flex items-center">
                                                <i class="fas fa-clock text-[10px] mr-2"></i>
                                                <span class="text-[10px] font-black italic">
                                                    <?= date("H:i", strtotime($row['start_time'])) ?> - <?= date("H:i", strtotime($row['end_time'])) ?>
                                                </span>
                                            </div>
                                            <h4 class="text-lg font-black text-slate-800 italic leading-tight mb-3">
                                                <?= htmlspecialchars($row['subject_name']) ?>
                                            </h4>
                                            <div class="flex items-center justify-between">
                                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-[10px] font-bold">
                                                    ថ្នាក់: <?= htmlspecialchars($row['class_name']) ?>
                                                </span>
                                                <span class="text-[10px] text-slate-400 font-bold italic uppercase">
                                                    បន្ទប់: <?= htmlspecialchars($row['room_number']) ?>
                                                </span>
                                            </div>
                                        </div>

                                    <?php endwhile; else: ?>
                                        <div class="col-span-full py-4 px-6 bg-slate-50 rounded-2xl border border-dashed border-slate-200 text-slate-400 italic text-xs flex items-center gap-2">
                                            <i class="fas fa-coffee"></i> សម្រាក ឬគ្មានម៉ោងបង្រៀន
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>