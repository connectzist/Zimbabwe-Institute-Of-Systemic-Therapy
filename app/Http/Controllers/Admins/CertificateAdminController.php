<?php

namespace App\Http\Controllers\Admins;

use App\Models\Module;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\CertificateModule;

use App\Models\CertificateResult;
use App\Http\Controllers\Controller;
use function PHPUnit\Framework\returnSelf;

class CertificateAdminController extends Controller
{
    public function show($student_id)
    {
         $certificateResults = CertificateResult::where('student_id', $student_id)
                                            ->with(['student', 'courseModule'])
                                            ->get();

        if ($certificateResults->isEmpty()) {
            abort(404, 'No certificate results found for this student.');
        }

        return view('certificate.certificate_results.show', compact('certificateResults'));

    }

    public function certshow($student_id)
    {
        $certificateResults = CertificateResult::where('student_id', $student_id)
            ->with('courseModule') 
            ->get();

        // Grading each mark type
        $certificateResults->each(function ($result) {
            $result->theory_grade = $this->getGrade($result->theory_mark);
            $result->practical_grade = $this->getGrade($result->practical_mark);
            $result->exam_grade = $this->getGrade($result->exam_mark);

            // Average
            $result->average_mark = round(($result->theory_mark + $result->practical_mark + $result->exam_mark) / 3);
        });

        // Flatten all marks into one array to get overall grade
        $allMarks = $certificateResults->flatMap(function ($result) {
            return [
                $result->theory_mark,
                $result->practical_mark,
                $result->exam_mark
            ];
        });

        if ($allMarks->count() > 0) {
            $averageMark = round($allMarks->avg());
            $overallGrade = $this->getGrade($averageMark);
        } else {
            $overallGrade = 'NAN';
        }

        return view('certificate.certificate_results.certshow', compact('certificateResults', 'overallGrade'));
    }
    
    public function certedit($id)
    {
        $modules = Module::where('course_id', 3)->get();
        $certificateResult = CertificateResult::findOrFail($id);

        $courseModules = CertificateModule::all();

        return view('certificate.certificate_results.certedit', compact('certificateResult', 'courseModules', 'modules'));
    }
    
    public function update(Request $request, $id)
    {
        $certificateResult = CertificateResult::findOrFail($id);

        // Validate inputs
        $validatedData = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'theory_mark' => 'required|numeric|min:0|max:100',
            'practical_mark' => 'required|numeric|min:0|max:100',
            'exam_mark' => 'required|numeric|min:0|max:100',
        ]);

        // Calculate rounded average
        $averageMark = round((
            $validatedData['theory_mark'] +
            $validatedData['practical_mark'] +
            $validatedData['exam_mark']
        ) / 3);

        // Update result
        $certificateResult->module_id = $validatedData['module_id'];
        $certificateResult->theory_mark = $validatedData['theory_mark'];
        $certificateResult->practical_mark = $validatedData['practical_mark'];
        $certificateResult->exam_mark = $validatedData['exam_mark'];
        $certificateResult->average_mark = $averageMark; 
        $certificateResult->save();

        return redirect()
            ->route('certificate.cert_coursework')
            ->with('success', 'Certificate result updated successfully.');
    }
    
    public function destroy($student_id)
    {
        $certificateResult = CertificateResult::where('student_id', $student_id)->first();
    
        if (!$certificateResult) {
            abort(404, 'Certificate result not found for this student.');
        }
    
        $certificateResult->delete();
    
        return redirect()->route('certificate.cert_coursework')->with('success', 'Certificate result deleted successfully!');
    }
    
    public function certificateStudents()
    {
        $certificateStudents = Student::where('course', 'Certificate In Systemic Family Counselling')->get();
        return view('certificate.certificate_students', compact('certificateStudents'));
    }

    public function certificateModules()
    {
        $certificateModules = CertificateModule::all();
        return view('certificate.certificate_modules', compact('certificateModules'));
    }

    public function coursework()
    {
        $students = Student::whereHas('certresultsRecords')  
            ->with('certresultsRecords.courseModule')  
            ->get();

        $studentsData = $students->map(function ($student) {
            $moduleName = optional($student->certresultsRecords->first()->courseModule)->module_name;

            return [
                'id' => $student->id, 
                'candidate_number' => $student->candidate_number,
                'candidate_name' => $student->first_name . ' ' . $student->last_name,
                'course_name' => $student->course,
                'course_module' => $moduleName, 
                'overall_pass' => '', 
            ];
        })->unique('id'); 

        return view('certificate.cert_coursework', compact('studentsData'));
    }
 
    public function certresults()
    {
        $students = Student::whereHas('certresultsRecords') 
            ->with('certresultsRecords.courseModule') 
            ->get();

        $studentsData = $students->map(function ($student) {
            $moduleName = optional($student->certresultsRecords->first()->courseModule)->module_name;

            return [
                'id' => $student->id, 
                'candidate_number' => $student->candidate_number,
                'candidate_name' => $student->first_name . ' ' . $student->last_name,
                'course_name' => $student->course,
                'course_module' => $moduleName,  
            ];
        })->unique('id'); 

        return view('certificate.cert_results', compact('studentsData'));
    }
    
    public function create()
    {
        $students = Student::where('course', 'Certificate In Systemic Family Counselling')->get();
        $modules = Module::where('course_id', 3)->get();

        return view('certificate.certificate_results.create', compact('students', 'modules'));
    }

    public function storeResults(Request $request)
    {
        $request->validate([
            'results' => 'required|array|min:1',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.module_id' => 'required|exists:modules,id',
            'results.*.theory_mark' => 'required|integer|min:0|max:100',
            'results.*.practical_mark' => 'required|integer|min:0|max:100',
            'results.*.exam_mark' => 'required|integer|min:0|max:100',
            'results.*.average_mark' => 'required|numeric|min:0|max:100',
        ]);
    
        foreach ($request->input('results') as $result) {
            $existingResult = CertificateResult::where('student_id', $result['student_id'])
                                               ->where('module_id', $result['module_id'])
                                               ->first();
    
            if ($existingResult) {
                return response()->json(['error' => 'This student already has this module recorded.'], 400);
            }
    
            CertificateResult::create([
                'student_id' => $result['student_id'],
                'module_id' => $result['module_id'],
                'theory_mark' => $result['theory_mark'],
                'practical_mark' => $result['practical_mark'],
                'exam_mark' => $result['exam_mark'],
                'average_mark' => $result['average_mark'],
            ]);
        }
    
        return response()->json(['success' => 'Results added successfully!']);
    }
    
    public function certresdestroy($id)
    {
        $certificate_result = CertificateResult::findOrFail($id);
        $certificate_result->delete();

        return redirect()->back()->with('success', 'Certificate result deleted successfully.');
    }   


      private function getGrade($mark)
    {
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
    }


}

   


