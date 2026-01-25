<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

// ១. ទាញព័ត៌មានសិស្ស
$user_id = $_SESSION['user_id']; 
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE user_id = '$user_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

$s_id = $student_info['student_id'] ?? 'N/A';
$display_name = $student_info['full_name'] ?? 'N/A';

// ២. Logic សម្រាប់ Class ID
$student_class_id = $student_info['class_id'] ?? '';
$active_grade_id = ($student_class_id == 7) ? 1 : $student_class_id; 
$active_grade = ($student_class_id == 7) ? "7" : $student_class_id; 

// ៣. រៀបចំថ្ងៃជាភាសាខ្មែរ
$days_mapping = [
    'Monday'    => 'ច័ន្ទ',
    'Tuesday'   => 'អង្គារ',
    'Wednesday' => 'ពុធ',
    'Thursday'  => 'ព្រហស្បតិ៍',
    'Friday'    => 'សុក្រ',
    'Saturday'  => 'សៅរ៍'
];

$current_day_en = date('l');
$active_day_en = isset($_GET['day']) ? mysqli_real_escape_string($conn, $_GET['day']) : ($current_day_en == 'Sunday' ? 'Monday' : $current_day_en);
$search_day_kh = $days_mapping[$active_day_en] ?? 'ច័ន្ទ';

// ៤. SQL Query ទាញកាលវិភាគសម្រាប់ UI ដើម (List View)
$sql_list = "SELECT t.*, s.subject_name, te.full_name as teacher_name
             FROM timetable t 
             LEFT JOIN subjects s ON t.subject_id = s.id 
             LEFT JOIN teachers te ON CAST(t.teacher_id AS UNSIGNED) = CAST(te.teacher_id AS UNSIGNED)
             WHERE t.class_id = '$active_grade_id' 
             AND t.day_of_week = '$search_day_kh' 
             AND t.is_deleted = 0 
             ORDER BY t.start_time ASC";
$result_list = mysqli_query($conn, $sql_list);

// ៥. SQL Query ទាញកាលវិភាគទាំងអស់សម្រាប់ Print (Matrix View)
$sql_matrix = "SELECT t.*, s.subject_name, te.full_name as teacher_name
               FROM timetable t 
               LEFT JOIN subjects s ON t.subject_id = s.id 
               LEFT JOIN teachers te ON CAST(t.teacher_id AS UNSIGNED) = CAST(te.teacher_id AS UNSIGNED)
               WHERE t.class_id = '$active_grade_id' AND t.is_deleted = 0 
               ORDER BY t.start_time ASC";
$result_matrix = mysqli_query($conn, $sql_matrix);

$matrix_data = [];
$time_slots = [];
while($row = mysqli_fetch_assoc($result_matrix)) {
    $time_key = date('H:i', strtotime($row['start_time'])) . ' - ' . date('H:i', strtotime($row['end_time']));
    $matrix_data[$time_key][$row['day_of_week']] = $row;
    if (!in_array($time_key, $time_slots)) $time_slots[] = $time_key;
}

// Path រូបភាព
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
                ? $profile_path . $student_info['profile_img'] . "?v=" . time() : null;

include '../../includes/header.php';
?>

<style>
    
    html, body { height: 100%; margin: 0; overflow: hidden; }
    
    @media print {
        header, aside, .sidebar, .no-print, .student-ui-original, footer { display: none !important; }
        .print-area { display: block !important; width: 100% !important; }
        @page { size: A4 landscape; margin: 10mm; }
        body { background: white !important; overflow: visible !important; }
        .main-table { width: 100% !important; border-collapse: collapse !important; border: 2px solid black !important; }
        .main-table th, .main-table td { border: 1.5px solid black !important; padding: 10px !important; text-align: center !important; }
        .main-table th { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
    }

    .print-area { display: none; }
</style>

<div class="flex h-screen w-full overflow-hidden">
    <?php include '../../includes/sidebar_student.php'; ?>

    <div class="flex-1 flex flex-col min-w-0 bg-slate-50 overflow-hidden">
        <!-- ==== header profile img ===== -->
          

        <main class="flex-1 overflow-y-auto student-ui-original no-print">
            <div class="bg-blue-600 p-8 text-white flex justify-between items-center shadow-lg mx-2 mt-6 rounded-3xl">
                <div>
                    <h1 class="text-2xl font-black uppercase">ថ្នាក់ទី <?= htmlspecialchars($active_grade) ?></h1>
                    <p class="opacity-80">កាលវិភាគប្រចាំថ្ងៃ<?= $search_day_kh ?></p>
                </div>
                <button onclick="window.print()" class="text-[15px] bg-white text-blue-600 px-2 lg:px-6 py-3 rounded-xl font-bold shadow-md hover:bg-blue-50 transition-all">
                    <i class="fas fa-print mr-2"></i> បោះពុម្ពកាលវិភាគ
                </button>
            </div>

            <div class="flex gap-2 p-6 overflow-x-auto shrink-0">
                <?php foreach($days_mapping as $en => $kh): ?>
                    <a href="?day=<?= $en ?>" class="px-6 py-2 rounded-xl text-sm font-bold transition-all whitespace-nowrap <?= ($active_day_en == $en) ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'bg-white text-slate-500 hover:bg-slate-100 border border-slate-200' ?>">
                        <?= $kh ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="px-6 pb-10">
                <div class="max-w-4xl mx-auto">
                    <?php if($result_list && mysqli_num_rows($result_list) > 0): 
                        while($row = mysqli_fetch_assoc($result_list)): ?>
                            <div class="bg-white p-6 rounded-[2rem] shadow-sm mb-4 border border-slate-100 flex justify-between items-center hover:shadow-md transition-all">
                                <div class="flex items-center gap-6">
                                    <div class="bg-blue-50 text-blue-600 w-20 h-20 rounded-2xl flex flex-col items-center justify-center border border-blue-100">
                                        <span class="text-lg font-black"><?= date('H:i', strtotime($row['start_time'])) ?></span>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-slate-800"><?= htmlspecialchars($row['subject_name']) ?></h3>
                                        <p class="text-slate-500 text-sm">គ្រូ៖ <?= htmlspecialchars($row['teacher_name']) ?></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] text-slate-400 font-bold block uppercase">ថ្នាក់ទី</span>
                                    <span class="text-3xl font-black text-slate-200"><?= htmlspecialchars($row['room_number']) ?></span>
                                </div>
                            </div>
                        <?php endwhile;
                    else: ?>
                        <div class="text-center py-20 text-slate-300 bg-white rounded-[3rem] border-2 border-dashed border-slate-100">
                            <i class="fas fa-calendar-times text-6xl mb-4 opacity-20"></i>
                            <p class="font-bold">មិនមានកាលវិភាគសម្រាប់ថ្ងៃ <?= $search_day_kh ?> ទេ</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="print-area">
    <div style="text-align: center; margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 900; text-decoration: underline;">កាលវិភាគសិក្សាថ្នាក់ទី <?= htmlspecialchars($active_grade) ?></h1>
        <p style="margin-top: 10px; font-weight: bold;">កាលបរិច្ឆេទបោះពុម្ព៖ <?= date('d/m/Y') ?></p>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th>ម៉ោងសិក្សា</th>
                <?php foreach($days_mapping as $kh): ?> <th>ថ្ងៃ<?= $kh ?></th> <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($time_slots as $slot): ?>
                <tr>
                    <td style="font-weight: bold; background: #f9fafb;"><?= $slot ?></td>
                    <?php foreach($days_mapping as $kh): ?>
                        <td>
                            <?php if(isset($matrix_data[$slot][$kh])): $item = $matrix_data[$slot][$kh]; ?>
                                <div style="font-weight: 800; font-size: 14px;"><?= htmlspecialchars($item['subject_name']) ?></div>
                                <div style="font-size: 10px; color: #666;"><?= htmlspecialchars($item['teacher_name']) ?></div>
                                <div style="font-size: 10px; color: #2563eb; font-weight: bold;">Room: <?= htmlspecialchars($item['room_number']) ?></div>
                            <?php endif; ?>
                        </td>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) sidebar.classList.toggle('-translate-x-full');
    }
</script>

<?php include '../../includes/footer.php'; ?>