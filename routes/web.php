<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthControllers\LoginController;
use App\Http\Controllers\AuthControllers\PasswordController;
use App\Http\Controllers\BranchInfo\AcademicYearClassController;
use App\Http\Controllers\BranchInfo\ApplicationTimingController;
use App\Http\Controllers\BranchInfo\InterviewController;
use App\Http\Controllers\BranchInfo\StudentController;
use App\Http\Controllers\Catalogs\AcademicYearController;
use App\Http\Controllers\Catalogs\DocumentTypeController;
use App\Http\Controllers\Catalogs\EducationTypeController;
use App\Http\Controllers\Catalogs\LevelController;
use App\Http\Controllers\Catalogs\SchoolController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\Finance\ApplicationReservationController;
use App\Http\Controllers\Finance\DiscountsController;
use App\Http\Controllers\Finance\TuitionController;
use App\Http\Controllers\GeneralControllers\PDFExportController;
use App\Http\Controllers\GeneralControllers\ProfileController;
use App\Http\Controllers\PaymentController;
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
    if (! Auth::check()) {
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
    Route::middleware('role:Super Admin')->group(function () {
        Route::resources([
            'Schools' => SchoolController::class,
            'DocumentTypes' => DocumentTypeController::class,
            'EducationTypes' => EducationTypeController::class,
            'Levels' => LevelController::class,
            'AcademicYears' => AcademicYearController::class,
        ]);

        $resources = ['Schools', 'DocumentTypes', 'EducationTypes', 'Levels', 'AcademicYears'];
        foreach ($resources as $resource) {
            Route::get("$resource/search", [ucfirst($resource).'Controller', 'search']);
        }
    });

    //Branch Info
    Route::resource('AcademicYearClasses', AcademicYearClassController::class);
    Route::get('/GetLevelsForAcademicYearClass', [AcademicYearClassController::class, 'levels']);
    Route::get('/GetAcademicYearStarttimeAndEndtime', [AcademicYearClassController::class, 'academicYearStarttimeAndEndtime']);
    Route::resource('ApplicationTimings', ApplicationTimingController::class);
    Route::get('/GetInterviewersForApplications', [ApplicationTimingController::class, 'interviewers']);
    Route::resource('Interviews', InterviewController::class);
    Route::get('/SetInterview/{id}', [InterviewController::class, 'GetInterviewForm']);
    Route::post('/SetInterview', [InterviewController::class, 'SetInterview']);

    //Finance
    Route::resource('ReservationInvoices', ApplicationReservationController::class);
    Route::post('ChangeApplicationPaymentStatus', [ApplicationReservationController::class, 'changeApplicationPaymentStatus']);
    Route::get('SearchReservationInvoices', [ApplicationReservationController::class, 'searchReservationInvoices'])->name('SearchReservationInvoices');
    Route::resource('Tuition', TuitionController::class);
    Route::post('ChangeTuitionPrice', [TuitionController::class, 'changeTuitionPrice']);
    Route::resource('Discounts', DiscountsController::class);
    Route::post('ChangeDiscountPercentage', [DiscountsController::class, 'changeDiscountPercentage']);
    Route::post('ChangeDiscountStatus', [DiscountsController::class, 'changeDiscountStatus']);

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::post('users/change_password', [UserController::class, 'changeUserPassword'])->middleware('can:access-SuperAdmin-and-Principal');
    Route::post('users/change_user_general_information', [ProfileController::class, 'changeUserGeneralInformation'])->middleware('can:access-SuperAdmin-and-Principal');
    Route::post('users/change_rules', [ProfileController::class, 'changeUserRole']);
    Route::post('users/change_school_admin_information', [UserController::class, 'changePrincipalInformation'])->middleware('can:access-SuperAdmin');
    Route::get('/searchUsers', [UserController::class, 'searchUser'])->middleware('can:access-SuperAdmin-and-Principal')->name('searchUser');

    Route::resource('Students', StudentController::class);

    //Applications
    Route::resource('Applications', ApplicationController::class);
    Route::post('Applications/RemoveFromReserve/{id}', [ApplicationController::class, 'removeFromReserve']);
    Route::post('Applications/ChangeInterviewStatus/{id}', [ApplicationController::class, 'changeApplicationStatus']);
    Route::get('GetAcademicYearsByLevel', [ApplicationController::class, 'getAcademicYearsByLevel']);
    Route::get('GetApplicationsByAcademicYear', [ApplicationController::class, 'getApplicationsByAcademicYear']);
    Route::get('CheckDateAndTimeToBeFreeApplication', [ApplicationController::class, 'checkDateAndTimeToBeFreeApplication']);
    Route::post('ApplicationPayment', [ApplicationController::class, 'preparationForApplicationPayment']);
    Route::get('PrepareToPayApplication/{application_id}', [ApplicationController::class, 'prepareToPay'])->name('PrepareToPayApplication');
    Route::post('PayApplicationFee', [ApplicationController::class, 'payApplicationFee']);

    Route::post('student/change_information', [StudentController::class, 'changeInformation']);

    Route::prefix('Documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index']);
        Route::get('/Show/{user_id}', [DocumentController::class, 'showUserDocuments'])->middleware('can:access-SuperAdmin-and-Principal');
        Route::post('/Create/{user_id}', [DocumentController::class, 'createDocumentForUser'])->middleware('can:access-SuperAdmin-and-Principal');
        Route::post('/Create', [DocumentController::class, 'createDocument']);
        Route::post('/Edit/{id}', [DocumentController::class, 'editUserDocuments'])->middleware('can:access-SuperAdmin-and-Principal');
        Route::post('/Delete/{document_id}', [DocumentController::class, 'deleteUserDocument'])->middleware('can:access-SuperAdmin-and-Principal');
    });

    Route::prefix('Profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::post('/EditMyProfile', [ProfileController::class, 'editMyProfile'])->name('EditMyProfile');
    });

    //Exports
    //PDF
    Route::prefix('PDF')->group(function () {
        Route::get('/tuition_card', [PDFExportController::class, 'tuitionCardExport']);
    });

    //Payment
    Route::post('testpay',[PaymentController::class,'behpardakhtPayment']);


});
Route::get('/import-excel', [ExcelController::class, 'index']);
Route::post('/importUsers', [ExcelController::class, 'importUsers'])->name('excel.importUsers');
Route::post('/importDocumentTypes', [ExcelController::class, 'importDocumentTypes'])->name('excel.importDocumentTypes');
Route::post('/importDocuments', [ExcelController::class, 'importDocuments'])->name('excel.importDocuments');
Route::post('/importParentFathers', [ExcelController::class, 'importParentFathers'])->name('excel.importParentFathers');
Route::post('/importParentMothers', [ExcelController::class, 'importParentMothers'])->name('excel.importParentMothers');
