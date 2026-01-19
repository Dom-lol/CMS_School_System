<div class="relative inline-block">
    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full border-2 border-slate-300 overflow-hidden bg-slate-100 shadow-sm">
        <img src="../../assets/uploads/profiles/<?php echo $_SESSION['profile_img'] ?? 'default.png'; ?>" 
             class="w-full h-full object-cover">
    </div>
    
    <form action="../../actions/students/upload_profile.php" method="POST" enctype="multipart/form-data">
        <label class="absolute bottom-0 right-0 w-6 h-6 md:w-8 md:h-8 bg-blue-600 text-white rounded-full flex items-center justify-center cursor-pointer border-2 border-white shadow-md hover:bg-blue-700 transition-all">
            <i class="fas fa-camera text-[10px] md:text-xs"></i>
            <input type="file" name="profile_img" class="hidden" accept="image/*" onchange="this.form.submit()">
        </label>
    </form>
</div>