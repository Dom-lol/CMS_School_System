<!-- For edit 
<?php
    require_once 'config/db.php';
    require_once 'config/session.php';

    $sql = " SELECT * FROM students ";
    $resuilt = mysqli_query($conn, $sql);

    

?>

<table border=1>
    <tr>
        <td >Full Name Khmer</td>
        <td>Full Name English</td>
        <td>ID</td>
        <td>Gender</td>
        <td>dob</td>
        <td>address</td>
        <td>father Name</td>
        <td>Mother Name</td>
        <td>Stream </td>
        <td>Grade</td>
        <td>class ID</td>
        <td>Profile IMG</td>

    </tr>

    <?php
  while ($row = mysqli_fetch_assoc($resuilt)){
    echo "<tr>";
    echo "<td >" . $row['full_name'];
    echo "<td>" . $row['full_name_en'];
    echo "<td>" . $row['student_id'];
    echo "<td>" . $row['gender'];
    echo "<td>" . $row['dob'];
    echo "<td>" . $row['address'];
    echo "<td>" . $row['father_name'];
    echo "<td>" . $row['mother_name'];
    echo "<td>" . $row['stream'];
    echo "<td>" . $row['class_name'];
    echo "<td>" . $row['class_id']; 
    echo "<td>" . $row['profile_img'];


  }

    ?>
        
</table> -->





<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .animate-step { animation: fadeInUp 0.4s ease forwards; }
    .input-focus:focus { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); }
    .btn-disabled { background-color: #cbd5e1 !important; cursor: not-allowed !important; }
</style>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-slate-800" style="font-family: 'Khmer OS Muol Light';">ចុះឈ្មោះសិស្សថ្មី</h1>
            <a href="student_list.php" class="bg-white text-slate-500 px-6 py-3 rounded-2xl font-bold border border-slate-200 hover:bg-slate-50 transition">
                <i class="fas fa-arrow-left mr-2"></i> បញ្ជីសិស្ស
            </a>
        </div>

        <div class="flex items-center justify-center mb-10 px-10">
            <div class="flex items-center w-full relative">
                <div id="step1-circle" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold z-10 transition-all">1</div>
                <div class="flex-1 h-1 bg-slate-200 mx-[-2px] relative"><div id="progress1" class="absolute h-full bg-blue-600 w-0 transition-all duration-500"></div></div>
                <div id="step2-circle" class="w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center font-bold z-10 transition-all">2</div>
                <div class="flex-1 h-1 bg-slate-200 mx-[-2px] relative"><div id="progress2" class="absolute h-full bg-blue-600 w-0 transition-all duration-500"></div></div>
                <div id="step3-circle" class="w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center font-bold z-10 transition-all">3</div>
            </div>
        </div>

        <form id="studentForm" action="../../actions/staff/save_student.php" method="POST" enctype="multipart/form-data" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            
            <div id="step1" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-step">
                <div class="md:col-span-2 border-b border-slate-50 pb-3 flex justify-between items-center">
                    <h2 class="text-blue-600 font-black flex items-center"><i class="fas fa-user-circle mr-2"></i> ព័ត៌មានផ្ទាល់ខ្លួន</h2>
                </div>

                <div class="md:col-span-2 flex flex-col items-center bg-slate-50 p-6 rounded-[2rem] border-2 border-dashed border-slate-200">
                    <div id="imagePreview" class="w-32 h-32 rounded-3xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden mb-3">
                        <i class="fas fa-user text-slate-300 text-4xl"></i>
                    </div>
                    <label class="cursor-pointer bg-blue-600 text-white px-5 py-2 rounded-xl text-sm font-bold">
                        <i class="fas fa-camera mr-2"></i> រើសរូបថត
                        <input type="file" name="photo" id="photoInput" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </label>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះពេញ (ខ្មែរ) *</label>
                    <input type="text" name="full_name" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះពេញ (Latin) *</label>
                    <input type="text" name="full_name_en" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none uppercase">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ភេទ *</label>
                    <select name="gender" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                        <option value="ប្រុស">ប្រុស</option><option value="ស្រី">ស្រី</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ថ្ងៃខែឆ្នាំកំណើត *</label>
                    <input type="date" name="dob" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>

                <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4 bg-blue-50/40 p-5 rounded-3xl border border-blue-100">
                    <div class="col-span-2 md:col-span-4 text-blue-700 font-bold text-xs uppercase">ទីកន្លែងកំណើត (POB)</div>
                    <input type="text" name="pob_v" placeholder="ភូមិ" class="input-focus w-full p-3 bg-white border border-slate-200 rounded-xl text-sm">
                    <input type="text" name="pob_c" placeholder="ឃុំ/សង្កាត់" class="input-focus w-full p-3 bg-white border border-slate-200 rounded-xl text-sm">
                    <input type="text" name="pob_d" placeholder="ស្រុក/ខណ្ឌ" class="input-focus w-full p-3 bg-white border border-slate-200 rounded-xl text-sm">
                    <input type="text" name="pob_p" placeholder="ខេត្ត/ក្រុង" class="input-focus w-full p-3 bg-white border border-slate-200 rounded-xl text-sm">
                </div>
            </div>

            <div id="step2" class="hidden p-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-step">
                <div class="md:col-span-2 border-b border-slate-50 pb-3">
                    <h2 class="text-blue-600 font-black flex items-center"><i class="fas fa-graduation-cap mr-2"></i> ព័ត៌មានការសិក្សា</h2>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">អត្តលេខសិស្ស ID *</label>
                    <input type="text" name="student_id" required placeholder="S2026001" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឆ្នាំសិក្សា *</label>
                    <input type="text" name="academic_year" required placeholder="2025-2026" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">កម្រិតថ្នាក់ (Select Grade) *</label>
                    <select name="class_id" id="gradeSelect" required onchange="autoFillClass()" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                        <option value="">--- ជ្រើសរើសថ្នាក់ ---</option>
                        <option value="7">ថ្នាក់ទី ៧</option><option value="8">ថ្នាក់ទី ៨</option><option value="9">ថ្នាក់ទី ៩</option>
                        <option value="10">ថ្នាក់ទី ១០</option><option value="11">ថ្នាក់ទី ១១</option><option value="12">ថ្នាក់ទី ១២</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">បន្ទប់/ឈ្មោះថ្នាក់ *</label>
                    <input type="text" name="class_name" id="className" required placeholder="ឧ. 7-A" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
            </div>

            <div id="step3" class="hidden p-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-step">
                <div class="md:col-span-2 border-b border-slate-50 pb-3">
                    <h2 class="text-blue-600 font-black flex items-center"><i class="fas fa-users mr-2"></i> អាណាព្យាបាល និងអាសយដ្ឋានបច្ចុប្បន្ន</h2>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះឪពុក *</label>
                    <input type="text" name="father_name" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">មុខរបរឪពុក</label>
                    <input type="text" name="father_job" placeholder="មុខរបរ..." class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះម្តាយ *</label>
                    <input type="text" name="mother_name" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">មុខរបរម្តាយ</label>
                    <input type="text" name="mother_job" placeholder="មុខរបរ..." class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                </div>
                <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4 bg-emerald-50/40 p-5 rounded-3xl border border-emerald-100">
                    <div class="col-span-2 md:col-span-4 text-emerald-700 font-bold text-xs uppercase mb-1">អាសយដ្ឋានបច្ចុប្បន្ន</div>
                    <input type="text" name="addr_v" placeholder="ភូមិ" class="input-focus w-full p-3 bg-white border border-slate-200 rounded-xl text-sm">
                    <input type="text" name="addr_c" placeholder="ឃុំ/សង្កាត់" class="input-focus w-full p-3 bg-white border border-slate-200 rounded-xl text-sm">
                    <input type="text" name="addr_d" placeholder="ស្រុក/ខណ្ឌ" class="input-focus w-full p-3 bg-white border border-slate-200 rounded-xl text-sm">
                    <input type="text" name="addr_p" placeholder="ខេត្ត/ក្រុង" class="input-focus w-full p-3 bg-white border border-slate-200 rounded-xl text-sm">
                </div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                <button type="button" id="prevBtn" onclick="changeStep(-1)" class="invisible font-bold text-slate-400">ថយក្រោយ</button>
                <div class="flex gap-3">
                    <button type="button" id="nextBtn" onclick="changeStep(1)" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold">ជំហានបន្ទាប់</button>
                    <button type="submit" id="submitBtn" disabled class="btn-disabled text-white px-10 py-4 rounded-2xl font-bold transition-all">
                        សូមបំពេញព័ត៌មានឱ្យគ្រប់
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    let currentStep = 1;

    function previewImage(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = e => document.getElementById('imagePreview').innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
            reader.readAsDataURL(input.files[0]);
        }
    }

    function autoFillClass() {
        const grade = document.getElementById('gradeSelect').value;
        const className = document.getElementById('className');
        if(grade) { className.value = grade + '-'; className.focus(); }
        checkValidity();
    }

    function checkValidity() {
        const form = document.getElementById('studentForm');
        const submitBtn = document.getElementById('submitBtn');
        const required = form.querySelectorAll('[required]');
        let ok = true;
        required.forEach(f => { if(!f.value.trim()) ok = false; });

        if(ok) {
            submitBtn.innerHTML = '<i class="fas fa-check-circle mr-1"></i> រួចរាល់! រក្សាទុកឥឡូវនេះ';
            submitBtn.classList.remove('btn-disabled');
            submitBtn.classList.add('bg-emerald-600');
            submitBtn.disabled = false;
        } else {
            submitBtn.innerHTML = 'សូមបំពេញព័ត៌មានឱ្យគ្រប់';
            submitBtn.classList.add('btn-disabled');
            submitBtn.classList.remove('bg-emerald-600');
            submitBtn.disabled = true;
        }
    }

    function changeStep(n) {
        if (n > 0) {
            const inputs = document.getElementById(`step${currentStep}`).querySelectorAll('[required]');
            let valid = true;
            inputs.forEach(i => { if(!i.value) { i.classList.add('border-red-400'); valid = false; } else i.classList.remove('border-red-400'); });
            if(!valid) return Swal.fire({ title: 'សូមបំពេញចន្លោះផ្កាយ (*)', icon: 'warning' });
        }
        document.getElementById(`step${currentStep}`).classList.add('hidden');
        currentStep += n;
        document.getElementById(`step${currentStep}`).classList.remove('hidden');
        
        // Update UI
        document.getElementById('prevBtn').style.visibility = currentStep === 1 ? 'hidden' : 'visible';
        document.getElementById('nextBtn').classList.toggle('hidden', currentStep === 3);
        document.getElementById('submitBtn').classList.toggle('hidden', currentStep !== 3);
        document.getElementById('progress1').style.width = currentStep >= 2 ? '100%' : '0%';
        document.getElementById('progress2').style.width = currentStep >= 3 ? '100%' : '0%';
        for(let i=1; i<=3; i++) document.getElementById(`step${i}-circle`).className = `w-10 h-10 rounded-full flex items-center justify-center font-bold z-10 transition-all ${i<=currentStep ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500'}`;
    }

    document.getElementById('studentForm').addEventListener('input', checkValidity);
</script>

<script>
      let currentStep = 1;

    // --- មុខងារ Import Excel ---
    function triggerImport() {
        document.getElementById('importInput').click();
    }

    function handleFileSelect() {
        const fileInput = document.getElementById('importInput');
        if (fileInput.files.length > 0) {
            Swal.fire({
                title: 'យល់ព្រម?',
                text: "តើអ្នកចង់នាំចូលទិន្នន័យពីឯកសារ " + fileInput.files[0].name + " មែនទេ?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                confirmButtonText: 'បាទ! នាំចូលឥឡូវនេះ',
                cancelButtonText: 'បោះបង់',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'កំពុងដំណើរការ...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    document.getElementById('importForm').submit();
                } else {
                    fileInput.value = '';
                }
            });
        }
    }

</script>

 <button type="button" onclick="triggerImport()" class="bg-blue-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition flex items-center active:scale-95">
                    <i class="fas fa-file-excel mr-2"></i> Import Excel
                </button>

                <?php if(isset($_GET['import_success'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'នាំចូលជោគជ័យ!',
                text: 'បានបញ្ចូលសិស្សចំនួន <?php echo (int)$_GET['import_success']; ?> នាក់ទៅក្នុងប្រព័ន្ធ',
                timer: 3500,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[2rem]' }
            });
        </script>
    <?php endif; ?>