<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// Login User Role_base
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

//
$search_input = isset($_GET['grade']) ? mysqli_real_escape_string($conn, $_GET['grade']) : ''; 
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$count = isset($_GET['count']) ? $_GET['count'] : 0;


// 
$sql = "SELECT t.*, s.subject_name as s_name, te.full_name as t_name, c.class_name
        FROM timetable t
        LEFT JOIN subjects s ON t.subject_id = s.id
        LEFT JOIN teachers te ON t.teacher_id = te.teacher_id
        INNER JOIN classes c ON t.class_id = c.id
        WHERE (t.class_id = '$search_input' OR c.class_name = '$search_input') 
        AND t.is_deleted = 0 
        ORDER BY t.start_time ASC";

$result = mysqli_query($conn, $sql);

// 
$days_mapping = [
    'Monday'    => 'ច័ន្ទ',
    'Tuesday'   => 'អង្គារ',
    'Wednesday' => 'ពុធ',
    'Thursday'  => 'ព្រហស្បតិ៍',
    'Friday'    => 'សុក្រ',
    'Saturday'  => 'សៅរ៏'
];

$timetable_matrix = [];
$time_slots = [];
$final_class_label = $search_input; 

if ($result && mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        // 
        $final_class_label = $row['class_name']; 
        
        $time_key = date('H:i', strtotime($row['start_time'])) . ' - ' . date('H:i', strtotime($row['end_time']));
        $kh_day = $days_mapping[$row['day_of_week']] ?? $row['day_of_week'];

        $timetable_matrix[$time_key][$kh_day] = $row;
        
        if (!in_array($time_key, $time_slots)) {
            $time_slots[] = $time_key;
        }
    }
}

$days = ['ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៏'];
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8fafc; }
    @media print {
        header, .sidebar, .no-print, footer, aside, nav { display: none !important; }
        @page { size: A4 landscape; margin: 5mm; }
        body { background: white !important; margin: 0; padding: 0; }
        main { margin: 0 !important; padding: 0 !important; width: 100vw !important; }
        .timetable-card { border: none !important; box-shadow: none !important; padding: 0 !important; width: 100% !important; }
        .main-table { width: 100% !important; border: 2px solid black !important; }
        .main-table th, .main-table td { border: 1.5px solid black !important; color: black !important; padding: 5px !important; }
        .main-table th { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
    }
    .timetable-card { background: white; border-radius: 1.5rem; padding: 30px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); border: 1px solid #e2e8f0; }
    .main-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .main-table th { background-color: #f3f4f6; border: 1.5px solid #000; padding: 12px; font-weight: 800; text-align: center; }
    .main-table td { border: 1.5px solid #000; padding: 10px; text-align: center; height: 80px; vertical-align: middle; }
    .time-col { font-weight: 900; background: #f9fafb; width: 130px; }
    .sub-name { font-weight: 800; color: #1e293b; font-size: 14px; display: block; }
    .tea-name { font-size: 10px; color: #64748b; display: block; margin-top: 2px; }
    .room-num { font-size: 10px; font-weight: bold; color: #2563eb; display: block; }
    .search-box { background: white; padding: 20px; border-radius: 1rem; margin-bottom: 20px; display: flex; align-items: end; gap: 15px; border: 1px solid #e2e8f0; }
</style>

<main class="flex-1 p-4 md:p-8 min-h-screen">
    <?php if($msg == 'success'): ?>
        <div class="no-print bg-green-500 text-white p-4 rounded-xl mb-6 flex items-center gap-3 animate-bounce">
            <i class="fas fa-check-circle"></i>
            <span class="font-bold text-sm">បានបញ្ចូលទិន្នន័យកាលវិភាគចំនួន <?= (int)$count ?> ជួរដោយជោគជ័យ!</span>
        </div>
    <?php endif; ?>
    
    <div class="no-print search-box flex-wrap">
        <div class="w-48">
            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 italic">ស្វែងរកតាមថ្នាក់</label>
            <form method="GET" class="flex gap-2">
                <input type="text" name="grade" value="<?= htmlspecialchars($search_input) ?>" 
                       placeholder="ឧ: 7" 
                       class="w-full border-2 border-blue-100 rounded-xl px-4 py-2 font-bold focus:border-blue-500 outline-none transition-all">
                <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold italic">ស្វែងរក</button>
            </form>
        </div>

        <div class="ml-4 border-l-2 border-slate-100 pl-6 flex-1">
            <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 italic">Import កាលវិភាគ (CSV)</label>
            <form action="../../actions/staff/import_timetable.php" method="POST" enctype="multipart/form-data" class="flex items-center gap-3">
                <input type="file" name="timetable_file" accept=".csv" required 
                       class="text-[10px] file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-slate-100 file:text-slate-700 hover:file:bg-blue-50">
                <button type="submit" name="import_btn" class="bg-green-600 text-white px-6 py-2 rounded-xl font-bold text-[12px] hover:bg-green-700 transition-all italic">
                    <i class="fas fa-file-import mr-1"></i> IMPORT
                </button>
            </form>
        </div>

        <button onclick="window.print()" class="ml-auto bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2 hover:bg-slate-700">
            <i class="fas fa-print"></i> បោះពុម្ពកាលវិភាគ
        </button>
    </div>

    <div class="max-w-full">
        <?php if($search_input): ?>
            <div class="timetable-card">
                <div class="text-center mb-6">
                    <h1 class="text-2xl font-black italic uppercase underline decoration-blue-500 decoration-4 underline-offset-8">កាលវិភាគសិក្សាថ្នាក់ទី <?= htmlspecialchars($final_class_label) ?></h1>
                    <p class="text-slate-500 font-bold mt-4 italic">កាលបរិច្ឆេទ៖ <?= date('d/m/Y') ?></p>
                </div>

                <table class="main-table">
                    <thead>
                        <tr>
                            <th class="time-col italic">ម៉ោងសិក្សា</th>
                            <?php foreach($days as $day): ?>
                                <th>ថ្ងៃ<?= $day ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(!empty($time_slots)): ?>
                            <?php foreach($time_slots as $slot): ?>
                            <tr>
                                <td class="time-col italic text-sm"><?= $slot ?></td>
                                <?php foreach($days as $day): ?>
                                <td>
                                    <?php if(isset($timetable_matrix[$slot][$day])): 
                                        $item = $timetable_matrix[$slot][$day]; ?>
                                        <span class="sub-name"><?= $item['s_name'] ?></span>
                                        <span class="tea-name"><?= $item['t_name'] ?? '---' ?></span>
                                        <span class="room-num italic">Room: <?= $item['room_number'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php endforeach; ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="py-20 text-slate-300 italic font-bold">រកមិនឃើញកាលវិភាគសម្រាប់ "<?= htmlspecialchars($search_input) ?>" ទេ</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <div class="hidden print:flex justify-between mt-12 px-10 font-bold text-center italic">
                    <div>
                        <p>គ្រូប្រចាំថ្នាក់</p>
                        <div class="h-24"></div>
                        <p>(......................................)</p>
                    </div>
                    <div>
                        <p>បានឃើញ និងឯកភាព</p>
                        <p class="mb-2">នាយកសាលា</p>
                        <div class="h-24"></div>
                        <p>(......................................)</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-32 bg-white rounded-[2.5rem] border-4 border-dashed border-slate-100 flex flex-col items-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-search text-3xl text-slate-300"></i>
                </div>
                <p class="text-slate-400 font-bold italic text-lg tracking-tight">សូមបញ្ចូលលេខថ្នាក់ដើម្បីបង្ហាញកាលវិភាគពេញមួយសប្តាហ៍</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>