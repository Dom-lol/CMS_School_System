<?php session_start(); ?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ចូលប្រើប្រាស់ប្រព័ន្ធ - School CMS</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon_v2.ico">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro&display=swap" rel="stylesheet">
    <style>body { font-family: 'Kantumruy Pro', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col">
    
    <div class="flex items-center justify-center text-2xl md:text-4xl px-4 pt-10 lg:pt-20 md:pt-20 font-bold text-blue-700">
        <img src="https://samlouthighschool.com/file/image/logo.png" alt="Logo" class="w-[70px] h-[70px] object-contain">
        វិទ្យាល័យលំដាប់ពិភពលោក
    </div>

    <div class="flex flex-1 items-center justify-center px-4 py-10">   
        <div class="bg-white p-8 md:p-10 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-slate-800">Welcome</h1>
                <p class="text-slate-500 text-sm mt-2">សូមបញ្ចូលព័ត៌មានដើម្បីចូលប្រើប្រាស់</p>
            </div>

            <form action="actions/auth/login_user.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">តួនាទី</label>
                    <select name="role" class="w-full border border-slate-200 p-3 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50 transition-all">
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">ឈ្មោះអ្នកប្រើប្រាស់</label>
                    <input type="text" name="username" placeholder="ឈ្មោះអ្នកប្រើប្រាស់" required 
                        class="w-full border border-slate-200 p-3 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">លេខសម្ងាត់</label>
                    <input type="password" name="password" placeholder="លេខសម្ងាត់" required 
                        class="w-full border border-slate-200 p-3 rounded-xl outline-none focus:ring-2 focus:ring-blue-500 transition-all">
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full bg-blue-600 text-white p-3.5 rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-200 active:scale-[0.98] transition-all">
                        ចូលប្រើប្រាស់ 
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
<!-- dhsfshaljkkj -->