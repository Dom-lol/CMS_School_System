<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#111827] text-slate-300 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col h-screen">
    
    <div class="p-6 text-xl font-bold text-white border-b border-slate-800 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <i class="fas fa-user-graduate text-blue-500"></i>
            <span class="tracking-wider uppercase text-sm">Student Portal</span>
        </div>
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto">
        <a href="dashboard.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-th-large w-5 text-center"></i>
            <span class="font-medium">Dashboard</span>
        </a>
        <a href="my_grades.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'my_grades.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-star w-5 text-center"></i>
            <span class="font-medium">លទ្ធផលសិក្សា (ពិន្ទុ)</span>
        </a>
        <a href="my_timetable.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'my_timetable.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-calendar-alt w-5 text-center"></i>
            <span class="font-medium">កាលវិភាគរៀន</span>
        </a>
        <a href="announcements.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'announcements.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-bullhorn w-5 text-center"></i>
            <span class="font-medium">សេចក្ដីជូនដំណឹង</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-800 mb-2">
        <a href="../../actions/auth/logout.php" class="flex items-center gap-4 p-4 rounded-2xl text-red-400 hover:bg-red-400/10 transition">
            <i class="fas fa-sign-out-alt w-5 text-center text-lg"></i>
            <span class="font-medium">ចាកចេញ</span>
        </a>
    </div>
</aside>

<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden backdrop-blur-sm"></div>