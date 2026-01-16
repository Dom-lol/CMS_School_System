<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$current_page = 'timetable.php';
include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

$user_id = $_SESSION['user_id'];

// ទាញយកទិន្នន័យកាលវិភាគ
$query = "SELECT * FROM timetables WHERE teacher_id = '$user_id'";
$result = mysqli_query($conn, $query);

// រៀបចំទិន្នន័យដាក់ក្នុង Array ដើម្បីងាយស្រួលបង្ហាញតាម Grid
$schedule = [];
while ($row = mysqli_fetch_assoc($result)) {
    $schedule[$row['day_of_week']][] = $row;
}

$days = ['ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍', 'អាទិត្យ'];
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen font-['Kantumruy_Pro']">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fas fa-calendar-alt text-blue-600"></i>
                កាលវិភាគបង្រៀនប្រចាំសប្ដាហ៍
            </h1>
        </div>
        <a href="manage_timetable.php" class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
            <i class="fas fa-plus mr-1"></i> បន្ថែមម៉ោងបង្រៀន
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="grid grid-cols-7 border-b border-slate-100">
                <?php foreach ($days as $day): ?>
                    <div class="p-4 text-center border-r border-slate-50 bg-slate-50/50">
                        <span class="font-bold text-slate-700 text-lg"><?php echo $day; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="grid grid-cols-7 min-h-[500px]">
                <?php foreach ($days as $day): ?>
                    <div class="border-r border-slate-50 p-2 space-y-3 bg-white">
                        <?php if (isset($schedule[$day])): ?>
                            <?php foreach ($schedule[$day] as $item): ?>
                                <div class="group relative p-3 rounded-2xl border border-blue-100 bg-blue-50/50 hover:bg-blue-100 transition duration-300">
                                    <div class="text-xs font-bold text-blue-600 mb-1">
                                        <?php echo date('H:i', strtotime($item['start_time'])); ?> - <?php echo date('H:i', strtotime($item['end_time'])); ?>
                                    </div>
                                    <div class="font-bold text-slate-800 text-sm mb-1"><?php echo $item['subject_name']; ?></div>
                                    <div class="text-[11px] text-slate-500">
                                        <i class="fas fa-users mr-1"></i> <?php echo $item['class_name']; ?><br>
                                        <i class="fas fa-map-marker-alt mr-1"></i> <?php echo $item['room_number']; ?>
                                    </div>
                                    
                                    <div class="absolute top-1 right-1 hidden group-hover:flex gap-1">
                                        <a href="manage_timetable.php?id=<?php echo $item['id']; ?>" class="p-1 bg-amber-400 text-white rounded-md text-[10px]"><i class="fas fa-edit"></i></a>
                                        <a href="../../actions/timetable/delete.php?id=<?php echo $item['id']; ?>" onclick="return confirm('លុប?')" class="p-1 bg-red-500 text-white rounded-md text-[10px]"><i class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="h-full flex items-center justify-center">
                                <span class="text-[10px] text-slate-300 italic">គ្មានម៉ោងបង្រៀន</span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>