<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

is_logged_in();
include '../../includes/header.php';

$selected_grade_level = isset($_GET['grade_level']) ? (int)$_GET['grade_level'] : 0; 
$search_input = isset($_GET['grade']) ? mysqli_real_escape_string($conn, $_GET['grade']) : ''; 
$academic_year = isset($_GET['academic_year']) ? mysqli_real_escape_string($conn, $_GET['academic_year']) : '2025-2026';
$msg = isset($_GET['msg']) ? $_GET['msg'] : '';
$count = isset($_GET['count']) ? $_GET['count'] : 0;

$specific_classes = [];
if ($selected_grade_level > 0) {
    $class_query = "SELECT id, class_name FROM classes WHERE class_name LIKE 'ថ្នាក់ទី $selected_grade_level%' ORDER BY id ASC";
    $class_res = mysqli_query($conn, $class_query);
    while($c_row = mysqli_fetch_assoc($class_res)) {
        $specific_classes[] = $c_row;
    }
}

// ទាញទិន្នន័យសម្រាប់ Modal Create
$subjects_list = mysqli_query($conn, "SELECT id, subject_name FROM subjects ORDER BY subject_name ASC");
$teachers_list = mysqli_query($conn, "SELECT teacher_id, full_name FROM teachers ORDER BY full_name ASC");

$timetable_matrix = [];
$time_slots = [];
$final_class_label = "";

if ($search_input) {
    $class_info = mysqli_query($conn, "SELECT class_name FROM classes WHERE id = '$search_input'");
    if($c_info = mysqli_fetch_assoc($class_info)) {
        $final_class_label = $c_info['class_name'];
    }

    $sql = "SELECT t.*, s.subject_name as s_name, te.full_name as t_name
            FROM timetable t
            LEFT JOIN subjects s ON t.subject_id = s.id
            LEFT JOIN teachers te ON t.teacher_id = te.teacher_id
            WHERE t.class_id = '$search_input' 
            AND t.academic_year = '$academic_year' 
            AND t.is_deleted = 0 
            ORDER BY t.start_time ASC";

    $result = mysqli_query($conn, $sql);
    while($row = mysqli_fetch_assoc($result)) {
        $time_key = date('H:i', strtotime($row['start_time'])) . ' - ' . date('H:i', strtotime($row['end_time']));
        $day_key = trim($row['day_of_week']); 
        $timetable_matrix[$time_key][$day_key] = $row;
        
        if (!in_array($time_key, $time_slots)) {
            $time_slots[] = $time_key;
        }
    }
}

$days = ['ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៏'];
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8fafc; margin: 0; }
    @media print {
        .no-print, .sidebar, .modal { display: none !important; }
        main { margin: 0 !important; width: 100% !important; padding: 0 !important; }
        .timetable-card { border: none !important; box-shadow: none !important; }
        .main-table th, .main-table td { border: 1.5px solid black !important; color: black !important; }
    }
    .main-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    .main-table th { background-color: #f3f4f6; border: 1.5px solid #000; padding: 12px; font-weight: 800; text-align: center; }
    .main-table td { border: 1.5px solid #000; padding: 10px; text-align: center; height: 85px; vertical-align: middle; }
    .sub-name { font-weight: 800; color: #1e293b; font-size: 14px; display: block; }
    .tea-name { font-size: 11px; color: #64748b; display: block; }

    /* Modal Styling */
    .modal { display: none; position: fixed; z-index: 50; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
    .modal-content { background: white; margin: 5% auto; padding: 25px; border-radius: 20px; width: 500px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); }
</style>

<div class="flex h-screen w-full overflow-hidden">
    <div class="no-print w-64 shrink-0 h-full bg-[#1e293b]">
        <?php include '../../includes/sidebar_staff.php'; ?>
    </div>

    <main class="flex-1 h-full overflow-y-auto bg-slate-50 p-8">
        
        <?php if($msg == 'success'): ?>
            <div class="no-print bg-green-500 text-white p-4 rounded-xl mb-6 flex items-center gap-3">
                <i class="fas fa-check-circle"></i>
                <span class="font-bold">ប្រតិបត្តិការជោគជ័យ!</span>
            </div>
        <?php endif; ?>

        <div class="no-print mb-8 flex flex-wrap gap-3 justify-center">
            <?php for($i=7; $i<=12; $i++): ?>
                <a href="?grade_level=<?= $i ?>&academic_year=<?= $academic_year ?>" 
                   class="px-8 py-3 rounded-2xl font-black transition-all shadow-sm <?= $selected_grade_level == $i ? 'bg-blue-600 text-white' : 'bg-white text-slate-400 border hover:bg-slate-50' ?>">
                    ថ្នាក់ទី <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>

        <div class="no-print bg-white p-6 rounded-3xl border shadow-sm mb-8 flex flex-wrap items-end gap-6">
            
            <div class="w-40">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 italic">ឆ្នាំសិក្សា</label>
                <form method="GET">
                    <input type="hidden" name="grade_level" value="<?= $selected_grade_level ?>">
                    <select name="academic_year" onchange="this.form.submit()" class="w-full border-2 border-slate-100 rounded-xl px-4 py-2 font-bold outline-none focus:border-blue-500">
                        <option value="2025-2026" <?= $academic_year == '2025-2026' ? 'selected' : '' ?>>2025-2026</option>
                        <option value="2026-2027" <?= $academic_year == '2026-2027' ? 'selected' : '' ?>>2026-2027</option>
                    </select>
                </form>
            </div>

            <div class="w-56">
                <label class="block text-[10px] font-black text-slate-400 uppercase mb-2 ml-1 italic">ឈ្មោះថ្នាក់</label>
                <form method="GET">
                    <input type="hidden" name="grade_level" value="<?= $selected_grade_level ?>">
                    <input type="hidden" name="academic_year" value="<?= $academic_year ?>">
                    <select name="grade" onchange="this.form.submit()" class="w-full border-2 border-slate-100 rounded-xl px-4 py-2 font-bold outline-none focus:border-blue-500">
                        <option value="">--- ជ្រើសរើសថ្នាក់ ---</option>
                        <?php foreach($specific_classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= $search_input == $class['id'] ? 'selected' : '' ?>><?= $class['class_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>

            <div class="border-l pl-6 flex items-center gap-4">
                <button onclick="document.getElementById('createModal').style.display='block'" class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold text-xs hover:bg-blue-700 shadow-sm">
                    <i class="fas fa-plus mr-1"></i> បង្កើតថ្មី
                </button>

                <div class="flex items-center gap-3">
                    <form action="../../actions/staff/import_timetable.php" method="POST" enctype="multipart/form-data" class="flex items-center gap-2 border-l pl-4 cursor-pointer">
                        <input type="hidden" name="academic_year" value="<?= $academic_year ?>">
                        <input type="file"  name="timetable_file" accept=".csv" required  class="text-[10px] w-32 cursor-pointer">
                        <button type="submit" name="import_btn" class="bg-green-600 text-white px-4 py-2 rounded-xl font-bold text-xs">
                            IMPORT
                        </button>
                    </form>
                </div>
            </div>

            <button onclick="window.print()" class="bg-slate-800 text-white px-6 py-2.5 rounded-xl font-bold flex items-center gap-2">
                <i class="fas fa-print"></i>
            </button>
        </div>

        <?php if($search_input && !empty($time_slots)): ?>
            <div class="bg-white rounded-[2rem] p-10 border shadow-sm">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-black italic underline decoration-blue-500 underline-offset-8">កាលវិភាគសិក្សា <?= htmlspecialchars($final_class_label) ?></h1>
                    <p class="text-slate-400 font-bold mt-4 italic">ឆ្នាំសិក្សា៖ <?= $academic_year ?></p>
                </div>

                <table class="main-table">
                    <thead>
                        <tr>
                            <th style="width: 150px;">ម៉ោងសិក្សា</th>
                            <?php foreach($days as $day): ?> <th>ថ្ងៃ<?= $day ?></th> <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($time_slots as $slot): ?>
                        <tr>
                            <td class="font-black bg-slate-50"><?= $slot ?></td>
                            <?php foreach($days as $day): ?>
                            <td>
                                <?php if(isset($timetable_matrix[$slot][$day])): 
                                    $item = $timetable_matrix[$slot][$day]; ?>
                                    <span class="sub-name text-blue-600"><?= $item['s_name'] ?></span>
                                    <span class="tea-name"><?= $item['t_name'] ?? '---' ?></span>
                                    <span class="text-[10px] font-bold text-slate-400 italic">បន្ទប់: <?= $item['room_number'] ?></span>
                                <?php endif; ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-32 bg-white rounded-[2.5rem] border-4 border-dashed border-slate-100">
                <p class="text-slate-300 font-bold italic text-lg text-uppercase">សូមជ្រើសរើសថ្នាក់សិក្សា</p>
            </div>
        <?php endif; ?>
    </main>
</div>

<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-5">
            <h2 class="font-black text-lg text-blue-900">បញ្ចូលកាលវិភាគថ្មី</h2>
            <button onclick="document.getElementById('createModal').style.display='none'" class="text-slate-400 hover:text-red-500"><i class="fas fa-times"></i></button>
        </div>
        <form action="../../actions/staff/create_timetable.php" method="POST" class="grid grid-cols-2 gap-4">
            <input type="hidden" name="academic_year" value="<?= $academic_year ?>">
            
            <div class="col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase">ថ្នាក់សិក្សា</label>
                <select name="class_id" required class="w-full border-2 p-2 rounded-lg font-bold">
                    <?php foreach($specific_classes as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $search_input == $c['id'] ? 'selected' : '' ?>><?= $c['class_name'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase">ថ្ងៃ</label>
                <select name="day" class="w-full border-2 p-2 rounded-lg font-bold">
                    <?php foreach($days as $d): ?> <option value="<?= $d ?>"><?= $d ?></option> <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase">បន្ទប់</label>
                <input type="text" name="room" required class="w-full border-2 p-2 rounded-lg font-bold" placeholder="9A">
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase">ម៉ោងផ្ដើម</label>
                <input type="time" name="start" required class="w-full border-2 p-2 rounded-lg font-bold">
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase">ម៉ោងបញ្ចប់</label>
                <input type="time" name="end" required class="w-full border-2 p-2 rounded-lg font-bold">
            </div>
            <div class="col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase">មុខវិជ្ជា</label>
                <select name="subject_id" required class="w-full border-2 p-2 rounded-lg font-bold">
                    <?php while($s = mysqli_fetch_assoc($subjects_list)): ?>
                        <option value="<?= $s['id'] ?>"><?= $s['subject_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-span-2">
                <label class="text-[10px] font-black text-slate-400 uppercase">គ្រូបង្រៀន</label>
                <select name="teacher_id" required class="w-full border-2 p-2 rounded-lg font-bold">
                    <?php while($t = mysqli_fetch_assoc($teachers_list)): ?>
                        <option value="<?= $t['teacher_id'] ?>"><?= $t['full_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-span-2 flex justify-end gap-3 mt-4">
                <button type="button" onclick="document.getElementById('createModal').style.display='none'" class="bg-slate-100 px-6 py-2 rounded-xl font-bold text-xs uppercase">បោះបង់</button>
                <button type="submit" name="save_btn" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold text-xs uppercase">រក្សាទុក</button>
            </div>
        </form>
    </div>
</div>

<script>
    window.onclick = function(event) {
        if (event.target == document.getElementById('createModal')) {
            document.getElementById('createModal').style.display = "none";
        }
    }
</script>

<?php include '../../includes/footer.php'; ?>