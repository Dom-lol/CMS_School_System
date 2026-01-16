<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_student.php';

$s_id = $_SESSION['username'];
$query = "SELECT s.subject_name, sc.monthly_score, sc.exam_score, sc.total_score, sc.grade 
          FROM scores sc 
          JOIN subjects s ON sc.subject_id = s.id 
          WHERE sc.student_id = '$s_id'";
$grades = mysqli_query($conn, $query);
?>

<main class="flex-1 p-8 bg-gray-50">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">លទ្ធផលសិក្សា</h1>

    <div class="bg-white rounded-xl shadow-custom overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b">
                <tr>
                    <th class="p-4 font-semibold text-slate-700">មុខវិជ្ជា</th>
                    <th class="p-4 font-semibold text-slate-700 text-center">ពិន្ទុប្រចាំខែ</th>
                    <th class="p-4 font-semibold text-slate-700 text-center">ពិន្ទុប្រលង</th>
                    <th class="p-4 font-semibold text-slate-700 text-center">សរុប</th>
                    <th class="p-4 font-semibold text-slate-700 text-center">និទ្ទេស</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php while($row = mysqli_fetch_assoc($grades)): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="p-4 font-medium text-slate-800"><?php echo $row['subject_name']; ?></td>
                    <td class="p-4 text-center"><?php echo $row['monthly_score']; ?></td>
                    <td class="p-4 text-center"><?php echo $row['exam_score']; ?></td>
                    <td class="p-4 text-center font-bold text-blue-600"><?php echo $row['total_score']; ?></td>
                    <td class="p-4 text-center">
                        <span class="px-3 py-1 rounded-full text-xs font-bold 
                            <?php echo ($row['grade'] == 'A' || $row['grade'] == 'B') ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'; ?>">
                            <?php echo $row['grade']; ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>