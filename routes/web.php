<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ParentsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\FeesController;
use App\Http\Controllers\RoleAssign;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\FeeDepositController;
use Illuminate\Support\Facades\Auth;

Auth::routes();
Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
Route::get('/profile/edit', [HomeController::class, 'profileEdit'])->name('profile.edit');
Route::put('/profile/update', [HomeController::class, 'profileUpdate'])->name('profile.update');
Route::post('/profile/changepassword', [HomeController::class, 'changePassword'])->name('profile.changePassword');
Route::middleware(['role:Admin'])->group(function () {
    Route::resource('holiday', HolidayController::class)->except(['index']);
    Route::resource('notice', NoticeController::class)->except(['index']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('holiday', [HolidayController::class, 'index'])->name('holiday.index');
    Route::get('notice', [NoticeController::class, 'index'])->name('notice.index');
    Route::get('notice/{notice}', [NoticeController::class, 'show'])->name('notice.show');
    Route::resource('complaint', ComplaintController::class);
    Route::get('/results/{id}/download', [ResultController::class, 'downloadPdf'])->name('result.download');
    Route::get('student-result/{result_id}', [ResultController::class, 'studentResult'])->name('exam.student-result');
});


Route::middleware(['auth', 'role:Admin'])->group(function () {
    Route::get('/roles-permissions', [RolePermissionController::class, 'roles'])->name('roles-permissions');
    Route::get('/role-create', [RolePermissionController::class, 'createRole'])->name('role.create');
    Route::post('/role-store', [RolePermissionController::class, 'storeRole'])->name('role.store');
    Route::get('/role-edit/{id}', [RolePermissionController::class, 'editRole'])->name('role.edit');
    Route::put('/role-update/{id}', [RolePermissionController::class, 'updateRole'])->name('role.update');
    Route::get('/db-backup', [HomeController::class, 'dbBackup'])->name('db-backup');
    Route::get('/refresh-system', [HomeController::class, 'refresh'])->name('refresh.system');

    Route::get('/permission-create', [RolePermissionController::class, 'createPermission'])->name('permission.create');
    Route::post('/permission-store', [RolePermissionController::class, 'storePermission'])->name('permission.store');
    Route::get('/permission-edit/{id}', [RolePermissionController::class, 'editPermission'])->name('permission.edit');
    Route::put('/permission-update/{id}', [RolePermissionController::class, 'updatePermission'])->name('permission.update');
    Route::get('exam-result/{id}', [ResultController::class, 'examResult'])->name('result.exam-result');
    Route::get('exam/{exam_id}/class/{class_id}/result', [ResultController::class, 'classResult'])->name('exam.class-result');
    Route::post('assign-subject-teacher-to-class', [GradeController::class, 'storeAssignedSubjectTeacher'])->name('store.class.assign.subject');

    Route::resource('assignrole', RoleAssign::class);
    Route::resource('classes', GradeController::class);
    Route::resource('subject', SubjectController::class);
    Route::resource('teacher', TeacherController::class);
    Route::resource('parents', ParentsController::class);
    Route::resource('student', StudentController::class);
    Route::resource('driver', DriverController::class);
    Route::resource('exam', ExamController::class);
    Route::resource('item', ItemController::class);
    Route::resource('vendor', VendorController::class);
    Route::resource('inventory', InventoryController::class);
    Route::resource('fees', FeesController::class)->only(['index', 'create', 'store', 'destroy']);;
    Route::resource('sale', SaleController::class);
    Route::resource('transport', TransportController::class);
    Route::resource('feeDeposit', FeeDepositController::class);
    Route::get('/search-students', [FeeDepositController::class, 'searchStudent'])->name('feeDeposit.searchStudent');
    Route::post('store-return-sale-items', [SaleController::class, 'storeReturnSaleItems'])->name('sale.store-return');
    Route::get('search-bill-no', [SaleController::class, 'searchBillNo'])->name('sale.search-billno');
    Route::get('show-return-details/{id}', [SaleController::class, 'showReturnDetails'])->name('return.show');
    Route::get('returns', [SaleController::class, 'returnIndex'])->name('sale.return-index');
    Route::get('/get-inventory-price', [InventoryController::class, 'getInventoryPrice'])->name('inventory.price');
    Route::get('/download-sale-invoice/{id}', [SaleController::class, 'donwloadSaleInvoice'])->name('sale.invoice-download');
    Route::get('/download-return-invoice/{id}', [SaleController::class, 'donwloadReturnInvoice'])->name('return.invoice-download');
    Route::get('/download-inventory-invoice/{id}', [InventoryController::class, 'donwloadInventoryInvoice'])->name('inventory.invoice-download');
    Route::post('store-item-ajax', [ItemController::class, 'storeItemAjax'])->name('item.store_ajax');
    Route::get('/class/{id}/detail', [GradeController::class, 'viewDetail'])->name('class.view.detail');
    Route::post('assign-class-teacher-to-class', [GradeController::class, 'storeClassTeacherToClass'])->name('store.class.teacher');
    Route::post('change-subject-teacher-of-class', [GradeController::class, 'changeSubjectTeacherOfClass'])->name('change.subject.teacher');
    Route::delete('/class/{class_id}/remove-subject/{subject_id}', [GradeController::class, 'removeSubjectFromClass'])->name('remove.subject.from.class');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('fee-structure', [FeesController::class, 'index_structure'])->name('fees.structure.index');
    Route::get('create-fee-structure', [FeesController::class, 'create_fee_structure'])->name('fees.structure.create');
    Route::get('edit-fee-structure/{id}', [FeesController::class, 'edit_fee_structure'])->name('fees.structure.edit');
    Route::post('store-fee-structure', [FeesController::class, 'store_fee_structure'])->name('fees.structure.store');
    Route::post('update-fee-structure/{id}', [FeesController::class, 'update_fee_structure'])->name('fees.structure.update');
});
Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::group(['middleware' => ['auth', 'role:Teacher']], function () {
    Route::post('store-attendance', [AttendanceController::class, 'store'])->name('teacher.attendance.store');
    Route::get('attendance-create/{classid}', [AttendanceController::class, 'createByTeacher'])->name('teacher.attendance.create');
    Route::get('attendance-list-for-teacher', [AttendanceController::class, 'attendanceListForTeacher'])->name('teacher.attendance.list');
    Route::resource('result', ResultController::class);
});

Route::group(['middleware' => ['auth', 'role:Parent']], function () {
    Route::get('attendance/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('exam-result-for-parents', [ResultController::class, 'parentIndex'])->name('result.parent-index');
});

Route::group(['middleware' => ['auth', 'role:Student']], function () {});
