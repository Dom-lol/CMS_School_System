<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in(); // ប្រាកដថាបាន Login រួចរាល់

// ១. Query ទាញយកទិន្នន័យដោយ JOIN ៥ តារាង និងតម្រៀបតាមថ្នាក់
$query = "SELECT t.*, 
                 c.class_name, 
                 s.subject_name, 
                 u.full_name AS teacher_name, 
                 tr.teacher_id AS t_code 
          FROM timetable t
          LEFT JOIN classes c ON t.class_id = c.id
          LEFT JOIN subjects s ON t.subject_id = s.id
          LEFT JOIN teachers tr ON t.teacher_id = tr.user_id 
          LEFT JOIN users u ON tr.user_id = u.id
          ORDER BY c.class_name ASC, 
                   FIELD(day_of_week, 'ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍', 'អាទិត្យ'), 
                   t.start_time ASC";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}

// ២. បែងចែកទិន្នន័យដាក់ក្នុង Array តាមឈ្មោះថ្នាក់ (Grouping)
$grouped_timetable = [];
while ($row = mysqli_fetch_assoc($result)) {
    $class_name = $row['class_name'] ?? 'មិនទាន់ចាត់ថ្នាក់';
    $grouped_timetable[$class_name][] = $row;
}

include '../../includes/header.php';
include '../../includes/sidebar_staff.php';
?>
<main class="flex-1 p-8 bg-gray-50 min-h-screen font-['Kantumruy_Pro']">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 italic">កាលវិភាគសិក្សាបែងចែកតាមថ្នាក់</h1>
            <p class="text-slate-500 text-sm mt-1">គ្រប់គ្រង និងពិនិត្យកាលវិភាគតាមក្រុមថ្នាក់នីមួយៗ</p>
        </div>
        <a href="manage_timetable.php" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-blue-700 hover:-translate-y-1 transition-all duration-200">
            <i class="fas fa-plus-circle mr-2"></i> បន្ថែមម៉ោងបង្រៀនថ្មី
        </a>
    </div>

    <?php if (!empty($grouped_timetable)): ?>
        <?php foreach ($grouped_timetable as $class_name => $schedules): ?>
            <div class="mb-12">
                <div class="flex items-center gap-4 mb-4">
                    <div class="bg-blue-600 text-white px-5 py-2 rounded-2xl font-bold shadow-sm">
                        <i class="fas fa-chalkboard mr-2"></i> ថ្នាក់៖ <?= htmlspecialchars($class_name) ?>
                    </div>
                    <div class="h-[2px] flex-1 bg-slate-200"></div>
                    <span class="text-slate-400 text-xs font-medium italic">ចំនួន <?= count($schedules) ?> ម៉ោងសិក្សា</span>
                </div>

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">ថ្ងៃ</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">ម៉ោងសិក្សា</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">មុខវិជ្ជា</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">គ្រូបង្រៀន (ID - ឈ្មោះ)</th>
                                <th class="p-4 text-xs font-bold text-slate-500 uppercase tracking-wider">បន្ទប់</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php foreach ($schedules as $row): ?>
                            <tr class="hover:bg-blue-50/40 transition-colors">
                                <td class="p-4 font-bold text-slate-700">
                                    <span class="px-3 py-1 bg-slate-100 rounded-lg text-sm"><?= $row['day_of_week'] ?></span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center text-blue-600 font-bold text-sm">
                                        <i class="far fa-clock mr-2 text-blue-400"></i>
                                        <?= date('H:i', strtotime($row['start_time'])) ?> - <?= date('H:i', strtotime($row['end_time'])) ?>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-slate-800 text-sm"><?= htmlspecialchars($row['subject_name']) ?></div>
                                </td>
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] font-bold text-blue-500 uppercase tracking-tight">
                                            <?= htmlspecialchars($row['t_code'] ?? 'N/A') ?>
                                        </span>
                                        <span class="text-sm font-medium text-slate-700">
                                            <?= htmlspecialchars($row['teacher_name'] ?? 'មិនស្គាល់អត្តសញ្ញាណ') ?>
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="text-sm text-slate-500 bg-slate-50 px-3 py-1 rounded-md border border-slate-100">
                                        <i class="fas fa-map-marker-alt mr-1 text-slate-400"></i> <?= htmlspecialchars($row['room_number']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="bg-white p-20 text-center rounded-[2rem] border-2 border-dashed border-slate-200">
            <div class="bg-slate-50 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-times text-3xl text-slate-300"></i>
            </div>
            <h3 class="text-slate-500 font-bold text-lg">មិនទាន់មានកាលវិភាគទេ</h3>
            <p class="text-slate-400 text-sm mb-6">សូមបញ្ចូលកាលវិភាគថ្មីដើម្បីបង្ហាញនៅទីនេះ។</p>
            <a href="manage_timetable.php" class="text-blue-600 font-bold hover:underline">ទៅកាន់ទំព័របន្ថែមថ្មី</a>
        </div>
    <?php endif; ?>
</main>