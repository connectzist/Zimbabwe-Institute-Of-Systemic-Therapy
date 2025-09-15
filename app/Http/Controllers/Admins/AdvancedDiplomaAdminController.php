<?php

namespace App\Http\Controllers\Admins;

use App\Models\Module;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdvancedSemesterExam;
use App\Models\AdvancedDiplomaModule;
use App\Models\AdvancedDiplomaResult;
use App\Models\AdvancedFinalEvaluation;

class AdvancedDiplomaAdminController extends Controller
{
    public function getCourseModulesByModule($moduleId)
    {
        $courseModules = AdvancedDiplomaModule::where('module_id', $moduleId)
            ->select('id', 'course_code', 'course_title')
            ->get();

        return response()->json($courseModules);
    }

    public function advanceddiplomaStudents()
    {
        $advanceddiplomaStudents  = Student::where('course', 'Advanced Diploma In Systemic Family Therapy')->get();
        return view('advanced_diploma.advanced_students', compact('advanceddiplomaStudents'));
    }

    public function advanceddiplomaModules()
    {
        $advanceddiplomaModules = AdvancedDiplomaModule::all();

        return view('advanced_diploma.advanced_modules', compact('advanceddiplomaModules'));
    }

    public function coursework()
    {
        $students = Student::whereHas('advanceddiplomaresultsRecords')
            ->with(['advanceddiplomaresultsRecords.courseModule', 'advanceddiplomaresultsRecords.Module'])
            ->get();

        $studentsData = [];

        $seen = []; //  To track unique (student_id + module_id)

        foreach ($students as $student) {
            foreach ($student->advanceddiplomaresultsRecords as $record) {
                $key = $student->id . '-' . $record->module_id;

                if (!isset($seen[$key])) {
                    $studentsData[] = [
                        'id' => $student->id,
                        'candidate_number' => $student->candidate_number,
                        'candidate_name' => $student->first_name . ' ' . $student->last_name,
                        'course_name' => $student->course,
                        'course_module' => optional($record->Module)->module_name,
                        'module_id' => $record->module_id,
                    ];

                    $seen[$key] = true;
                }
            }
        }

        return view('advanced_diploma.advanced_coursework', compact('studentsData'));
    }


   public function advanced_semester_exams()
    {
        $students = Student::whereHas('semesterExamRecords')
        ->with(['semesterExamRecords.module'])
        ->get();

        $studentsData = [];
        

        foreach ($students as $student) {
            foreach ($student->semesterExamRecords as $record) {
                $studentsData[] = [
                    'id' => $student->id,
                    'candidate_number' => $student->candidate_number,
                    'candidate_name' => $student->first_name . ' ' . $student->last_name,
                    'course_name' => $student->course,
                    'module_id' => $record->module->id ?? null,
                    'module_name' => $record->module->module_name ?? 'N/A',
                ];
            }
        }
        return view('advanced_diploma.advanced_semester_exams', compact('studentsData'));
    }


    public function create()
    {
        $students = Student::where('course', 'Advanced Diploma In Systemic Family Therapy')->get();
        $course_modules = AdvancedDiplomaModule::all();
        $modules = Module::where('course_id', 1)->get();

        return view('advanced_diploma.advanced_diploma_results.create', compact('students', 'course_modules', 'modules'));
    }

     public function semesterexams()  
    {
        $students = Student::where('course', 'Advanced Diploma In Systemic Family Therapy')->get();
        $course_modules = AdvancedDiplomaModule::all();
        $modules = Module::where('course_id', 1)
                    ->where('module_name', '!=', 'Module 5')
                    ->get();

        return view('advanced_diploma.advanced_diploma_results.add_exam', compact('students', 'course_modules', 'modules'));
    }


    public function storeResults(Request $request)
    {
        $request->validate([
            'results' => 'required|array|min:1',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.course_module' => 'required',
            'results.*.module_id' => 'required|exists:modules,id',
            'results.*.mark' => 'required|integer|min:0|max:100',
        ]);

        foreach ($request->input('results') as $result) {
            $studentId = $result['student_id'];
            $moduleId = $result['module_id'];
            $courseModule = $result['course_module'];
            $mark = $result['mark'];

            $module = \App\Models\Module::find($moduleId);
            if (!$module) {
                return response()->json(['error' => 'Invalid module selected.'], 400);
            }

            $isModule5 = strtolower(trim($module->module_name)) === 'module 5';

            // Handle special fields for Module 5
            if ($isModule5 && in_array($courseModule, ['final_theory', 'clinicals'])) {
                $finalEvaluation = \App\Models\AdvancedFinalEvaluation::firstOrNew([
                    'student_id' => $studentId,
                ]);

                // Check if already recorded
                if (!is_null($finalEvaluation->$courseModule)) {
                    return response()->json([
                        'error' => "This student already has this recorded."
                    ], 400);
                }

                $finalEvaluation->$courseModule = $mark;
                $finalEvaluation->save();
                continue;
            }

            // Handle ADFT110 inside Module 5
            $course = \App\Models\AdvancedDiplomaModule::find($courseModule);
            if (!$course) {
                return response()->json(['error' => 'Invalid course module selected.'], 400);
            }

            if ($isModule5 && $course->course_code === 'ADFT110') {
                $finalEvaluation = \App\Models\AdvancedFinalEvaluation::firstOrNew([
                    'student_id' => $studentId,
                ]);

                if (!is_null($finalEvaluation->adft110_internal)) {
                    return response()->json([
                        'error' => "This student already has this recorded."
                    ], 400);
                }

                $finalEvaluation->adft110_internal = $mark;
                $finalEvaluation->save();
                continue;
            }

            // AdvancedDiplomaResult
            $existingResult = \App\Models\AdvancedDiplomaResult::where('student_id', $studentId)
                ->where('module_id', $moduleId)
                ->where('course_module', $courseModule)
                ->first();

            if ($existingResult) {
                return response()->json([
                    'error' => 'This student already has this course module recorded.'
                ], 400);
            }

            \App\Models\AdvancedDiplomaResult::create([
                'student_id' => $studentId,
                'module_id' => $moduleId,
                'course_module' => $courseModule,
                'mark' => $mark,
            ]);
        }

        return response()->json(['success' => 'Results added successfully!']);
    }


    public function storeSemesterExamMark(Request $request)
    {
        $request->validate([
            'results' => 'required|array|min:1',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.module_id' => 'required|exists:modules,id',
            'results.*.exam_mark' => 'required|integer|min:0|max:100',
        ]);

        foreach ($request->input('results') as $result) {
            $existing = AdvancedSemesterExam::where('student_id', $result['student_id'])
                ->where('module_id', $result['module_id'])
                ->first();

            if ($existing) {
                return response()->json(['error' => 'This student already has a record for this module.'], 400);
            }

            AdvancedSemesterExam::create([
                'student_id' => $result['student_id'],
                'module_id' => $result['module_id'],
                'exam_mark' => $result['exam_mark'],
            ]);
        }

        return response()->json(['success' => 'Results added successfully!']);
    }

    public function show($student_id, $module_id)
    {
        $advanceddiplomaResults = AdvancedDiplomaResult::where('student_id', $student_id)
            ->where('module_id', $module_id)
            ->with(['student', 'courseModule', 'Module'])
            ->get();

        if ($advanceddiplomaResults->isEmpty()) {
            abort(404, 'No diploma results found for this student and module.');
        }

        $student = $advanceddiplomaResults->first()->student;
        $module = $advanceddiplomaResults->first()->Module;

        // Loading the AdvancedFinalEvaluation
        $finalEvaluation = null;
        if ($module->module_name === 'Module 5') {
            $finalEvaluation = AdvancedFinalEvaluation::where('student_id', $student_id)->first();
        }

        return view('advanced_diploma.advanced_diploma_results.show', compact(
            'advanceddiplomaResults', 'module', 'student', 'finalEvaluation'
        ));
    }



    public function examshow($student_id, $module_id)
    {
        $examResults = AdvancedSemesterExam::where('student_id', $student_id)
                            ->where('module_id', $module_id)
                            ->with(['student', 'module']) 
                            ->get();

        if ($examResults->isEmpty()) {
            abort(404, 'No exam results found for this student.');
        }

        return view('advanced_diploma.advanced_diploma_results.examshow', compact('examResults'));
    }

    public function advancededit($student_id, $course_module)
    {
   
        $advanceddiplomaResult = AdvancedDiplomaResult::where('student_id', $student_id)
            ->where('course_module', $course_module)
            ->with(['student', 'Module', 'courseModule'])
            ->firstOrFail();

        $courseModules = AdvancedDiplomaModule::all();


        return view('advanced_diploma.advanced_diploma_results.advancededit', compact('advanceddiplomaResult', 'courseModules'));
    }

    public function advancedExamedit($id)
    {
        $examResult = AdvancedSemesterExam::findOrFail($id);
        $modules = Module::where('course_id', 1)
                    ->where('module_name', '!=', 'Module 5')
                    ->get();


        return view('advanced_diploma.advanced_diploma_results.advancedexamedit', compact('examResult', 'modules'));
    }

    public function ExamUpdate(Request $request, $id)
    {
        $request->validate([
            'exam_mark' => 'required|numeric|min:0|max:100',
            'module_id' => 'required|exists:modules,id'
        ]);

        $exam = AdvancedSemesterExam::findOrFail($id);
        $exam->exam_mark = $request->exam_mark;
        $exam->module_id = $request->module_id;
        $exam->save();

        return redirect()->route('advanced_diploma.advanced_semester_exams')->with('success', 'Exam result updated successfully.');
    }

    public function update(Request $request, $id)
    {
        $advanced_diplomaResult = AdvancedDiplomaResult::findOrFail($id);

        $validatedData = $request->validate([
            'mark' => 'required|numeric|min:0|max:100', 
            'course_module' => 'required', 
        ]);

        $advanced_diplomaResult->mark = $validatedData['mark'];
        $advanced_diplomaResult->course_module = $validatedData['course_module']; 
        
        $advanced_diplomaResult->save();

        return redirect()->route('advanced_diploma.advanced_coursework')->with('success', 'Result updated successfully.');
    }

    public function destroy($student_id)
    {
        $advanceddiplomaResults = AdvancedDiplomaResult::where('student_id', $student_id)->first();
    
        if (!$advanceddiplomaResults) {
            abort(404, 'Advanced Diploma result not found for this student.');
        }
    
        $advanceddiplomaResults->delete();
    
        return redirect()->route('advanced_diploma.advanced_coursework')->with('success', 'Advanced Diploma result deleted successfully!');
    }
    
    // Exam Delete in Edit Form
    public function advanced_diploma_resultdelete($id)
    {
        $advanceddiplomaResults = AdvancedDiplomaResult::findOrFail($id);
        $advanceddiplomaResults->delete();
        return redirect()->route('advanced_diploma.advanced_coursework')->with('success', 'Record deleted successfully.');
    } 
    
    public function advanced_diploma_examdelete($id)
    {
        $examResult = AdvancedSemesterExam::findOrFail($id);
        
        if (!$examResult) {
            abort(404, 'Record not found for this student.');
        }
        $examResult->delete();
        return redirect()->route('advanced_diploma.advanced_semester_exams')->with('success', 'Exam record deleted successfully.');
    }  

    public function advancedresults()
    {
        $students = Student::whereHas('advanceddiplomaresultsRecords') 
            ->with(['advanceddiplomaresultsRecords.courseModule', 'advanceddiplomaresultsRecords.Module']) 
            ->get();

        $studentsData = [];

        $seen = []; 

        foreach ($students as $student) {
            foreach ($student->advanceddiplomaresultsRecords as $record) {
                $key = $student->id . '-' . $record->module_id;

                if (!isset($seen[$key])) {
                    $studentsData[] = [
                        'id' => $student->id,
                        'candidate_number' => $student->candidate_number,
                        'candidate_name' => $student->first_name . ' ' . $student->last_name,
                        'course_name' => $student->course,
                        'course_module' => optional($record->Module)->module_name,
                        'module_id' => $record->module_id,
                    ];

                    $seen[$key] = true;
                }
            }
        }

        return view('advanced_diploma.advanced_results', compact('studentsData'));
    }


    public function advancedshow($student_id, $module_id)
    {
        $advanced_diplomaResults = AdvancedDiplomaResult::where('student_id', $student_id)
            ->where('module_id', $module_id)
            ->with(['student', 'module', 'courseModule'])
            ->get();

        if ($advanced_diplomaResults->isEmpty()) {
            abort(404, 'No coursework results found.');
        }

        $student = $advanced_diplomaResults->first()->student;
        $module = $advanced_diplomaResults->first()->module;

        // Grade each record
        $advanced_diplomaResults->each(function ($result) {
            $result->grade = $this->getGrade($result->mark);
        });

        // Calculate overall grade
        $marks = $advanced_diplomaResults->pluck('mark')->toArray(); 

        $moduleName = strtolower($advanced_diplomaResults->first()->module->module_name);
        $overallGrade = 'NAN'; 
        $evaluationGrades = []; 

        if ($moduleName === 'module 5') {
            $overall_a = round(collect($marks)->avg());

            $finalEval = AdvancedFinalEvaluation::where('student_id', $student_id)->first();

            if ($finalEval) {
                $finalMarks = [
                    $finalEval->adft110_internal,
                    $finalEval->final_theory,
                    $finalEval->clinicals,
                ];

                $overall_b = round(collect($finalMarks)->avg());

                $overallGrade = $this->getGrade(round(($overall_a + $overall_b) / 2));
            }

        } else {
            $exam = AdvancedSemesterExam::where('student_id', $student_id)
                ->where('module_id', $module_id)
                ->first();

            $examMark = $exam ? $exam->exam_mark : null;

            $combinedMarks = $marks;

            if (!is_null($examMark)) {
                $combinedMarks[] = $examMark;
            }

            if (count($combinedMarks) > 0) {
                $overallAverage = round(collect($combinedMarks)->avg());
                $overallGrade = $this->getGrade($overallAverage);
            }
        }

        // End-of-Module exam grade
        $examGrades = AdvancedSemesterExam::where('student_id', $student_id)
            ->where('module_id', $module_id)
            ->get()
            ->mapWithKeys(function ($exam) {
                return [$exam->module_id => $this->getGrade($exam->exam_mark)];
            });

        // Handle Module 5 final evaluation
        $finalEvaluation = null;
        $finalEvaluationGrades = [];

        if ($module && $module->module_name === 'Module 5') {
            $finalEvaluation = AdvancedFinalEvaluation::where('student_id', $student_id)->first();

            if ($finalEvaluation) {
                $finalEvaluationGrades = [
                    'Applied Research In Family Therapy' => $this->getGrade($finalEvaluation->adft110_internal),
                    'Final Theory'       => $this->getGrade($finalEvaluation->final_theory),
                    'Clinicals'          => $this->getGrade($finalEvaluation->clinicals),
                ];
            }
        }

        return view('advanced_diploma.advanced_diploma_results.advancedshow', compact(
            'advanced_diplomaResults',
            'overallGrade',
            'examGrades',
            'finalEvaluationGrades'
        ));
    }

    public function evaluationsedit($id)
    {
        $evaluation = AdvancedFinalEvaluation::findOrFail($id);
        return view('advanced_diploma.advanced_diploma_results.evaluationsedit', compact('evaluation'));
    }

    public function evaluationsupdate(Request $request, $id)
    {
        $request->validate([
            'adft110_internal' => 'required|numeric|min:0|max:100',
            'final_theory' => 'required|numeric|min:0|max:100',
            'clinicals' => 'required|numeric|min:0|max:100',
        ]);

        $evaluation = AdvancedFinalEvaluation::findOrFail($id);
        $evaluation->update([
            'adft110_internal' => $request->adft110_internal,
            'final_theory' => $request->final_theory,
            'clinicals' => $request->clinicals,
        ]);

        return redirect()->route('advanced_diploma.advanced_coursework')
            ->with('success', 'Record updated successfully.');
    }


    public function evaluationsdestroy($id)
    {
        $evaluation = AdvancedFinalEvaluation::findOrFail($id);
        $evaluation->delete();

        return redirect()->route('advanced_diploma.advanced_coursework')
                         ->with('success', 'Final evaluation deleted successfully.');
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

