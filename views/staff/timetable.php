<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ១. ចាប់យកតម្លៃថ្នាក់
$active_grade = isset($_GET['grade']) ? mysqli_real_escape_string($conn, $_GET['grade']) : ''; 

// ២. SQL ទាញទិន្នន័យសម្រាប់គ្រប់ថ្ងៃទាំងអស់
$sql = "SELECT t.*, s.subject_name as s_name, te.full_name as t_name
        FROM timetable t
        LEFT JOIN subjects s ON t.subject_id = s.id
        LEFT JOIN teachers te ON t.teacher_id = te.teacher_id
        WHERE t.class_id = '$active_grade' AND t.is_deleted = 0 
        ORDER BY t.start_time ASC";

$result = mysqli_query($conn, $sql);

// ៣. រៀបចំទិន្នន័យជា Matrix (ម៉ោងសិក្សា x ថ្ងៃ)
$timetable_matrix = [];
$time_slots = [];
while($row = mysqli_fetch_assoc($result)) {
    $time_key = date('H:i', strtotime($row['start_time'])) . ' - ' . date('H:i', strtotime($row['end_time']));
    $timetable_matrix[$time_key][$row['day_of_week']] = $row;
    
    if (!in_array($time_key, $time_slots)) {
        $time_slots[] = $time_key;
    }
}

$days = ['ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍'];
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8fafc; }

    /* បញ្ជាពេលបោះពុម្ព (Print) */
    @media print {
        /* លាក់ Sidebar, Header របស់ Dashboard និងផ្នែក Search */
        header, .sidebar, .no-print, footer, aside, nav {
            display: none !important;
        }

        @page { size: A4 landscape; margin: 5mm; }

        body { background: white !important; margin: 0; padding: 0; }
        main { margin: 0 !important; padding: 0 !important; }
        .timetable-card { border: none !important; box-shadow: none !important; padding: 0 !important; width: 100% !important; }
        
        .main-table { width: 100% !important; border: 2px solid black !important; }
        .main-table th, .main-table td { border: 1.5px solid black !important; color: black !important; padding: 5px !important; }
        .main-table th { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
    }

    /* រចនាប័ទ្មសម្រាប់មើលក្នុង Staff Dashboard */
    .timetable-card {
        background: white;
        border-radius: 1.5rem;
        padding: 30px;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        border: 1px solid #e2e8f0;
    }

    .main-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .main-table th { background-color: #f3f4f6; border: 1.5px solid #000; padding: 12px; font-weight: 800; text-align: center; }
    .main-table td { border: 1.5px solid #000; padding: 10px; text-align: center; height: 80px; vertical-align: middle; }

    .time-col { font-weight: 900; background: #f9fafb; width: 130px; }
    .sub-name { font-weight: 800; color: #1e293b; font-size: 14px; display: block; }
    .tea-name { font-size: 10px; color: #64748b; display: block; margin-top: 2px; }
    .room-num { font-size: 10px; font-weight: bold; color: #2563eb; display: block; }

    .search-box {
        background: white; padding: 20px; border-radius: 1rem; margin-bottom: 20px;
        display: flex; align-items: end; gap: 15px; border: 1px solid #e2e8f0;
    }
</style>

<main class="flex-1 p-4 md:p-8 min-h-screen">
    
    <div class="no-print search-box">
        <div class="w-64">
            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1">ស្វែងរកកាលវិភាគថ្នាក់</label>
            <form method="GET" class="flex gap-2">
                <input type="text" name="grade" value="<?= htmlspecialchars($active_grade) ?>" 
                       placeholder="វាយលេខថ្នាក់ (ឧ: 7)" 
                       class="w-full border-2 border-blue-100 rounded-xl px-4 py-2 font-bold focus:border-blue-500 outline-none transition-all">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold">ស្វែងរក</button>
            </form>
        </div>
        <button onclick="window.print()" class="ml-auto bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2">
            <i class="fas fa-print"></i> បោះពុម្ពកាលវិភាគ
        </button>
    </div>

    <div class="max-w-full">
        <?php if($active_grade): ?>
            <div class="timetable-card">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-black italic uppercase underline decoration-blue-500 decoration-4 underline-offset-8">កាលវិភាគសិក្សាថ្នាក់ទី <?= htmlspecialchars($active_grade) ?></h1>
                    <p class="text-slate-500 font-bold mt-4">កាលបរិច្ឆេទ៖ <?= date('d/m/Y') ?></p>
                </div>

                <table class="main-table">
                    <thead>
                        <tr>
                            <th class="time-col">ម៉ោងសិក្សា</th>
                            <?php foreach($days as $day): ?>
                                <th>ថ្ងៃ<?= $day ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($time_slots)): ?>
                            <?php foreach($time_slots as $slot): ?>
                            <tr>
                                <td class="time-col italic"><?= $slot ?></td>
                                <?php foreach($days as $day): ?>
                                <td>
                                    <?php if(isset($timetable_matrix[$slot][$day])): 
                                        $item = $timetable_matrix[$slot][$day]; ?>
                                        <span class="sub-name"><?= $item['s_name'] ?></span>
                                        <span class="tea-name"><?= $item['t_name'] ?? '---' ?></span>
                                        <span class="room-num">R-<?= $item['room_number'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="py-20 text-slate-400 italic font-bold">មិនមានទិន្នន័យសម្រាប់ថ្នាក់នេះទេ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="hidden print:flex justify-between mt-12 px-10 font-bold text-center">
                    <div>
                        <p>គ្រូប្រចាំថ្នាក់</p>
                        <div class="h-20"></div>
                        <p>(......................................)</p>
                    </div>
                    <div>
                        <p>បានឃើញ និងឯកភាព</p>
                        <p>នាយកសាលា</p>
                        <div class="h-20"></div>
                        <p>(......................................)</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                <p class="text-slate-400 font-bold italic">សូមបញ្ចូលលេខថ្នាក់ដើម្បីបង្ហាញកាលវិភាគពេញមួយសប្តាហ៍</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>