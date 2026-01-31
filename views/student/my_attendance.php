<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

if (session_status() === PHP_SESSION_NONE) { session_start(); }

// ១. ទាញយកព័ត៌មានសិស្ស (ប្រើ Logic ដូច Dashboard ដែលលោកគ្រូថាដើរ) [cite: 2026-01-20]
$s_id = $_SESSION['username'] ?? '';
$student_query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$s_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_query);

// កំណត់ Variable សំខាន់ៗ
$real_st_id   = $student_info['id'] ?? 0; 
$display_name = $student_info['full_name'] ?? ($_SESSION['full_name'] ?? $s_id);
$s_id_display = $student_info['student_id'] ?? $s_id;

// Query លើ Table attendance
$stats_query = mysqli_query($conn, "
    SELECT 
        SUM(CASE WHEN status = 'present' THEN 1 ELSE 0 END) as total_present,
        SUM(CASE WHEN status = 'absent' THEN 1 ELSE 0 END) as total_absent,
        SUM(CASE WHEN status = 'permission' THEN 1 ELSE 0 END) as total_permission
    FROM attendance 
    WHERE student_id = '$real_st_id'
");
$stats = mysqli_fetch_assoc($stats_query);


$att_list = mysqli_query($conn, "
    SELECT attendance_date, status 
    FROM attendance 
    WHERE student_id = '$real_st_id' 
    ORDER BY attendance_date DESC
");

// ៤. រៀបចំ Path រូបភាព
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
                ? $profile_path . $student_info['profile_img'] . "?v=" . time() 
                : null;

include '../../includes/header.php'; 
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden font-['Kantumruy_Pro']">
    <?php include '../../includes/sidebar_student.php'; ?>
    
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        <!-- header profile img -->
       <header class="bg-white border-b-2 border-slate-100 h-20 flex items-center justify-between px-6 md:px-10 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden p-3 bg-slate-100 text-slate-600 rounded-2xl hover:bg-slate-200">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>

            <div class="flex items-center gap-5">
               
                <div class="text-right ">
                    <p class="text-[18px] font-bold text-slate-900 leading-tight"><?php echo $display_name; ?></p>
                    <p class="text-[12px] text-gray-500 font-bold uppercase ">អត្តលេខ: <?php echo $s_id; ?></p>
                </div>
                <div class="relative group">
                    <div onclick="openInfoModal()"  class="w-16 h-16 rounded-full border-4 border-white shadow-md overflow-hidden bg-blue-600 flex items-center justify-center">
                        <?php if($current_img): ?>
                            <img src="<?php echo $current_img; ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span class="text-white text-xl font-bold"><?php echo mb_substr($display_name, 0, 1); ?></span>
                        <?php endif; ?>
                    </div>
                    <form action="../../actions/students/upload_profile.php" method="POST" enctype="multipart/form-data" id="profileForm">
                        <label class="absolute -bottom-1 -right-1 w-7 h-7 bg-white text-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-md border border-slate-100 hover:bg-blue-600 hover:text-white transition-all">
                            <i class="fas fa-camera text-[10px]"></i>
                            <input type="file" name="profile_img" class="hidden" accept="image/*" onchange="document.getElementById('profileForm').submit()">
                        </label>
                    </form>
                </div>
                
               
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-6 md:p-10 custom-scrollbar">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-[2rem] border-2 border-green-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center"><i class="fas fa-user-check"></i></div>
                    <div>
                        <p class="text-[13px] font-black text-green-500 uppercase">វត្តមានសរុប</p>
                        <h3 class="text-3xl font-black text-slate-800"><?= $stats['total_present'] ?? 0 ?></h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border-2 border-red-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center"><i class="fas fa-user-times"></i></div>
                    <div>
                        <p class="text-[13px] font-black text-red-500 uppercase">អវត្តមានសរុប</p>
                        <h3 class="text-3xl font-black text-slate-800"><?= $stats['total_absent'] ?? 0 ?></h3>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border-2 border-orange-100 shadow-sm flex items-center gap-5">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center"><i class="fas fa-file-signature"></i></div>
                    <div>
                        <p class="text-[13px] font-black text-orange-500 uppercase">ច្បាប់សរុប</p>
                        <h3 class="text-3xl font-black text-slate-800"><?= $stats['total_permission'] ?? 0 ?></h3>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-900 text-white">
                        <tr>
                            <th class="p-6 text-[15px] font-bold uppercase tracking-widest">ថ្ងៃខែឆ្នាំ</th>
                            <th class="p-6 text-[15px] font-bold uppercase tracking-widest text-center">ស្ថានភាព</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (mysqli_num_rows($att_list) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($att_list)): ?>
                            <tr class="hover:bg-slate-50 transition-all">
                                <td class="p-6 font-bold text-slate-700">
                                    <?= date('d-M-Y', strtotime($row['attendance_date'])) ?>
                                </td>
                                <td class="p-6 text-center ">
                                    <?php 
                                        $s = $row['status'];
                                        $c = ($s == 'present') ? 'bg-green-100 text-green-600 text-[12px]' : (($s == 'absent') ? 'bg-red-100 text-red-600' : 'bg-orange-100 text-orange-600');
                                        $t = ($s == 'present') ? 'មក' : (($s == 'absent') ? 'អវត្តមាន' : 'ច្បាប់');
                                    ?>
                                    <span class="<?= $c ?> px-4 py-1 rounded-full text-[10px] font-black uppercase italic"><?= $t ?></span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="2" class="p-20 text-center opacity-40">
                                    <i class="fas fa-folder-open text-4xl mb-4 text-slate-200"></i>
                                    <p class="text-slate-400 font-black uppercase italic">មិនទាន់មានទិន្នន័យវត្តមាន</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>

<script>
    function toggleSidebar() { 
        const sidebar = document.getElementById('sidebar');
        if(sidebar) sidebar.classList.toggle('-translate-x-full'); 
    }
</script>