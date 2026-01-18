<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
if (session_status() === PHP_SESSION_NONE) { session_start(); }

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../index.php?error=unauthorized"); exit();
}

$user_id = $_SESSION['user_id'] ?? 0;

// ១. ទាញយក class_id របស់សិស្ស (ឆែក error ជាមុន)
$student_res = mysqli_query($conn, "SELECT class_id FROM students WHERE user_id = '$user_id' LIMIT 1");

if (!$student_res) {
    // បើ error ត្រង់នេះ បង្ហាញថា Table students ឬ Column user_id មិនត្រឹមត្រូវ
    die("Database Error (Students): " . mysqli_error($conn));
}

$student_data = mysqli_fetch_assoc($student_res);
$my_class_id = $student_data['class_id'] ?? 0;

// ២. ទាញយកកាលវិភាគ (លុប Subquery homework_count ចេញដើម្បីកុំឱ្យ error)
$query = "SELECT t.*, s.subject_name, u.full_name AS teacher_name
          FROM timetable t
          LEFT JOIN subjects s ON t.subject_id = s.id
          LEFT JOIN teachers tr ON t.teacher_id = tr.user_id 
          LEFT JOIN users u ON tr.user_id = u.id
          WHERE t.class_id = '$my_class_id'
          ORDER BY t.start_time ASC";

$result = mysqli_query($conn, $query);

if (!$result) {
    // បើ error ត្រង់នេះ បង្ហាញថា Table timetable ឬការ Join មានបញ្ហា
    die("Database Error (Timetable): " . mysqli_error($conn));
}

$daily_schedules = [];
while ($row = mysqli_fetch_assoc($result)) {
    // រៀបចំ Array តាមថ្ងៃ (ច័ន្ទ, អង្គារ...)
    $day = $row['day_of_week'];
    $daily_schedules[$day][] = $row;
}

// ស្វែងរកថ្ងៃបច្ចុប្បន្នជាភាសាខ្មែរ
$day_map = ['Monday'=>'ច័ន្ទ', 'Tuesday'=>'អង្គារ', 'Wednesday'=>'ពុធ', 'Thursday'=>'ព្រហស្បតិ៍', 'Friday'=>'សុក្រ', 'Saturday'=>'សៅរ៍'];
$today_kh = $day_map[date('l')] ?? 'ច័ន្ទ';
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>កាលវិភាគសិក្សា | Smart School</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; background-color: #F8FAFC; }
        .day-tab.active { background: #2563eb; color: white; transform: scale(1.05); }
        .day-tab.active .day-num { color: white; }
        .animate-pop { animation: pop 0.3s ease-out; }
        @keyframes pop { 0% { opacity: 0; transform: translateY(10px); } 100% { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <?php include '../../includes/sidebar_student.php'; ?>

    <div class="flex-1 flex flex-col min-w-0">
        <header class="bg-[#1E56B1] pt-8 pb-20 px-6 relative">
            <div class="max-w-4xl mx-auto flex flex-col gap-6">
                <div class="flex justify-between items-center text-white">
                    <div>
                        <h1 class="text-2xl font-bold italic uppercase tracking-wider">Timetable</h1>
                        <p class="text-white/60 text-xs">តារាងពេលវេលាសិក្សារបស់អ្នក</p>
                    </div>
                    <div class="text-right">
                        <span class="block text-xl font-bold uppercase"><?= date('M Y') ?></span>
                        <span class="text-[10px] bg-white/20 px-2 py-0.5 rounded-full">School Management System</span>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-2 shadow-2xl flex justify-between gap-1 overflow-x-auto">
                    <?php 
                    $start_date = 19; // ឧទាហរណ៍ចាប់ពីថ្ងៃទី ១៩
                    foreach (['ច័ន្ទ', 'អង្គារ', 'ពុធ', 'ព្រហស្បតិ៍', 'សុក្រ', 'សៅរ៍'] as $i => $day): 
                        $is_today = ($day == $today_kh);
                    ?>
                    <button onclick="updateSchedule('<?= $day ?>', this)" 
                            class="day-tab flex-1 min-w-[65px] py-3 rounded-[1.5rem] transition-all flex flex-col items-center <?= $is_today ? 'active' : 'text-slate-400' ?>">
                        <span class="text-[9px] font-bold mb-1 opacity-70"><?= $day ?></span>
                        <span class="day-num text-lg font-black text-slate-800"><?= $start_date + $i ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto px-6 -mt-10 pb-10">
            <div id="schedule-list" class="max-w-4xl mx-auto space-y-4">
                </div>
        </main>
    </div>

    <script>
        const data = <?= json_encode($daily_schedules) ?>;

        function updateSchedule(day, btn) {
            // Update UI State
            document.querySelectorAll('.day-tab').forEach(t => t.classList.remove('active', 'text-slate-400'));
            document.querySelectorAll('.day-tab').forEach(t => t.classList.add('text-slate-400'));
            btn.classList.add('active');
            btn.classList.remove('text-slate-400');

            const container = document.getElementById('schedule-list');
            container.innerHTML = '';

            if (!data[day]) {
                container.innerHTML = `<div class="bg-white p-12 rounded-[2rem] text-center text-slate-300 italic">មិនមានកាលវិភាគសម្រាប់ថ្ងៃនេះទេ</div>`;
                return;
            }

            data[day].forEach(item => {
                const hwStatus = item.homework_count > 0 ? `<span class="text-rose-500">មានកិច្ចការ</span>` : `<span class="text-slate-300">មិនមាន</span>`;
                const html = `
                    <div class="animate-pop">
                        <div class="flex justify-between items-center px-4 mb-2">
                            <span class="text-xs font-black text-slate-500">${item.start_time.slice(0,5)} - ${item.end_time.slice(0,5)}</span>
                            <span class="text-[9px] bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-full font-bold">IN SESSION</span>
                        </div>
                        <div class="bg-white rounded-[2rem] p-6 shadow-sm border-l-[8px] border-[#27A785] flex items-center gap-6">
                            <div class="w-14 h-14 bg-[#27A785]/10 text-[#27A785] rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-slate-800 leading-tight">${item.subject_name}</h3>
                                <p class="text-xs text-blue-500 font-medium">${item.teacher_name} • បន្ទប់ ${item.room_number}</p>
                                
                                <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-slate-50 text-center">
                                    <div><p class="text-[8px] font-black text-[#27A785] uppercase">ស្រាវជ្រាវ</p><p class="text-[10px] font-bold text-slate-300">-----</p></div>
                                    <div class="border-x border-slate-100"><p class="text-[8px] font-black text-[#27A785] uppercase">មេរៀន</p><p class="text-[10px] font-bold text-slate-300">-----</p></div>
                                    <div><p class="text-[8px] font-black text-[#27A785] uppercase">កិច្ចការផ្ទះ</p><p class="text-[10px] font-black">${hwStatus}</p></div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                container.innerHTML += html;
            });
        }

        // Initialize with Today's Schedule
        window.onload = () => {
            const tabs = document.querySelectorAll('.day-tab');
            const todayTab = Array.from(tabs).find(t => t.innerText.includes('<?= $today_kh ?>'));
            updateSchedule('<?= $today_kh ?>', todayTab || tabs[0]);
        };
    </script>
</body>
</html>