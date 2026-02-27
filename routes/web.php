<?php

use App\Http\Controllers\ADLController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ElderlyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CGController;
use App\Models\BarthelAdl;
use App\Models\CareGiver;
use App\Http\Controllers\ADLExportController;
use App\Http\Controllers\CGExportController;
use App\Http\Controllers\TAIController;
use App\Http\Controllers\PerformanceReportController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineLotController;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider and all of them will | be assigned to the "web" middleware group. Make something great! | */

Route::get('/', function () {
    $sliders = App\Models\Slider::orderBy('id', 'desc')->get();
    $news = App\Models\News::orderBy('id', 'desc')->get();

    $adlAssessmentCount = BarthelAdl::count();
    $cgAssessmentCount = CareGiver::count();

    $adlGroupCounts = [
        'กลุ่มติดสังคม' => BarthelAdl::where('Group_ADL', 'กลุ่มติดสังคม')->count(),
        'กลุ่มติดบ้าน' => BarthelAdl::where('Group_ADL', 'กลุ่มติดบ้าน')->count(),
        'กลุ่มติดเตียง' => BarthelAdl::where('Group_ADL', 'กลุ่มติดเตียง')->count(),
    ];

    return view('welcome', compact('sliders', 'news', 'adlAssessmentCount', 'cgAssessmentCount', 'adlGroupCounts'));
})->name('welcome');

Route::get('/news/{id}', function ($id) {
    $newsItem = App\Models\News::findOrFail($id);
    return view('layout.newshow', compact('newsItem'));
})->name('news.show');





Route::controller(AuthController::class)->group(function () {

    Route::get('homepage', 'Homepage');
    Route::get('login', 'login');
    Route::post('/login', 'loginUser')->name('login.submit');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('dashboard-Doctor', 'Dashboard_Dcotor');

    // แสดงฟอร์มขอรีเซ็ทรหัสผ่าน
    Route::get('password/request', 'showPasswordRequestForm')->name('password.request');
    // ส่งรหัสยืนยันไปทางอีเมล
    Route::post('password/verify', 'sendVerificationCode')->name('password.verify');
    // แสดงฟอร์มกรอกรหัสยืนยันและรีเซ็ทรหัสผ่าน
    Route::post('password/verify-code', 'verifyCode')->name('password.verify-code');
    // บันทึกกรหัสผ่านใหม่
    Route::post('password/reset', 'resetPassword')->name('password.reset');
});






Route::get('/contact', function () {
    return view('layout.contact');
})->name('contact');
Route::get('/about', function () {
    return view('layout.about');
})->name('about');
Route::get('/about/history', function () {
    return view('layout.history');
})->name('history');
Route::get('/about/vision', function () {
    return view('layout.vision');
})->name('vision');

Route::get('/about/personnel', [PersonnelController::class, 'showPersonnel'])->name('personnel');



Route::get('error', function () {
    return view('error.error');
});


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////Middleware
Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('profile-user', [ProfileController::class, 'showProfile'])->name('profile-user');
    Route::get('edit-profile', [ProfileController::class, 'editProfile'])->name('edit-profile');
    Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->name('update-profile');
});

Route::middleware(['CheckLogin', 'IsAdmin'])->group(function () {
    // User Management
    Route::get('register-user', [AdminController::class, 'registerUser'])->name('user.register');
    Route::post('register-submit', [AdminController::class, 'submitUser'])->name('register.submit');
    Route::delete('user-delete/{id}', [AdminController::class, 'deleteUser'])->name('user.delete');
    Route::get('user-edit/{id}', [AdminController::class, 'editUser'])->name('user.edit');
    Route::put('user-update/{id}', [AdminController::class, 'updateUser'])->name('user.update');


    // Reports
    Route::get('/admin/report-user-pdf', [AdminController::class, 'ReportUser'])->name('admin.report-user');
    Route::get(
        '/admin/report-user-pdf-content',
        function () {
            return view('admin.report-admin');
        }
    );

    // Layout & Settings
    Route::get('layout-admin', [AdminController::class, 'ShowlayoutAdmin'])->name('admin.layout-admin');

    // News Management
    Route::post('news/store', [AdminController::class, 'storeNews'])->name('admin.news.store');
    Route::put('news/{id}', [AdminController::class, 'updateNews'])->name('admin.news.update');
    Route::delete('news/{id}', [AdminController::class, 'destroyNews'])->name('admin.news.destroy');

    // Slider Management
    Route::post('sliders/store', [AdminController::class, 'storeSlider'])->name('admin.sliders.store');
    Route::put('sliders/{id}', [AdminController::class, 'updateSlider'])->name('admin.sliders.update');
    Route::delete('sliders/{id}', [AdminController::class, 'destroySlider'])->name('admin.sliders.destroy');

    // Role Impersonation (Admin Only)
    Route::get('switch-role/{role}', [AdminController::class, 'switchRole'])->name('admin.switch-role');
});

// Revert Role is outside IsAdmin because when impersonating, they might fail IsAdmin check
Route::middleware(['CheckLogin'])->group(function () {
    Route::get('revert-role', [AdminController::class, 'revertRole'])->name('admin.revert-role');
});

Route::middleware(['CheckLogin', 'IsStaff'])->group(function () {
    // Guided Workflow
    Route::get('staff/workflow/start', [\App\Http\Controllers\StaffWorkflowController::class, 'start'])->name('staff.workflow.start');

    // Elderly Management
    Route::get('add-elderly', [ElderlyController::class, 'Addelderly'])->name('add-elderly');
    Route::post('/store-elderly', [ElderlyController::class, 'Storeelderly'])->name('store-elderly');
    Route::get('edit-elderly/{id}', [ElderlyController::class, 'Editelderly'])->name('edit-elderly');
    Route::put('/update-elderly/{id}', [ElderlyController::class, 'Updateelderly'])->name('update-elderly');
    Route::delete('/delete-elderly/{id}', [ElderlyController::class, 'Deleteelderly'])->name('delete-elderly');
    Route::get('search-location/{id}', [ElderlyController::class, 'searchLocation'])->name('search-location');
    Route::get('elderly-profile/{id}', [ElderlyController::class, 'showProfile'])->name('elderly.profile');
    Route::get('check-assessment-today/{id}', [ElderlyController::class, 'checkAssessmentToday'])->name('check.assessment.today');

    // ADL Assessment
    Route::get('adl-show', [ADLController::class, 'index'])->name('adl.index');
    Route::get('adl-elderly', [ADLController::class, 'create'])->name('adl.create');
    Route::post('/adl/submit', [ADLController::class, 'submitADL'])->name('adl.submit');
    Route::get('adl-edit/{id}', [ADLController::class, 'edit'])->name('adl.edit');
    Route::patch('adl-update/{id}', [ADLController::class, 'update'])->name('adl.update');
    Route::delete('adl-destroy/{id}', [ADLController::class, 'destroy'])->name('adl.destroy');

    // Care Giver Assessment
    Route::get('cg-show', [CGController::class, 'index'])->name('cg.index');
    Route::get('cg-create', [CGController::class, 'create'])->name('cg.create');
    Route::post('cg-store', [CGController::class, 'store'])->name('cg.store');
    Route::get('cg-edit/{id}', [CGController::class, 'edit'])->name('cg.edit');
    Route::put('cg-update/{id}', [CGController::class, 'update'])->name('cg.update');
    Route::delete('cg-destroy/{id}', [CGController::class, 'destroy'])->name('cg.destroy');
    Route::get('get-elderly-details/{elderlyId}', [CGController::class, 'getElderlyDetails'])->name('get-elderly-details');

    // Activity Care Giver
    Route::get('acg-show', [CGController::class, 'showACG'])->name('acg.index');
    Route::get('acg-create', [CGController::class, 'createActivity'])->name('activities.create');
    Route::post('/acg-store', [CGController::class, 'storeActivity'])->name('activities.store');
    Route::get('acg-edit/{id}', [CGController::class, 'editActivity'])->name('acg.edit');
    Route::patch('/acg-update/{id}', [CGController::class, 'updateActivity'])->name('acg.update');
    Route::delete('/acg-destroy/{id}', [CGController::class, 'destroyActivity'])->name('acg.destroy');

    Route::get('tai-show', [TAIController::class, 'index'])->name('tai.index');
    Route::get('tai-edit/{id}', [TAIController::class, 'edit'])->name('tai.edit');
    Route::patch('tai-update/{id}', [TAIController::class, 'update'])->name('tai.update');
    Route::delete('tai-destroy/{id}', [TAIController::class, 'destroy'])->name('tai.destroy');

    // Note: Care Instructions routing was migrated out of IsStaff mapping to Centralized RBAC Map.

    // Performance Report
    Route::get('performance-report', [PerformanceReportController::class, 'index'])->name('performanceReport.index');
    Route::get('performance-report/create', [PerformanceReportController::class, 'create'])->name('performanceReport.create');
    Route::post('performance-report/store', [PerformanceReportController::class, 'store'])->name('performanceReport.store');
    Route::get('performance-report/{id}', [PerformanceReportController::class, 'show'])->name('performanceReport.show');
    Route::get('performance-report/{id}/edit', [PerformanceReportController::class, 'edit'])->name('performanceReport.edit');
    Route::put('performance-report/{id}', [PerformanceReportController::class, 'update'])->name('performanceReport.update');
    Route::delete('performance-report/{id}', [PerformanceReportController::class, 'destroy'])->name('performanceReport.destroy');
    Route::get('performance-report/data/{elderly}', [PerformanceReportController::class, 'getPerformanceData'])->name('performanceReport.data');

    // Monthly Reports
    Route::get('/report/monthly-summary', [ReportController::class, 'MonthlySummary'])->name('report.monthly.summary');

    // Reports
    Route::get('/report-all-adl', [ADLController::class, 'ReportADLAll'])->name('report.all.adl');
    Route::get('/report-adl/{id}', [ADLController::class, 'ReportADL'])->name('report.adl');
    Route::get('/report-all-cg', [ReportController::class, 'ReportCGAll'])->name('report.all.cg');
    Route::get('report-cg/{id}', [ReportController::class, 'ReportCG'])->name('report.cg');
    Route::get('report-all-acg', [ReportController::class, 'ReportACGAll'])->name('report.all.acg');
    Route::get('report-acg/{id}', [ReportController::class, 'ReportACG'])->name('report.acg');
    Route::get('/report-all-tai', [ReportController::class, 'ReportTAIAll'])->name('report.all.tai');
    Route::get('report-ci-confirm', [ReportController::class, 'ReportCIConfirm'])->name('report.ci.confirm');
    Route::get('/report-ci-single/{id}', [ReportController::class, 'ReportCI_Single'])->name('report.ci.single');
    Route::get('elderly-report', [ElderlyController::class, 'showReport'])->name('elderly-report');

    // PDF Reports (Direct PDF links)
    Route::get('report-tai-pdf/{id}', [ReportController::class, 'ReportTAI'])->name('report.tai.pdf');
    Route::get('report-adl-pdf/{id}', [ReportController::class, 'ReportADL'])->name('report.adl.pdf');
    Route::get('report-acg-pdf/{id}', [ReportController::class, 'ReportACG'])->name('report.acg.pdf');
    Route::get('performance-report/{id}/export-pdf', [ReportController::class, 'ReportPerformanceReport'])->name('performanceReport.exportPDF');

    // Export Functions
    Route::get('/export-adl', [ADLExportController::class, 'export'])->name('adl.export');
    Route::get('/export-cg', [CGExportController::class, 'export'])->name('cg.export');
    Route::get('/export-tai', [TAIController::class, 'ExportTAI'])->name('tai.export');

});

Route::middleware(['CheckLogin', 'IsPharmacist'])->group(function () {
    // Medicines Management
    Route::resource('medicines', MedicineController::class)->except(['show']);
    Route::resource('medicines.lots', MedicineLotController::class)->only(['create', 'store']);
});


Route::middleware(['auth'])->group(function () {
    // Care Instructions (RBAC Centralized)
    Route::get('/care-instructions', [\App\Http\Controllers\CareInstructionController::class, 'index'])->name('care_instructions.index');
    Route::get('/care-instructions/create', [\App\Http\Controllers\CareInstructionController::class, 'create'])->name('care_instructions.create');
    Route::post('/care-instructions', [\App\Http\Controllers\CareInstructionController::class, 'store'])->name('care_instructions.store');
    Route::get('/care-instructions/{id}/edit', [\App\Http\Controllers\CareInstructionController::class, 'edit'])->name('care_instructions.edit');
    Route::put('/care-instructions/{id}', [\App\Http\Controllers\CareInstructionController::class, 'update'])->name('care_instructions.update');

    // PDF Reports (Accessible by Doctor & Staff)
    Route::get('report-ci-pdf/{id}', [ReportController::class, 'ReportCI_Single'])->name('report.ci.pdf');
    Route::get('report-ci-all-pdf', [ReportController::class, 'ReportCI_All'])->name('report.ci.all.pdf');
    Route::delete('/care-instructions/{id}', [\App\Http\Controllers\CareInstructionController::class, 'destroy'])->name('care_instructions.destroy');
    Route::put('/care-instructions/{id}/confirm', [\App\Http\Controllers\CareInstructionController::class, 'confirm'])->name('care_instructions.confirm');
    Route::put('/care-instructions/{id}/unconfirm', [\App\Http\Controllers\CareInstructionController::class, 'unconfirm'])->name('care_instructions.unconfirm');
    Route::put('/care-instructions/{id}/dispense', [\App\Http\Controllers\CareInstructionController::class, 'dispense'])->name('care_instructions.dispense');
});

// Doctor-only roles no longer need a dedicated routing block as 
// their functionality spans via RBAC into CareInstructionController and Dashboard.
