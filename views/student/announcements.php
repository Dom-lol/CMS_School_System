<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

$current_page = 'announcements.php';

include '../../includes/header.php';
include '../../includes/sidebar_student.php'; // ប្រើ Sidebar របស់សិស្ស

$query = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen font-['Kantumruy_Pro']">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3">
            <i class="fas fa-bullhorn text-blue-600"></i>
            សេចក្ដីជូនដំណឹងសម្រាប់សិស្ស
        </h1>
        <p class="text-slate-500 mt-2">រាល់ព័ត៌មានផ្លូវការពីខាងសាលានឹងបង្ហាញនៅទីនេះ</p>
    </div>

    <div class="grid grid-cols-1 gap-6 max-w-4xl">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800"><?php echo htmlspecialchars($row['title']); ?></h2>
                                <span class="text-xs text-slate-400">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    <?php echo date('d M, Y', strtotime($row['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="text-slate-600 leading-relaxed border-t border-slate-50 pt-4">
                        <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                    </div>

                    <div class="mt-4 flex items-center justify-between border-t border-slate-50 pt-4 text-xs text-slate-400 italic">
                        <span>ដោយ៖ <?php echo htmlspecialchars($row['posted_by']); ?></span>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white p-12 rounded-3xl border-2 border-dashed border-slate-200 text-center">
                <i class="fas fa-comment-slash text-slate-300 text-3xl mb-4"></i>
                <h3 class="text-xl font-bold text-slate-400">មិនទាន់មានការប្រកាសថ្មីៗទេ</h3>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>