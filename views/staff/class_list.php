<?php 
// ១. រាប់បញ្ចូល File ភ្ជាប់ Database និង Session (ត្រូវប្រាកដថា Path ត្រឹមត្រូវ)
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

// ឆែកសិទ្ធិ
if ($_SESSION['role'] !== 'staff' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=unauthorized");
    exit();
}

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ២. Query ទាញយកឈ្មោះថ្នាក់ និងរាប់ចំនួនសិស្សក្នុងថ្នាក់នីមួយៗ
// ប្រើ $conn ដែលបានមកពី require_once '../../config/db.php'
$class_query = "SELECT class_name, COUNT(*) as student_count 
                FROM students 
                WHERE class_name != '' 
                GROUP BY class_name 
                ORDER BY class_name ASC";
$class_result = mysqli_query($conn, $class_query);
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div><i class="fa-solid fa-angle-left"></i>
    <a href="student_list.php">Back</a></div>
     
    <div class="py-5">
       
        <h1 class="text-2xl font-black text-slate-800 tracking-tight">បញ្ជីថ្នាក់រៀនទាំងអស់</h1>
       
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php 
        if ($class_result && mysqli_num_rows($class_result) > 0):
            while($row = mysqli_fetch_assoc($class_result)): 
        ?>
            <div class="bg-white p-6 rounded-[0.5rem] shadow-sm border border-slate-100  transition-all duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 bg-indigo-50 text-blue-600 rounded-2xl flex items-center justify-center  transition-colors">
                        <i class="fas fa-school text-xl"></i>
                    </div>
                    <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black rounded-lg uppercase">Active</span>
                </div>
                
                <h3 class="text-[20px] font-black text-slate-800">ថ្នាក់ទី <?php echo htmlspecialchars($row['class_name']); ?></h3>
                
                <div class="mt-6 flex items-center justify-between p-4 bg-slate-50 rounded-2xl">
                    <span class="text-slate-600 text-xs font-bold uppercase">សិស្សសរុប</span>
                    <span class="text-xl font-black text-blue-600"><?php echo $row['student_count']; ?> <span class="text-xs font-normal text-slate-400">នាក់</span></span>
                </div>

                <a href="student_list.php?class=<?php echo urlencode($row['class_name']); ?>" 
                   class="mt-6 flex items-center justify-center gap-2 w-full py-4 bg-blue-600 text-white rounded-[0.5rem] font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-slate-100">
                    <i class="fas fa-users-viewfinder"></i>
                    មើលបញ្ជីឈ្មោះសិស្ស
                </a>
            </div>
        <?php 
            endwhile; 
        else:
        ?>
            <div class="col-span-full p-12 bg-white rounded-[2.5rem] border border-dashed border-slate-300 text-center">
                <i class="fas fa-folder-open text-4xl text-slate-200 mb-4"></i>
                <p class="text-slate-400 italic">មិនទាន់មានទិន្នន័យថ្នាក់រៀននៅឡើយទេ។</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>