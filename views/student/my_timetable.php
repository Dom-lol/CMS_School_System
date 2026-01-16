<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$user_id = $_SESSION['user_id'];

// ១. ទាញយក class_id របស់សិស្ស
$student_res = mysqli_query($conn, "SELECT class_id FROM students WHERE user_id = '$user_id' LIMIT 1");

$my_class_id = 0;
if ($student_res && mysqli_num_rows($student_res) > 0) {
    $student_data = mysqli_fetch_assoc($student_res);
    $my_class_id = $student_data['class_id'];
}

// ២. ទាញយកកាលវិភាគ
$query = "SELECT t.*, c.class_name, s.subject_name, u.full_name AS teacher_name, tr.teacher_id AS t_code 
          FROM timetable t
          LEFT JOIN classes c ON t.class_id = c.id
          LEFT JOIN subjects s ON t.subject_id = s.id
          LEFT JOIN teachers tr ON t.teacher_id = tr.user_id 
          LEFT JOIN users u ON tr.user_id = u.id
          WHERE t.class_id = '$my_class_id'
          ORDER BY FIELD(day_of_week, 'ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍', 'អាទិត្យ'), t.start_time ASC";

$result = mysqli_query($conn, $query);

$daily_schedules = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $daily_schedules[$row['day_of_week']][] = $row;
    }
}
include '../../includes/header.php';
include '../../includes/sidebar_student.php';
// បិទ PHP នៅត្រង់នេះ មុននឹងចាប់ផ្ដើម HTML
?>

<main class="flex-1 p-4 md:p-8 bg-slate-50 min-h-screen font-['Kantumruy_Pro']">
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-slate-800">កាលវិភាគសិក្សារបស់ខ្ញុំ</h1>
        <?php if ($my_class_id > 0): ?>
            <p class="text-blue-600 font-medium italic">សួស្តី! នេះគឺជាកាលវិភាគសម្រាប់ថ្នាក់របស់អ្នក</p>
        <?php endif; ?>
    </div>

    <div class="max-w-3xl mx-auto space-y-6">
        <?php if (!empty($daily_schedules)): ?>
            <?php foreach ($daily_schedules as $day => $lessons): ?>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="bg-slate-800 px-5 py-3 text-white font-bold flex justify-between items-center">
                        <span><i class="far fa-calendar-alt mr-2"></i> ថ្ងៃ<?= $day ?></span>
                    </div>
                    <div class="divide-y divide-slate-50">
                        <?php foreach ($lessons as $lesson): ?>
                            <div class="p-4 flex items-center gap-4">
                                <div class="w-24 flex-shrink-0 text-center border-r border-slate-100 pr-4">
                                    <div class="text-sm font-bold text-slate-700"><?= date('H:i', strtotime($lesson['start_time'])) ?></div>
                                    <div class="text-[10px] text-slate-400 uppercase">ដល់</div>
                                    <div class="text-sm font-bold text-slate-700"><?= date('H:i', strtotime($lesson['end_time'])) ?></div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-blue-600"><?= htmlspecialchars($lesson['subject_name']) ?></h3>
                                    <div class="text-xs text-slate-500 mt-1">
                                        <?= htmlspecialchars($lesson['teacher_name']) ?> (<?= $lesson['t_code'] ?>)
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-[10px] block text-slate-400 font-bold">បន្ទប់</span>
                                    <span class="text-sm font-bold text-slate-700"><?= htmlspecialchars($lesson['room_number']) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <p class="text-slate-400 italic">មិនទាន់មានកាលវិភាគសម្រាប់ថ្នាក់របស់អ្នកនៅឡើយទេ</p>
            </div>
        <?php endif; ?>
    </div>
</main>