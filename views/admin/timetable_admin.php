<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

$current_page = 'timetable.php';
include '../../includes/header.php'; 

// ១. រៀបចំ Array ឱ្យត្រូវតាម Database (ភាសាខ្មែរ)
$days = [
    'ច័ន្ទ' => 'ថ្ងៃច័ន្ទ', 
    'អង្គារ' => 'ថ្ងៃអង្គារ', 
    'ពុធ' => 'ថ្ងៃពុធ',
    'ព្រហស្បតិ៍' => 'ថ្ងៃព្រហស្បតិ៍', 
    'សុក្រ' => 'ថ្ងៃសុក្រ', 
    'សៅរ៍' => 'ថ្ងៃសៅរ៍'
];

// ២. រៀបចំម៉ោងសិក្សាឱ្យត្រូវតាម start_time និង end_time ក្នុង DB
$timeslots = [
    ['start' => '07:00:00', 'end' => '07:50:00', 'display' => '07:00 - 07:50'],
    ['start' => '08:00:00', 'end' => '08:50:00', 'display' => '08:00 - 08:50'],
    ['start' => '09:00:00', 'end' => '09:50:00', 'display' => '09:00 - 09:50'],
    ['start' => '10:00:00', 'end' => '10:50:00', 'display' => '10:00 - 10:50']
];

$class_id = isset($_GET['class_id']) ? mysqli_real_escape_string($conn, $_GET['class_id']) : 1;

// ទាញឈ្មោះថ្នាក់
$class_info = mysqli_query($conn, "SELECT class_name FROM classes WHERE id = '$class_id'");
$class_data = mysqli_fetch_assoc($class_info);
$display_name = $class_data ? $class_data['class_name'] : 'ថ្នាក់ទី ៧';
?>

<style>
    /* លាក់ Sidebar និង Header ពេល Print */
    @media print {
        aside, .no-print, header, nav, .sidebar-admin { display: none !important; }
        .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; }
        body { background: white !important; }
        .table-border-print { border: 2px solid black !important; border-radius: 0 !important; }
        th, td { border: 1px solid black !important; color: black !important; }
    }
</style>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden font-['Kantumruy_Pro']">
    <div class="no-print h-full">
        <?php include '../../includes/sidebar_admin.php'; ?>
    </div>

    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden main-content">
        <header class="bg-white border-b border-slate-100 h-24 flex items-center justify-between px-10 shrink-0 no-print">
            <form method="GET" class="flex items-center gap-3 bg-slate-50 p-2 rounded-2xl border border-slate-200">
                <span class="pl-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">ជ្រើសរើសថ្នាក់៖</span>
                <select name="class_id" onchange="this.form.submit()" class="bg-white border-none rounded-xl px-4 py-2 font-bold text-sm text-slate-700 shadow-sm">
                    <?php 
                    $all_classes = mysqli_query($conn, "SELECT id, class_name FROM classes");
                    while($c = mysqli_fetch_assoc($all_classes)):
                    ?>
                    <option value="<?= $c['id'] ?>" <?= $class_id == $c['id'] ? 'selected' : '' ?>><?= $c['class_name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </form>
            <button onclick="window.print()" class="bg-[#1e293b] text-white px-8 py-3 rounded-2xl font-bold flex items-center gap-3 hover:bg-black transition-all">
                <i class="fas fa-print"></i> បោះពុម្ព
            </button>
        </header>

        <main class="flex-1 overflow-y-auto p-12 bg-white custom-scrollbar">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-slate-800 italic uppercase border-b-4 border-blue-600 inline-block pb-2">កាលវិភាគសិក្សា<?= $display_name ?></h2>
                <p class="text-[12px] text-slate-400 font-bold uppercase mt-4 tracking-[0.3em]">កាលបរិច្ឆេទ៖ <?= date('d/m/Y') ?></p>
            </div>

            <div class="table-border-print border-[1.5px] border-slate-900 rounded-2xl overflow-hidden shadow-2xl">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="border border-slate-900 p-6 text-center font-black italic text-slate-800 uppercase text-[11px] w-40">ម៉ោងសិក្សា</th>
                            <?php foreach($days as $kh): ?>
                                <th class="border border-slate-900 p-6 text-center font-black italic text-slate-800 uppercase text-[11px]"><?= $kh ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($timeslots as $slot): ?>
                        <tr>
                            <td class="border border-slate-900 p-6 text-center font-black italic text-slate-800 text-[14px] bg-slate-50/50"><?= $slot['display'] ?></td>
                            <?php foreach($days as $day_db => $day_kh): 
                                // SQL Query ឱ្យត្រូវតាម start_time, end_time និង day_of_week (ខ្មែរ)
                                $sql = "SELECT t.*, s.subject_name, te.full_name as teacher_name 
                                        FROM timetable t
                                        LEFT JOIN subjects s ON t.subject_id = s.id
                                        LEFT JOIN teachers te ON t.teacher_id = te.teacher_id
                                        WHERE t.class_id = '$class_id' 
                                        AND t.day_of_week = '$day_db' 
                                        AND t.start_time = '{$slot['start']}' 
                                        AND t.end_time = '{$slot['end']}' LIMIT 1";
                                $res = mysqli_query($conn, $sql);
                                $data = ($res && mysqli_num_rows($res) > 0) ? mysqli_fetch_assoc($res) : null;
                            ?>
                            <td class="border border-slate-900 p-5 text-center min-w-[150px]">
                                <?php if($data): ?>
                                    <div class="flex flex-col gap-1">
                                        <span class="font-black text-blue-800 text-[15px] italic leading-tight uppercase"><?= $data['subject_name'] ?></span>
                                        <span class="text-[11px] font-bold text-slate-500 italic"><?= $data['teacher_name'] ?></span>
                                        <span class="text-[10px] font-black text-blue-500 italic uppercase mt-1">Room: <?= $data['room_number'] ?></span>
                                    </div>
                                <?php else: ?>
                                    <span class="opacity-10 text-[10px] font-bold text-slate-200 uppercase">---</span>
                                <?php endif; ?>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>