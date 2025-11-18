<?php

namespace App\Http\Controllers\Admins;

use App\Models\Module;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\DiplomaModule;
use App\Models\DiplomaResult;
use App\Models\DiplomaFinalModule;
use App\Http\Controllers\Controller;

class DiplomaAdminController extends Controller
{
    public function diplomaStudents()
    {
        $diplomaStudents = Student::where('course', 'Diploma In Systemic Family Counselling')->get();
        return view('diploma.diploma_students', compact('diplomaStudents'));
    }

    public function modules()
    {
        $diplomaModules = DiplomaModule::all();
        return view('diploma.diploma_modules', compact('diplomaModules'));
    }

    public function coursework()
    {
        $students = Student::whereHas('diplomaresultsRecords')  
            ->with('diplomaresultsRecords.courseModule')  
            ->get();
    
        $studentsData = $students->map(function ($student) {
            $moduleName = optional($student->diplomaresultsRecords->first()->courseModule)->module_name;
            
            return [
                'id' => $student->id, 
                'candidate_number' => $student->candidate_number,
                'candidate_name' => $student->first_name . ' ' . $student->last_name,
                'course_name' => $student->course,
                'course_module' => $moduleName, 
                'overall_pass' => '', 
            ];  

            })->unique('id');  

        return view('diploma.diploma_coursework', compact('studentsData'));
    }

    public function finalmodule()
    {
        $students = Student::whereHas('diplomaFinalRecords')  
            ->with('diplomaFinalRecords.module')  
            ->get();
    
        $studentsData = $students->map(function ($student) {
            $moduleName = optional($student->diplomaresultsRecords->first()->courseModule)->module_name;
            
            return [
                'id' => $student->id, 
                'candidate_number' => $student->candidate_number,
                'candidate_name' => $student->first_name . ' ' . $student->last_name,
                'course_name' => $student->course,
                'course_module' => $moduleName, 
                'overall_pass' => '', 
            ];  

            })->unique('id');  

        return view('diploma.diploma_finalmodule', compact('studentsData'));
    }

    public function create()
    {
        $students = Student::where('course', 'Diploma In Systemic Family Counselling')->get();
        $modules = Module::where('course_id', 2)->get();


        return view('diploma.diploma_results.create', compact('students', 'modules'));
    }


    public function storeResults(Request $request)
    {
        $request->validate([
            'results' => 'required|array|min:1',
            'results.*.student_id' => 'required|exists:students,id',
            'results.*.module_id' => 'required|exists:modules,id',
            'results.*.exam_mark' => 'required|integer|min:0|max:100',
            'results.*.practical_mark' => 'required|integer|min:0|max:100',
            'results.*.research_mark' => 'nullable|integer|min:0|max:100',
            'results.*.theory_mark' => 'nullable|integer|min:0|max:100',
        ]);

        foreach ($request->results as $result) {
            $module = Module::find($result['module_id']);
            $isModule4 = strtolower($module->module_name) === 'module 4';

            if ($isModule4) {
                // Check for duplicates
                $exists = DiplomaFinalModule::where('student_id', $result['student_id'])
                            ->where('module_id', $result['module_id'])->first();

                if ($exists) {
                    return response()->json(['error' => 'This student already has results for this module.'], 400);
                }

                // Save to DiplomaResult (Module 4)
                DiplomaFinalModule::create([
                    'student_id' => $result['student_id'],
                    'module_id' => $result['module_id'],
                    'exam_mark' => $result['exam_mark'],
                    'practical_mark' => $result['practical_mark'],
                    'research_mark' => $result['research_mark'],
                ]);
            } else {
                // Check for duplicates
                $exists = DiplomaResult::where('student_id', $result['student_id'])
                            ->where('module_id', $result['module_id'])->first();

                if ($exists) {
                    return response()->json(['error' => 'This student already has results for this module.'], 400);
                }

                // Saving to DiplomaModuleResult (non-Module 4)
                DiplomaResult::create([
                    'student_id' => $result['student_id'],
                    'module_id' => $result['module_id'],
                    'exam_mark' => $result['exam_mark'],
                    'practical_mark' => $result['practical_mark'],
                    'theory_mark' => $result['theory_mark'],
                ]);
            }
        }

        return response()->json(['success' => 'Results added successfully!']);
    }


    public function FinalModuleshow($student_id)
    {
        $diplomaResults = DiplomaFinalModule::where('student_id', $student_id)
                                        ->with(['student', 'module'])
                                        ->get();
        if ($diplomaResults->isEmpty()) {
            abort(404, 'No diploma results found for this student.');
        }
        return view('diploma.diploma_results.finalmoduleshow', compact('diplomaResults'));
    }


    public function show($student_id)
    {
        $diplomaResults = DiplomaResult::where('student_id', $student_id)
                                        ->with(['student', 'courseModule'])
                                        ->get();
        if ($diplomaResults->isEmpty()) {
            abort(404, 'No diploma results found for this student.');
        }
        return view('diploma.diploma_results.show', compact('diplomaResults'));
    }

    public function diplomaedit($id)
    {
        $modules = Module::where('course_id', 2)->get();
        $diplomaResult = DiplomaResult::findOrFail($id);
        $courseModules = DiplomaModule::all();
        
        return view('diploma.diploma_results.diplomaedit', compact('diplomaResult', 'courseModules','modules'));
    }

    public function diplomafinaledit($id)
    {
        $modules = Module::where('course_id', 2)
                    ->where('module_name', 'Module 4')
                    ->get();
        $diplomaResult = DiplomaFinalModule::findOrFail($id);
        $courseModules = DiplomaModule::all();

        return view('diploma.diploma_results.diplomafinaledit', compact('diplomaResult', 'courseModules','modules'));
    }

    public function update(Request $request, $id)
    {
        $diplomaResult = DiplomaResult::findOrFail($id);

        $validatedData = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'exam_mark' => 'required|numeric|min:0|max:100',
            'practical_mark' => 'required|numeric|min:0|max:100',
            'theory_mark' => 'required|numeric|min:0|max:100',
        ]);

        $diplomaResult->module_id = $validatedData['module_id'];
        $diplomaResult->exam_mark = $validatedData['exam_mark'];
        $diplomaResult->practical_mark = $validatedData['practical_mark'];
        $diplomaResult->theory_mark = $validatedData['theory_mark'];

        $diplomaResult->save();

        return redirect()->route('diploma.diploma_coursework')->with('success', 'Diploma result updated successfully.');
    }

     public function finalmoduleupdate(Request $request, $id)
    {
        $diplomaResult = DiplomaFinalModule::findOrFail($id);

        $validatedData = $request->validate([
            'module_id' => 'required|exists:modules,id',
            'exam_mark' => 'required|numeric|min:0|max:100',
            'practical_mark' => 'required|numeric|min:0|max:100',
            'research_mark' => 'required|numeric|min:0|max:100',
        ]);

        $diplomaResult->module_id = $validatedData['module_id'];
        $diplomaResult->exam_mark = $validatedData['exam_mark'];
        $diplomaResult->practical_mark = $validatedData['practical_mark'];
        $diplomaResult->research_mark = $validatedData['research_mark'];

        $diplomaResult->save();

        return redirect()->route('diploma.diploma_finalmodule')->with('success', 'Diploma result updated successfully.');
    }

    public function destroy($student_id)
    {
        $diplomaResult = DiplomaResult::where('student_id', $student_id)->first();
    
        if (!$diplomaResult) {
            abort(404, 'Diploma result not found for this student.');
        }
    
        $diplomaResult->delete();
    
        return redirect()->route('diploma.diploma_coursework')->with('success', 'Diploma result deleted successfully!');
    }

    public function finalmoduledestroy($student_id)
    {
        $diplomaResult = DiplomaFinalModule::where('student_id', $student_id)->first();
    
        if (!$diplomaResult) {
            abort(404, 'Diploma cecord result not found for this student.');
        }
    
        $diplomaResult->delete();
    
        return redirect()->route('diploma.diploma_finalmodule')->with('success', 'Diploma record deleted successfully!');
    }

    public function diploma_resultdelete($id)
    {
        $diplomaResults = DiplomaResult::findOrFail($id);
        $diplomaResults->delete();

        return redirect()->back()->with('success', 'Record deleted successfully.');
    }  

    public function diploma_resultfinaldelete($id)
    {
        $diplomaResults = DiplomaFinalModule::findOrFail($id);
        $diplomaResults->delete();
        
        return redirect()->route('diploma.diploma_finalmodule')->with('success', 'Record deleted successfully.');

    }  

    public function diplomaresults()
    {
        $students = Student::whereHas('diplomaresultsRecords') 
            ->with('diplomaresultsRecords.courseModule') 
            ->get();

        $studentsData = $students->map(function ($student) {
            $moduleName = optional($student->diplomaresultsRecords->first()->courseModule)->module_name;

                return [
                'id' => $student->id, 
                'candidate_number' => $student->candidate_number,
                'candidate_name' => $student->first_name . ' ' . $student->last_name,
                'course_name' => $student->course,
                'course_module' => $moduleName, 
                'overall_pass' => '', 
            ];
            
            })->unique('id'); 

        return view('diploma.diploma_results', compact('studentsData'));
    }

    public function diploshow($student_id)
    {
        $diplomaResults = DiplomaResult::where('student_id', $student_id)
            ->with(['courseModule', 'student']) 
            ->get();

        $diplomaResults->each(function ($result) {
            $result->theory_grade = $this->getGrade($result->theory_mark);
            $result->practical_grade = $this->getGrade($result->practical_mark);
            $result->exam_grade = $this->getGrade($result->exam_mark);
            $result->average_mark = round(($result->theory_mark + $result->practical_mark + $result->exam_mark) / 3);
        });

        $allMarks = $diplomaResults->flatMap(function ($result) {
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

        $finalModules = DiplomaFinalModule::where('student_id', $student_id)
            ->with('module') 
            ->get();

        $finalModules->each(function ($module) {
            $module->exam_grade = $this->getGrade($module->exam_mark);
            $module->practical_grade = $this->getGrade($module->practical_mark);
            $module->research_grade = $this->getGrade($module->research_mark);
        });

        return view('diploma.diploma_results.diploshow', compact('diplomaResults', 'overallGrade', 'finalModules'));
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



