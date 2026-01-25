<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

// ឆែកសិទ្ធិបុគ្គលិក ឬ Admin
if ($_SESSION['role'] !== 'staff' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=unauthorized");
    exit();
}

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ទាញទិន្នន័យសង្ខេបពិតប្រាកដពី Database
$total_students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM students"))['total'];
$total_classes = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(DISTINCT class_name) as total FROM students WHERE class_name != ''"))['total'];
$total_absent_today = 5; // កន្លែងនេះបងអាច Query ពី Table attendance តាមក្រោយ
?>

<main class="flex-1 p-6 md:p-10 bg-[#f8fafc] min-h-screen overflow-y-auto">
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">រដ្ឋបាលសាលា</h1>
            <p class="text-slate-500 mt-1 italic">សូមស្វាគមន៍មកកាន់ប្រព័ន្ធគ្រប់គ្រងរដ្ឋបាលវិទ្យាល័យលំដាប់ពិភពលោក</p>
        </div>
        <div class="flex gap-3">
            <span class="px-4 py-2 bg-white border border-slate-200 rounded-2xl text-slate-600 text-sm font-bold flex items-center shadow-sm">
                <i class="far fa-calendar-alt mr-2 text-blue-500"></i>
                <?php echo date('d M, Y'); ?>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <a href="student_list.php" class="group">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-blue-100 transition-all duration-300 relative overflow-hidden">
                <div class="absolute -right-2 -top-2 w-16 h-16 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform"></div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">សិស្សសរុប</p>
                <h3 class="text-4xl font-black text-slate-800 mt-2"><?php echo $total_students; ?></h3>
                <div class="mt-4 flex items-center text-blue-600 text-xs font-bold">
                    មើលបញ្ជីឈ្មោះ <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </div>
        </a>

        <a href="class_list.php" class="group">
    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 border-l-[6px] border-l-purple-500 hover:shadow-xl hover:shadow-purple-100 transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">ថ្នាក់រៀនសរុប</p>
                <h3 class="text-4xl font-black text-slate-800 mt-2"><?php echo $total_classes; ?></h3>
                <div class="mt-4 flex items-center text-purple-600 text-xs font-bold">
                    មើលបញ្ជីថ្នាក់ <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </div>
            </div>
            <div class="w-12 h-12 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center">
                <i class="fas fa-school text-xl"></i>
            </div>
        </div>
    </div>
</a>

        <!-- <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 border-l-[6px] border-l-red-500">
            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">អវត្តមានថ្ងៃនេះ</p>
            <h3 class="text-4xl font-black text-red-600 mt-2"><?php echo $total_absent_today; ?> <span class="text-lg font-normal">នាក់</span></h3>
            <p class="text-slate-400 text-xs mt-2 italic">គិតត្រឹមម៉ោងនេះ</p>
        </div> -->

        <a href="add_student.php" class="group">
            <div class="bg-slate-900 p-6 rounded-[2rem] shadow-lg shadow-slate-200 text-white hover:bg-blue-700 transition-all duration-300 flex flex-col justify-between h-full">
                <div class="flex justify-between items-start">
                    <i class="fas fa-user-plus text-2xl opacity-50"></i>
                    <i class="fas fa-plus-circle text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold">បន្ថែមសិស្ស</h3>
                    <p class="text-slate-400 text-xs group-hover:text-white/80">បញ្ចូលទិន្នន័យសិស្សថ្មី</p>
                </div>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-8">
                <h2 class="font-bold text-slate-800 text-xl flex items-center">
                    <div class="w-10 h-10 bg-orange-50 text-orange-500 rounded-xl flex items-center justify-center mr-3">
                        <i class="fas fa-tasks text-sm"></i>
                    </div>
                    ការងារដែលត្រូវធ្វើ (To-Do)
                </h2>
                <button class="text-blue-600 text-sm font-bold hover:underline">+ បន្ថែមការងារ</button>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:bg-white hover:shadow-md transition-all">
                    <div class="w-6 h-6 border-2 border-slate-300 rounded-full mr-4 group-hover:border-blue-500 transition-colors"></div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-700">ពិនិត្យវត្តមានសិស្សថ្នាក់ទី ១២ វិទ្យាសាស្ត្រ</p>
                        <p class="text-[10px] text-slate-400 uppercase mt-1">ថ្ងៃនេះ ម៉ោង ៩:០០ ព្រឹក</p>
                    </div>
                    <span class="px-3 py-1 bg-orange-100 text-orange-600 text-[10px] font-black rounded-lg">សំខាន់</span>
                </div>

                <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:bg-white hover:shadow-md transition-all">
                    <div class="w-6 h-6 border-2 border-slate-300 rounded-full mr-4"></div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-700">បោះពុម្ពកាតសិស្សសម្រាប់សិស្សថ្មីទាំង ៣០ នាក់</p>
                        <p class="text-[10px] text-slate-400 uppercase mt-1">ថ្ងៃស្អែក</p>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:bg-white hover:shadow-md transition-all opacity-60 line-through">
                    <div class="w-6 h-6 bg-blue-500 flex items-center justify-center rounded-full mr-4 text-white">
                        <i class="fas fa-check text-[10px]"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-bold text-slate-700">បញ្ចូលពិន្ទុប្រចាំខែវិច្ឆិកា</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h2 class="font-bold text-slate-800 text-xl mb-8 flex items-center">
                <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center mr-3">
                    <i class="fas fa-bell text-sm"></i>
                </div>
                សេចក្ដីជូនដំណឹង
            </h2>

            <div class="space-y-6">
                <div class="relative pl-6 border-l-2 border-blue-500">
                    <div class="absolute -left-[9px] top-0 w-4 h-4 bg-blue-500 rounded-full border-4 border-white shadow-sm"></div>
                    <p class="text-xs font-black text-blue-600 uppercase tracking-tighter">ប្រជុំបុគ្គលិក</p>
                    <p class="text-sm font-bold text-slate-700 mt-1">ថ្ងៃសុក្រ ម៉ោង ៤ រសៀល នៅបន្ទប់ប្រជុំធំ</p>
                    <p class="text-[10px] text-slate-400 mt-1">បង្ហោះមុននេះ ២ ម៉ោង</p>
                </div>

                <div class="relative pl-6 border-l-2 border-slate-200">
                    <div class="absolute -left-[9px] top-0 w-4 h-4 bg-slate-300 rounded-full border-4 border-white shadow-sm"></div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-tighter">ឈប់សម្រាក</p>
                    <p class="text-sm font-bold text-slate-700 mt-1">ថ្ងៃបុណ្យឯករាជ្យជាតិ ឈប់សម្រាក ១ ថ្ងៃ</p>
                    <p class="text-[10px] text-slate-400 mt-1">ម្សិលមិញ</p>
                </div>
            </div>

            <a href="announcements.php" class="mt-10 block w-full py-4 bg-slate-50 text-slate-500 rounded-2xl text-center font-bold text-sm hover:bg-slate-100 transition">
                មើលសេចក្ដីជូនដំណឹងទាំងអស់
            </a>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>