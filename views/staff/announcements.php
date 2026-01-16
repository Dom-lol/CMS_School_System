<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

// កំណត់ឈ្មោះ Page ដើម្បីឱ្យ Sidebar បង្ហាញពណ៌ Active
$current_page = 'announcements.php';

include '../../includes/header.php';
include '../../includes/sidebar_staff.php';

// ១. ទាញយកទិន្នន័យប្រកាសព័ត៌មាន
$query = "SELECT * FROM announcements ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
?>

<main class="flex-1 p-8 bg-gray-50 min-h-screen font-['Kantumruy_Pro']">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 flex items-center gap-3">
                <i class="fas fa-bullhorn text-blue-600"></i>
                សេចក្ដីជូនដំណឹង
            </h1>
            <p class="text-slate-500 mt-2">តាមដាន និងគ្រប់គ្រងការផ្សព្វផ្សាយព័ត៌មានរបស់សាលា</p>
        </div>
        
        <a href="add_announcement.php" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-blue-700 transition flex items-center gap-2 shadow-lg shadow-blue-200">
            <i class="fas fa-plus"></i> បង្កើតដំណឹងថ្មី
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 max-w-4xl">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 rounded-full flex items-center justify-center text-blue-600">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800"><?php echo htmlspecialchars($row['title']); ?></h2>
                                <span class="text-xs text-slate-400 italic">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    <?php echo date('d M, Y | h:i A', strtotime($row['created_at'])); ?>
                                </span>
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs rounded-full font-bold">ទូទៅ</span>
                    </div>

                    <div class="text-slate-600 leading-relaxed border-t border-slate-50 pt-4">
                        <?php 
                            $content = htmlspecialchars($row['content']);
                            echo nl2br(mb_strimwidth($content, 0, 250, "...")); 
                        ?>
                    </div>

                        <div class="mt-6 flex items-center justify-between border-t border-slate-50 pt-4">
    <span class="text-sm text-slate-500 italic">
        <i class="fas fa-user-edit mr-1 text-blue-500"></i> 
        ដោយ៖ <span class="font-semibold text-slate-700"><?php echo htmlspecialchars($row['posted_by']); ?></span>
    </span>
    
    <div class="flex gap-3">
        <a href="edit_announcement.php?id=<?php echo $row['id']; ?>" class="text-amber-500 hover:text-amber-600">
            <i class="fas fa-edit"></i>
        </a>
        <a href="../../actions/announcements/delete.php?id=<?php echo $row['id']; ?>" 
           onclick="return confirm('តើអ្នកចង់លុបសារនេះមែនទេ?')" class="text-red-500 hover:text-red-600">
            <i class="fas fa-trash"></i>
        </a>
    </div>
</div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="bg-white p-12 rounded-3xl border-2 border-dashed border-slate-200 text-center">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="fas fa-comment-slash text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-400 font-['Kantumruy_Pro']">មិនទាន់មានការផ្សព្វផ្សាយនៅឡើយទេ</h3>
                <p class="text-slate-400 mt-1 text-sm">សូមចុចប៊ូតុងខាងលើដើម្បីបង្កើតការប្រកាសថ្មី។</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include '../../includes/footer.php'; ?>