<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_teacher.php'; 

$t_username = $_SESSION['username'];

// ១. ទាញយក teacher_id
$teacher_res = mysqli_query($conn, "SELECT t.teacher_id FROM teachers t JOIN users u ON t.user_id = u.id WHERE u.username = '$t_username'");
$teacher_data = mysqli_fetch_assoc($teacher_res);
$t_id = $teacher_data['teacher_id'];

// ២. បង្កើត Array សម្រាប់ឈ្មោះថ្ងៃជាភាសាខ្មែរ
$days_kh = [
    'Monday' => 'ច័ន្ទ',
    'Tuesday' => 'អង្គារ',
    'Wednesday' => 'ពុធ',
    'Thursday' => 'ព្រហស្បតិ៍',
    'Friday' => 'សុក្រ',
    'Saturday' => 'សៅរ៍',
    'Sunday' => 'អាទិត្យ'
];
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">កាលវិភាគបង្រៀន</h1>
        <p class="text-slate-500 mt-2">តារាងម៉ោងបង្រៀនប្រចាំសប្តាហ៍របស់លោកគ្រូ/អ្នកគ្រូ</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white">
                        <th class="px-6 py-4 font-bold border-r border-slate-700 w-40 text-center">ថ្ងៃ</th>
                        <th class="px-6 py-4 font-bold">ព័ត៌មានម៉ោងបង្រៀន (ថ្នាក់ - មុខវិជ្ជា - ម៉ោង)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    <?php 
                    // លុបឈ្មោះថ្ងៃនីមួយៗមកបង្ហាញ
                    foreach ($days_kh as $day_en => $day_kh_name): 
                        // ទាញទិន្នន័យតាមថ្ងៃនីមួយៗ
                        $sql = "SELECT t.*, s.subject_name 
                                FROM timetable t 
                                JOIN subjects s ON t.subject_id = s.id 
                                WHERE t.teacher_id = '$t_id' AND t.day_of_week = '$day_en'
                                ORDER BY t.time_slot ASC";
                        $schedule_query = mysqli_query($conn, $sql);
                    ?>
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-6 font-bold text-slate-700 bg-slate-50 border-r border-slate-200 text-center">
                            <?php echo $day_kh_name; ?>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-4">
                                <?php if (mysqli_num_rows($schedule_query) > 0): ?>
                                    <?php while ($row = mysqli_fetch_assoc($schedule_query)): ?>
                                        <div class="bg-white border-l-4 border-blue-600 p-4 rounded-xl shadow-sm border border-slate-200 min-w-[250px]">
                                            <div class="flex items-center text-blue-600 mb-1">
                                                <i class="fas fa-clock text-xs mr-2"></i>
                                                <span class="text-xs font-bold uppercase tracking-wide">
                                                    <?php echo $row['time_slot']; ?>
                                                </span>
                                            </div>
                                            <h4 class="text-lg font-bold text-slate-800">
                                                <?php echo $row['subject_name']; ?>
                                            </h4>
                                            <p class="text-slate-500 text-sm font-medium">
                                                <i class="fas fa-chalkboard mr-1"></i> ថ្នាក់: <?php echo $row['class_name']; ?>
                                            </p>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <span class="text-slate-400 italic text-sm italic">--- គ្មានម៉ោងបង្រៀន ---</span>
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

<?php include '../../includes/footer.php'; ?>