<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="w-64 bg-slate-900 text-slate-300 flex flex-col h-screen sticky top-0 shadow-2xl">
    <div class="p-6 text-xl font-bold text-white border-b border-slate-800 flex items-center">
        <i class="fas fa-user-shield mr-3 text-blue-500"></i> ADMIN PANEL
    </div>
    
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="dashboard.php" class="flex items-center p-3 rounded-xl transition-all <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800'; ?>">
            <i class="fas fa-chart-line w-8 text-lg"></i> 
            <span class="font-medium">គ្រប់គ្រងគ្រូ</span>
        </a>

       

        <a href="students_list.php" class="flex items-center p-3 rounded-xl transition-all <?php echo ($current_page == 'students_list.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800'; ?>">
            <i class="fas fa-user-graduate w-8 text-lg"></i> 
            <span class="font-medium">គ្រប់គ្រងសិស្ស</span>
        </a>

        
    </nav>

    <div class="p-4 border-t border-slate-800">
        <a href="../../actions/auth/logout.php" class="flex items-center p-3 rounded-xl text-gray-400 hover:bg-gray-500/10 transition group">
            <i class="fas fa-sign-out-alt w-8 text-lg group-hover:translate-x-1 transition"></i> 
            <span class="font-medium">ចាកចេញ</span>
        </a>
    </div>
</aside>