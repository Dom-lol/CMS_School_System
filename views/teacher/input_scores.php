<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
// ១. កែសម្រួលការហៅ Sidebar ឱ្យត្រូវឈ្មោះ File
include '../../includes/sidebar_teacher.php'; 

$t_username = $_SESSION['username'];

// ២. ទាញយកព័ត៌មានគ្រូ និង teacher_id
$teacher_query = mysqli_query($conn, "SELECT t.teacher_id, u.full_name FROM teachers t JOIN users u ON t.user_id = u.id WHERE u.username = '$t_username'");
$teacher_info = mysqli_fetch_assoc($teacher_query);
$t_id = $teacher_info['teacher_id'];
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">សួស្ដី លោកគ្រូ/អ្នកគ្រូ, <?php echo $teacher_info['full_name']; ?></h1>
        <p class="text-slate-500 mt-2">សូមជ្រើសរើសមុខវិជ្ជាខាងក្រោមដើម្បីបញ្ចូលពិន្ទុ</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php
        // ៣. ទាញយកមុខវិជ្ជា និងថ្នាក់ពីកាលវិភាគ (Timetable)
        $query = "SELECT DISTINCT t.class_name, s.subject_name, s.id as subject_id 
                  FROM timetable t 
                  JOIN subjects s ON t.subject_id = s.id 
                  WHERE t.teacher_id = '$t_id'";
        
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
        ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden hover:shadow-lg transition-all">
                <div class="p-1 bg-blue-600"></div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-wider">
                            ថ្នាក់: <?php echo $row['class_name']; ?>
                        </span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-6"><?php echo $row['subject_name']; ?></h3>
                    
                    <a href="manage_grades.php?class=<?php echo $row['class_name']; ?>&subject_id=<?php echo $row['subject_id']; ?>" 
                       class="flex items-center justify-center gap-2 w-full py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-blue-600 transition">
                        <i class="fas fa-edit"></i> បញ្ចូលពិន្ទុឥឡូវនេះ
                    </a>
                </div>
            </div>
        <?php 
            endwhile; 
        else: 
        ?>
            <div class="col-span-full bg-white p-12 rounded-3xl border-2 border-dashed border-slate-200 text-center text-slate-400">
                <i class="fas fa-book-reader text-5xl mb-4"></i>
                <p>មិនទាន់មានមុខវិជ្ជាបង្រៀនដែលត្រូវបានកំណត់នៅឡើយទេ</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>