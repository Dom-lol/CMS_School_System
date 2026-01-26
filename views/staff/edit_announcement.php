<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

// ចាប់យក ID ពី URL ដើម្បីដឹងថាត្រូវកែប្រែ Record មួយណា
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;
$query = "SELECT * FROM announcements WHERE id = '$id' LIMIT 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) { header("Location: announcements.php"); exit(); }
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-3xl shadow-sm border border-slate-100">
        <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <i class="fas fa-edit text-amber-500"></i> កែប្រែសេចក្ដីជូនដំណឹង
        </h2>
        
        <form action="../../actions/announcements/update.php" method="POST" class="space-y-4">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

            <div>
                <label class="block text-[15px] font-medium text-slate-700 mb-1">ចំណងជើង</label>
                <input type="text" name="title" value="<?php echo htmlspecialchars($row['title']); ?>" required 
                       class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div>
                <label class="block text-[15px] font-medium text-slate-700 mb-1">ខ្លឹមសារព័ត៌មាន</label>
                <textarea name="content" rows="6" required 
                          class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none"><?php echo htmlspecialchars($row['content']); ?></textarea>
            </div>
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">Save</button>
                <a href="announcements.php" class="bg-slate-100 text-slate-600 px-6 py-2 rounded-xl font-bold hover:bg-slate-200 transition">Back</a>
            </div>
        </form>
    </div>
</main>
<?php include '../../includes/footer.php'; ?>