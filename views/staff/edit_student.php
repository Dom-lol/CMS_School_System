<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
$query = mysqli_query($conn, "SELECT * FROM students WHERE student_id = '$id' LIMIT 1");
$st = mysqli_fetch_assoc($query);

if (!$st) {
    header("Location: student_list.php?error=notfound");
    exit();
}

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-step { animation: fadeInUp 0.4s ease forwards; }
    .input-focus:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
</style>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto">
        
        <div class="mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-800 tracking-tight">កែប្រែព័ត៌មានសិស្ស (ទម្រង់ពេញ)</h1>
                <p class="text-slate-500 mt-1">អត្តលេខ៖ <span class="text-blue-600 font-bold"><?= htmlspecialchars($st['student_id']) ?></span></p>
            </div>
            <a href="student_list.php" class="bg-white text-slate-500 px-6 py-3 rounded-2xl font-bold border border-slate-200 hover:bg-slate-50 transition flex items-center shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> ត្រឡប់ក្រោយ
            </a>
        </div>

        <div class="mb-10 flex justify-between items-center px-4 max-w-3xl mx-auto">
            <?php for($i=1; $i<=5; $i++): ?>
                <div id="step-circle-<?= $i ?>" class="w-10 h-10 rounded-full flex items-center justify-center font-bold transition-all <?= $i==1 ? 'bg-blue-600 text-white shadow-lg' : 'bg-slate-200 text-slate-500' ?>">
                    <?= $i ?>
                </div>
                <?php if($i < 5): ?>
                    <div class="flex-1 h-1 bg-slate-200 mx-2"><div id="step-line-<?= $i ?>" class="h-full bg-blue-600 w-0 transition-all duration-500"></div></div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>

        <form id="studentForm" action="../../actions/staff/update_student.php" method="POST" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            <input type="hidden" name="db_id" value="<?= $st['id'] ?>">
            <input type="hidden" name="old_student_id" value="<?= htmlspecialchars($st['student_id']) ?>">

            <div id="step1" class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6 animate-step">
                <h2 class="md:col-span-3 text-blue-600 font-black uppercase text-sm border-b pb-2"><i class="fas fa-user mr-2"></i> ព័ត៌មានផ្ទាល់ខ្លួន</h2>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះខ្មែរ *</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($st['full_name']) ?>" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះឡាតាំង *</label>
                    <input type="text" name="full_name_en" value="<?= htmlspecialchars($st['full_name_en']) ?>" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none uppercase">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ភេទ *</label>
                    <select name="gender" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                        <option value="ប្រុស" <?= $st['gender'] == 'ប្រុស' ? 'selected' : '' ?>>ប្រុស</option>
                        <option value="ស្រី" <?= $st['gender'] == 'ស្រី' ? 'selected' : '' ?>>ស្រី</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ថ្ងៃខែឆ្នាំកំណើត *</label>
                    <input type="date" name="dob" value="<?= $st['dob'] ?>" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">ទីកន្លែងកំណើត</label>
                    <input type="text" name="pob" value="<?= htmlspecialchars($st['pob']) ?>" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
            </div>

            <div id="step2" class="hidden p-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-step">
                <h2 class="md:col-span-2 text-blue-600 font-black uppercase text-sm border-b pb-2"><i class="fas fa-graduation-cap mr-2"></i> ព័ត៌មានសិក្សា</h2>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">អត្តលេខសិស្ស *</label>
                    <input type="text" name="student_id" value="<?= htmlspecialchars($st['student_id']) ?>" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none font-bold text-blue-600">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">កម្រិតថ្នាក់ *</label>
                    <select name="class_name" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                        <?php for($g=7; $g<=12; $g++) echo "<option value='$g' ".($st['class_name']==$g?'selected':'').">ថ្នាក់ទី $g</option>"; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឆ្នាំសិក្សា *</label>
                    <input type="text" name="academic_year" value="<?= htmlspecialchars($st['academic_year']) ?>" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none" placeholder="2025-2026">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ស្ថានភាពសិស្ស</label>
                    <select name="status" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                        <option value="កំពុងរៀន" <?= ($st['status']??'') == 'កំពុងរៀន' ? 'selected' : '' ?>>កំពុងរៀន</option>
                        <option value="ឈប់រៀន" <?= ($st['status']??'') == 'ឈប់រៀន' ? 'selected' : '' ?>>ឈប់រៀន</option>
                        <option value="ផ្អាក" <?= ($st['status']??'') == 'ផ្អាក' ? 'selected' : '' ?>>ផ្អាក</option>
                    </select>
                </div>
            </div>

            <div id="step3" class="hidden p-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-step">
                <h2 class="md:col-span-2 text-blue-600 font-black uppercase text-sm border-b pb-2"><i class="fas fa-users-cog mr-2"></i> ព័ត៌មានអាណាព្យាបាល</h2>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះឪពុក</label>
                    <input type="text" name="father_name" value="<?= htmlspecialchars($st['father_name']??'') ?>" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">មុខរបរឪពុក</label>
                    <input type="text" name="father_job" value="<?= htmlspecialchars($st['father_job']??'') ?>" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះម្តាយ</label>
                    <input type="text" name="mother_name" value="<?= htmlspecialchars($st['mother_name']??'') ?>" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">មុខរបរម្តាយ</label>
                    <input type="text" name="mother_job" value="<?= htmlspecialchars($st['mother_job']??'') ?>" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
            </div>

            <div id="step4" class="hidden p-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-step">
                <h2 class="md:col-span-2 text-blue-600 font-black uppercase text-sm border-b pb-2"><i class="fas fa-phone mr-2"></i> ទំនាក់ទំនង និងអាសយដ្ឋាន</h2>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">លេខទូរស័ព្ទសិស្ស</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($st['phone']??'') ?>" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">លេខទូរស័ព្ទអាណាព្យាបាល</label>
                    <input type="text" name="parent_phone" value="<?= htmlspecialchars($st['parent_phone']??'') ?>" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">អាសយដ្ឋានបច្ចុប្បន្ន *</label>
                    <textarea name="address" rows="3" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"><?= htmlspecialchars($st['address']) ?></textarea>
                </div>
            </div>

            <div id="step5" class="hidden p-8 animate-step">
                <h2 class="text-blue-600 font-black uppercase text-sm border-b pb-2 mb-6"><i class="fas fa-info-circle mr-2"></i> ព័ត៌មានផ្សេងៗ</h2>
                <label class="block text-sm font-bold text-slate-700 mb-2">សម្គាល់ / ចំណាំ</label>
                <textarea name="note" rows="5" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none" placeholder="ព័ត៌មានបន្ថែមអំពីសិស្ស..."><?= htmlspecialchars($st['note']??'') ?></textarea>
                
                <div class="mt-8 p-6 bg-blue-50 rounded-3xl border border-blue-100 flex items-start">
                    <i class="fas fa-shield-alt text-blue-600 mt-1 mr-4 text-xl"></i>
                    <div>
                        <h4 class="font-bold text-blue-800">ផ្ទៀងផ្ទាត់ទិន្នន័យ</h4>
                        <p class="text-blue-600 text-sm">សូមពិនិត្យមើលរាល់ព័ត៌មានដែលបានកែប្រែឱ្យបានច្បាស់លាស់ មុននឹងចុចប៊ូតុងរក្សាទុក។</p>
                    </div>
                </div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-between">
                <button type="button" id="prevBtn" onclick="changeStep(-1)" class="invisible font-bold text-slate-500 flex items-center"><i class="fas fa-arrow-left mr-2"></i> ថយក្រោយ</button>
                <div class="flex gap-4">
                    <button type="button" id="nextBtn" onclick="changeStep(1)" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold shadow-lg shadow-blue-100 transition hover:bg-blue-700">ទៅមុខ <i class="fas fa-arrow-right ml-2"></i></button>
                    <button type="submit" id="submitBtn" class="hidden bg-emerald-600 text-white px-10 py-4 rounded-2xl font-bold shadow-lg shadow-emerald-100 transition hover:bg-emerald-700"><i class="fas fa-save mr-2"></i> រក្សាទុកការកែប្រែ</button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    let currentStep = 1;
    const totalSteps = 5;

    function changeStep(n) {
        if (n > 0) {
            let valid = true;
            document.getElementById(`step${currentStep}`).querySelectorAll('[required]').forEach(i => {
                if(!i.value.trim()){ i.classList.add('border-red-400', 'bg-red-50'); valid = false; }
                else i.classList.remove('border-red-400', 'bg-red-50');
            });
            if(!valid) return Swal.fire({ title: 'សូមបំពេញព័ត៌មាន *', icon: 'warning', confirmButtonColor: '#3b82f6' });
        }

        document.getElementById(`step${currentStep}`).classList.add('hidden');
        currentStep += n;
        document.getElementById(`step${currentStep}`).classList.remove('hidden');

        // Update Stepper UI
        document.getElementById('prevBtn').style.visibility = currentStep === 1 ? 'hidden' : 'visible';
        document.getElementById('nextBtn').classList.toggle('hidden', currentStep === totalSteps);
        document.getElementById('submitBtn').classList.toggle('hidden', currentStep !== totalSteps);

        for(let i=1; i<=totalSteps; i++) {
            const circle = document.getElementById(`step-circle-${i}`);
            if(i <= currentStep) {
                circle.classList.add('bg-blue-600', 'text-white');
                circle.classList.remove('bg-slate-200', 'text-slate-500');
            } else {
                circle.classList.remove('bg-blue-600', 'text-white');
                circle.classList.add('bg-slate-200', 'text-slate-500');
            }
            if(i < currentStep) document.getElementById(`step-line-${i}`).style.width = '100%';
            else if(i < totalSteps) document.getElementById(`step-line-${i}`).style.width = '0%';
        }
    }

    document.getElementById('studentForm').onsubmit = function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'រក្សាទុក?',
            text: "តើអ្នកចង់កែប្រែទិន្នន័យសិស្សនេះមែនទេ?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            confirmButtonText: 'យល់ព្រម',
            cancelButtonText: 'បោះបង់'
        }).then((result) => { if (result.isConfirmed) this.submit(); });
    };
</script>

<?php include '../../includes/footer.php'; ?>