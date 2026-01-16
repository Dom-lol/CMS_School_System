<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
?>

<aside class="w-64 bg-slate-900 text-slate-300 flex flex-col h-screen sticky top-0 shadow-2xl">
    <div class="p-6 text-xl font-bold text-white border-b border-slate-800 flex items-center">
        <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
            <i class="fas fa-briefcase text-sm"></i>
        </div>
        STAFF PORTAL
    </div>
    
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        
        <a href="dashboard.php" 
           class="flex items-center p-3 rounded-xl transition-all duration-300 <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-blue-600 hover:text-white'; ?>">
            <i class="fas fa-th-large w-8 text-lg"></i> 
            <span class="font-medium">ទំព័រដើម</span>
        </a>

        <a href="add_student.php" 
           class="flex items-center p-3 rounded-xl transition-all duration-300 <?php echo ($current_page == 'add_student.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-blue-600 hover:text-white'; ?>">
            <i class="fas fa-user-plus w-8 text-lg"></i> 
            <span class="font-medium">បន្ថែមសិស្សថ្មី</span>
        </a>

        <a href="teachers_list.php" 
        class="flex items-center p-3 mb-1 rounded-xl transition-all <?php echo ($current_page == 'teachers_list.php') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-600'; ?>">
            <i class="fas fa-users w-8 text-lg"></i>
            <span class="font-medium">បញ្ជីគ្រូបង្រៀន</span>
        </a>

        <a href="add_teacher.php" 
        class="flex items-center p-3 mb-4 rounded-xl transition-all <?php echo ($current_page == 'add_teacher.php') ? 'bg-blue-600 text-white' : 'hover:bg-blue-50 text-slate-600'; ?>">
            <i class="fas fa-user-plus w-8 text-lg"></i>
            <span class="font-medium">បន្ថែមគ្រូថ្មី</span>
        </a>
        
        <a href="timetable.php" 
        class="flex items-center p-3 rounded-xl transition-all <?php echo ($current_page == 'timetable.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-blue-600 hover:text-white'; ?>">
            <i class="fas fa-calendar-week w-8 text-lg"></i> 
            <span class="font-medium">កាលវិភាគបង្រៀន</span>
        </a>
            

        <a href="student_list.php" 
           class="flex items-center p-3 rounded-xl transition-all duration-300 <?php echo ($current_page == 'student_list.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-blue-600 hover:text-white'; ?>">
            <i class="fas fa-users w-8 text-lg"></i> 
            <span class="font-medium">បញ្ជីឈ្មោះសិស្ស</span>
        </a>

        <a href="announcements.php" 
           class="flex items-center p-3 rounded-xl transition-all duration-300 <?php echo ($current_page == 'announcements.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-blue-600 hover:text-white'; ?>">
            <i class="fas fa-bullhorn w-8 text-lg"></i> 
            <span class="font-medium">ផ្សព្វផ្សាយដំណឹង</span>
        </a>

    </nav>

    <div class="p-4 border-t border-slate-800">
        <a href="../../actions/auth/logout.php" 
           class="flex items-center p-3 rounded-xl text-white-400 hover:bg-gray-700/10 transition-all group">
            <i class="fas fa-sign-out-alt w-8 text-lg group-hover:translate-x-1 transition-transform"></i> 
            <span class="font-medium">ចាកចេញពីប្រព័ន្ធ</span>
        </a>
    </div>
</aside>