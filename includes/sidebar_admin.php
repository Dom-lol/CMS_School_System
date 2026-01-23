<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="w-72 bg-[#1e293b] text-slate-400 flex flex-col h-screen sticky top-0 shadow-2xl shrink-0">
    <div class="p-8 flex items-center gap-4 border-b border-slate-700/30">
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg transform -rotate-6 group-hover:rotate-0 transition-all">
            <i class="fas fa-user-shield text-blue-600 text-xl"></i>
        </div>
        <div class="flex flex-col">
            <span class="text-white font-black tracking-tighter text-xl italic leading-none uppercase">Admin</span>
            <span class="text-[9px] font-bold uppercase tracking-[0.2em] text-blue-500 mt-1">Control Panel</span>
        </div>
    </div>
    
    <nav class="flex-1 px-6 py-8 space-y-2 overflow-y-auto custom-scrollbar">
        
        <a href="dashboard.php" class="flex items-center p-4 rounded-2xl transition-all group <?php echo ($current_page == 'dashboard.php') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-800/50 hover:text-white'; ?>">
            <i class="fas fa-th-large w-8 text-lg group-hover:scale-110 transition-transform"></i> 
            <span class="font-bold text-sm uppercase italic">Dashboard</span>
        </a>

        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest px-4 pt-4 pb-2">ការគ្រប់គ្រង</p>

        <a href="teachers_list_admin.php" class="flex items-center p-4 rounded-2xl transition-all group <?php echo ($current_page == 'teachers_list_admin.php') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-800/50 hover:text-white'; ?>">
            <i class="fas fa-chalkboard-teacher w-8 text-lg group-hover:scale-110 transition-transform"></i> 
            <span class="font-bold text-sm uppercase italic">គ្រប់គ្រងគ្រូ</span>
        </a>

        <a href="students_list.php" class="flex items-center p-4 rounded-2xl transition-all group <?php echo ($current_page == 'students_list.php') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-800/50 hover:text-white'; ?>">
            <i class="fas fa-user-graduate w-8 text-lg group-hover:scale-110 transition-transform"></i> 
            <span class="font-bold text-sm uppercase italic">គ្រប់គ្រងសិស្ស</span>
        </a>

        <a href="classes_list.php" class="flex items-center p-4 rounded-2xl transition-all group <?php echo ($current_page == 'classes_list.php') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-800/50 hover:text-white'; ?>">
            <i class="fas fa-school w-8 text-lg group-hover:scale-110 transition-transform"></i> 
            <span class="font-bold text-sm uppercase italic">គ្រប់គ្រងថ្នាក់</span>
        </a>

        <a href="timetable_admin.php" class="flex items-center p-4 rounded-2xl transition-all group <?php echo ($current_page == 'timetable_admin.php') ? 'bg-blue-600 text-white shadow-xl shadow-blue-900/40' : 'hover:bg-slate-800/50 hover:text-white'; ?>">
            <i class="fas fa-calendar-alt w-8 text-lg group-hover:scale-110 transition-transform"></i> 
            <span class="font-bold text-sm uppercase italic">កាលវិភាគ</span>
        </a>

    </nav>

    <div class="p-6 border-t border-slate-700/30">
        <a href="../../actions/auth/logout.php" class="flex items-center p-4 rounded-2xl text-slate-400 hover:bg-red-500/10 hover:text-red-500 transition-all group">
            <i class="fas fa-power-off w-8 text-lg group-hover:rotate-12 transition-transform"></i> 
            <span class="font-bold text-sm uppercase italic">ចាកចេញ</span>
        </a>
    </div>
</aside>