<?php 
$current_page = basename($_SERVER['PHP_SELF']); 
?>

<aside class="w-64 bg-slate-900 text-slate-300 flex flex-col h-screen sticky top-0 shadow-2xl z-50">
    <div class="p-6 text-xl font-bold text-white border-b border-slate-800 flex items-center">
        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 bg-white">
            <img src="../../assets/favicon_v2.ico" alt="Logo" class="w-6 h-6 object-contain">
        </div>
        រដ្ឋបាលសាលា
    </div>
    
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
        
        <a href="dashboard.php" 
           class="flex items-center p-3 rounded-xl transition-all duration-300 <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-blue-600 hover:text-white'; ?>">
            <i class="fas fa-th-large w-8 text-lg"></i> 
            <span class="font-medium">ទំព័រដើម</span>
        </a>

        <div class="space-y-1">
            <?php 
                $student_pages = ['student_list.php', 'add_student.php'];
                $is_student_active = in_array($current_page, $student_pages);
            ?>
            <button onclick="toggleDropdown('studentMenu', 'studentArrow')" 
                class="w-full flex items-center justify-between p-3 rounded-xl transition-all duration-300 hover:bg-slate-800 <?php echo $is_student_active ? 'text-white bg-slate-800/50' : ''; ?>">
                <div class="flex items-center">
                     <i class="fas fa-user-graduate w-8 text-lg"></i>
                    <span class="font-medium">គ្រប់គ្រងសិស្ស</span>
                </div>
                <i id="studentArrow" class="fas fa-chevron-right text-[10px] transition-transform duration-300 <?php echo $is_student_active ? 'rotate-90' : ''; ?>"></i>
            </button>
            <div id="studentMenu" class="<?php echo $is_student_active ? '' : 'hidden'; ?> ml-4 border-l border-slate-700 pl-2 space-y-1">
                <a href="student_list.php" class="block p-2 pl-4 text-sm rounded-lg transition-all <?php echo ($current_page == 'student_list.php') ? 'text-blue-500 font-bold' : 'hover:text-white'; ?>">បញ្ជីឈ្មោះសិស្ស</a>
                <a href="add_student.php" class="block p-2 pl-4 text-sm rounded-lg transition-all <?php echo ($current_page == 'add_student.php') ? 'text-blue-500 font-bold' : 'hover:text-white'; ?>">បន្ថែមសិស្សថ្មី</a>
            </div>
        </div>

        <div class="space-y-1">
            <?php 
                $teacher_pages = ['teachers_list.php', 'add_teacher.php'];
                $is_teacher_active = in_array($current_page, $teacher_pages);
            ?>
            <button onclick="toggleDropdown('teacherMenu', 'teacherArrow')" 
                class="w-full flex items-center justify-between p-3 rounded-xl transition-all duration-300 hover:bg-slate-800 <?php echo $is_teacher_active ? 'text-white bg-slate-800/50' : ''; ?>">
                <div class="flex items-center">
                    <i class="fas fa-chalkboard-teacher w-8 text-lg"></i> 
                    <span class="font-medium">គ្រប់គ្រងគ្រូ</span>
                </div>
                <i id="teacherArrow" class="fas fa-chevron-right text-[10px] transition-transform duration-300 <?php echo $is_teacher_active ? 'rotate-90' : ''; ?>"></i>
            </button>
            <div id="teacherMenu" class="<?php echo $is_teacher_active ? '' : 'hidden'; ?> ml-4 border-l border-slate-700 pl-2 space-y-1">
                <a href="teachers_list.php" class="block p-2 pl-4 text-sm rounded-lg transition-all <?php echo ($current_page == 'teachers_list.php') ? 'text-blue-500 font-bold' : 'hover:text-white'; ?>">បញ្ជីគ្រូបង្រៀន</a>
                <a href="add_teacher.php" class="block p-2 pl-4 text-sm rounded-lg transition-all <?php echo ($current_page == 'add_teacher.php') ? 'text-blue-500 font-bold' : 'hover:text-white'; ?>">បន្ថែមគ្រូថ្មី</a>
            </div>
        </div>

        <a href="timetable.php" 
           class="flex items-center p-3 rounded-xl transition-all <?php echo ($current_page == 'timetable.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-blue-600 hover:text-white'; ?>">
            <i class="fas fa-calendar-week w-8 text-lg"></i> 
            <span class="font-medium">កាលវិភាគបង្រៀន</span>
        </a>

        <a href="announcements.php" 
           class="flex items-center p-3 rounded-xl transition-all duration-300 <?php echo ($current_page == 'announcements.php') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'hover:bg-blue-600 hover:text-white'; ?>">
            <i class="fas fa-bullhorn w-8 text-lg"></i> 
            <span class="font-medium">ផ្សព្វផ្សាយដំណឹង</span>
        </a>

    </nav>

    <div class="p-4 border-t border-slate-800">
        <a href="../../actions/auth/logout.php" 
           class="flex items-center p-3 rounded-xl text-slate-400 hover:bg-gray-500/10 hover:text-gray-500 transition-all group">
            <i class="fas fa-sign-out-alt w-8 text-lg group-hover:translate-x-1 transition-transform"></i> 
            <span class="font-medium">ចាកចេញពីប្រព័ន្ធ</span>
        </a>
    </div>
</aside>

<script>
function toggleDropdown(menuId, arrowId) {
    const menu = document.getElementById(menuId);
    const arrow = document.getElementById(arrowId);
    
    // បិទ/បើក Menu
    menu.classList.toggle('hidden');
    
    // បង្វិលព្រួញ
    arrow.classList.toggle('rotate-90');
}
</script>

<style>
/* សម្រាប់រចនា Scrollbar ឱ្យតូចស្អាត */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #1e293b;
    border-radius: 10px;
}
</style>