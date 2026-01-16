<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

// ទាញទិន្នន័យដោយប្រើ JOIN ដើម្បីឱ្យឃើញព័ត៌មានទាំង ២ តារាង
$query = "SELECT u.id AS user_id, u.full_name, u.username AS email, t.teacher_id, t.subjects AS major, t.phone 
          FROM teachers t 
          JOIN users u ON t.user_id = u.id 
          WHERE u.role = 'teacher' 
          ORDER BY t.teacher_id ASC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main class="flex-1 p-8 bg-gray-50 min-h-screen font-['Kantumruy_Pro']">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-slate-800">គ្រប់គ្រងព័ត៌មានគ្រូបង្រៀន</h1>
        <a href="add_teacher.php" class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold hover:bg-blue-700 shadow-md transition">
            <i class="fas fa-plus mr-1"></i> បន្ថែមគ្រូថ្មី
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b">
                <tr>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase">អត្តលេខ</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase">ឈ្មោះពេញ</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase">ឯកទេស</th>
                    <th class="p-4 text-xs font-bold text-slate-500 uppercase">សកម្មភាព</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr class="hover:bg-blue-50/30 transition">
                    <td class="p-4 font-bold text-blue-600"><?= $row['teacher_id'] ?></td>
                    <td class="p-4 font-bold text-slate-700"><?= $row['full_name'] ?></td>
                    <td class="p-4 text-sm text-slate-600"><?= $row['major'] ?></td>
                    <td class="p-4 flex gap-2">
                        <a href="edit_teacher.php?id=<?= $row['user_id'] ?>" class="p-2 bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200"><i class="fas fa-edit"></i></a>
                        <a href="../../actions/teachers/delete.php?id=<?= $row['user_id'] ?>" 
                           onclick="return confirm('តើអ្នកប្រាកដថាចង់លុបគ្រូឈ្មោះ <?= $row['full_name'] ?>? វានឹងលុបគណនីប្រើប្រាស់របស់គាត់ផងដែរ។')" 
                           class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200">
                           <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>