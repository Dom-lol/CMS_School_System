<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$sid = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
$query = "SELECT s.*, u.username FROM students s LEFT JOIN users u ON s.user_id = u.id WHERE s.student_id = '$sid'";
$result = mysqli_query($conn, $query);
$student = mysqli_fetch_assoc($result);

if (!$student) { die("រកមិនឃើញទិន្នន័យ"); }

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 
?>

<style>
    /* CSS សម្រាប់ Print ឱ្យចេញព័ត៌មានពេញទំព័រ */
    @media print {
        /* ១. លាក់រាល់ Element មិនចាំបាច់ទាំងអស់ (Sidebar, Header, Buttons) */
        header, aside, footer, .no-print, .main-header, .main-sidebar { 
            display: none !important; 
        }

        /* ២. រៀបចំផ្ទៃ Body ឱ្យស និងគ្មាន Margin */
        body, html { 
            background: white !important; 
            margin: 0 !important; 
            padding: 0 !important; 
        }

        /* ៣. បង្ហាញព័ត៌មានក្នុង Card ឱ្យរីកពេញទំព័រ */
        .pdf-page {
            position: absolute;
            left: 0;
            top: 0;
            width: 100% !important;
            display: block !important;
            padding: 20mm !important; /* បន្សល់គម្លាតសងខាងក្រដាស A4 */
        }

        /* ៤. បង្ខំឱ្យចេញពណ៌ និងរូបភាព */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        @page { size: A4; margin: 0; }
    }

    /* រចនាប័ទ្មសម្រាប់បង្ហាញលើអេក្រង់ UI */
    .student-card-ui {
        max-width: 800px;
        margin: 30px auto;
        background: white;
        border-radius: 30px;
        padding: 50px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 40px rgba(0,0,0,0.02);
    }
</style>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="no-print mb-6">
        <a href="student_list.php" class="text-blue-600 font-bold flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> ត្រឡប់ទៅបញ្ជីឈ្មោះ
        </a>
    </div>

    <div class="pdf-page student-card-ui">
        <div class="flex justify-between items-center mb-12 pb-8 border-b-2 border-slate-50">
            <div class="flex items-center gap-8">
                
                <div class="w-28 h-28 bg-slate-50 border-2 border-slate-100 rounded-[50%] flex items-center justify-center text-4xl font-bold text-slate-300">
                    <?php echo mb_substr($student['full_name'], 0, 1, 'UTF-8'); ?>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-slate-800 mb-2"><?php echo $student['full_name']; ?></h1>
                    <p class="text-xl text-slate-400 uppercase tracking-widest font-bold"><?php echo $student['full_name_en']; ?></p>
                </div>
            </div>
            <div class="text-right">
                <span class="px-5 py-2 bg-green-50 text-green-600 border border-green-100 rounded-full text-xs font-bold uppercase italic">
                    <?php echo $student['status']; ?>
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
            <div class="space-y-6">
                <h3 class="text-lg font-bold text-blue-600 border-b pb-3 flex items-center gap-2 italic">
                    <i class="fas fa-user-graduate"></i> ព័ត៌មានផ្ទាល់ខ្លួន
                </h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400 text-xl">អត្តលេខសិស្ស:</span>
                        <span class="font-bold text-slate-700"><?php echo $student['student_id']; ?></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400">ភេទ:</span>
                        <span class="font-bold text-slate-700"><?php echo $student['gender']; ?></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400">ថ្ងៃខែឆ្នាំកំណើត:</span>
                        <span class="font-bold text-slate-700"><?php echo date('d-M-Y', strtotime($student['dob'])); ?></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400">ថ្នាក់រៀន:</span>
                        <span class="font-bold text-slate-700">ថ្នាក់ទី <?php echo $student['class_name']; ?></span>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <h3 class="text-lg font-bold text-blue-600 border-b pb-3 flex items-center gap-2 italic">
                    <i class="fas fa-home"></i> ព័ត៌មានគ្រួសារ
                </h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400">ឈ្មោះឪពុក:</span>
                        <span class="font-bold text-slate-700"><?php echo $student['father_name'] ?: '---'; ?></span>
                    </div>
                    <div class="flex justify-between border-b border-slate-50 pb-2">
                        <span class="text-slate-400">ឈ្មោះម្តាយ:</span>
                        <span class="font-bold text-slate-700"><?php echo $student['mother_name'] ?: '---'; ?></span>
                    </div>
                    <div class="pt-2">
                        <span class="text-slate-400 text-xs font-bold uppercase block mb-1">អាសយដ្ឋានបច្ចុប្បន្ន:</span>
                        <p class="text-slate-700 font-bold italic leading-relaxed bg-slate-50 p-4 rounded-2xl">
                            <?php echo $student['address'] ?: 'មិនទាន់មានទិន្នន័យ'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-20 pt-8 border-t border-slate-100 text-center text-slate-300 text-[10px]">
            ឯកសារនេះត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិពីប្រព័ន្ធគ្រប់គ្រងសាលារៀន - <?php echo date('d-m-Y'); ?>
        </div>
    </div>

    <div class="no-print mt-10 text-center">
        <button onclick="window.print()" class="px-12 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 shadow-xl shadow-blue-100 flex items-center gap-3 mx-auto">
            <i class="fas fa-print"></i> បោះពុម្ពព័ត៌មានជា PDF
        </button>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>