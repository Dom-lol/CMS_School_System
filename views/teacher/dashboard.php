<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_teacher.php';

// ទាញយកព័ត៌មានគ្រូដែលកំពុង Login
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id FROM teachers WHERE user_id = '$u_id'");
$teacher_data = mysqli_fetch_assoc($teacher_query);
$t_id = $teacher_data['teacher_id'];

// ទាញមុខវិជ្ជាដែលគ្រូនេះបង្រៀន
$subjects = mysqli_query($conn, "SELECT * FROM subjects WHERE teacher_id = '$t_id'");
?>

<main class="flex-1 p-8 bg-gray-50">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-800">សួស្ដី លោកគ្រូ/អ្នកគ្រូ <?php echo $_SESSION['full_name']; ?></h1>
        <p class="text-slate-500 text-sm">សូមជ្រើសរើសមុខវិជ្ជាខាងក្រោមដើម្បីបញ្ចូលពិន្ទុ</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while($sub = mysqli_fetch_assoc($subjects)): ?>
        <div class="bg-white p-6 rounded-2xl shadow-custom border border-gray-100 hover:border-blue-500 transition-all">
            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-4 text-xl">
                <i class="fas fa-book-open"></i>
            </div>
            <h3 class="text-lg font-bold text-slate-800 mb-1"><?php echo $sub['subject_name']; ?></h3>
            <p class="text-slate-500 text-sm mb-4">គ្រប់គ្រងការបញ្ចូលពិន្ទុ និង Grade</p>
            <a href="input_scores.php?subject_id=<?php echo $sub['id']; ?>" 
               class="inline-block w-full text-center bg-slate-900 text-white py-2 rounded-lg hover:bg-blue-600 transition">
                បញ្ចូលពិន្ទុ
            </a>
        </div>
        <?php endwhile; ?>
    </div>
</main>