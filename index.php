<?php session_start(); ?>
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>ចូលប្រើប្រាស់ប្រព័ន្ធ - School CMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro&display=swap" rel="stylesheet">
    <style>body { font-family: 'Kantumruy Pro', sans-serif; }</style>
</head>
<body class="bg-slate-100 h-screen" >
    <div class="text-center text-[30px] pt-[100px] fw-bold text-blue-700">វិទ្យាល័យលំដាប់ពិភពលោក</div>
    <div class=" flex items-center justify-center pt-[50px]">   
        <div class="bg-white p-10 rounded-2xl shadow-2xl w-full max-w-md">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-slate-800">Welcome</h1>
            </div>
            <form action="actions/auth/login_user.php" method="POST" class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700">តួនាទី (Role)</label>
                    <select name="role" class="w-full border p-3 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                      
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div>
                    <input type="text" name="username" placeholder="ឈ្មោះអ្នកប្រើប្រាស់" required 
                        class="w-full border p-3 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <input type="password" name="password" placeholder="លេខសម្ងាត់" required 
                        class="w-full border p-3 rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded-lg font-bold hover:bg-blue-700 transition">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>