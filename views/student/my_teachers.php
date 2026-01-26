<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

/** * ដោះស្រាយបញ្ហា Fatal Error: 
 * ប្រសិនបើរកមុខងារ student_access() មិនឃើញ យើងនឹងប្រើលក្ខខណ្ឌឆែកផ្ទាល់
 */
if (function_exists('student_access')) {
    student_access();
} else {
    // បើរក Function មិនឃើញ ប្រើកូដការពារនេះវិញដើម្បីឈប់វិល Loop
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        header("Location: ../../login.php");
        exit();
    }
}

$u_id = $_SESSION['user_id'];
$s_id = $_SESSION['username'] ?? '';

// ទាញព័ត៌មានសិស្ស
$student_info_query = mysqli_query($conn, "SELECT * FROM students WHERE user_id = '$u_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_info_query);
$display_name = $student_info['full_name'] ?? $s_id;

// ទាញបញ្ជីគ្រូ
$sql = "SELECT teacher_id, full_name, subjects, phone, profile_image FROM teachers ORDER BY full_name ASC";
$teacher_q = mysqli_query($conn, $sql);

// Path រូបភាព
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
               ? $profile_path . $student_info['profile_img'] . "?v=" . time() 
               : null;

include '../../includes/header.php';
?>

<div class="flex h-screen w-full overflow-hidden bg-white font-khmer">
    <?php include '../../includes/sidebar_student.php'; ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
         <!-- ===== Header profile img ===== -->
        <header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right ">
                    <p class="text-[20px] font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
                    <p class="text-[12px] text-gray-500 font-bold uppercase tracking-[0.2em]">អត្តលេខ: <?php echo $s_id; ?></p>
                </div>
                
                <div class="relative group">
                    <div class="w-16 h-16 rounded-full border-4 border-white shadow-lg overflow-hidden bg-blue-600 flex items-center justify-center">
                        <?php if($current_img): ?>
                            <img src="<?php echo $current_img; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-white text-xl font-bold"><?php echo mb_substr($display_name, 0, 1); ?></span>
                        <?php endif; ?>
                    </div>
                    <form action="../../actions/uploads/profiles" method="POST" enctype="multipart/form-data" class="absolute -bottom-1 -right-1">
                        <label class="w-7 h-7 bg-white text-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-md border border-slate-100 hover:bg-blue-50 transition-all">
                            <i class="fas fa-camera text-[10px]"></i>
                            <input type="file" name="profile_img" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto p-4 md:p-8">
            <!-- controll profile img -->
            <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div id="teacherList">
                    <?php if(mysqli_num_rows($teacher_q) > 0): ?>
                        <?php while($t = mysqli_fetch_assoc($teacher_q)): ?>
                        <div class="teacher-row flex items-center justify-between p-5 border-b border-slate-100 hover:bg-blue-50/30 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-[80px] h-[80px] rounded-[50%] overflow-hidden bg-white border border-slate-200 flex items-center justify-center shadow-sm">
                                   <?php 
                                     $img_path = "../../assets/uploads/teachers/";
                                     $file_name = !empty($t['profile_image']) ? $t['profile_image'] : 'default_user.png';
                                     
                                     
                                     if (!file_exists($img_path . $file_name)) {
                                         $file_name = 'default_user.png';
                                     }
                                   ?>
                                   <img src="<?= $img_path . $file_name ?>" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col">
                                    <h4 class="teacher-name text-slate-800 font-bold text-lg leading-tight mb-1"><?= $t['full_name'] ?></h4>
                                    <p class="text-slate-500 text-sm italic leading-tight"><?= $t['subjects'] ?></p>
                                </div>
                            </div>
                            <a href="tel:<?= $t['phone'] ?>" class="w-11 h-11 bg-green-50 text-green-600 rounded-xl flex items-center justify-center border border-green-100 hover:bg-green-100 active:scale-90 transition-all">
                                <i class="fas fa-phone-alt"></i>
                            </a>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="p-20 text-center text-slate-400 italic">មិនទាន់មានទិន្នន័យគ្រូ</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    document.getElementById('teacherSearch').addEventListener('input', function() {
        let input = this.value.toLowerCase().trim();
        let rows = document.getElementsByClassName('teacher-row');
        Array.from(rows).forEach(row => {
            let name = row.querySelector('.teacher-name').innerText.toLowerCase();
            row.style.display = name.includes(input) ? "flex" : "none";
        });
    });
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) sidebar.classList.toggle('-translate-x-full');
    }
</script>
<?php include '../../includes/footer.php'; ?>