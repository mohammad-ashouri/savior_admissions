<?php

use App\Http\Controllers\AuthControllers\LoginController;
use App\Http\Controllers\AuthControllers\PasswordController;
use App\Http\Controllers\Catalogs\DocumentTypeController;
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
    Route::resource('DocumentTypes', DocumentTypeController::class)->middleware('role:Admin');


    Route::resource('roles', RoleController::class)->middleware('role:Admin');
    Route::resource('users', UserController::class)->middleware('role:Admin');
    Route::post('users/change_password', [UserController::class, 'changeUserPassword'])->middleware('role:Admin');
    Route::post('users/change_user_general_information', [ProfileController::class, 'changeUserGeneralInformation'])->middleware('role:Admin');
    Route::post('users/change_rules', [ProfileController::class, 'changeUserRole'])->middleware('role:Admin');

    Route::prefix('Documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::get('/Show/{user_id}', [DocumentController::class, 'showUserDocuments'])->middleware('role:Admin');
        Route::post('/Create/{user_id}', [DocumentController::class, 'createDocument'])->middleware('role:Admin');
        Route::post('/Create', [DocumentController::class, 'createDocument']);
        Route::post('/Edit/{id}', [DocumentController::class, 'editUserDocuments'])->middleware('role:Admin');
        Route::post('/Delete/{document_id}', [DocumentController::class, 'deleteUserDocument'])->middleware('role:Admin');
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
