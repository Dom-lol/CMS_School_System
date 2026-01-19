<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$u_id = $_SESSION['user_id'];
$s_id = $_SESSION['username'] ?? '';

// ១. ទាញព័ត៌មានសិស្សសម្រាប់ Header
$student_info_query = mysqli_query($conn, "SELECT * FROM students WHERE user_id = '$u_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_info_query);
$display_name = $student_info['full_name'] ?? $s_id;

$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
               ? $profile_path . $student_info['profile_img'] . "?v=" . time() 
               : null;

// ២. ទាញបញ្ជីគ្រូ (teacher_id, full_name, subjects, phone)
$sql = "SELECT teacher_id, full_name, subjects, phone FROM teachers ORDER BY full_name ASC";
$teacher_q = mysqli_query($conn, $sql);

if (!$teacher_q) {
    die("SQL Error: " . mysqli_error($conn));
}

include '../../includes/header.php';
?>

<div class="flex h-screen w-full overflow-hidden bg-white font-khmer">
    
    <?php include '../../includes/sidebar_student.php'; ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden bg-slate-50">
        
       
<header class="bg-white border-b-2 border-slate-100 h-24 flex items-center justify-between px-6 md:px-10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-slate-800 hidden md:block uppercase tracking-tight italic">Student Dashboard</h1>
            </div>

            <div class="flex items-center gap-5">
                <div class="text-right ">
                    <p class="text-base font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
                    <p class="text-[11px] text-blue-500 font-bold uppercase tracking-[0.2em]">អត្តលេខ: <?php echo $s_id; ?></p>
                </div>
                
                <div class="relative group">
                    <div class="w-16 h-16 rounded-full border-4 border-white shadow-lg overflow-hidden bg-blue-600 flex items-center justify-center">
                        <?php if($current_img): ?>
                            <img src="<?php echo $current_img; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-white text-xl font-bold"><?php echo mb_substr($display_name, 0, 1); ?></span>
                        <?php endif; ?>
                    </div>
                    <form action="../../actions/students/upload_profile.php" method="POST" enctype="multipart/form-data" class="absolute -bottom-1 -right-1">
                        <label class="w-7 h-7 bg-white text-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-md border border-slate-100 hover:bg-blue-50 transition-all">
                            <i class="fas fa-camera text-[10px]"></i>
                            <input type="file" name="profile_img" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>
            </div>
        </header>
        <div class="flex-1 overflow-y-auto">

            <div class="max-w-4xl mx-auto bg-white min-h-full shadow-sm md:mt-6 md:rounded-t-[2.5rem] md:mb-10">

                <div id="teacherList">
                    <?php if(mysqli_num_rows($teacher_q) > 0): ?>
                        <?php while($t = mysqli_fetch_assoc($teacher_q)): ?>
                        
                        <div class="teacher-row flex items-center justify-between p-4 border-b border-slate-100 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full overflow-hidden bg-blue-50 border border-slate-200 flex items-center justify-center shadow-sm">
                                    <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" class="w-full h-full object-cover opacity-90" alt="Avatar">
                                </div>

                                <div class="flex flex-col">
                                    <h4 class="teacher-name text-slate-800 font-bold text-lg leading-tight mb-1"><?= $t['full_name'] ?></h4>
                                    <p class="text-slate-500 text-sm italic leading-tight"><?= $t['subjects'] ?></p>
                                </div>
                            </div>

                            <a href="tel:<?= $t['phone'] ?>" class="w-12 h-12 bg-[#e9f2ff] text-[#2b64be] rounded-full flex items-center justify-center border border-blue-100 shadow-sm active:scale-90 transition-all">
                                <i class="fas fa-phone-alt"></i>
                            </a>
                        </div>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="p-20 text-center text-slate-400 italic font-bold">
                            <i class="fas fa-user-slash text-4xl mb-3 block opacity-20"></i>
                            មិនទាន់មានទិន្នន័យគ្រូក្នុងប្រព័ន្ធ
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    // មុខងារ Search ឈ្មោះគ្រូ (Live Search)
    document.getElementById('teacherSearch').addEventListener('input', function() {
        let input = this.value.toLowerCase().trim();
        let rows = document.getElementsByClassName('teacher-row');
        
        Array.from(rows).forEach(row => {
            let name = row.querySelector('.teacher-name').innerText.toLowerCase();
            if(name.includes(input)) {
                row.style.display = "flex";
            } else {
                row.style.display = "none";
            }
        });
    });

    // មុខងារបើកបិទ Sidebar
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.toggle('-translate-x-full');
        }
    }
</script>

<style>
    /* បន្ថែម Font ខ្មែរឱ្យស្អាត */
    @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap');
    
    .font-khmer { 
        font-family: 'Kantumruy Pro', sans-serif; 
    }

    /* លាក់ Scrollbar សម្រាប់ Chrome, Safari និង Opera */
    ::-webkit-scrollbar {
        width: 0px;
        background: transparent;
    }

    /* រចនាបថបន្ថែមសម្រាប់រលកនៃ Row */
    .teacher-row {
        animation: fadeIn 0.3s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<?php include '../../includes/footer.php'; ?>