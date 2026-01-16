<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

// ចាប់យក ID ពី URL
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

// ទាញយកទិន្នន័យតែ ១ ជួរគត់តាម ID
$query = "SELECT * FROM announcements WHERE id = '$id' LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// បើអត់មាន ID ក្នុង DB ឱ្យត្រឡប់ទៅវិញ
if (!$row) {
    header("Location: announcements.php");
    exit();
}
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="max-w-3xl mx-auto">
        <a href="announcements.php" class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-6">
            <i class="fas fa-chevron-left"></i> ត្រឡប់ក្រោយ
        </a>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="h-32 bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-white">
                <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-wider">
                    សេចក្ដីជូនដំណឹង
                </span>
            </div>

            <div class="p-8 -mt-12">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-50">
                    <h1 class="text-3xl font-bold text-slate-800 mb-4"><?php echo htmlspecialchars($row['title']); ?></h1>
                    
                    <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 mb-8 border-b border-slate-100 pb-4">
                        <span class="flex items-center gap-1">
                            <i class="far fa-calendar-alt text-blue-500"></i>
                            <?php echo date('d M, Y', strtotime($row['created_at'])); ?>
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="far fa-clock text-blue-500"></i>
                            <?php echo date('h:i A', strtotime($row['created_at'])); ?>
                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-user-circle text-blue-500"></i>
                            ដោយ៖ <?php echo htmlspecialchars($row['posted_by']); ?>
                        </span>
                    </div>

                    <div class="text-slate-700 leading-loose text-lg font-['Kantumruy_Pro']">
                        <?php echo nl2br(htmlspecialchars($row['content'])); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>