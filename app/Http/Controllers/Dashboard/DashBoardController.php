<?php

namespace App\Http\Controllers\dashboard;

use App\Models\Course;
use App\Models\Student; 
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Models\DiplomaModule;
use App\Models\CertificateModule;
use App\Http\Controllers\Controller;
use App\Models\AdvancedDiplomaModule;

class DashBoardController extends Controller
{
    public function dashboard()
    {
        $totalStudents = Student::count(); 
        $totalCourses = Course::count(); 
        $totalDepartmentsWithRecords = 0;
        $courses = [
            'Advanced Diploma In Systemic Family Therapy' => AdvancedDiplomaModule::class,
            'Certificate In Systemic Family Counselling' => CertificateModule::class,
            'Diploma In Systemic Family Counselling' => DiplomaModule::class,
        ];
    
        foreach ($courses as $courseName => $moduleClass) {
            $students = Student::where('course', $courseName)->get();

            if ($students->isNotEmpty()) {
                $totalModules = $moduleClass::count();
 
                if ($totalModules === 0) {
                    continue;
                }
    
                $allStudentsHaveRecords = true;
    
                foreach ($students as $student) {
                    if ($courseName == 'Advanced Diploma In Systemic Family Therapy') {
                        $studentRecordCount = $student->advanceddiplomaresultsRecords()->count();
                    } elseif ($courseName == 'Certificate In Systemic Family Counselling') {
                        $studentRecordCount = $student->certresultsRecords()->count();
                    } elseif ($courseName == 'Diploma In Systemic Family Counselling') {
                        $studentRecordCount = $student->diplomaresultsRecords()->count();
                    }
    
                    if ($studentRecordCount != $totalModules) {
                        $allStudentsHaveRecords = false;
                        break; 
                    }
                }
    
                if ($allStudentsHaveRecords) {
                    $totalDepartmentsWithRecords++;
                }
            }
        }

        return view('dashboard.dashboard', compact('totalStudents', 'totalCourses', 'totalDepartmentsWithRecords'));
    }
    
     
    public function showAdvDiplodashboard()
    {
        $totalStudents = Student::where('course', 'Advanced Diploma In Systemic Family Therapy')->count();
        $totalAdvancedDiplomaModules = AdvancedDiplomaModule::count();
        $studentsWithRecords = Student::whereHas('advanceddiplomaresultsRecords')->count();

        return view('dashboard.advdiplodashboard', compact('totalStudents','totalAdvancedDiplomaModules','studentsWithRecords'));

    }

    public function showCertdashboard()
    {
        $totalStudents = Student::where('course', 'Certificate In Systemic Family Counselling')->count();
        $totalCertificateModules = CertificateModule::count();
        $studentsWithRecords = Student::whereHas('certresultsRecords')->count();

        return view('dashboard.certdashboard', compact('totalStudents','totalCertificateModules','studentsWithRecords'));

    }

    public function showDiplodashboard()
    {
        $totalStudents = Student::where('course', 'Diploma In Systemic Family Counselling')->count();
        $totalDiplomaModules = DiplomaModule::count();
        $studentsWithRecords = Student::whereHas('diplomaresultsRecords')->count();
        
        return view('dashboard.diplodashboard', compact('totalStudents','totalDiplomaModules','studentsWithRecords'));

    }

    public function registration(Request $request)
    {
        $query = Registration::with(['student.course', 'module'])
                    ->where('registered', true);
    
        if ($request->filled('search')) {
            $search = $request->input('search');
    
            $query->whereHas('student', function ($q) use ($search) {
                $q->where('candidate_number', 'LIKE', "%{$search}%")
                  ->orWhere('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('course', 'LIKE', "%{$search}%"); 
            })->orWhereHas('module', function ($q) use ($search) {
                $q->where('module_name', 'LIKE', "%{$search}%");
            });
        }
    
        $sortField = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);
    
        $registrations = $query->paginate(10)->appends($request->query());
    
        return view('registration.registered_students', compact('registrations', 'sortField', 'sortDirection'));
    }

    public function course_modules()
    {
        return view('course_modules.course_modules');
    }

    public function courses()
    {
        return view('courses.index', compact('totalCourses')); 
    }

    public function students()
    {
        return view('students.index');
    }

    public function fees()
    {
        return view('fees.index');
    }

    public function students_results()
    {
        return  view('students_results.students_results');
    }

    public function students_transcripts()
    {
        return  view('transcripts.view');
    }

    public function students_uploads()
    {
        return  view('uploads.view');
    }
}