<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#111827] text-slate-300 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 flex flex-col h-screen">
    
    <div class="pt-6 p-3  text-xl font-bold text-white border-b border-slate-800 flex justify-between items-center">
        <div class="flex items-center ">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-white">
            <img src="../../assets/favicon_v2.ico" alt="Logo" class="w-6 h-6 object-contain">
        </div>
            <span class="tracking-wider uppercase text-[17px] lg:text-xl">វិទ្យាល័យលំដាប់ពិភពលោក</span>
        </div>
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto">
        <a href="dashboard.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-th-large w-5 text-center"></i>
            <span class="font-medium">ទំព័រដើម</span>
        </a>
         <a href="my_attendance.php" class=" flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'my_attendance.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-regular w-5 fa-address-book"></i>
            <span class="font-medium">អវត្តមាន</span>
        </a>
            <a href=" my_timetable.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'my_timetable.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-calendar-alt w-5 text-center"></i>
            <span class="font-medium">កាលវិភាគរៀន</span>
        </a>
        <a href="my_grades.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'my_grades.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-star w-5 text-center"></i>
            <span class="font-medium">លទ្ធផលសិក្សា</span>
        </a>
        <a href="my_teachers.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'my_teachers.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-solid fa-users"></i>
            <span class="font-medium">បញ្ជីគ្រូ</span>
        </a>
        <a href="announcements.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'announcements.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-bullhorn w-5 text-center"></i>
            <span class="font-medium">សេចក្ដីជូនដំណឹង</span>
        </a>

        
    </nav>

    <div class="p-4 border-t border-slate-800 mb-2">
        <a href="../../actions/auth/logout.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-400 hover:bg-gray-400/10 transition-all group">
            <i class="fas fa-sign-out-alt w-5 text-center text-lg group-hover:translate-x-1 transition-transform group"></i>
            <span class="font-medium">ចាកចេញ</span>
        </a>
    </div>
</aside>

<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden backdrop-blur-sm"></div>

<!-- To-do List -->