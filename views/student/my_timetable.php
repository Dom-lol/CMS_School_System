<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_student.php';

$s_id = $_SESSION['username'];

// ១. ទាញរកឈ្មោះថ្នាក់ (ឆែកមើលថាមានទិន្នន័យក្នុង table students ឬអត់)
$student_query = mysqli_query($conn, "SELECT class_name FROM students WHERE student_id = '$s_id'");
$student = mysqli_fetch_assoc($student_query);

if (!$student) {
    echo "<main class='flex-1 p-8'><div class='bg-red-100 p-4 text-red-700 rounded'>រកមិនឃើញព័ត៌មានសិស្សក្នុងប្រព័ន្ធឡើយ (ID: $s_id)</div></main>";
    include '../../includes/footer.php';
    exit();
}

$class = $student['class_name'];

// ២. ទាញកាលវិភាគ (ត្រូវប្រាកដថាឈ្មោះ Table ទាំងនេះមានក្នុង DB របស់អ្នក)
$sql = "SELECT t.*, s.subject_name, u.full_name as teacher_name 
        FROM timetable t 
        JOIN subjects s ON t.subject_id = s.id 
        JOIN teachers tea ON t.teacher_id = tea.teacher_id
        JOIN users u ON tea.user_id = u.id
        WHERE t.class_name = '$class'";

$timetable = mysqli_query($conn, $sql);

// ឆែកមើលថា SQL Query ដំណើរការឬទេ
if (!$timetable) {
    die("SQL Error: " . mysqli_error($conn)); // បង្ហាញ Error បើឈ្មោះ Table ឬ Column ខុស
}
?>

<main class="flex-1 p-8 bg-gray-50">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">កាលវិភាគសិក្សា - ថ្នាក់ <?php echo $class; ?></h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php if (mysqli_num_rows($timetable) > 0): ?>
            <?php while($time = mysqli_fetch_assoc($timetable)): ?>
                <div class="bg-white p-4 rounded-xl shadow border-l-4 border-blue-600">
                    <p class="text-blue-600 font-bold text-sm uppercase"><?php echo $row['day_of_week'] ?? ''; ?></p>
                    <h3 class="text-lg font-bold text-slate-800 my-1"><?php echo $time['subject_name']; ?></h3>
                    <p class="text-slate-500 text-xs"><?php echo $time['time_slot']; ?></p>
                    <p class="text-slate-600 text-sm mt-2 font-medium">គ្រូ៖ <?php echo $time['teacher_name']; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-slate-500">មិនទាន់មានកាលវិភាគសម្រាប់ថ្នាក់នេះនៅឡើយទេ។</p>
        <?php endif; ?>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>