<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
$current_page = basename($_SERVER['PHP_SELF']);
?>

<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#111827] text-slate-300 transform -translate-x-full transition-transform duration-300 ease-in-out lg:sticky lg:top-0 lg:translate-x-0 flex flex-col h-screen shrink-0 shadow-2xl lg:shadow-none">
    
    <div class="p-6 text-xl font-bold text-white border-b border-slate-800 flex items-center justify-between">
        <div class="flex items-center">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-white">
                <img src="../../assets/favicon_v2.ico" alt="Logo" class="w-6 h-6 object-contain">
            </div>
            <span class="text-lg tracking-wide uppercase">លោកគ្រូ អ្នកគ្រូ</span>
        </div>
        <button onclick="toggleSidebar()" class="lg:hidden text-slate-400 hover:text-white transition-colors">
            <i class="fas fa-times text-2xl"></i>
        </button>
    </div>
    
    <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto custom-scrollbar">
        <a href="dashboard.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-200 <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-th-large w-5 text-center text-lg"></i>
            <span class="font-medium">ទំព័រដើម</span>
        </a>

        <a href="my_classes.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-200 <?php echo ($current_page == 'my_classes.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-graduation-cap w-5 text-center text-lg"></i>
            <span class="font-medium">ថ្នាក់រៀនរបស់ខ្ញុំ</span>
        </a>

        <a href="scores.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-200 <?php echo ($current_page == 'scores.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-pen-nib w-5 text-center text-lg"></i>
            <span class="font-medium">បញ្ចូលពិន្ទុសិស្ស</span>
        </a>

        <a href="teaching_schedule.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-200 <?php echo ($current_page == 'teaching_schedule.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
            <i class="fas fa-calendar-alt w-5 text-center text-lg"></i>
            <span class="font-medium">កាលវិភាគបង្រៀន</span>
        </a>

         <a href="attendance.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-200 <?php echo ($current_page == 'attendance.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
          <i class="fa-solid fa-clipboard-user w-5 text-center text-lg"></i>
            <span class="font-medium">អវត្តមាន</span>
        </a>

         <a href="student_list.php" class="flex items-center gap-4 p-4 rounded-2xl transition-all duration-200 <?php echo ($current_page == 'student_list.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-slate-800 hover:text-white'; ?>">
          <i class="fa-solid fa-clipboard-user w-5 text-center text-lg"></i>
            <span class="font-medium">List</span>
        </a>
    </nav>

    <div class="p-4 border-t border-slate-800 mb-2">
        <a href="../../actions/auth/logout.php" onclick="return confirm('តើលោកគ្រូពិតជាចង់ចាកចេញមែនទេ?')" class="flex items-center gap-4 p-4 rounded-2xl text-slate-400 hover:bg-gray-500/10 hover:text-gray-400 transition-all font-bold">
            <i class="fas fa-sign-out-alt w-5 text-center text-lg"></i>
            <span class="font-medium">ចាកចេញ</span>
        </a>
    </div>
</aside>