<?php
// ទាញយកឈ្មោះ File បច្ចុប្បន្ន (ឧទាហរណ៍៖ dashboard.php)
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside class="w-64 bg-slate-900 text-slate-300 flex flex-col h-screen sticky top-0">
    <div class="p-6 text-xl font-bold text-white border-b border-slate-800 flex items-center">
        <i class="fas fa-user-graduate mr-3 text-purple-500"></i> STUDENT PORTAL
    </div>
    
    <nav class="flex-1 px-4 py-6 space-y-2">
        <a href="dashboard.php" 
           class="flex items-center p-3 rounded-lg transition-all <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-columns w-6"></i> 
            <span>Dashboard</span>
        </a>

        <a href="my_grades.php" 
           class="flex items-center p-3 rounded-lg transition-all <?php echo ($current_page == 'my_grades.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-star w-6"></i> 
            <span>លទ្ធផលសិក្សា (ពិន្ទុ)</span>
        </a>

        <a href="my_timetable.php" 
           class="flex items-center p-3 rounded-lg transition-all <?php echo ($current_page == 'timetable.php' || $current_page == 'my_timetable.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-clock w-6"></i> 
            <span>កាលវិភាគរៀន</span>
        </a>

        <a href="announcements.php" 
        class="flex items-center p-3 rounded-lg transition-all <?php echo ($current_page == 'announcements.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/50 ' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-bullhorn w-6"></i>
            <span class="font-medium">សេចក្ដីជូនដំណឹង</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-800">
        <a href="../../actions/auth/logout.php" class="flex items-center p-3 rounded-lg text-gray-400 hover:bg-gray-500/10 transition">
            <i class="fas fa-sign-out-alt w-6"></i> ចាកចេញ
        </a>
    </div>
</aside>