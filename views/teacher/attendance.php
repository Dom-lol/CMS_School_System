<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ទាញយកព័ត៌មានគ្រូ [cite: 2026-01-20]
$u_id = $_SESSION['user_id'];
$t_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$t_data = mysqli_fetch_assoc($t_query);
$real_t_id = $t_data['teacher_id'] ?? 0;
$t_full_name = $t_data['full_name'] ?? 'Teacher';
$t_profile = $t_data['profile_image'] ?? '';

// ២. កំណត់ថ្ងៃខែ
$date = $_GET['date'] ?? date('Y-m-d');

// ៣. ទាញបញ្ជីសិស្សថ្នាក់ទី 7 (កែ class_name = '7' តាម Database លោកគ្រូ)
$st_query = "SELECT id, full_name, student_id, gender FROM students 
             WHERE class_name = '7' AND status = 'Active' 
             ORDER BY gender DESC, full_name ASC";
$students = mysqli_query($conn, $st_query);

include '../../includes/header.php'; 
?>

<div class="flex h-screen w-full bg-[#f8fafc] overflow-hidden">
    <aside class="hidden lg:block w-72 bg-slate-900 shadow-2xl shrink-0 h-full overflow-y-auto">
        <?php include '../../includes/sidebar_teacher.php'; ?>
    </aside>
    
    <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden">
        
     <header class="h-24 bg-white border-b flex items-center justify-between px-6 md:px-10 shrink-0 shadow-sm z-10">
    <div class="flex items-center gap-4">
        <div class="lg:hidden text-slate-600 bg-slate-100 p-3 rounded-xl cursor-pointer"><i class="fas fa-bars"></i></div>
        <div>
            <h2 class="text-xl font-black text-slate-800 uppercase italic leading-none">Attendance</h2>
            
            <form id="dateForm" method="GET" class="mt-2 flex items-center gap-2">
                <input type="hidden" name="class_name" value="7"> <div class="relative">
                    <input type="date" name="date" id="attendanceDate" 
                           value="<?= $date ?>" 
                           onchange="document.getElementById('dateForm').submit()"
                           class="bg-blue-50 text-blue-600 font-bold uppercase text-[10px] px-3 py-1.5 rounded-lg border-none outline-none cursor-pointer hover:bg-blue-100 transition-all">
                </div>
            </form>
        </div>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-black text-slate-900 leading-none"><?= $t_full_name ?></p>
            <p class="text-[9px] text-slate-400 font-bold uppercase mt-1 italic tracking-tighter">ID: #<?= $real_t_id ?></p>
        </div>
        
        <div class="w-14 h-14 rounded-2xl bg-blue-600 border-4 border-slate-50 shadow-lg overflow-hidden flex items-center justify-center">
            <?php 
                $profile_url = "../../assets/img/profiles/" . $t_profile;
                if(!empty($t_profile) && file_exists($profile_url)): 
            ?>
                <img src="<?= $profile_url ?>" class="w-full h-full object-cover">
            <?php else: ?>
                <span class="text-white font-black text-xl italic"><?= mb_substr($t_full_name, 0, 1) ?></span>
            <?php endif; ?>
        </div>giass_name" value="7">
                <input type="hidden" name="date" value="<?= $date ?>">

                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mb-24">
                    <div class="overflow-x-auto"> 
                        <table class="w-full text-left min-w-[600px]">
                            <thead class="bg-slate-900 text-white">
                                <tr>
                                    <th class="p-6 font-bold uppercase text-[10px] tracking-widest">ព័ត៌មានសិស្ស</th>
                                    <th class="p-6 font-bold uppercase text-[10px] tracking-widest text-center">ស្ថានភាពវត្តមាន</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php while($row = mysqli_fetch_assoc($students)): ?>
                                <tr class="hover:bg-slate-50 transition-all">
                                    <td class="p-6">
                                        <div class="font-bold text-slate-800 uppercase italic leading-tight"><?= $row['full_name'] ?></div>
                                        <div class="text-[9px] text-slate-400 font-black uppercase mt-1 italic tracking-tighter">ID: <?= $row['student_id'] ?> | ភេទ: <?= $row['gender'] ?></div>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex justify-center items-center gap-6 md:gap-12">
                                            <label class="flex flex-col items-center gap-1 cursor-pointer group">
                                                <input type="radio" name="att[<?= $row['id'] ?>]" value="present" checked class="w-6 h-6 accent-green-500 cursor-pointer">
                                                <span class="text-[9px] font-black text-slate-400 uppercase italic group-hover:text-green-600">មក</span>
                                            </label>
                                            
                                            <label class="flex flex-col items-center gap-1 cursor-pointer group">
                                                <input type="radio" name="att[<?= $row['id'] ?>]" value="permission" class="w-6 h-6 accent-orange-500 cursor-pointer">
                                                <span class="text-[9px] font-black text-slate-400 uppercase italic group-hover:text-orange-600">ច្បាប់</span>
                                            </label>

                                            <label class="flex flex-col items-center gap-1 cursor-pointer group">
                                                <input type="radio" name="att[<?= $row['id'] ?>]" value="absent" class="w-6 h-6 accent-red-500 cursor-pointer">
                                                <span class="text-[9px] font-black text-slate-400 uppercase italic group-hover:text-red-600">អវត្តមាន</span>
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
                    រកមិនឃើញសិស្សក្នុងថ្នាក់ទី 7
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>