<?php 
require_once '../../config/db.php';
require_once '../../config/session.php';
is_logged_in();

include '../../includes/header.php';
include '../../includes/sidebar.php';
?>

<main class="flex-1 p-8 bg-gray-50">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">របាយការណ៍សរុប</h1>
    
    <div class="bg-white p-8 rounded-xl shadow-custom text-center">
        <div class="inline-block p-4 rounded-full bg-blue-50 text-blue-600 mb-4">
            <span class="text-4xl">📊</span>
        </div>
        <h2 class="text-xl font-semibold mb-2">ទិន្នន័យស្ថិតិកំពុងរៀបចំ</h2>
        <p class="text-slate-500">លោកអ្នកអាចទាញយករបាយការណ៍ជា PDF ឬ Excel ក្នុងពេលឆាប់ៗនេះ។</p>
    </div>
</main>