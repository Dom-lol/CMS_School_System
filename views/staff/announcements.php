<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';

// ១. ឆែកសិទ្ធិជាមុនសិន
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php'; 

// ២. ទាញយកទិន្នន័យ (Query)
$sql = "SELECT * FROM announcements ORDER BY created_at DESC";
$announcements = mysqli_query($conn, $sql);

// ពិនិត្យថា Query ដើរឬអត់ ដើម្បីការពារ Error មុននេះ
if (!$announcements) {
    $announcements_count = 0;
} else {
    $announcements_count = mysqli_num_rows($announcements);
}
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 tracking-tight">ផ្សព្វផ្សាយដំណឹង</h1>
            <p class="text-slate-500 mt-1">គ្រប់គ្រងការជូនដំណឹងទៅកាន់គ្រូ និងសិស្សានុសិស្ស</p>
        </div>
        <button onclick="document.getElementById('addModal').classList.remove('hidden')" 
                class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-xl font-bold shadow-lg transition flex items-center">
            <i class="fas fa-plus mr-2"></i> បង្កើតដំណឹងថ្មី
        </button>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-xl border border-green-200">
            បង្ហោះដំណឹងបានជោគជ័យ!
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 gap-6">
        <?php if ($announcements_count > 0): ?>
            <?php while($row = mysqli_fetch_assoc($announcements)): ?>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex justify-between items-start">
                    <div class="flex gap-5">
                        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-slate-800"><?php echo htmlspecialchars($row['title']); ?></h3>
                            <p class="text-slate-500 mt-2 leading-relaxed"><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
                            <div class="flex gap-4 mt-4 text-xs font-bold text-slate-400">
                                <span><i class="far fa-calendar-alt mr-1"></i> <?php echo date('d M Y', strtotime($row['created_at'])); ?></span>
                                <span><i class="far fa-user mr-1"></i> ដោយ: <?php echo htmlspecialchars($row['created_by']); ?></span>
                            </div>
                        </div>
                    </div>
                    <a href="../../actions/staff/delete_announcement.php?id=<?php echo $row['id']; ?>" 
                       onclick="return confirm('តើអ្នកពិតជាចង់លុបដំណឹងនេះមែនទេ?')"
                       class="text-slate-300 hover:text-red-500 transition">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white p-12 rounded-3xl border-2 border-dashed border-slate-200 text-center text-slate-400">
                <p>មិនទាន់មានដំណឹងត្រូវបានផ្សព្វផ្សាយនៅឡើយទេ</p>
            </div>
        <?php endif; ?>
    </div>

    <div id="addModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm flex items-center justify-center hidden z-50">
        <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="text-xl font-bold text-slate-800">បង្កើតដំណឹងថ្មី</h2>
                <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="../../actions/staff/save_announcement.php" method="POST" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ចំណងជើង</label>
                    <input type="text" name="title" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">ខ្លឹមសារដំណឹង</label>
                    <textarea name="message" rows="4" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                </div>
                <button type="submit" class="w-full bg-orange-600 text-white py-4 rounded-xl font-bold hover:bg-orange-700 transition shadow-lg">
                    បង្ហោះដំណឹងឥឡូវនេះ
                </button>
            </form>
        </div>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>