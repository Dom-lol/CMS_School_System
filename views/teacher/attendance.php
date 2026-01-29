<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ទាញយកព័ត៌មានគ្រូសម្រាប់ Header
$u_id = $_SESSION['user_id'];
$t_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$t_data = mysqli_fetch_assoc($t_query);

$real_t_id   = $t_data['teacher_id'] ?? 'N/A';
$t_full_name = $t_data['full_name'] ?? $_SESSION['full_name'];
$t_profile   = $t_data['profile_image'] ?? '';

// ២. ទទួលយក Class ID និង Date ពី Dropdown (យកគំរូតាម Student List)
$target_class_id = isset($_GET['class_id']) ? (int)$_GET['class_id'] : 1; 
$date = $_GET['date'] ?? date('Y-m-d');

// Mapping ឈ្មោះថ្នាក់សម្រាប់បង្ហាញចំណងជើង
$grade_map = [1 => "7", 2 => "8", 3 => "9", 4 => "10", 5 => "11", 6 => "12"];
$display_class_name = $grade_map[$target_class_id] ?? '---';

// ៣. ទាញបញ្ជីថ្នាក់ដែលគ្រូនេះមានបង្រៀន (សម្រាប់ដាក់ក្នុង Select)
$all_classes_res = mysqli_query($conn, "SELECT DISTINCT class_id FROM timetable WHERE teacher_id = '$real_t_id' AND is_deleted = 0");

// ៤. ទាញបញ្ជីសិស្សតាមថ្នាក់ដែលបានរើស
$st_query = "SELECT id, full_name, student_id, gender FROM students 
             WHERE class_id = '$target_class_id' AND status = 'Active' 
             ORDER BY full_name ASC";
$students = mysqli_query($conn, $st_query);

include '../../includes/header.php'; 
?>

<style>
    body { font-family: 'Kantumruy Pro', sans-serif; }
    /* លុប arrow default របស់ select */
    select { -webkit-appearance: none; appearance: none; }
</style>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    <?php include '../../includes/sidebar_teacher.php'; ?>
    
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
        <header class="bg-white border-b-4 border-blue-600 shadow-md px-4 md:px-10 py-4 shrink-0">
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
                
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <button onclick="toggleSidebar()" class="lg:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="w-16 h-16 rounded-full border-4 border-blue-100 shadow-sm overflow-hidden bg-slate-200">
                        <?php 
                            $path = "../../assets/uploads/teachers/";
                            $display_img = (!empty($t_profile) && file_exists($path . $t_profile)) ? $path . $t_profile : $path . 'default_user.png';
                        ?>
                        <img src="<?= $display_img ?>" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="text-xl md:text-2xl font-black text-blue-700 leading-tight"><?= htmlspecialchars($t_full_name) ?></h2>
                        <p class="text-[10px] font-bold text-slate-400 uppercase italic">ID: #<?= $real_t_id ?></p>
                    </div>
                </div>

                <div class="w-full md:w-auto">
                    <form id="filterForm" method="GET" class="flex flex-wrap items-center justify-center gap-3">
                        <input type="date" name="date" value="<?= $date ?>" onchange="document.getElementById('filterForm').submit()"
                               class="bg-slate-100 text-slate-700 font-bold px-4 py-3 rounded-xl border-none outline-none cursor-pointer hover:bg-slate-200 transition-all">
                        
                        <div class="relative w-full md:w-48">
                            <select name="class_id" onchange="this.form.submit()" 
                                    class="w-full bg-blue-600 text-white text-lg font-black rounded-2xl px-6 py-4 shadow-lg outline-none cursor-pointer hover:bg-blue-700 transition-all text-center pr-10">
                                <option value="" class="bg-white text-slate-800">--- រើសថ្នាក់ ---</option>
                                <?php if($all_classes_res): 
                                    mysqli_data_seek($all_classes_res, 0); 
                                    while($c = mysqli_fetch_assoc($all_classes_res)): 
                                        $id = $c['class_id'];
                                        $label = $grade_map[$id] ?? $id;
                                ?>
                                    <option value="<?= $id ?>" <?= $target_class_id == $id ? 'selected' : '' ?> class="bg-white text-slate-800 text-left">
                                        ថ្នាក់ទី <?= $label ?>
                                    </option>
                                <?php endwhile; endif; ?>
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-5 text-white text-xs pointer-events-none"></i>
                        </div>
                    </form>
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 bg-[#f8fafc] custom-scrollbar">
            
            <div class="mb-8">
                <div class="bg-slate-900 p-8 rounded-[2.5rem] text-white shadow-xl flex items-center justify-between border-b-8 border-blue-600">
                    <div>
                        <p class="text-[10px] opacity-50 uppercase font-black tracking-widest mb-1">Attendance for</p>
                        <h1 class="text-3xl font-black italic uppercase">ថ្នាក់ទី <?= $display_class_name ?></h1>
                    </div>
                    <div class="text-right hidden md:block">
                        <p class="text-xl font-bold opacity-30 italic"><?= date('D, d M Y', strtotime($date)) ?></p>
                    </div>
                </div>
            </div>

            <?php if ($students && mysqli_num_rows($students) > 0): ?>
            <form action="../../actions/teachers/save_attendance.php" method="POST">
                <input type="hidden" name="class_id" value="<?= $target_class_id ?>">
                <input type="hidden" name="date" value="<?= $date ?>">

                <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-24">
                    <div class="overflow-x-auto"> 
                        <table class="w-full text-left min-w-[600px]">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="p-8 font-black uppercase text-[11px] text-slate-400 tracking-widest">ព័ត៌មានសិស្ស</th>
                                    <th class="p-8 font-black uppercase text-[11px] text-slate-400 tracking-widest text-center">ស្ថានភាពវត្តមាន</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php while($row = mysqli_fetch_assoc($students)): 
                                    $s_id = $row['id'];
                                    $check_att = mysqli_query($conn, "SELECT status FROM attendance WHERE student_id='$s_id' AND attendance_date='$date' LIMIT 1");
                                    $saved_data = mysqli_fetch_assoc($check_att);
                                    $current_status = $saved_data['status'] ?? 'present';
                                ?>
                                <tr class="hover:bg-blue-50/30 transition-all">
                                    <td class="p-6">
                                        <div class="text-xl font-bold text-slate-800 uppercase italic leading-tight"><?= htmlspecialchars($row['full_name']) ?></div>
                                        <div class="text-[10px] text-slate-400 font-black uppercase mt-1 italic tracking-tighter">ID: <?= $row['student_id'] ?> | ភេទ: <?= $row['gender'] ?></div>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex justify-center items-center gap-8 md:gap-16">
                                            <label class="flex flex-col items-center gap-2 cursor-pointer group">
                                                <input type="radio" name="att[<?= $s_id ?>]" value="present" <?= $current_status == 'present' ? 'checked' : '' ?> class="w-8 h-8 accent-green-500">
                                                <span class="text-[11px] font-black <?= $current_status == 'present' ? 'text-green-600' : 'text-slate-400' ?> uppercase italic">មក</span>
                                            </label>
                                            
                                            <label class="flex flex-col items-center gap-2 cursor-pointer group">
                                                <input type="radio" name="att[<?= $s_id ?>]" value="permission" <?= $current_status == 'permission' ? 'checked' : '' ?> class="w-8 h-8 accent-orange-500">
                                                <span class="text-[11px] font-black <?= $current_status == 'permission' ? 'text-orange-600' : 'text-slate-400' ?> uppercase italic">ច្បាប់</span>
                                            </label>

                                            <label class="flex flex-col items-center gap-2 cursor-pointer group">
                                                <input type="radio" name="att[<?= $s_id ?>]" value="absent" <?= $current_status == 'absent' ? 'checked' : '' ?> class="w-8 h-8 accent-red-500">
                                                <span class="text-[11px] font-black <?= $current_status == 'absent' ? 'text-red-600' : 'text-slate-400' ?> uppercase italic">អវត្តមាន</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="fixed bottom-8 right-8 z-50">
                    <button type="submit" class="bg-blue-600 text-white px-10 py-5 rounded-full font-black uppercase text-xs tracking-[0.2em] shadow-2xl hover:bg-slate-900 transition-all hover:-translate-y-1 flex items-center gap-3 active:scale-95">
                        <i class="fas fa-save text-lg"></i>
                        <span>រក្សាទុកទិន្នន័យ</span>
                    </button>
                </div>
            </form>
            <?php else: ?>
                <div class="h-64 flex flex-col items-center justify-center border-4 border-dashed border-slate-100 rounded-[3rem] opacity-50 italic uppercase font-black text-slate-300">
                    <i class="fas fa-user-slash text-4xl mb-4"></i>
                    រកមិនឃើញសិស្សក្នុងថ្នាក់ទី <?= $display_class_name ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar'); 
        if(sidebar) sidebar.classList.toggle('-translate-x-full');
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'ជោគជ័យ!',
            text: 'វត្តមានត្រូវបានរក្សាទុកដោយជោគជ័យ',
            confirmButtonColor: '#2563eb',
            timer: 2500
        });
    }
</script>

<?php include '../../includes/footer.php'; ?>