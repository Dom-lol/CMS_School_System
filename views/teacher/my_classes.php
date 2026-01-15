<?php
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
// កែពី sidebar.php មកជា sidebar_teacher.php
include '../../includes/sidebar_teacher.php'; 

$t_username = $_SESSION['username'];

// ទាញយក teacher_id ជាមុនសិន
$teacher_res = mysqli_query($conn, "SELECT teacher_id FROM teachers t JOIN users u ON t.user_id = u.id WHERE u.username = '$t_username'");
$teacher_data = mysqli_fetch_assoc($teacher_res);
$t_id = $teacher_data['teacher_id'];
?>

<main class="flex-1 p-8 bg-gray-50">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">ថ្នាក់រៀនរបស់ខ្ញុំ</h1>
        <p class="text-slate-500">បញ្ជីថ្នាក់រៀន និងមុខវិជ្ជាដែលលោកអ្នកកំពុងបង្រៀន</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        // ទាញយកបញ្ជីថ្នាក់ចេញពី Table timetable
        $query = "SELECT DISTINCT t.class_name, s.subject_name 
                  FROM timetable t 
                  JOIN subjects s ON t.subject_id = s.id 
                  WHERE t.teacher_id = '$t_id'";
        
        $classes = mysqli_query($conn, $query);

        if (mysqli_num_rows($classes) > 0):
            while ($row = mysqli_fetch_assoc($classes)):
        ?>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 hover:border-blue-500 transition-all group">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                        <i class="fas fa-graduation-cap text-xl"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-400">ACTIVE</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800">ថ្នាក់: <?php echo $row['class_name']; ?></h3>
                <p class="text-slate-500 mb-6"><?php echo $row['subject_name']; ?></p>
                
                <a href="student_list.php?class=<?php echo $row['class_name']; ?>" 
                   class="flex items-center justify-center w-full py-2 bg-slate-100 text-slate-700 rounded-lg font-medium hover:bg-blue-600 hover:text-white transition">
                    មើលបញ្ជីសិស្ស
                </a>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="col-span-full bg-white p-12 rounded-2xl border-2 border-dashed border-slate-200 text-center">
                <div class="text-slate-300 mb-4">
                    <i class="fas fa-folder-open text-6xl"></i>
                </div>
                <p class="text-slate-500 font-medium">មិនទាន់មានថ្នាក់រៀនដែលត្រូវបង្រៀននៅឡើយទេ</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>