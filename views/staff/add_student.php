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
        <div class="mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <h1 class="text-3xl font-bold text-slate-800" style="font-family: 'Khmer OS Muol Light';">ចុះឈ្មោះសិស្សថ្មី</h1>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('importInput').click()" class="bg-emerald-600 text-white px-6 py-3 rounded-2xl font-bold shadow-lg hover:bg-emerald-700 transition flex items-center active:scale-95">
                    <i class="fas fa-file-excel mr-2"></i> នាំចូលពី Excel (CSV)
                </button>
                <a href="student_list.php" class="bg-white text-slate-500 px-6 py-3 rounded-2xl font-bold border border-slate-200 hover:bg-slate-50 transition flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> បញ្ជីសិស្ស
                </a>
            </div>
        </div>

        <form id="importForm" action="../../actions/staff/import_students.php" method="POST" enctype="multipart/form-data" class="hidden">
            <input type="file" id="importInput" name="excel_data" accept=".csv" onchange="handleImport(this)">
        </form>

        
        <div class="flex items-center justify-center mb-10 px-10">
            <div class="flex items-center w-full relative">
                <div id="step1-circle" class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold z-10 transition-all">1</div>
                <div class="flex-1 h-1 bg-slate-200 mx-[-2px] relative"><div id="progress1" class="absolute h-full bg-blue-600 w-0 transition-all duration-500"></div></div>
                <div id="step2-circle" class="w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center font-bold z-10 transition-all">2</div>
                <div class="flex-1 h-1 bg-slate-200 mx-[-2px] relative"><div id="progress2" class="absolute h-full bg-blue-600 w-0 transition-all duration-500"></div></div>
                <div id="step3-circle" class="w-10 h-10 bg-slate-200 text-slate-500 rounded-full flex items-center justify-center font-bold z-10 transition-all">3</div>
            </div>
        </div>

        <form id="studentForm" action="../../actions/staff/save_student.php" method="POST" class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
            
            <div id="step1" class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-step">
                <div class="md:col-span-2 border-b border-slate-50 pb-3"><h2 class="text-blue-600 font-black flex items-center"><i class="fas fa-user-circle mr-2"></i> ព័ត៌មានផ្ទាល់ខ្លួន</h2></div>
                <div><label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះពេញ (ខ្មែរ) </label><input type="text" name="full_name" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"></div>
                <div><label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះពេញ (Latin) </label><input type="text" name="full_name_en" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none uppercase"></div>
                <div><label class="block text-sm font-bold text-slate-700 mb-2">ភេទ </label><select name="gender" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"><option value="ប្រុស">ប្រុស</option><option value="ស្រី">ស្រី</option></select></div>
                <div><label class="block text-sm font-bold text-slate-700 mb-2">ថ្ងៃខែឆ្នាំកំណើត </label><input type="date" name="dob" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"></div>
                <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4 bg-blue-50/40 p-5 rounded-3xl border border-blue-100">
                    <div class="col-span-4 text-slate-700 font-bold text-sm uppercase">ទីកន្លែងកំណើត </div>
                    <input type="text" name="pob_v" placeholder="ភូមិ" class="w-full p-3 bg-white border border-slate-200 rounded-xl">
                    <input type="text" name="pob_c" placeholder="ឃុំ" class="w-full p-3 bg-white border border-slate-200 rounded-xl">
                    <input type="text" name="pob_d" placeholder="ស្រុក" class="w-full p-3 bg-white border border-slate-200 rounded-xl">
                    <input type="text" name="pob_p" placeholder="ខេត្ត" class="w-full p-3 bg-white border border-slate-200 rounded-xl">
                </div>
            </div>

            <div id="step2" class="hidden p-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-step">
                <div class="md:col-span-2 border-b border-slate-50 pb-3"><h2 class="text-blue-600 font-black flex items-center"><i class="fas fa-graduation-cap mr-2"></i> ព័ត៌មានការសិក្សា</h2></div>
                <div><label class="block text-sm font-bold text-slate-700 mb-2">អត្តលេខសិស្ស ID *</label><input type="text" name="student_id" required placeholder="S2026001" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"></div>
                <div><label class="block text-sm font-bold text-slate-700 mb-2">ឆ្នាំសិក្សា *</label><input type="text" name="academic_year" value="2025-2026" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"></div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">កម្រិតថ្នាក់ *</label>
                    <select name="class_id" id="gradeSelect" required onchange="autoFillClass()" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none">
                        <option value="1" selected>ថ្នាក់ទី ៧</option>
                        <option value="2">ថ្នាក់ទី ៨</option>
                        <option value="3">ថ្នាក់ទី ៩</option>
                        <option value="4">ថ្នាក់ទី ១០</option>
                        <option value="5">ថ្នាក់ទី ១១</option>
                        <option value="6">ថ្នាក់ទី ១២</option>
                    </select>
                </div>
                <div><label class="block text-sm font-bold text-slate-700 mb-2">បន្ទប់/ឈ្មោះថ្នាក់ *</label><input type="text" name="class_name" id="className" required placeholder="ឧ. 7-A" class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"></div>
            </div>

            <div id="step3" class="hidden p-8 grid grid-cols-1 md:grid-cols-2 gap-6 animate-step">
                <div class="md:col-span-2 border-b border-slate-50 pb-3"><h2 class="text-blue-600 font-black flex items-center"><i class="fas fa-users mr-2"></i> អាសយដ្ឋានបច្ចុប្បន្ន</h2></div>
                <div><label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះឪពុក *</label><input type="text" name="father_name" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"></div>
                <div><label class="block text-sm font-bold text-slate-700 mb-2">ឈ្មោះម្តាយ *</label><input type="text" name="mother_name" required class="input-focus w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl outline-none"></div>
                <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4 bg-emerald-50/40 p-5 rounded-3xl border border-emerald-100">
                    <div class="col-span-4 text-emerald-700 font-bold text-xs uppercase mb-1">អាសយដ្ឋានបច្ចុប្បន្ន</div>
                    <input type="text" name="addr_v" placeholder="ភូមិ" class="w-full p-3 bg-white border border-slate-200 rounded-xl">
                    <input type="text" name="addr_c" placeholder="ឃុំ" class="w-full p-3 bg-white border border-slate-200 rounded-xl">
                    <input type="text" name="addr_d" placeholder="ស្រុក" class="w-full p-3 bg-white border border-slate-200 rounded-xl">
                    <input type="text" name="addr_p" placeholder="ខេត្ត" class="w-full p-3 bg-white border border-slate-200 rounded-xl">
                </div>
            </div>

            <div class="p-8 bg-slate-50 border-t border-slate-100 flex justify-between items-center">
                <button type="button" id="prevBtn" onclick="changeStep(-1)" class="invisible font-bold text-slate-400">ថយក្រោយ</button>
                <div class="flex gap-3">
                    <button type="button" id="nextBtn" onclick="changeStep(1)" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-bold active:scale-95 transition">Next</button>
                    <button type="submit" id="submitBtn" disabled class="btn-disabled hidden text-white px-10 py-4 rounded-2xl font-bold">រក្សាទុក</button>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    let currentStep = 1;

    function autoFillClass() {
        let g = document.getElementById('gradeSelect');
        let val = g.value;
        let text = g.options[g.selectedIndex].text;
        let c = document.getElementById('className');
        if(val == "1") { c.value = "7-"; } 
        else { 
            let num = text.match(/\d+/);
            c.value = num ? num[0] + "-" : ""; 
        }
        c.focus();
        checkValidity();
    }

    function checkValidity() {
        let f = document.getElementById('studentForm');
        let b = document.getElementById('submitBtn');
        let ok = true;
        f.querySelectorAll('[required]').forEach(i => { if(!i.value.trim()) ok = false; });
        if(ok) { b.classList.replace('btn-disabled', 'bg-emerald-600'); b.disabled = false; b.innerHTML = "រក្សាទុកឥឡូវនេះ"; }
        else { b.classList.add('btn-disabled'); b.disabled = true; b.innerHTML = "សូមបំពេញព័ត៌មានឱ្យគ្រប់"; }
    }

    function changeStep(n) {
        if (n > 0) {
            let valid = true;
            document.getElementById(`step${currentStep}`).querySelectorAll('[required]').forEach(i => {
                if(!i.value) { i.classList.add('border-red-400'); valid = false; }
                else i.classList.remove('border-blue-400');
            });
            if(!valid) return Swal.fire({ title: 'សូមបំពេញព័ត៌មានឱ្យគ្រប់', icon: 'warning' });
        }
        document.getElementById(`step${currentStep}`).classList.add('hidden');
        currentStep += n;
        document.getElementById(`step${currentStep}`).classList.remove('hidden');

        document.getElementById('prevBtn').style.visibility = currentStep === 1 ? 'hidden' : 'visible';
        document.getElementById('nextBtn').classList.toggle('hidden', currentStep === 3);
        document.getElementById('submitBtn').classList.toggle('hidden', currentStep !== 3);
        
        document.getElementById('progress1').style.width = currentStep >= 2 ? '100%' : '0%';
        document.getElementById('progress2').style.width = currentStep >= 3 ? '100%' : '0%';

        for(let i=1; i<=3; i++) {
            document.getElementById(`step${i}-circle`).className = `w-10 h-10 rounded-full flex items-center justify-center font-bold z-10 ${i<=currentStep ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500'}`;
        }
    }

    function handleImport(input) {
        if (input.files.length > 0) {
            Swal.fire({
                title: 'នាំចូលទិន្នន័យ?',
                text: "តើអ្នកចង់ import សិស្សពី Excel?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'yes'
            }).then(r => { if(r.isConfirmed) document.getElementById('importForm').submit(); });
        }
    }

    document.getElementById('studentForm').addEventListener('input', checkValidity);
    window.onload = autoFillClass;
</script>