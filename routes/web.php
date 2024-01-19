<?php

use App\Http\Controllers\AuthControllers\LoginController;
use App\Http\Controllers\AuthControllers\PasswordController;
use App\Http\Controllers\Catalogs\AcademicYearController;
use App\Http\Controllers\Catalogs\ChangeStatusController;
use App\Http\Controllers\Catalogs\DocumentTypeController;
use App\Http\Controllers\Catalogs\EducationTypeController;
use App\Http\Controllers\Catalogs\EducationYearController;
use App\Http\Controllers\Catalogs\LevelController;
use App\Http\Controllers\Catalogs\SchoolController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\GeneralControllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckLoginMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    return redirect()->route('dashboard');
});
Route::get('/home', function () {
    Auth::logout();
    return redirect()->route('login');
});

Route::prefix('login')->group(function () {
    Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/', [LoginController::class, 'login']);
});

Route::prefix('password')->group(function () {
    Route::get('/forgot', [PasswordController::class, 'showForgetPassword'])->name('ForgetPassword');
    Route::post('/getToken', [PasswordController::class, 'sendToken'])->name('sendToken');
    Route::get('/reset/{token}', [PasswordController::class, 'showResetPassword']);
    Route::post('/reset', [PasswordController::class, 'resetPassword']);
    Route::post('/change', [PasswordController::class, 'changePassword']);
});

Route::get('/captcha', [LoginController::class, 'getCaptcha'])->name('captcha');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(CheckLoginMiddleware::class)->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    //Catalogs
    Route::resource('Schools', SchoolController::class)->middleware('role:Super Admin');
    Route::resource('DocumentTypes', DocumentTypeController::class)->middleware('role:Super Admin');
    Route::resource('EducationTypes', EducationTypeController::class)->middleware('role:Super Admin');
    Route::resource('Levels', LevelController::class)->middleware('role:Super Admin');
    Route::resource('AcademicYears', AcademicYearController::class)->middleware('role:Super Admin');

    Route::resource('roles', RoleController::class)->middleware('role:Super Admin');
    Route::resource('users', UserController::class)->middleware('can:access-SuperAdmin-and-SchoolAdmin');
    Route::post('users/change_password', [UserController::class, 'changeUserPassword'])->middleware('can:access-SuperAdmin-and-SchoolAdmin');
    Route::post('users/change_user_general_information', [ProfileController::class, 'changeUserGeneralInformation'])->middleware('can:access-SuperAdmin-and-SchoolAdmin');
    Route::post('users/change_rules', [ProfileController::class, 'changeUserRole'])->middleware('can:access-SuperAdmin');
    Route::post('users/change_student_information', [UserController::class, 'changeStudentInformation'])->middleware('can:access-SuperAdmin');
    Route::post('users/change_school_admin_information', [UserController::class, 'changeSchoolAdminInformation'])->middleware('can:access-SuperAdmin');
    Route::get('/searchUsers', [UserController::class, 'searchUser'])->middleware('can:access-SuperAdmin-and-SchoolAdmin')->name('searchUser');

    Route::prefix('Documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::get('/Show/{user_id}', [DocumentController::class, 'showUserDocuments'])->middleware('can:access-SuperAdmin-and-SchoolAdmin');
        Route::post('/Create/{user_id}', [DocumentController::class, 'createDocumentForUser'])->middleware('can:access-SuperAdmin-and-SchoolAdmin');
        Route::post('/Create', [DocumentController::class, 'createDocument']);
        Route::post('/Edit/{id}', [DocumentController::class, 'editUserDocuments'])->middleware('can:access-SuperAdmin-and-SchoolAdmin');
        Route::post('/Delete/{document_id}', [DocumentController::class, 'deleteUserDocument'])->middleware('can:access-SuperAdmin-and-SchoolAdmin');
    });

    Route::prefix('Profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::post('/EditMyProfile', [ProfileController::class, 'editMyProfile'])->name('EditMyProfile');
    });

    Route::get('/import-excel', [ExcelController::class, 'index']);
    Route::post('/importUsers', [ExcelController::class, 'importUsers'])->name('excel.importUsers');
    Route::post('/importDocumentTypes', [ExcelController::class, 'importDocumentTypes'])->name('excel.importDocumentTypes');
    Route::post('/importDocuments', [ExcelController::class, 'importDocuments'])->name('excel.importDocuments');

});
