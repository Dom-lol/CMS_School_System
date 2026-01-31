<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

// ចាប់យកអត្តលេខសិស្សពី URL
$sid = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';

// ទាញយកទិន្នន័យសិស្ស
$query = "SELECT s.*, u.username FROM students s LEFT JOIN users u ON s.user_id = u.id WHERE s.student_id = '$sid'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

if (!$student) { 
    die("<div class='text-center py-20 font-bold text-red-500 text-2xl'>រកមិនឃើញទិន្នន័យសិស្សឡើយ!</div>"); 
}

// === Logic បង្ហាញកម្រិតថ្នាក់ ===
$cid = $student['class_id'];
$grades = [1 => "៧", 2 => "៨", 3 => "៩", 4 => "១០", 5 => "១១", 6 => "១២"];
$grade_label = isset($grades[$cid]) ? $grades[$cid] : $cid;

// === Logic រូបភាព ===
$p_img = $student['profile_img']; 
$path_profile = "../../assets/uploads/profiles/" . $p_img;
$path_staff = "../../assets/uploads/students/" . $student['photo'];

if (!empty($p_img) && file_exists($path_profile)) {
    $display_photo = $path_profile . "?v=" . time();
} elseif (!empty($student['photo']) && file_exists($path_staff)) {
    $display_photo = $path_staff;
} else {
    $display_photo = "../../assets/img/default.png";
}

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 
?>

<style>
    .student-card-ui {
        max-width: 900px; margin: 20px auto; background: white;
        border-radius: 40px; padding: 50px; border: 1px solid #eef2f6;
        box-shadow: 0 20px 60px rgba(0,0,0,0.02);
    }
    .info-box { background: #fcfdfe; border: 1px solid #f1f5f9; padding: 1.5rem; border-radius: 1.5rem; }
   
</style>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="no-print mb-6 flex justify-between items-center max-w-[900px] mx-auto">
        <a href="student_list.php" class="bg-white px-5 py-2 rounded-xl text-slate-600 font-bold shadow-sm hover:bg-slate-50 transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> បញ្ជីឈ្មោះ
        </a>
     
    </div>

    <div class="pdf-page student-card-ui animate-step">
        <div class="flex flex-col md:flex-row justify-between items-center md:items-start mb-12 pb-8 border-b-2 border-slate-50 gap-6">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="w-40 h-40 bg-slate-50 border-4 border-white shadow-xl rounded-[50%] overflow-hidden">
                    <img src="<?php echo $display_photo; ?>" class="w-full h-full object-cover">
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-4xl font-bold text-slate-800 mb-2" style="font-family: 'Khmer OS Muol Light';"><?php echo $student['full_name']; ?></h1>
                    <p class="text-xl text-blue-600 uppercase tracking-widest font-black"><?php echo $student['full_name_en']; ?></p>
                    <div class="mt-3 flex items-center justify-center md:justify-start gap-4">
                        <span class="text-slate-500 font-bold">ឆ្នាំសិក្សា៖ <span class="text-slate-700"><?php echo $student['academic_year']; ?></span></span>
                    </div>
                </div>
            </div>
            <div class="text-center md:text-right">
                <div class="px-6 py-3 bg-slate-900 text-white rounded-2xl text-sm font-bold mb-3 shadow-md">
                    ID: <?php echo $student['student_id']; ?>
                </div>
                <span class="px-4 py-1.5 <?php echo ($student['status'] == 'Active' || $student['status'] == 'កំពុងរៀន') ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600'; ?> rounded-full text-xs font-black uppercase">
                    ● <?php echo $student['status']; ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-8 h-8 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm"><i class="fas fa-user"></i></span>
                    ព័ត៌មានផ្ទាល់ខ្លួន & ទំនាក់ទំនង
                </h3>
                <div class="info-box space-y-4">
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-500 font-medium">ភេទ</span>
                        <span class="font-bold text-slate-700"><?php echo $student['gender']; ?></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-500 font-medium">ថ្ងៃខែឆ្នាំកំណើត</span>
                        <span class="font-bold text-slate-700"><?php echo date('d-m-Y', strtotime($student['dob'])); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-500 font-medium">កម្រិតថ្នាក់</span>
                        <span class="font-bold text-blue-600">ថ្នាក់ទី <?php echo $grade_label; ?> </span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-500 font-medium">ទូរស័ព្ទសិស្ស</span>
                        <span class="font-bold text-slate-700"><?php echo $student['phone'] ?: '---'; ?></span>
                    </div>
                    <div class="pt-2">
                        <span class="text-slate-500 text-xs font-bold uppercase block mb-2">ទីកន្លែងកំណើត</span>
                        <p class="text-slate-700 text-sm leading-relaxed"><?php echo $student['pob'] ?: '---'; ?></p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-3">
                    <span class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-sm"><i class="fas fa-users"></i></span>
                    ព័ត៌មានអាណាព្យាបាល
                </h3>
                <div class="info-box space-y-4">
                    <div class="border-b border-slate-100 pb-2">
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">ឪពុក</span>
                            <span class="font-bold text-slate-700"><?php echo $student['father_name'] ?: '---'; ?></span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-500 ">មុខរបរ៖ <?php echo $student['father_job'] ?: '---'; ?></span>
                        </div>
                    </div>
                    <div class="border-b border-slate-100 pb-2">
                        <div class="flex justify-between">
                            <span class="text-slate-500 font-medium">ម្តាយ</span>
                            <span class="font-bold text-slate-700"><?php echo $student['mother_name'] ?: '---'; ?></span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-slate-500">មុខរបរ៖ <?php echo $student['mother_job'] ?: '---'; ?></span>
                        </div>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-slate-500 font-medium">ទូរស័ព្ទអាណាព្យាបាល</span>
                        <span class="font-bold text-emerald-600"><?php echo $student['parent_phone'] ?: '---'; ?></span>
                    </div>
                    <div class="pt-2">
                        <span class="text-emerald-600 text-xs font-bold uppercase block mb-2">អាសយដ្ឋានបច្ចុប្បន្ន</span>
                        <p class="text-slate-700 text-sm leading-relaxed  border-l-2 border-emerald-200 pl-4 bg-emerald-50/30 py-3 rounded-r-xl">
                            <?php echo $student['address'] ?: 'មិនទាន់មានទិន្នន័យ'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!empty($student['note'])): ?>
        <div class="mt-8 p-4 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
            <span class="text-slate-400 text-[10px] font-bold uppercase block mb-1">សម្គាល់បន្ថែម៖</span>
            <p class="text-slate-600 text-sm italic"><?php echo $student['note']; ?></p>
        </div>
        <?php endif; ?>

        <!-- <div class="mt-16 pt-8 border-t border-slate-50 text-center">
            <p class="text-slate-500 text-[10px] uppercase tracking-[0.2em] font-bold">
              <?php echo date('Y-m-d H:i'); ?>
            </p>
        </div> -->
    </div>
</main>

<?php include '../../includes/footer.php'; ?>