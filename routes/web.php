<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AuthControllers\LoginController;
use App\Http\Controllers\BranchInfo\AcademicYearClassController;
use App\Http\Controllers\BranchInfo\ApplicationTimingController;
use App\Http\Controllers\BranchInfo\EvidenceController;
use App\Http\Controllers\BranchInfo\InterviewController;
use App\Http\Controllers\BranchInfo\StudentController;
use App\Http\Controllers\Catalogs\AcademicYearController;
use App\Http\Controllers\Catalogs\DocumentTypeController;
use App\Http\Controllers\Catalogs\EducationTypeController;
use App\Http\Controllers\Catalogs\LevelController;
use App\Http\Controllers\Catalogs\SchoolController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\Finance\ApplicationReservationController;
use App\Http\Controllers\Finance\DiscountsController;
use App\Http\Controllers\Finance\TuitionController;
use App\Http\Controllers\Finance\TuitionPaymentController;
use App\Http\Controllers\GeneralControllers\PDFExportController;
use App\Http\Controllers\GeneralControllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SMSController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckIfProfileRegistered;
use App\Http\Middleware\CheckImpersonatePermission;
use App\Http\Middleware\CheckLoginMiddleware;
use App\Http\Middleware\NoCache;
use App\Http\Middleware\SettingsCheck;
use App\Livewire\Auth\CreateAccount;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\LostPassword;
use App\Livewire\BranchInfo\ConfirmAppliance;
use App\Livewire\Documents\Show as ShowUserDocuments;
use App\Livewire\Documents\UploadDocumentsParent\Create as UploadDocumentsParentCreate;
use App\Livewire\Documents\UploadDocumentsParent\Edit as UploadDocumentsParentEdit;
use App\Livewire\Documents\UploadDocumentsParent\Show as UploadDocumentsParentShow;
use App\Livewire\Temp\ReInsertion;
use App\Livewire\Tuition\TuitionInvoices\EditApplianceInvoices;
use App\Livewire\Users\PendingUserApprovals;
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

Route::get('/create-account', CreateAccount::class)->name('CreateAccount');

Route::get('login', Login::class)->name('login');
Route::get('forget-password', LostPassword::class)->name('ForgetPassword');

Route::get('/captcha', [LoginController::class, 'getCaptcha'])->name('captcha');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('web')->middleware(NoCache::class)->middleware(CheckLoginMiddleware::class)->group(function () {
    Route::group(['middleware' => ['auth', CheckImpersonatePermission::class]], function () {
        Route::impersonate();
    });
    Route::middleware(CheckIfProfileRegistered::class)->group(function () {
        Route::middleware(SettingsCheck::class)->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Catalogs
            Route::group([], function () {
                Route::resources([
                    'Schools' => SchoolController::class,
                    'DocumentTypes' => DocumentTypeController::class,
                    'EducationTypes' => EducationTypeController::class,
                    'Levels' => LevelController::class,
                    'AcademicYears' => AcademicYearController::class,
                ]);

                $resources = ['Schools', 'DocumentTypes', 'EducationTypes', 'Levels', 'AcademicYears'];
                foreach ($resources as $resource) {
                    Route::get("$resource/search", [ucfirst($resource) . 'Controller', 'search'])->name("$resource.search");
                }
            });

            // Get school educational charter
            Route::get('GetEducationalCharter', [SchoolController::class, 'EducationalCharter'])->name('EducationalCharter');

            // Search routes
            Route::get('/SearchRoles', [RoleController::class, 'search'])->name('Roles.search');

            // Branch Info
            Route::resource('AcademicYearClasses', AcademicYearClassController::class);
            Route::get('/GetLevelsForAcademicYearClass', [AcademicYearClassController::class, 'levels']);
            Route::get('/GetAcademicYearStarttimeAndEndtime', [AcademicYearClassController::class, 'academicYearStarttimeAndEndtime']);
            Route::get('/GetFinancialCharterFile', [AcademicYearClassController::class, 'academicYearFinancialCharterFile']);
            Route::resource('ApplicationTimings', ApplicationTimingController::class);
            Route::get('/GetInterviewersForApplications', [ApplicationTimingController::class, 'interviewers']);
            Route::get('/GetGrades', [ApplicationTimingController::class, 'grades']);

            Route::get('/Interviews/{form}/{id}/edit', [InterviewController::class, 'edit'])->name('interviews.edit');
            Route::get('/Interviews/{form}/{id}', [InterviewController::class, 'show'])->name('interviews.show');
            Route::resource('Interviews', InterviewController::class)->names([
                'index' => 'interviews.index',
            ]);
            Route::get('/SetInterview/{form}/{id}', [InterviewController::class, 'GetInterviewForm']);
            Route::get('/SearchInterviews', [InterviewController::class, 'searchInterviews'])->name('SearchInterviews');
            Route::post('/SetInterview', [InterviewController::class, 'SetInterview'])->name('interviews.SetInterview');
            Route::post('/SubmitAbsence', [InterviewController::class, 'submitAbsence'])->name('interviews.submitAbsence');

            // Finance
            Route::resource('ReservationInvoices', ApplicationReservationController::class);
            Route::post('ChangeApplicationPaymentStatus', [ApplicationReservationController::class, 'changeApplicationPaymentStatus']);
            Route::get('SearchReservationInvoices', [ApplicationReservationController::class, 'searchReservationInvoices'])->name('SearchReservationInvoices');

            Route::resource('Tuition', TuitionController::class);
            Route::post('ChangeTuitionPrice', [TuitionController::class, 'changeTuitionPrice'])->name('changeTuitionPrice');
            Route::get('TuitionsStatus', [TuitionController::class, 'tuitionsStatus'])->name('tuitionsStatus');
            Route::get('SearchTuitionsStatus', [TuitionController::class, 'searchTuitionsStatus'])->name('SearchTuitionStatus');
            Route::get('ShowApplianceInvoices/{appliance_id}', [TuitionPaymentController::class, 'applianceInvoices'])->name('applianceInvoices');
            Route::get('EditApplianceInvoices/{appliance_id}', EditApplianceInvoices::class)->name('applianceInvoices.edit');
            Route::get('AllTuitions/{academic_year?}', [TuitionController::class, 'allTuitions'])->name('allTuitions');

            // Pay Tuition
            Route::get('PayTuition/{student_id}', [TuitionController::class, 'payTuition'])->name('Tuitions.PayTuition');
            Route::post('PayTuition', [TuitionController::class, 'tuitionPayment'])->name('Tuitions.Pay');

            // Payment list
            Route::resource('TuitionInvoices', TuitionPaymentController::class);
            Route::get('searchTuitionInvoices', [TuitionPaymentController::class, 'search'])->name('searchTuitionInvoices');
            Route::post('TuitionInvoices/ChangeInvoiceStatus', [TuitionPaymentController::class, 'changeTuitionInvoiceStatus']);
            Route::post('TuitionInvoices/changeTuitionInvoiceDetails', [TuitionPaymentController::class, 'changeTuitionInvoiceDetails']);
            Route::post('TuitionInvoices/ChangeCustomTuitionInvoiceDetails', [TuitionPaymentController::class, 'changeCustomTuitionInvoiceDetails'])->name('ChangeCustomTuitionInvoiceDetails');
            Route::get('PayTuitionInstallment/{tuition_id}', [TuitionPaymentController::class, 'prepareToPayTuition']);
            Route::post('PayTuitionInstallment', [TuitionPaymentController::class, 'payTuition'])->name('TuitionInvoices.payTuition');
            Route::get('InvoicesDetails', [TuitionPaymentController::class, 'invoicesDetailsIndex']);
            Route::get('SearchInvoicesDetails', [TuitionPaymentController::class, 'searchInvoicesDetails'])->name('searchInvoicesDetails');

            Route::resource('Discounts', DiscountsController::class);
            Route::post('ChangeDiscountPercentage', [DiscountsController::class, 'changeDiscountPercentage']);
            Route::post('ChangeDiscountStatus', [DiscountsController::class, 'changeDiscountStatus']);
            Route::get('GetDiscountPercentage', [DiscountsController::class, 'getDiscountPercentage']);

            Route::resource('roles', RoleController::class);
            Route::get('users/pending_user_approvals', PendingUserApprovals::class)->name('pending-user-approvals');
            Route::resource('users', UserController::class);
            Route::post('users/change_password', [UserController::class, 'changeUserPassword']);
            Route::post('users/change_user_general_information', [ProfileController::class, 'changeUserGeneralInformation']);
            Route::post('users/change_rules', [ProfileController::class, 'changeUserRole']);
            Route::post('users/change_school_admin_information', [UserController::class, 'changePrincipalInformation'])->middleware('can:access-SuperAdmin');
            Route::get('/searchUsers', [UserController::class, 'searchUser'])->name('searchUser');

            // Students management
            Route::resource('Students', StudentController::class);
            Route::post('Students/uploadPersonalPicture', [StudentController::class, 'uploadPersonalPicture'])->name('UploadPersonalPicture');

            // Applications
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
                Route::get('/Show/{user_id}', ShowUserDocuments::class)->name('show-user-documents');
                Route::post('/Create/{user_id}', [DocumentController::class, 'createDocumentForUser'])->middleware('can:access-SuperAdmin-and-Principal');
                Route::post('/Create', [DocumentController::class, 'createDocument']);
                Route::post('/Edit/{id}', [DocumentController::class, 'editUserDocuments'])->middleware('can:access-SuperAdmin-and-Principal');
                Route::post('/Delete', [DocumentController::class, 'deleteUserDocument'])->middleware('can:access-SuperAdmin-and-Principal');
            });
            Route::get('UploadStudentDocumentByParent/{student_id}', UploadDocumentsParentCreate::class)->name('Document.UploadByParent');
            //            Route::post('UploadStudentDocumentByParent', [DocumentController::class, 'uploadStudentDocuments'])->name('Documents.UploadDocumentsByParent');
            Route::get('EditUploadedEvidences/{student_id}', UploadDocumentsParentEdit::class)->name('Document.EditUploadedEvidences');
            //            Route::post('EditUploadedEvidences', [DocumentController::class, 'updateStudentDocuments'])->name('Document.EditUploadedEvidences.update');

            // Application confirmation
            Route::get('ConfirmApplication', ConfirmAppliance::class)->name('Application.ConfirmApplicationList');
            Route::get('ConfirmApplication/{application_id}/{appliance_id}', [ApplicationController::class, 'showApplicationConfirmation']);
            Route::post('ConfirmApplication', [ApplicationController::class, 'confirmStudentAppliance'])->name('Application.ConfirmApplication');

            // Evidences confirmation
            Route::get('ConfirmEvidences', [EvidenceController::class, 'index'])->name('Evidences');
            Route::get('ConfirmEvidences/{appliance_id}', UploadDocumentsParentShow::class)->name('Evidences.show');
            Route::get('Evidences/show/{appliance_id}', UploadDocumentsParentShow::class)->name('Evidences.showEvidence');
            //            Route::post('ConfirmEvidences', [EvidenceController::class, 'confirmEvidences'])->name('Evidences.confirm');
            Route::post('ExtensionOfDocumentUpload', [EvidenceController::class, 'extensionOfDocumentUpload'])->name('Evidences.extensionOfDocumentUpload');

            // Student status
            Route::get('StudentStatuses', App\Livewire\BranchInfo\StudentStatus\Index::class)->name('StudentStatus');
            Route::get('SearchStudentApplianceStatuses', [StudentController::class, 'search'])->name('SearchStudentApplianceStatuses');
            Route::get('GetApplianceConfirmationInformation', [StudentController::class, 'getApplianceConfirmationInformation']);
            Route::get('StudentStatisticsReport', [StudentController::class, 'studentStatisticsReportIndex'])->name('StudentStatisticsReport');
            Route::get('SearchStudentStatisticsReport', [StudentController::class, 'searchStudentStatisticsReport'])->name('SearchStudentStatisticsReport');

            // Exports
            // PDF
            Route::prefix('PDF')->group(function () {
                Route::get('/tuition_card_en/{appliance_id}', [PDFExportController::class, 'tuitionCardEnExport'])->name('tuitionCard.en');
                Route::get('/tuition_card_fa/{appliance_id}', [PDFExportController::class, 'tuitionCardFaExport'])->name('tuitionCard.fa');
            });

        });

        // SMS
        Route::post('sendSMS', [SMSController::class, 'sendSMS'])->name('sms.send');

    });

    Route::prefix('Profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');
        Route::post('/EditMyProfile', [ProfileController::class, 'editMyProfile'])->name('EditMyProfile');
    });

    Route::get('re-insertion-data/fida-code', ReInsertion\FidaCode::class)->name('re-insertion-data.fida-code');
});

// Payments
Route::post('/VerifyApplicationPayment', [PaymentController::class, 'verifyApplicationPayment']);
Route::post('/VerifyTuitionPayment', [PaymentController::class, 'verifyTuitionPayment']);
Route::post('/VerifyTuitionInstallmentPayment', [PaymentController::class, 'verifyTuitionInstallmentPayment']);

// Route::get('/import-excel', [ExcelController::class, 'index']);
// Route::post('/importUsers', [ExcelController::class, 'importUsers'])->name('excel.importUsers');
// Route::post('/importDocumentTypes', [ExcelController::class, 'importDocumentTypes'])->name('excel.importDocumentTypes');
// Route::post('/importDocuments', [ExcelController::class, 'importDocuments'])->name('excel.importDocuments');
// Route::post('/importParentFathers', [ExcelController::class, 'importParentFathers'])->name('excel.importParentFathers');
// Route::post('/importParentMothers', [ExcelController::class, 'importParentMothers'])->name('excel.importParentMothers');
// Route::post('/importNewUsers', [ExcelController::class, 'importNewUsers'])->name('excel.importNewUsers');
// Route::get('/ExportExcelFromUsersMobile', [ExcelController::class, 'exportExcelFromUsersMobile'])->name('excel.importParentMothers');
// Route::get('/ExportExcelFromAllStudents', [ExcelController::class, 'exportExcelFromAllStudents']);
// Route::get('/ExportExcelFromAllGuardianWithStudents', [ExcelController::class, 'exportExcelFromAllGuardianWithStudents']);
