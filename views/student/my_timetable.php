<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$user_id = $_SESSION['user_id'] ?? ''; 
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE user_id = '$user_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

$active_grade = $student_info['class_id'] ?? '';
$student_name = $student_info['full_name'] ?? 'មិនស្គាល់ឈ្មោះ';
$s_id = $student_info['student_id'] ?? 'N/A';
$display_name = $student_name;

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
$search_day_kh = $days_mapping[$active_day_en];

$result = false;
if (!empty($active_grade)) {
    $sql = "SELECT t.*, s.subject_name, te.full_name 
            FROM timetable t 
            LEFT JOIN subjects s ON t.subject_id = s.id 
            LEFT JOIN teachers te ON t.teacher_id = te.teacher_id 
            WHERE t.class_id = '$active_grade' 
            AND t.day_of_week = '$search_day_kh' 
            AND t.is_deleted = 0 
            ORDER BY t.start_time ASC";
    $result = mysqli_query($conn, $sql);
}

include '../../includes/header.php';
?>

<style>
    /* CSS សម្រាប់ប្តូរទម្រង់ Print ឱ្យដូច Staff */
    @media print {
        /* លាក់ផ្នែកមិនចាំបាច់ */
        .no-print, #sidebar, header, .bg-blue-600, .flex.gap-2.p-4 { 
            display: none !important; 
        }
        
        /* កំណត់ Layout បោះពុម្ព */
        body { background: white !important; font-family: 'Khmer OS Battambang', sans-serif !important; }
        .flex.h-screen { display: block !important; height: auto !important; }
        main { width: 100% !important; margin: 0 !important; padding: 0 !important; overflow: visible !important; }
        
        /* បង្ហាញ Header ផ្លូវការ */
        .print-header { display: block !important; text-align: center; margin-bottom: 20px; }
        
        /* ប្តូរ Card ទៅជា Table (Bordered) */
        .web-cards { display: none !important; } /* លាក់ Card ពេល Print */
        .print-table { display: table !important; width: 100%; border-collapse: collapse; }
        .print-table th, .print-table td { border: 1.5px solid black !important; padding: 10px; text-align: center; font-size: 14px; }
        .print-table th { background-color: #f2f2f2 !important; font-family: 'Khmer OS Muol Light'; }
        
        /* ហត្ថលេខា */
        .signature-row { display: flex !important; justify-content: space-between; margin-top: 50px; }
    }

    /* លាក់ផ្នែក Print នៅលើអេក្រង់ Web */
    .print-header, .print-table, .signature-row { display: none; }
</style>

<div class="flex h-screen w-full overflow-hidden bg-slate-50">
    <?php include '../../includes/sidebar_student.php'; ?>

    <main class="flex-1 flex flex-col w-full h-screen overflow-hidden">
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 flex-shrink-0 no-print">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200 transition-colors">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-slate-800 hidden md:block uppercase tracking-tight">Dashboard</h1>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right hidden sm:block">
                    <p class="text-base font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
                    <p class="text-[11px] text-blue-500 font-bold uppercase tracking-[0.2em]">អត្តលេខ: <?php echo $s_id; ?></p>
                </div>
                <div class="w-16 h-16 bg-gradient-to-tr from-blue-600 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg border-4 border-white">
                    <?php echo mb_substr($display_name, 0, 1); ?>
                </div>
            </div>
        </header>

        <div class="w-full bg-blue-600 p-6 text-white shadow-lg flex justify-between items-center no-print">
            <div>
                <h1 class="text-2xl font-black italic uppercase text-white">កាលវិភាគថ្នាក់ទី <?= htmlspecialchars($active_grade) ?></h1>
                <p class="text-sm opacity-80">ថ្ងៃ<?= $search_day_kh ?></p>
            </div>
            <button onclick="window.print()" class="bg-white text-blue-600 px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 shadow-md hover:scale-105 transition-all">
                <i class="fas fa-print"></i> បោះពុម្ព
            </button>
        </div>

        <div class="w-full flex gap-2 p-4 overflow-x-auto bg-white shadow-sm border-b no-print">
            <?php foreach($days_mapping as $en => $kh): ?>
                <a href="?day=<?= $en ?>" class="px-6 py-2 rounded-full text-sm font-bold transition-all whitespace-nowrap <?= ($active_day_en == $en) ? 'bg-blue-600 text-white shadow-md' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' ?>">
                    <?= $kh ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="flex-1 w-full overflow-y-auto p-6 scroll-smooth">
            
            <div class="max-w-7xl mx-auto web-cards">
                <?php if($result && mysqli_num_rows($result) > 0): 
                    mysqli_data_seek($result, 0); 
                    while($row = mysqli_fetch_assoc($result)): ?>
                        <div class="bg-white p-6 rounded-2xl shadow-sm mb-4 border-l-8 border-blue-500 flex justify-between items-center">
                            <div>
                                <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-[10px] font-black italic">
                                    <?= date('H:i', strtotime($row['start_time'])) ?> - <?= date('H:i', strtotime($row['end_time'])) ?>
                                </span>
                                <h3 class="text-xl font-black text-slate-800 mt-2"><?= $row['subject_name'] ?></h3>
                                <p class="text-sm text-slate-500 font-medium italic"><?= $row['full_name'] ?></p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] text-slate-400 font-bold block uppercase">បន្ទប់</span>
                                <span class="text-2xl font-black text-blue-600"><?= $row['room_number'] ?></span>
                            </div>
                        </div>
                    <?php endwhile;
                else: ?>
                    <div class="text-center py-24 no-print">
                        <div class="text-slate-200 mb-4"><i class="fas fa-calendar-times text-8xl"></i></div>
                        <p class="text-slate-400 italic font-bold">មិនមានកាលវិភាគសម្រាប់ថ្ងៃ <?= $search_day_kh ?> ទេ</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="print-header">
                <h2 class="text-xl font-bold" style="font-family: 'Khmer OS Muol Light';">កាលវិភាគសិក្សាថ្នាក់ទី <?= $active_grade ?></h2>
                <h3 class="text-md font-bold mt-1">កាលបរិច្ឆេទបោះពុម្ព៖ <?= date('d/m/Y') ?></h3>
                <div class="flex justify-between mt-4 px-2" style="font-size: 14px; font-weight: bold;">
                    <span>ឈ្មោះសិស្ស៖ <?= $student_name ?></span>
                    <span>ថ្ងៃ៖ <?= $search_day_kh ?></span>
                </div>
            </div>

            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 25%;">ម៉ោងសិក្សា</th>
                        <th>មុខវិជ្ជា</th>
                        <th>គ្រូបង្រៀន</th>
                        <th style="width: 15%;">បន្ទប់</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if($result) {
                        mysqli_data_seek($result, 0); 
                        while($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td class="font-bold"><?= date('H:i', strtotime($row['start_time'])) ?> - <?= date('H:i', strtotime($row['end_time'])) ?></td>
                                <td class="font-bold"><?= $row['subject_name'] ?></td>
                                <td><?= $row['full_name'] ?></td>
                                <td class="font-bold"><?= $row['room_number'] ?></td>
                            </tr>
                        <?php endwhile; 
                    } ?>
                </tbody>
            </table>

            <div class="signature-row">
                <div class="text-center">
                    <p>គ្រូប្រចាំថ្នាក់</p>
                    <br><br><br>
                    <p>(......................................)</p>
                </div>
                <div class="text-center">
                    <p>បានឃើញ និងឯកភាព<br>នាយកសាលា</p>
                    <br><br>
                    <p>(......................................)</p>
                </div>
            </div>
        </div>
    </main>
</div>

<?php include '../../includes/footer.php'; ?>