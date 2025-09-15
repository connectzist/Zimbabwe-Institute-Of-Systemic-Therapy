<?php

namespace App\Http\Controllers\Auth;
use App\Models\Fee;
use App\Models\Book;
use App\Models\Course;
use App\Models\Module;

use App\Models\Notice;
use App\Models\Student;
use App\Models\Ejournal;
use App\Models\ImapUser;
use App\Models\Research;
use App\Models\Timetable;
use Illuminate\Support\Str;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Models\DiplomaResult;
use App\Models\PastExamPaper;
use App\Models\StudentDetail;
use App\Models\CertificateResult;
use App\Models\DiplomaFinalModule;
use App\Services\ImapAuthenticator;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\AdvancedSemesterExam;
use App\Services\CpanelEmailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\AdvancedDiplomaResult;
use App\Models\EmailVerificationCode;
use App\Models\AdvancedFinalEvaluation;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;

class StudentAuthController extends Controller
{


    public function login(Request $request, ImapAuthenticator $imap)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = 'imap_login:' . $request->ip();
        if (cache()->has($key) && cache()->get($key) >= 10) {
            return back()->withErrors(['email' => 'Too many attempts. Please try again later.']);
        }

        $email = strtolower(trim($request->input('email')));
        $password = $request->input('password');

        $allowedDomain = 'connect.org.zw';
        $emailDomain = substr(strrchr($email, "@"), 1);

        if ($emailDomain !== $allowedDomain) {
            return view('student.error', ['message' => 'Only organizational email addresses are allowed to login. Kindly use your CONNECT ZIST emails']);
        }

        $studentEmailPattern = '/^[a-zA-Z]+\d+@connect\.org\.zw$/'; 
        $adminEmailPattern = '/^[a-zA-Z0-9._%+-]+@connect\.org\.zw$/';

        if (!preg_match($studentEmailPattern, $email)) {
            if (preg_match($adminEmailPattern, $email)) {
                return view('student.emailerror', ['message' => 'Use your specified way of logging to your dashboard.']);
            }

            return view('student.error', ['message' => 'Something went wrong, please try again later.']);
        }

        if (!$imap->attempt($email, $password)) {
            cache()->increment($key, 1);
            cache()->put($key, cache()->get($key), now()->addMinutes(10));
            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        $user = ImapUser::firstOrCreate(
            ['email' => $email],
            ['name' => $this->defaultNameFromEmail($email)]
        );

        $user = ImapUser::where('email', $email)->first();

        if (!$user) {
            return view('student.error', [
                'message' => 'You have logged in successfully, but no student record was found in our system. Please contact support or wait for your account to be registered.'
            ]);
        }

        Auth::guard('student')->login($user, true);
        cache()->forget($key);

        return redirect()->to('/student/dashboard');
    }

    private function defaultNameFromEmail(string $email): string
    {
        $local = Str::before($email, '@');
        return Str::headline(str_replace('.', ' ', $local)); 
    }

    public function student_login()
    {
        return view('student.login');
    }
    
    protected $cpanel;

    public function __construct(CpanelEmailService $cpanel)
    {
        $this->cpanel = $cpanel;
    }

    public function verifyForm()
    {
        return view('student.email-forgot-passverify');
    }

    public function showForm()
    {
        return view('student.email-forgot-password');
    }


    public function sendVerificationCode(Request $request)
    {
        try {
            $request->validate([
                'email' => [
                    'required',
                    'email',
                    function ($attribute, $value, $fail) {
                        if (!str_ends_with(strtolower($value), '@connect.org.zw')) {
                            $fail('Only school emails are allowed.');
                        }
                    },
                ],
            ]);

            $email = strtolower($request->email);

            if (EmailVerificationCode::where('email', $email)->where('created_at', '>', now()->subMinutes(1))->exists()) {
                return back()->withErrors([
                    'email' => 'Please wait at least 1 minute before requesting another code.'
                ]);
            }

            try {
                if (!$this->cpanel->emailExists($email)) {
                    return back()->withErrors(['email' => 'This email does not exist on the server.']);
                }
            } catch (\Exception $e) {
                Log::error('Error verifying email with cPanel', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
                return back()->withErrors(['email' => 'Could not verify email. Try again later.']);
            }

            $code = mt_rand(100000, 999999);

            EmailVerificationCode::where('email', $email)->delete();

            EmailVerificationCode::create([
                'email' => $email,
                'code' => $code,
                'expires_at' => now()->addMinutes(15),
            ]);

            try {
                Mail::to($email)->send(new \App\Mail\SendVerificationCode($code));
            } catch (\Exception $e) {
                Log::error('Failed to send verification email', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
                return back()->withErrors(['email' => 'Could not send verification code. Please try again later.']);
            }

            return redirect()->route('email.reset.form')->with('email', $email);

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e; 
        } catch (\Exception $e) {
            Log::error('Unexpected error in sendVerificationCode()', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['email' => 'Something went wrong. Please try again.']);
        }
    }

    public function verifyAndReset(Request $request)
    {
        $validated = $request->validate([
            'token-verify' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $code = $validated['token-verify'];

        try {
            $record = EmailVerificationCode::where('code', $code)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();

            if (!$record) {
                return back()->withErrors(['token-verify' => 'Invalid or expired verification code.']);
            }

            $email = $record->email;

            $result = $this->cpanel->resetEmailPassword($email, $validated['password']);

            if ($result === true) {
                EmailVerificationCode::where('email', $email)->delete();

                return redirect()->back()->with('success', 'Password successfully reset. Redirecting to login...');
            }

            return back()->withErrors(['email' => 'Failed to reset password: ' . $result]);

        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return back()->withErrors(['general' => 'Something went wrong. Please try again later.']);
        }
    }


    public function dashboard()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();
        $email = $student->email;

        $candidateNumber = strtoupper(explode('@', $email)[0]);

        $foundStudent = Student::where('candidate_number', $candidateNumber)->first();

        if (!$foundStudent) {
            Auth::guard('student')->logout();
            session()->invalidate();
            session()->regenerateToken();

            return view('student.error', [
                'message' => 'You have been logged out because no student record was found for your account. Please contact support for help.'
            ]);
        }

        $id = $foundStudent->id;
        $course = $foundStudent->course;

        $fees = Fee::where('student_id', $id)->get();

        $totalBalance = ($course === 'Advanced Diploma In Systemic Family Therapy')
            ? 480 - $fees->sum('amount')
            : 450 - $fees->sum('amount');

        return view('student.dashboard', compact('fees', 'totalBalance', 'student'));
    }



    public function statement()
    {
        $student = Auth::guard('student')->user();
        $email = $student->email;
    
        $candidateNumber = strtoupper(explode('@', $email)[0]);
    
        $foundStudent = Student::where('candidate_number', $candidateNumber)->first();

        if ($foundStudent) {
            $id = $foundStudent->id;
            $course = $foundStudent->course;
            
            $fees = Fee::where('student_id', $id)->get(); 
            
            if ($course === 'Advanced Diploma In Systemic Family Therapy') {
                $totalBalance = 480 - $fees->sum('amount'); 
            } else {
                $totalBalance = 450 - $fees->sum('amount'); 
            }
            
            return view('student.statement', compact('fees', 'totalBalance','student'));
        } else {
            return response()->json(['error' => 'Student not found'], 404);
        }
    }

    
    public function results()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();
        $email = $student->email;

        $candidateNumber = strtoupper(explode('@', $email)[0]);
        $foundStudent = Student::where('candidate_number', $candidateNumber)->first();

        if ($foundStudent) {
            $course = $foundStudent->course;
            return view('student.results', compact('student', 'course'));
        } else {
            return response()->json(['error' => 'Student not found'], 404);
        }

    }

    public function registration()
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }

        $student = Auth::guard('student')->user();
        $email = $student->email;

        $candidateNumber = strtoupper(explode('@', $email)[0]);

        $foundStudent = Student::where('candidate_number', $candidateNumber)->first();

        if ($foundStudent) {
            $id = $foundStudent->id;
            $studentCourseId = $foundStudent->course_id;
            $studentCourse = Course::find($studentCourseId); 

            if ($studentCourse) {
                $modules = Module::where('course_id', $studentCourseId)
                                ->where('reg_start', '<=', now())  
                                ->where('reg_due', '>=', now()) 
                                ->get();

                foreach ($modules as $module) {
                    $existingRegistration = Registration::where('student_id', $id)
                                                        ->where('module_id', $module->id)
                                                        ->first();

                    if ($existingRegistration) {
                        if (!$existingRegistration->registered) {
                            // Automatically re-activate the registration
                            $existingRegistration->registered = true;
                            $existingRegistration->save();
                        }
                        $module->is_registered = true;
                    } else {
                        $module->is_registered = false;
                    }
                }

                return view('student.registration', compact('student', 'studentCourse', 'modules'));
            } else {
                return response()->json(['error' => 'Course not found for the student'], 404);
            }
        } else {
            return response()->json(['error' => 'Student not found'], 404);
        }
    }

    public function notices()
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();

            $email = $student->email;
            $candidateNumber = strtoupper(explode('@', $email)[0]);

            $foundStudent = Student::where('candidate_number', $candidateNumber)->first();
    
            if ($foundStudent) {
                $course = $foundStudent->course;
                $studentType = '';
    
                if ($course == 'Certificate In Systemic Family Counselling') {
                    $studentType = 'certificate';
                } elseif ($course == 'Diploma In Systemic Family Counselling') {
                    $studentType = 'diploma';
                } elseif ($course == 'Advanced Diploma In Systemic Family Therapy') {
                    $studentType = 'advanced';
                }
 
                $currentDate = now();

                $notices = Notice::where(function ($query) use ($studentType) {
                        $query->where('student_type', $studentType)
                              ->orWhere('student_type', 'All');
                    })
                    ->where(function ($query) use ($currentDate) {
                        $query->whereNull('expiry_date')
                              ->orWhere('expiry_date', '>=', $currentDate);
                    })
                    ->get();

                return view('student.notices', compact('student', 'notices'));
            } else {
                return redirect()->route('student.login');
            }
        } else {
            return redirect()->route('student.login');
        }
    }
    

    public function online_classes()
    {
            if (Auth::guard('student')->check()) {
                $student = Auth::guard('student')->user(); 
                return view('student.online_classes', compact('student'));
            } else{
            return redirect()->route('student.login'); 
        }
    }


    public function timetable()
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();

            $email = $student->email;
            $candidateNumber = strtoupper(explode('@', $email)[0]);

            $foundStudent = Student::where('candidate_number', $candidateNumber)->first();

            if ($foundStudent) {
                $course = $foundStudent->course;

                $studentType = '';
                if ($course == 'Certificate In Systemic Family Counselling') {
                    $studentType = 'certificate';
                } elseif ($course == 'Diploma In Systemic Family Counselling') {
                    $studentType = 'diploma';
                } elseif ($course == 'Advanced Diploma In Systemic Family Therapy') {
                    $studentType = 'advanced';
                }

                $timetables = Timetable::where('student_type', $studentType)->get();
    
                return view('student.timetable', compact('student', 'timetables'));
            } else {
                return redirect()->route('student.login');
            }
        } else {
            return redirect()->route('student.login');
        }
    }
    

    public function e_library(Request $request)
    {
            if (Auth::guard('student')->check()) {
                $student = Auth::guard('student')->user(); 
                $student = Auth::guard('student')->user(); 

                $letter = $request->input('letter');

                $query = Book::query();
            
                if ($letter) {
                    $query->where('title', 'like', $letter . '%');
                }
            
                $books = $query->orderBy('title')->paginate(5);
                return view('student.e_library',  compact('student','books','letter'));
            } else{
            return redirect()->route('student.login'); 
        }
    }

    public function e_counselling()
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user(); 
            return view('student.e_counselling', compact('student'));
        } else{
        return redirect()->route('student.login'); 
    }
    }
    public function destroy(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('student.login');
    }
    

    public function ResultFilterShow()
        {
        $student = Auth::guard('student')->user();

        if (!$student) {
            return redirect()->route('student.login'); 
        }
        
        $email = $student->email;
        $candidateNumber = strtoupper(explode('@', $email)[0]);

        $foundStudent = Student::where('candidate_number', $candidateNumber)->first();

        if ($foundStudent) {
            $id = $foundStudent->id;
            $course = $foundStudent->course;
            $fees = Fee::where('student_id', $id)->get();
            $totalBalance = $fees->sum('amount');

            if ($course === 'Advanced Diploma In Systemic Family Therapy') {
                $totalBalance = 480 - $fees->sum('amount');
            } else {
                $totalBalance = 450 - $fees->sum('amount');
            }
            
            if ($totalBalance > 0) {
                return view('student.results.results', ['error' => 'You have an outstanding balance of $' . $totalBalance, 'student' => $student]);
            }

            $data = [
                'student' => $student,
            ];

        
            // Grading
            $getGrade = function($mark) {
                if ($mark >= 80 && $mark <= 100) {
                    return 'Distinction';
                } elseif ($mark >= 70 && $mark <= 79) {
                    return 'Merit';
                } elseif ($mark >= 60 && $mark <= 69) {
                    return 'Credit';
                } elseif ($mark >= 50 && $mark <= 59) {
                    return 'Pass';
                } else {
                    return 'Fail';
                }
            };     

           if ($course === 'Certificate In Systemic Family Counselling') {
                // Only fetch published results for this student
                $Certificateresults = CertificateResult::where('student_id', $foundStudent->id)
                    ->where('is_published', true)
                    ->with(['courseModule' => function($query) {
                        $query->select('id', 'module_name');
                    }])
                    ->get();

                // If no published results exist
                if ($Certificateresults->isEmpty()) {
                    return view('student.results.results', [
                        'error' => 'Results are not yet published.',
                        'student' => $student
                    ]);
                }

                $data['certificate_results'] = $Certificateresults->map(function ($result) use ($getGrade) {
                    $average = ($result->exam_mark + $result->practical_mark + $result->theory_mark) / 3;

                    return [
                        'module_name' => $result->courseModule->module_name,
                        'theory_grade' => $getGrade($result->theory_mark),
                        'practical_grade' => $getGrade($result->practical_mark),
                        'exam_grade' => $getGrade($result->exam_mark),
                        'overall_grade' => $getGrade($average),
                    ];
                });

                return view('student.results.results', [
                    'certificate_results' => $data['certificate_results'],
                    'student' => $student
                ]);

            } 
           elseif ($course === 'Diploma In Systemic Family Counselling') {
                // Fetch only published DiplomaResult records
                $diplomaResults = DiplomaResult::where('student_id', $foundStudent->id)
                    ->where('is_published', true)
                    ->with(['courseModule' => function ($query) {
                        $query->select('id', 'module_name');
                    }])
                    ->get();

                // Fetch the final module record (Module 4)
                $finalModuleResult = DiplomaFinalModule::where('student_id', $foundStudent->id)
                    ->where('is_published', true)
                    ->with(['module' => function ($query) {
                        $query->select('id', 'module_name');
                    }])
                    ->first();

                // If no published results at all
                if ($diplomaResults->isEmpty() && !$finalModuleResult) {
                    return view('student.results.results', [
                        'error' => 'Results are not yet published.',
                        'student' => $student
                    ]);
                }

                $data['diploma_results'] = [];

                // Process regular (non-final) modules
                foreach ($diplomaResults as $result) {
                    $average = ($result->exam_mark + $result->practical_mark + $result->theory_mark) / 3;

                    $data['diploma_results'][] = [
                        'module_name' => $result->courseModule->module_name,
                        'theory_grade' => $getGrade($result->theory_mark),
                        'practical_grade' => $getGrade($result->practical_mark),
                        'exam_grade' => $getGrade($result->exam_mark),
                        'overall_grade' => $getGrade($average),
                    ];
                }

                // Process Module 4 (final module)
                if ($finalModuleResult && $finalModuleResult->module) {
                    $average = ($finalModuleResult->exam_mark + $finalModuleResult->practical_mark + $finalModuleResult->research_mark) / 3;

                    $data['diploma_results'][] = [
                        'module_name' => $finalModuleResult->module->module_name,
                        'research_grade' => $getGrade($finalModuleResult->research_mark),
                        'practical_grade' => $getGrade($finalModuleResult->practical_mark),
                        'exam_grade' => $getGrade($finalModuleResult->exam_mark),
                        'overall_grade' => $getGrade($average),
                    ];
                }

                // Send to view
                return view('student.results.results', [
                    'diploma_results' => $data['diploma_results'],
                    'student' => $student
                ]);

            } 
        elseif ($course === 'Advanced Diploma In Systemic Family Therapy') {
            // Fetching module results with courseModule and Module (for module_name)
            $advancedResults = AdvancedDiplomaResult::where('student_id', $foundStudent->id)
                ->where('is_published', true)
                ->with([
                    'courseModule' => function ($query) {
                        $query->select('id', 'course_code', 'course_title');
                    },
                    'Module' => function ($query) {
                        $query->select('id', 'module_name'); 
                    }
                ])
                ->get();

            // Fetch semester exams by module
            $semesterExams = AdvancedSemesterExam::where('student_id', $foundStudent->id)
                ->where('is_published', true)
                ->get()
                ->keyBy('module_id');

            // Fetch final evaluation record for the student
            $finalEvaluation = AdvancedFinalEvaluation::where('student_id', $foundStudent->id)
                ->where('is_published', true)
                ->first();

            // If no module results and no exam
            if ($advancedResults->isEmpty() && $semesterExams->isEmpty()) {
                return view('student.results.results', [
                    'error' => 'Results are not yet published.',
                    'student' => $student
                ]);
            }

            $groupedResults = [];
            $overallGrades = [];

            // Build module ID => module name map using Module relationship
            $moduleNames = [];

            foreach ($advancedResults as $result) {
                $moduleId = $result->module_id ?? 'Unknown';
                $moduleName = $result->Module->module_name ?? 'Unknown Module Name';

                $moduleNames[$moduleId] = $moduleName;

                // Add module results to the grouped results
                $groupedResults[$moduleId][] = [
                    'module_name' => $moduleName,
                    'course_code' => $result->courseModule->course_code ?? '',
                    'course_title' => $result->courseModule->course_title ?? '',
                    'grade' => $getGrade($result->mark),
                    'mark' => $result->mark,
                ];
            }

            // Add semester exams to the relevant module group
            foreach ($semesterExams as $exam) {
                $moduleId = $exam->module_id;
                $groupedResults[$moduleId][] = [
                    'course_code' => '',
                    'course_title' => 'End Of Semester Exam',
                    'grade' => $getGrade($exam->exam_mark),
                    'mark' => $exam->exam_mark,
                ];
            }

            // Find the module ID of "Module 5" from the map
            $moduleIdForModule5 = null;
            foreach ($moduleNames as $id => $name) {
                if (strtolower($name) === 'module 5') {
                    $moduleIdForModule5 = $id;
                    break;
                }
            }

            // Add final evaluations to "Module 5" if final evaluation and module found
            if ($finalEvaluation && $moduleIdForModule5) {
                $groupedResults[$moduleIdForModule5] = $groupedResults[$moduleIdForModule5] ?? [];

                if (!is_null($finalEvaluation->adft110_internal)) {
                    $groupedResults[$moduleIdForModule5][] = [
                        'course_code' => 'ADFT110',
                        'course_title' => 'Applied Research In Family Therapy',
                        'grade' => $getGrade($finalEvaluation->adft110_internal),
                        'mark' => $finalEvaluation->adft110_internal,
                    ];
                }

                if (!is_null($finalEvaluation->final_theory)) {
                    $groupedResults[$moduleIdForModule5][] = [
                        'course_code' => '',
                        'course_title' => 'Final Theory',
                        'grade' => $getGrade($finalEvaluation->final_theory),
                        'mark' => $finalEvaluation->final_theory,
                    ];
                }

                if (!is_null($finalEvaluation->clinicals)) {
                    $groupedResults[$moduleIdForModule5][] = [
                        'course_code' => '',
                        'course_title' => 'Clinicals',
                        'grade' => $getGrade($finalEvaluation->clinicals),
                        'mark' => $finalEvaluation->clinicals,
                    ];
                }
            }

            // Calculate overall grades for each module
            foreach ($groupedResults as $moduleId => $results) {
                $totalMarks = array_sum(array_column($results, 'mark'));
                $numComponents = count($results);
                $average = $totalMarks / $numComponents;
                $overallGrades[$moduleId] = $getGrade(round($average));
            }

            return view('student.results.results', [
                'advanced_diploma_results_grouped' => $groupedResults,
                'overallGrades' => $overallGrades,
                'student' => $student
            ]);
        }
        }
    }

    public function ModuleDetails(Request $request)
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();

            $email = $student->email;
            $candidateNumber = strtoupper(explode('@', $email)[0]);

            $foundStudent = Student::where('candidate_number', $candidateNumber)->first();

            if ($foundStudent) {
                $id = $foundStudent->id;
                $studentCourseId = $foundStudent->course_id;
                $studentCourse = Course::find($studentCourseId);

                if (!$studentCourse) {
                    return redirect()->route('student.login');
                }

                $courseName = $studentCourse->title;
                $fees = Fee::where('student_id', $id)->get(); 
                $totalPaid = $fees->sum('amount');

                // Determine full course fee based on course title
                if ($courseName === 'Advanced Diploma In Systemic Family Therapy') {
                    $fullCourseFee = 480;
                } else {
                    $fullCourseFee = 450;
                }

                $requiredBalance = 300;
                $totalBalance = $fullCourseFee - $totalPaid;

                // Ensure totalBalance never goes negative
                // if ($totalBalance < 0) {
                //     $totalBalance = 0;
                // }

                $modules = Module::where('course_id', $studentCourse->id)
                                ->where('reg_start', '<=', now())
                                ->where('reg_due', '>=', now())
                                ->get();

                $selectedModule = $modules->first();

                return view('student.register_module', compact(
                    'student',
                    'candidateNumber',
                    'studentCourse',
                    'modules',
                    'selectedModule',
                    'courseName',
                    'totalBalance',
                    'requiredBalance'
                ));
            }
        }

        return redirect()->route('student.login');
    }


   public function register(Request $request)
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();

            $email = $student->email;
            $candidateNumber = strtoupper(explode('@', $email)[0]);

            $foundStudent = Student::where('candidate_number', $candidateNumber)->first();

            if (!$foundStudent) {
                return redirect()->route('student.register.module')
                    ->with('error', 'Student not found!');
            }

            $id = $foundStudent->id;
            $studentCourseId = $foundStudent->course_id;
            $studentCourse = Course::find($studentCourseId);

            if (!$studentCourse) {
                return redirect()->route('student.register.module')
                    ->with('error', 'Course not found!');
            }

            $courseName = $studentCourse->title;
            $fees = Fee::where('student_id', $id)->get();
            $totalPaid = $fees->sum('amount');

            // Set course fee and required balance
            $courseFee = $courseName === 'Advanced Diploma In Systemic Family Therapy' ? 480 : 450;
            $requiredBalance = 300;

            $remainingAfterFee = $totalPaid - $courseFee;

            $selectedModule = Module::where('course_id', $studentCourseId)
                ->where('reg_start', '<=', now())
                ->where('reg_due', '>=', now())
                ->first();

            $modules = Module::where('course_id', $studentCourseId)->get();

            $data = compact('student', 'candidateNumber', 'studentCourse', 'modules', 'selectedModule', 'courseName', 'remainingAfterFee', 'requiredBalance');

            if ($selectedModule) {
                $existingRegistration = Registration::where('student_id', $id)
                    ->where('module_id', $selectedModule->id)
                    ->where('registered', true)
                    ->first();

                if ($existingRegistration) {
                    return redirect()->route('student.register.module')
                        ->with('error', 'You are already registered for this module.')
                        ->with($data);
                }

                //  Final logic
                if ($remainingAfterFee >= $requiredBalance) {
                    Registration::create([
                        'student_id' => $id,
                        'module_id' => $selectedModule->id,
                        'registered' => true,
                    ]);

                    return redirect()->route('student.registration')
                        ->with('success', 'Registration Successful')
                        ->with($data);
                } else {

                    if ($totalPaid == 0) {
                        $totalRequired = $courseFee + $requiredBalance;
                        return redirect()->route('student.register.module')
                            ->with('error', "You have insufficient balance to register. Kindly pay your outstanding balance of $$totalRequired to proceed with registration.")
                            ->with($data);
                    } else {
                        $neededToRegister = ($courseFee + $requiredBalance) - $totalPaid;
                        return redirect()->route('student.register.module')
                            ->with('error', "You have an outstanding of $$neededToRegister to proceed with registration.")
                            ->with($data);
                    }
                }
            } else {
                return redirect()->route('student.register.module')
                    ->with('error', 'No module found for registration.')
                    ->with($data);
            }
        }

        return redirect()->route('student.login');
    }


    public function PastExamPapers(Request $request)
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user(); 
            $papers = collect();

            if ($request->has('search')) {
                $searchTerm = $request->input('search');

                $papers = PastExamPaper::where('exam_title', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('module_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('exam_year', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('course', function ($query) use ($searchTerm) {
                        $query->where('title', 'LIKE', "%{$searchTerm}%");
                    })
                    ->with('course') 
                    ->get();
            }

            return view('student.library.past_exam_paper', compact('student', 'papers'));
        } else {
            return redirect()->route('student.login'); 
        }

    }

    public function SearchBook(Request $request)
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user(); 
    
            $query = Book::query();
    
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
    
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('author', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('isbn', 'LIKE', '%' . $searchTerm . '%');
                });
            }
    
            $books = $query->get();
    
            return view('student.library.search_book', compact('student', 'books'));
        } else {
            return redirect()->route('student.login'); 
        }
    }

    public function Rules_Regulations()
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user(); 
            return view('student.library.rules_regulations', compact('student'));
        } else{
        return redirect()->route('student.login'); 
    }
    }

    public function Researches(Request $request)
    {
        if (!Auth::guard('student')->check()) {
            return redirect()->route('student.login');
        }
    
        $student = Auth::guard('student')->user();
    
        $query = Research::query();
    
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('research_title', 'LIKE', "%{$search}%")
                  ->orWhere('researcher', 'LIKE', "%{$search}%")
                  ->orWhere('year_of_research', 'LIKE', "%{$search}%")
                  ->orWhere('student_type', 'LIKE', "%{$search}%");
            });
        }
    
        // Filtering
        if ($request->filled('year')) {
            $query->where('year_of_research', $request->year);
        }
    
        if ($request->filled('type')) {
            $query->where('student_type', $request->type);
        }
    
        // Sorting
        $researches = $query->orderBy('year_of_research', 'desc')->get();
    
        // Dropdowns
        $allYears = Research::select('year_of_research')->distinct()->orderBy('year_of_research', 'desc')->pluck('year_of_research');
        $allTypes = Research::select('student_type')->distinct()->pluck('student_type');
    
        return view('student.library.researchs', compact('student', 'researches', 'allYears', 'allTypes'));
    }
    
    
    public function BookCategories(Request $request)
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user(); 
    
            $books = collect(); 
    
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $books = Book::where('category', 'LIKE', '%' . $searchTerm . '%')->get();
            }
    
            return view('student.library.categories', compact('student', 'books'));
        } else {
            return redirect()->route('student.login'); 
        }
    }
    

    public function EJournals(Request $request)
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user();
    
            $journals = collect(); 
    
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $journals = Ejournal::where('title', 'LIKE', '%' . $searchTerm . '%')->get();
            }
    
            return view('student.library.e_journals', compact('student', 'journals'));
        } else {
            return redirect()->route('student.login');
        }
    }

    public function ContactUs()
    {
        if (Auth::guard('student')->check()) {
            $student = Auth::guard('student')->user(); 
            return view('student.library.contact_us', compact('student'));
        } else{
        return redirect()->route('student.login'); 
    }
    }

}