<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

if ($_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
    header("Location: ../../index.php?error=no_permission");
    exit();
}

// ទាញយកព័ត៌មានគ្រូ
$u_id = $_SESSION['user_id'];
$teacher_query = mysqli_query($conn, "SELECT teacher_id, full_name, profile_image FROM teachers WHERE user_id = '$u_id' LIMIT 1");
$teacher_info = mysqli_fetch_assoc($teacher_query);
$t_full_name = $teacher_info['full_name'] ?? 'Teacher';

// ចាប់តម្លៃពី URL
$class_id = $_GET['class_id'] ?? 0;
$subject_id = $_GET['subject_id'] ?? 0;

// ទាញយកឈ្មោះថ្នាក់ និងមុខវិជ្ជា
$info_query = mysqli_query($conn, "SELECT c.class_name, s.subject_name FROM classes c, subjects s WHERE c.id = '$class_id' AND s.id = '$subject_id'");
$info = mysqli_fetch_assoc($info_query);
$class_display = $info['class_name'] ?? 'N/A';
$subject_display = $info['subject_name'] ?? 'N/A';

// ទាញយកសិស្ស (ប្រើ class_name = '7' តាម Database លោកគ្រូ)
$student_sql = "SELECT id, student_id, full_name, gender FROM students WHERE class_name = '7' ORDER BY gender DESC, full_name ASC";
$students = mysqli_query($conn, $student_sql);
?>

<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>បញ្ចូលពិន្ទុ - <?= $class_display ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Kantumruy Pro', sans-serif; background-color: #f8fafc; }
        .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    </style>
</head>
<body class="min-h-screen">

<div class="flex flex-col lg:flex-row min-h-screen w-full">
    <div class="hidden lg:block w-64 bg-slate-900 shadow-xl"><?php include '../../includes/sidebar_teacher.php'; ?></div>

    <div class="flex-1 flex flex-col">
        <header class="bg-white border-b h-20 flex items-center justify-between px-6 sticky top-0 z-50">
            <div>
                <h2 class="text-lg font-black text-slate-800 uppercase italic">Input Scores</h2>
                <p class="text-[10px] text-blue-600 font-bold uppercase italic">ថ្នាក់: <?= $class_display ?> | មុខវិជ្ជា: <?= $subject_display ?></p>
            </div>
            <div class="flex items-center gap-3">
                <p class="text-sm font-black text-slate-900"><?= $t_full_name ?></p>
                <div class="w-10 h-10 rounded-xl bg-blue-600"></div>
            </div>
        </header>

        <main class="p-4 md:p-10">
            <?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
                <div class="mb-6 p-4 bg-green-500 text-white rounded-2xl font-bold text-sm animate-pulse">
                    <i class="fas fa-check-circle mr-2"></i> រក្សាទុកពិន្ទុដោយជោគជ័យ!
                </div>
            <?php endif; ?>

            <form action="save_grades.php" method="POST">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">
                <input type="hidden" name="subject_id" value="<?= $subject_id ?>">

                <div class="bg-white rounded-[2rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    <div class="table-responsive">
                        <table class="w-full text-left min-w-[600px]">
                            <thead class="bg-slate-50 border-b">
                                <tr class="text-[10px] font-black uppercase text-slate-400">
                                    <th class="px-6 py-5 text-center w-16">ល.រ</th>
                                    <th class="px-6 py-5">អត្តលេខ</th>
                                    <th class="px-6 py-5">ឈ្មោះសិស្ស</th>
                                    <th class="px-6 py-5 text-center">ភេទ</th>
                                    <th class="px-6 py-5 text-center w-40">ពិន្ទុ</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (mysqli_num_rows($students) > 0): $i=1; ?>
                                    <?php while($row = mysqli_fetch_assoc($students)): ?>
                                        <tr class="hover:bg-blue-50/50 transition-all">
                                            <td class="px-6 py-4 text-center font-bold text-slate-300 italic"><?= $i++ ?></td>
                                            <td class="px-6 py-4 font-bold text-blue-600 italic"><?= $row['student_id'] ?></td>
                                            <td class="px-6 py-4 font-black text-slate-800 uppercase italic"><?= $row['full_name'] ?></td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="px-3 py-1 rounded-lg text-[9px] font-black uppercase <?= ($row['gender'] == 'Female' || $row['gender'] == 'ស្រី') ? 'bg-pink-100 text-pink-600' : 'bg-indigo-100 text-indigo-600' ?>">
                                                    <?= $row['gender'] ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <input type="number" name="grade[<?= $row['id'] ?>]" min="0" max="100" step="0.1" required 
                                                       class="w-full bg-slate-50 border-2 border-transparent rounded-xl px-4 py-2 text-center font-black focus:bg-white focus:border-blue-600 outline-none transition-all shadow-inner">
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="w-full md:w-auto bg-slate-900 text-white px-12 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-blue-600 shadow-xl transition-all">
                        <i class="fas fa-save mr-2"></i> រក្សាទុកពិន្ទុ
                    </button>
                </div>
            </form>
        </main>
    </div>
</div>

</body>
</html>