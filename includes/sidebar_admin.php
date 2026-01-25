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
            <span class="tracking-wider uppercase text-[17px] lg:text-xl">Admin Dashboard</span>
        </div>
        <button onclick="toggleSidebar()" class="md:hidden text-slate-400 hover:text-white">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-5 space-y-3 overflow-y-auto">
        <a href="dashboard.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-th-large w-8 text-lg"></i>
            <span class="font-medium">ទំព័រដើម</span>
        </a>
        <a href="students_list.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'students_list.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
              <i class="fas fa-user-graduate w-8 text-lg"></i>
            <span class="font-medium">Student List</span>
        </a>
        <a href="teachers_list_admin.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'teachers_list_admin.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
           <i class="fas fa-chalkboard-teacher w-8 text-lg"></i> 
            <span class="font-medium">Teacher List</span>
        </a>
        <a href="classes_list.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'classes_list.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-bullhorn w-8 text-lg"></i>
            <span class="font-medium">Class List</span>
        </a>

          <a href="timetable_admin.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all <?php echo ($current_page == 'timetable_admin.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fa-solid fa-users w-8 text-lg"></i>
            <span class="font-medium">Timetable</span>
        </a>

        
    </nav>

    <div class="p-4 border-t border-slate-800 mb-2">
        <a href="../../actions/auth/logout.php" class="flex items-center gap-4 p-4 rounded-2xl text-gray-400 hover:bg-gray-400/10 transition">
            <i class="fas fa-sign-out-alt w-5 text-center text-lg"></i>
            <span class="font-medium">ចាកចេញ</span>
        </a>
    </div>
</aside>

<div id="sidebar-overlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden backdrop-blur-sm"></div>

<!-- To-do List -->