<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$u_id = $_SESSION['user_id'];
$s_id = $_SESSION['username'] ?? ''; // ចាប់យក username ជា s_id

// ១. ទាញយកព័ត៌មានសិស្សពេញលេញ (ដើម្បីយក display_name, s_id និង profile_img)
$student_info_query = mysqli_query($conn, "SELECT * FROM students WHERE user_id = '$u_id' LIMIT 1");
$student_info = mysqli_fetch_assoc($student_info_query);

$st_id = $student_info['student_id'] ?? 0;
$display_name = $student_info['full_name'] ?? ($_SESSION['full_name'] ?? $s_id);

// រៀបចំ Path រូបភាព Profile
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
               ? $profile_path . $student_info['profile_img'] . "?v=" . time() 
               : null;

// ២. ចាប់យក Filter ចំនួន ៣ (Month, Year, Subject)
$selected_month = $_GET['month'] ?? date('m');
$selected_year = $_GET['year'] ?? date('Y');
$selected_sub = $_GET['subject_id'] ?? 'all';

$months = ["01" => "មករា", "02" => "កុម្ភៈ", "03" => "មីនា", "04" => "មេសា", "05" => "ឧសភា", "06" => "មិថុនា", "07" => "កក្កដា", "08" => "សីហា", "09" => "កញ្ញា", "10" => "តុលា", "11" => "វិច្ឆិកា", "12" => "ធ្នូ"];

// ៣. គណនាចំណាត់ថ្នាក់ (Ranking)
$rank_q = "SELECT student_id, AVG(total_score) as avg_score 
           FROM scores 
           WHERE MONTH(created_at) = '$selected_month' 
           AND YEAR(created_at) = '$selected_year' 
           GROUP BY student_id 
           ORDER BY avg_score DESC";
$rank_res = mysqli_query($conn, $rank_q);
$rank = 0; $total_st = ($rank_res) ? mysqli_num_rows($rank_res) : 0;
$count = 0;
if($rank_res){
    while($r = mysqli_fetch_assoc($rank_res)){
        $count++;
        if($r['student_id'] == $st_id){ $rank = $count; break; }
    }
}

// Path រូបភាព
$profile_path = "../../assets/uploads/profiles/";
$current_img = (!empty($student_info['profile_img']) && file_exists($profile_path . $student_info['profile_img'])) 
               ? $profile_path . $student_info['profile_img'] . "?v=" . time() 
               : null;


include '../../includes/header.php';
?>

<div class="flex h-screen w-full overflow-hidden bg-slate-100 font-khmer">
    <?php include '../../includes/sidebar_student.php'; ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        <!-- header profile img -->
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
                    <form action="../../actions/students/upload_profile.php" method="POST" enctype="multipart/form-data" class="absolute -bottom-1 -right-1">
                        <label class="w-7 h-7 bg-white text-blue-600 rounded-full flex items-center justify-center cursor-pointer shadow-md border border-slate-100 hover:bg-blue-50 transition-all">
                            <i class="fas fa-camera text-[10px]"></i>
                            <input type="file" name="profile_img" class="hidden" accept="image/*" onchange="this.form.submit()">
                        </label>
                    </form>
                </div>
            </div>
        </header>

        <header class="bg-white border-b h-16 flex items-center justify-between px-6 flex-shrink-0 z-40">
            <h1 class="text-sm font-black text-slate-800 uppercase">លទ្ធផលសិក្សាប្រចាំខែ</h1>
        </header>

        <div class="flex-1 overflow-y-auto bg-blue-600 p-4 md:p-8">
            <div class="max-w-5xl mx-auto">
                
                <div class="mb-6 flex flex-col gap-4">
                    <form action="" method="GET" class="flex flex-wrap items-center bg-white/20 p-2 rounded-3xl backdrop-blur-md border border-white/30 gap-2">
                        <select name="month" onchange="this.form.submit()" class="bg-transparent text-white font-bold text-xs outline-none px-4 py-2 appearance-none">
                            <?php foreach ($months as $num => $kh): ?>
                                <option value="<?= $num ?>" <?= ($num == $selected_month) ? 'selected' : '' ?> class="text-slate-800"><?= $kh ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="year" onchange="this.form.submit()" class="bg-transparent text-white font-bold text-xs outline-none px-4 py-2 appearance-none">
                            <?php for ($y = date('Y'); $y >= 2023; $y--): ?>
                                <option value="<?= $y ?>" <?= ($y == $selected_year) ? 'selected' : '' ?> class="text-slate-800"><?= $y ?></option>
                            <?php endfor; ?>
                        </select>
                        <select name="subject_id" onchange="this.form.submit()" class="bg-transparent text-white font-bold text-xs outline-none px-4 py-2 appearance-none flex-1">
                            <option value="all" class="text-slate-800">គ្រប់មុខវិជ្ជា</option>
                            <?php
                            $sub_list = mysqli_query($conn, "SELECT id, subject_name FROM subjects");
                            while($s = mysqli_fetch_assoc($sub_list)):
                                $sel = ($selected_sub == $s['id']) ? 'selected' : '';
                                echo "<option value='{$s['id']}' $sel class='text-slate-800'>{$s['subject_name']}</option>";
                            endwhile;
                            ?>
                        </select>
                    </form>

                    <div class="relative">
                        <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-white/50 text-xs"></i>
                        <input type="text" id="subjectSearch" placeholder="ស្វែងរកមុខវិជ្ជា..." 
                               class="w-full pl-12 pr-6 py-4 bg-white/10 border border-white/20 rounded-3xl text-white text-sm outline-none shadow-xl">
                    </div>
                </div>

                <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl mb-8 border-b-8 border-orange-400">
                    <?php
                    $sum_q = mysqli_query($conn, "SELECT AVG(total_score) as avg, SUM(total_score) as total FROM scores WHERE student_id = '$st_id' AND MONTH(created_at) = '$selected_month' AND YEAR(created_at) = '$selected_year'");
                    $sum = mysqli_fetch_assoc($sum_q);
                    $avg = number_format($sum['avg'] ?? 0, 2);
                    ?>
                    <div class="text-center mb-8">
                        <p class="text-slate-400 text-[10px] font-black uppercase mb-1">មធ្យមភាគប្រចាំខែ <?= $months[$selected_month] ?></p>
                        <h2 class="text-6xl font-black text-slate-900"><?= $avg ?><span class="text-slate-300 text-2xl">/50</span></h2>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 border-t pt-8">
                        <div class="text-center border-r"><p class="text-[10px] text-slate-400 font-bold mb-1">ចំណាត់ថ្នាក់</p><p class="font-black text-orange-500 text-xl"><?= $rank ?>/<?= $total_st ?></p></div>
                        <div class="text-center md:border-r"><p class="text-[10px] text-slate-400 font-bold mb-1">និទ្ទេស</p><p class="font-black text-green-500 text-xl italic"><?= ($avg >= 45) ? 'A' : ($avg >= 35 ? 'B' : 'C') ?></p></div>
                        <div class="text-center border-r"><p class="text-[10px] text-slate-400 font-bold mb-1">ពិន្ទុសរុប</p><p class="font-black text-slate-800 text-xl"><?= (int)$sum['total'] ?></p></div>
                        <div class="text-center"><p class="text-[10px] text-slate-400 font-bold mb-1">អវត្តមាន</p><p class="font-black text-red-500 text-xl">0</p></div>
                    </div>
                </div>

                <div id="subjectContainer" class="space-y-3 pb-10">
                    <?php 
                    $sub_filter = ($selected_sub != 'all') ? "AND s.subject_id = '$selected_sub'" : "";
                    $list_q = mysqli_query($conn, "SELECT s.*, sub.subject_name 
                                                   FROM scores s 
                                                   JOIN subjects sub ON s.subject_id = sub.id 
                                                   WHERE s.student_id = '$st_id' 
                                                   AND MONTH(s.created_at) = '$selected_month' 
                                                   AND YEAR(s.created_at) = '$selected_year' 
                                                   $sub_filter");
                    
                    if($list_q && mysqli_num_rows($list_q) > 0):
                        while($row = mysqli_fetch_assoc($list_q)):
                    ?>
                    <div class="subject-card flex justify-between items-center bg-white p-5 rounded-[2.2rem] shadow-lg border-l-[10px] border-blue-500 hover:scale-[1.01] transition-all">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 font-black italic">
                                <?= mb_substr($row['subject_name'], 0, 1) ?>
                            </div>
                            <div>
                                <h4 class="subject-name font-black text-slate-800 text-sm"><?= $row['subject_name'] ?></h4>
                                <p class="text-[10px] text-slate-400 font-bold">ប្រចាំខែ: <?= (int)$row['monthly_score'] ?> | ប្រឡង: <?= (int)$row['exam_score'] ?></p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase">សរុប</span>
                            <span class="font-black text-blue-600 text-lg"><?= (int)$row['total_score'] ?></span>
                        </div>
                    </div>
                    <?php endwhile; else: ?>
                        <div class="text-center py-20 bg-white/10 rounded-[2.5rem] border-2 border-dashed border-white/20 text-white italic">មិនទាន់មានទិន្នន័យ</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('-translate-x-full');
}

// Search Function
document.getElementById('subjectSearch').addEventListener('input', function() {
    let input = this.value.toLowerCase().trim();
    let cards = document.getElementsByClassName('subject-card');
    Array.from(cards).forEach(card => {
        let name = card.querySelector('.subject-name').innerText.toLowerCase();
        card.style.display = name.includes(input) ? "flex" : "none";
    });
});
</script>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700&display=swap');
    .font-khmer { font-family: 'Kantumruy Pro', sans-serif; }
    ::-webkit-scrollbar { display: none; }
</style>

<?php include '../../includes/footer.php'; ?>