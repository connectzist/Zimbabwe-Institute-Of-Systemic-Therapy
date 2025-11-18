<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Semesters\ModuleController;
use App\Http\Controllers\Uploads\NoticeController;
use App\Http\Controllers\Uploads\TimetableController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Auth\LogInController;
use App\Http\Controllers\Transcripts\TranscriptController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Finance\FeesController;
use App\Http\Controllers\Registrations\RegistrationController;
use App\Http\Controllers\Courses\CourseController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\Admins\SuperAdminController;
use App\Http\Controllers\Admins\DiplomaAdminController;
use App\Http\Controllers\Auth\StudentProfileController;
use App\Http\Controllers\Dashboard\DashBoardController;
use App\Http\Controllers\Modules\DiplomaModulesController;
use App\Http\Controllers\Admins\CertificateAdminController;
use App\Http\Controllers\Modules\CertificateModuleController;
use App\Http\Controllers\Admins\AdvancedDiplomaAdminController;
use App\Http\Controllers\Library\LibraryController;
use App\Http\Controllers\Modules\AdvancedDiplomaModulesController;


                                // ZIST Portal System Routes

// Login 
Route::middleware(['guest:web'])->get('/', [LogInController::class, 'AdminLogin'])->name('admin_login');
Route::post('/admin/admin_login', [LogInController::class, 'loginUser'])->name('loginUser');
Route::get('/return-to-login', [LogInController::class, 'returnToLogin'])->name('return_to_login');


// Logout 
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Users 
Route::middleware(['auth.custom:web,SuperAdmin'])->prefix('users')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

// Dashboard 
Route::prefix('dashboard')->middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/dashboard', [DashBoardController::class, 'dashboard'])->name('dashboard.dashboard');
    Route::get('/users', [UserController::class, 'index'])->name('dashboard.users');
    Route::get('/courses', [DashBoardController::class, 'courses'])->name('dashboard.courses');
    Route::get('/student', [DashBoardController::class, 'student'])->name('dashboard.student');
    Route::get('/registration', [DashBoardController::class, 'registration'])->name('registration.registered_students');
    Route::get('/students_results', [DashBoardController::class, 'students_results'])->name('students_results.students_results');
    Route::get('/students_transcripts', [DashBoardController::class, 'students_transcripts'])->name('transcripts.view');
    Route::get('/students_uploads', [DashBoardController::class, 'students_uploads'])->name('uploads.view');
    Route::get('/course_modules', [DashBoardController::class, 'course_modules'])->name('course_modules.course_modules');
});

// Departmental Dashboards
Route::middleware(['auth.custom:web,AdvancedDiplomaAdmin'])->group(function () {
    Route::get('/advanced-diploma-dashboard', [DashBoardController::class, 'showAdvDiplodashboard'])->name('dashboard.advdiplodashboard');
});

Route::middleware(['auth.custom:web,CertificateAdmin'])->group(function () {
    Route::get('/certificate-dashboard', [DashBoardController::class, 'showCertdashboard'])->name('dashboard.certdashboard');
});

Route::middleware(['auth.custom:web,DiplomaAdmin'])->group(function () {
    Route::get('/diploma-dashboard', [DashBoardController::class, 'showDiplodashboard'])->name('dashboard.diplodashboard');
});


// Students
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    Route::get('/students/create', [StudentController::class, 'create'])->name('students.create');
    Route::post('/students', [StudentController::class, 'store'])->name('students.store');
    Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');
    Route::get('/students/{student}/edit', [StudentController::class, 'edit'])->name('students.edit');
    Route::put('/students/{student}', [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');
});

// Courses 
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');
});

// Fees Management 
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::prefix('finance')->group(function () {
    Route::get('/students/fees', [FeesController::class, 'index'])->name('fees.index');
    Route::get('/students/fees/create', [FeesController::class, 'create'])->name('fees.create');
    Route::post('/students/fees/store', [FeesController::class, 'store'])->name('fees.store');
    Route::get('/student/statement/{id}', [FeesController::class, 'show'])->name('fees.show');
    Route::get('/student/fees-statement/{id}/edit', [FeesController::class, 'edit'])->name('fees.edit');
    Route::put('/student/finance-updates/{id}', [FeesController::class, 'update'])->name('fees.update');
    Route::delete('/student/finance-destrory/{id}', [FeesController::class, 'destroy'])->name('fees.destroy');
    Route::get('/students/search', [FeesController::class, 'search'])->name('students.search');
    Route::get('/students/{id}/course', [FeesController::class, 'getCourse'])->name('students.course');
});
});

// Diploma modules
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/diploma', [DiplomaModulesController::class, 'index'])->name('diploma.index');
    Route::get('/diploma/create', [DiplomaModulesController::class, 'create'])->name('diploma.create');
    Route::post('/diploma', [DiplomaModulesController::class, 'store'])->name('diploma.store');
    Route::get('/diploma/{diplomaModule}', [DiplomaModulesController::class, 'show'])->name('diploma.show');
    Route::get('/diploma/{diplomaModule}/edit', [DiplomaModulesController::class, 'edit'])->name('diploma.edit');
    Route::put('/diploma/{diplomaModule}', [DiplomaModulesController::class, 'update'])->name('diploma.update');
    Route::delete('/diploma/{diplomaModule}', [DiplomaModulesController::class, 'destroy'])->name('diploma.destroy');
});

// Certificate modules
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/certificate', [CertificateModuleController::class, 'index'])->name('certificate.index');
    Route::get('/certificate/create', [CertificateModuleController::class, 'create'])->name('certificate.create');
    Route::post('/certificate', [CertificateModuleController::class, 'store'])->name('certificate.store');
    Route::get('/certificate/{certificateModule}', [CertificateModuleController::class, 'show'])->name('certificate.show');
    Route::get('/certificate/{certificateModule}/edit', [CertificateModuleController::class, 'edit'])->name('certificate.edit');
    Route::put('/certificate/{certificateModule}', [CertificateModuleController::class, 'update'])->name('certificate.update');
    Route::delete('/certificate/{certificateModule}', [CertificateModuleController::class, 'destroy'])->name('certificate.destroy');
});

//Advanced Diploma Modules
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/advanced_diploma', [AdvancedDiplomaModulesController::class, 'index'])->name('advanced_diploma.index');
    Route::get('/advanced_diploma/create', [AdvancedDiplomaModulesController::class, 'create'])->name('advanced_diploma.create');
    Route::post('/advanced_diploma', [AdvancedDiplomaModulesController::class, 'store'])->name('advanced_diploma.store');
    Route::get('/advanced_diploma/{advancedDiplomaModule}', [AdvancedDiplomaModulesController::class, 'show'])->name('advanced_diploma.show');
    Route::get('/advanced_diploma/{advancedDiplomaModule}/edit', [AdvancedDiplomaModulesController::class, 'edit'])->name('advanced_diploma.edit');
    Route::put('/advanced_diploma/{advancedDiplomaModule}', [AdvancedDiplomaModulesController::class, 'update'])->name('advanced_diploma.update');
    Route::delete('/advanced_diploma/{advancedDiplomaModule}', [AdvancedDiplomaModulesController::class, 'destroy'])->name('advanced_diploma.destroy');
});

// CertificateAdmin 
Route::middleware(['auth.custom:web,CertificateAdmin'])->group(function () {
    Route::get('/certificate_students', [CertificateAdminController::class, 'certificateStudents'])->name('certificate.certificate_students');
    Route::get('/certificate_modules', [CertificateAdminController::class, 'certificateModules'])->name('certificate.certificate_modules');
    Route::get('/cert_coursework', [CertificateAdminController::class, 'coursework'])->name('certificate.cert_coursework');
    Route::get('/cert_results', [CertificateAdminController::class, 'certresults'])->name('certificate.cert_results');
    Route::get('/certificate_results', [CertificateAdminController::class, 'certresults']);
    Route::get('/certificate_results/create', [CertificateAdminController::class, 'create'])->name('certificate_results.create');
    Route::get('/certificate_results/{id}', [CertificateAdminController::class, 'show'])->name('certificate_results.show');
    Route::put('/certificate_results/{id}', [CertificateAdminController::class, 'update'])->name('certificate_results.update');  
    Route::patch('/certificate_results/{id}', [CertificateAdminController::class, 'update'])->name('certificate_results.update'); 
    Route::delete('/certificate_results/{id}', [CertificateAdminController::class, 'destroy'])->name('certificate_results.destroy');
    Route::get('/certificate_results/{id}/certedit', [CertificateAdminController::class, 'certedit'])->name('certificate_results.certedit');
    Route::post('certificate_results/store', [CertificateAdminController::class, 'storeResults'])->name('certificate_results.store');
    Route::delete('/certificate-results/{id}', [CertificateAdminController::class, 'certresdestroy'])->name('certificate_results.certdelete');
    Route::get('/ccertificate_results/{student_id}', [CertificateAdminController::class, 'certshow'])->name('certificate_results.certshow');
});

// DiplomaAdmin 
Route::middleware(['auth.custom:web,DiplomaAdmin'])->group(function () {
    Route::get('/diploma_students', [DiplomaAdminController::class, 'diplomaStudents'])->name('diploma.diploma_students');
    Route::get('/diploma_modules', [DiplomaAdminController::class, 'modules'])->name('diploma.diploma_modules');
    Route::get('/diploma_coursework', [DiplomaAdminController::class, 'coursework'])->name('diploma.diploma_coursework');
    Route::get('/diploma_finalmodule', [DiplomaAdminController::class, 'finalmodule'])->name('diploma.diploma_finalmodule');
    Route::delete('/diploma_results/{id}', [DiplomaAdminController::class, 'diploma_resultdelete'])->name('diploma_results.diplomadelete');
    Route::delete('/diploma_final/record/{id}', [DiplomaAdminController::class, 'diploma_resultfinaldelete'])->name('diploma_results.diplomafinaldelete');
    Route::get('/diploma_results', [DiplomaAdminController::class, 'diplomaresults'])->name('diploma.diploma_results');
    Route::get('/diploma_results/create', [DiplomaAdminController::class, 'create'])->name('diploma_results.create');
    Route::post('/diploma_results', [DiplomaAdminController::class, 'storeResults'])->name('diploma_results.store');
    Route::get('/diploma_results/{id}/edit', [DiplomaAdminController::class, 'diplomaedit'])->name('diploma_results.diplomaedit');
    Route::get('/diploma_results/{id}', [DiplomaAdminController::class, 'show'])->name('diploma_results.show');
    Route::get('/diploma_results/finalrecord/{id}/edit', [DiplomaAdminController::class, 'diplomafinaledit'])->name('diploma_results.diplomafinaledit');
    Route::get('/diploma_final/record/{id}', [DiplomaAdminController::class, 'FinalModuleshow'])->name('diploma_results.FinalModuleshow');
    Route::put('/diploma_results/{id}', [DiplomaAdminController::class, 'update'])->name('diploma_results.update');
    Route::put('/diploma_results/finalmodule/{id}', [DiplomaAdminController::class, 'finalmoduleupdate'])->name('diploma_results.finalmoduleupdate');
    Route::get('/diploma_results/student/{student_id}', [DiplomaAdminController::class, 'diploshow'])->name('diploma_results.diploshow');
    Route::delete('/diploma_results/student/{student_id}', [DiplomaAdminController::class, 'destroy'])->name('diploma_results.destroy');
    Route::delete('/diploma_results/finalmodule/record/{student_id}', [DiplomaAdminController::class, 'finalmoduledestroy'])->name('diploma_results.finalmoduledestroy');
    Route::delete('/diploma_final/record/{id}', [DiplomaAdminController::class, 'diploma_resultfinaldelete'])->name('diploma_results.diplomafinaldelete');
});

// Advanced Diploma Admin  
Route::middleware(['auth.custom:web,AdvancedDiplomaAdmin'])->group(function () {
    Route::get('/advanced_diploma_results/{student_id}/{module_id}', [AdvancedDiplomaAdminController::class, 'show'])->name('advanced_diploma_results.show');
    Route::get('/advanced-results/{student_id}/{module_id}', [AdvancedDiplomaAdminController::class, 'advancedshow'])->name('advanced_diploma_results.advancedshow');
    Route::get('/advanced_students', [AdvancedDiplomaAdminController::class, 'advanceddiplomaStudents'])->name('advanced_diploma.advanced_students');
    Route::get('/advanced_modules', [AdvancedDiplomaAdminController::class, 'advanceddiplomaModules'])->name('advanced_diploma.advanced_modules');
    Route::get('/advanced_semester_exams', [AdvancedDiplomaAdminController::class, 'advanced_semester_exams'])->name('advanced_diploma.advanced_semester_exams');
    Route::get('/advanced_coursework', [AdvancedDiplomaAdminController::class, 'coursework'])->name('advanced_diploma.advanced_coursework');
    Route::get('/advanced_results', [AdvancedDiplomaAdminController::class, 'advancedresults'])->name('advanced_diploma.advanced_results');
    Route::get('/advanced_diploma_results/create', [AdvancedDiplomaAdminController::class, 'create'])->name('advanced_diploma_results.create');
    Route::get('/advanced_diploma_exams/add_exam', [AdvancedDiplomaAdminController::class, 'semesterexams'])->name('advanced_diploma_results.add_exam');
    Route::post('/advanced_diploma_results', [AdvancedDiplomaAdminController::class, 'storeResults'])->name('advanced_diploma_results.store');
    Route::post('/advanced_diploma_semesterExam/exam', [AdvancedDiplomaAdminController::class, 'storeSemesterExamMark'])->name('advanced_diploma_results.examstore');
    

    // Route::get('/advanced_diploma_results/{id}', [AdvancedDiplomaAdminController::class, 'show'])->name('advanced_diploma_results.show');
    Route::get('/advanced_diploma_examination/{student_id}/{module_id}', [AdvancedDiplomaAdminController::class, 'examshow'])->name('advanced_diploma_results.examshow');

   
    Route::get('/advanced_diploma_results/exam/{id}/edit', [AdvancedDiplomaAdminController::class, 'advancedExamedit'])->name('advanced_diploma_results.advancedexamedit');
    Route::get('/advanced_diploma_evaluations/{id}/edit', [AdvancedDiplomaAdminController::class, 'evaluationsedit'])->name('advanced_diploma_results.evaluationsedit');
    Route::put('/advanced_final_evaluations/records/{id}', [AdvancedDiplomaAdminController::class, 'evaluationsupdate'])->name('advanced_diploma_results.evaluationsupdate');
    Route::get('/advanced_diploma_results/{student_id}/{course_module}/edit', [AdvancedDiplomaAdminController::class, 'advancededit'])->name('advanced_diploma_results.advancededit');

    Route::delete('/advanced_diploma_results/examination/{id}', [AdvancedDiplomaAdminController::class, 'advanced_diploma_examdelete'])->name('advanced_diploma_results.advanced_diploma_examdelete');
    Route::put('/advanced_diploma_results/{id}', [AdvancedDiplomaAdminController::class, 'update'])->name('advanced_diploma_results.update');
    Route::put('/advanced_diploma_results/exam/{id}', [AdvancedDiplomaAdminController::class, 'ExamUpdate'])->name('advanced_diploma_exam.update');
   
    Route::delete('/advanced_diploma_results/student/{student_id}', [AdvancedDiplomaAdminController::class, 'destroy'])->name('advanced_diploma_results.destroy');
    Route::delete('/advanced_diploma_results/{id}', [AdvancedDiplomaAdminController::class, 'advanced_diploma_resultdelete'])->name('advanced_diploma_results.advanced_diploma_resultdelete');
    Route::delete('/advanced_final_evaluations/record-destroy/{id}', [AdvancedDiplomaAdminController::class, 'evaluationsdestroy'])->name('advanced_final_evaluations.evaluationsdestroy');
});

// Super Admin
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/students_results/certificate', [SuperAdminController::class, 'certresults'])->name('students_results.certificate.cert_results');
    Route::get('/students_results/{student_id}', [SuperAdminController::class, 'certificate_view'])->name('students_results.certificate_view');
    Route::get('/students_results/diploma/diploma_results', [SuperAdminController::class, 'diplomaresults'])->name('students_results.diploma.diploma_results');
    Route::get('/students_results/advanced_diploma/advanced_diplomaresults', [SuperAdminController::class, 'advanced_diplomaresults'])->name('students_results.advanced_diploma.advanced_diplomaresults');
    Route::get('/students_results/diploma/{student_id}', [SuperAdminController::class, 'diploma_view'])->name('students_results.diploma.diploma_view');
    Route::get('/students_results/advanced_diploma/{student_id}', [SuperAdminController::class, 'advanced_diploma_view'])->name('students_results.advanced_diploma.advanced_diploma_view');
});

// Student Login
Route::middleware(['guest:student'])->get('student/auth/login', [StudentAuthController::class, 'student_login'])->name('student.login');

Route::post('/portal/login', [StudentAuthController::class, 'login'])->name('login.attempt');
Route::get('/student/verify-email-received', [StudentAuthController::class, 'verifyForm'])
    ->name('verify.email.received');
Route::get('/student/forgot-email-password', [StudentAuthController::class, 'showForm'])
    ->name('forgot.email.password');

Route::post('/student/forgot-password', [StudentAuthController::class, 'sendVerificationCode'])->name('email.send.code');

Route::get('/student/reset-password-form', function () {
        return view('student.email-forgot-password');
    })->name('email.reset.form');

Route::post('/student/reset-password', [StudentAuthController::class, 'verifyAndReset'])->name('forgot.email.password.post');

// Student
Route::middleware('student.auth')->group(function () {
    Route::get('student/dashboard', [StudentAuthController::class, 'dashboard'])->name('student.dashboard');
    Route::get('student/statement', [StudentAuthController::class, 'statement'])->name('student.statement');
    Route::get('student/results', [StudentAuthController::class, 'results'])->name('student.results');
    Route::get('student/registration', [StudentAuthController::class, 'registration'])->name('student.registration');
    Route::get('student/notices', [StudentAuthController::class, 'notices'])->name('student.notices');
    Route::get('student/e_counselling', [StudentAuthController::class, 'e_counselling'])->name('student.e_counselling');
    Route::get('student/e_library', [StudentAuthController::class, 'e_library'])->name('student.e_library');
    Route::get('student/online-classes', [StudentAuthController::class, 'online_classes'])->name('student.online_classes');
    Route::get('student/timetable', [StudentAuthController::class, 'timetable'])->name('student.timetable');
    Route::get('/student/profile', [StudentProfileController::class, 'showProfile'])->name('student.profile');
    Route::get('/student/results/{selected_programme}', [StudentAuthController::class, 'ResultFilterShow'])->name('program.details');

    Route::match(['get', 'post'], 'student/module details', [StudentAuthController::class, 'ModuleDetails'])->name('student.register.module');
    Route::post('/register/module', [StudentAuthController::class, 'register'])->name('student.register');
    Route::post('student/logout', [StudentAuthController::class, 'destroy'])->name('student.destroy');
});

// Registrations
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('manual-registration', [RegistrationController::class, 'manualRegistration'])->name('registration.create_reg');
    Route::post('manual-reg-store', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('manual-reg-edit/{id}', [RegistrationController::class, 'edit'])->name('registrations.edit');
    Route::delete('manual-reg-destroy/{id}', [RegistrationController::class, 'destroy'])->name('registrations.destroy');
    Route::put('manual-reg-update/{id}', [RegistrationController::class, 'update'])->name('registrations.update');
});


// Student login errors
Route::get('/error-page', function () { return view('error');})->name('error-page');
Route::view('/student/emailerror', 'student.emailerror')->name('student.emailerror');


// Publishing System 
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/upload', [SuperAdminController::class, 'diploma_publish'])->name('diploma_publish');
    Route::get('c_upload', [SuperAdminController::class, 'certificate_publish'])->name('certificate_publish');
    Route::get('ad_upload', [SuperAdminController::class, 'Advanced_Diploma_publish'])->name('Advanced_Diploma_publish');

    Route::get('/publish results', [SuperAdminController::class, 'Diploma_PublishResult'])->name('diploma.publish');
    Route::get('/publish cresults', [SuperAdminController::class, 'Certificate_PublishResult'])->name('certificate.publish');
    Route::get('/publish adresults', [SuperAdminController::class, 'Advanced_Diploma_PublishResult'])->name('advanced_diploma.publish');

    Route::get('/certificate unpublish', [SuperAdminController::class, 'certificate_unpublishResults'])->name('certificate.unpublish');
    Route::get('/diploma unpublish', [SuperAdminController::class, 'diploma_unpublishResults'])->name('diploma.unpublish');
    Route::get('/advanced-diploma unpublish', [SuperAdminController::class, 'Advanced_diploma_unpublishResults'])->name('advanced_diploma.unpublish');
});


// Transcripts 
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/transcripts', [TranscriptController::class, 'index'])->name('transcripts.index');
    Route::get('/transcripts/search', [TranscriptController::class, 'search'])->name('transcripts.search');
    Route::get('/transcripts/pdf/{studentId}', [TranscriptController::class, 'generateTranscriptPDF'])->name('transcripts.pdf');
});


// Timetable 
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/upload/timetable', [TimetableController::class, 'create'])->name('upload.timetable');
    Route::post('/upload-timetable', [TimetableController::class, 'store'])->name('store.timetable');
    Route::get('/timetable/view', [TimetableController::class, 'index'])->name('timetables.view');
    Route::get('/timetable/{timetable}/edit', [TimetableController::class, 'edit'])->name('timetables.edit');
    Route::put('/timetable/{timetable}', [TimetableController::class, 'update'])->name('timetables.update');
    Route::delete('/timetable/{timetable}', [TimetableController::class, 'destroy'])->name('timetables.destroy');
});


//Notices
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/notices/view', [NoticeController::class, 'index'])->name('notices.view');
    Route::get('/notices/create', [NoticeController::class, 'create'])->name('notices.create');
    Route::post('/notices', [NoticeController::class, 'store'])->name('store.notice');
    Route::get('/notices/{id}/edit', [NoticeController::class, 'edit'])->name('notices.edit');
    Route::put('/notices/{id}', [NoticeController::class, 'update'])->name('notices.update');
    Route::delete('/notices/{id}', [NoticeController::class, 'destroy'])->name('notices.destroy');
});

//Modules / Semesters
Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/modules/view', [ModuleController::class, 'index'])->name('modules.view_modules');
    Route::get('/modules/create', [ModuleController::class, 'create'])->name('modules.create_module');
    Route::post('/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::get('/modules/{module}/edit', [ModuleController::class, 'edit'])->name('modules.edit');
    Route::put('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
    Route::get('/modules-by-course/{course_id}', [ModuleController::class, 'getModulesByCourse']);
});

Route::middleware(['auth.custom:web,SuperAdmin'])->group(function () {
    Route::get('/library', [LibraryController::class, 'viewLibrary'])->name('zist.library');
    Route::get('/library/books', [LibraryController::class, 'viewBooks'])->name('library.all.books');
    Route::get('/library/books/new', [LibraryController::class, 'AddNewBook'])->name('library.newBook');
    Route::get('uploads/book/{id}', [LibraryController::class, 'showBook'])->name('uploads.book.show');
    Route::delete('/uploads/book/{id}', [LibraryController::class, 'deleteBook'])->name('uploads.book.delete');
    Route::get('/upload/book', [LibraryController::class, 'AddNewBook'])->name('uploads.book.upload');
    Route::post('/book/store', [LibraryController::class, 'storeBook'])->name('uploads.book.store');
    

    Route::get('/research/view', [LibraryController::class, 'research'])->name('research.view');
    Route::get('/research/new', [LibraryController::class, 'Addresearch'])->name('research.new');
    Route::get('uploads/research', [LibraryController::class, 'research'])->name('uploads.research');
    Route::get('uploads/research/add', [LibraryController::class, 'Addresearch'])->name('uploads.research.add');
    Route::post('uploads/research/store', [LibraryController::class, 'storeResearch'])->name('uploads.research.store');
    Route::get('uploads/research/list', [LibraryController::class, 'listResearches'])->name('uploads.research.list');
    Route::get('uploads/research/{id}', [LibraryController::class, 'showResearch'])->name('uploads.research.show');
    Route::delete('/uploads/research/{id}', [LibraryController::class, 'deleteResearch'])->name('uploads.research.delete');

    Route::get('/library/journals', [LibraryController::class, 'viewJournals'])->name('library.journals');
    Route::get('/library/journal/new', [LibraryController::class, 'AddNewJournal'])->name('library.newJournal');
    Route::get('library/journal/{id}', [LibraryController::class, 'showJournal'])->name('library.journal.show');
    Route::delete('/library/journal/{id}', [LibraryController::class, 'deleteJournal'])->name('library.journal.delete');
    Route::post('/journal/store', [LibraryController::class, 'storeEjournal'])->name('library.journal.store');


    Route::get('/library/past/exam/papers', [LibraryController::class, 'viewPastExamPapers'])->name('library.past_exam_papers');
    Route::get('/library/past/exam/paper/new', [LibraryController::class, 'AddNewPastExamPaper'])->name('library.newPastExamPaper');
    Route::post('/library/past/exam/paper/store', [LibraryController::class, 'storePastExamPapers'])->name('library.library.past_exam_papers.store');
    Route::get('/library/past/exam/paper/{id}', [LibraryController::class, 'showPastExamPaper'])->name('library.past_exam_papers.show');
    Route::delete('/library/past/exam/paper/{id}', [LibraryController::class, 'deletePastExamPaper'])->name('library.past_exam_papers.delete');



    Route::get('/books/{id}/edit', [LibraryController::class, 'BookEdit'])->name('library.book.edit_book');
    Route::put('/books/{id}', [LibraryController::class, 'Bookupdate'])->name('library.book.update');

    Route::get('/past-exam-paper/{id}/edit', [LibraryController::class, 'PastExamEdit'])->name('library.past_exam_papers.edit');
    Route::put('/past-exam-paper/{id}', [LibraryController::class, 'PastExamUpdate'])->name('library.past_exam_papers.update');

    Route::get('/journals/{id}/edit', [LibraryController::class, 'JournalEdit'])->name('library.journal.edit');
    Route::put('/journals/{id}', [LibraryController::class, 'JournalUpdate'])->name('library.journal.update');

});










Route::middleware('student.auth')->group(function () {
Route::get('/CONNECT ZIST/Past Exam Papers', [StudentAuthController::class, 'PastExamPapers'])->name('student.past_exam_paper');
Route::get('/CONNECT ZIST/Library/Search Book', [StudentAuthController::class, 'SearchBook'])->name('student.search_book');
Route::get('/CONNECT ZIST/Library/Researches', [StudentAuthController::class, 'Researches'])->name('student.researches');
Route::get('/CONNECT ZIST/Library/Books/Categories', [StudentAuthController::class, 'BookCategories'])->name('library.categories');
Route::get('/CONNECT ZIST/Library/E Journals', [StudentAuthController::class, 'EJournals'])->name('library.e_journals');
Route::get('/CONNECT ZIST/Library/Contact Us', [StudentAuthController::class, 'ContactUs'])->name('zist.contact_us');
Route::get('/CONNECT ZIST/Library/Rules & Regulations', [StudentAuthController::class, 'Rules_Regulations'])->name('student.lab_rules');

});

;

Route::get('/get-course-modules/{moduleId}', [AdvancedDiplomaAdminController::class, 'getCourseModulesByModule']);







// End of Routing




