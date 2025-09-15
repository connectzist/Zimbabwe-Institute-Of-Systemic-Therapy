<?php

namespace App\Http\Controllers\Admins;

use App\Models\Module;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\DiplomaResult;
use App\Models\CertificateResult;
use App\Models\DiplomaFinalModule;
use App\Http\Controllers\Controller;
use App\Models\AdvancedSemesterExam;
use App\Models\AdvancedDiplomaModule;
use App\Models\AdvancedDiplomaResult;
use App\Models\AdvancedFinalEvaluation;

class SuperAdminController extends Controller
{
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

        return view('students_results.certificate.cert_results', compact('studentsData'));
    }

    public function certificate_view($student_id)
    {
        $certificateResults = CertificateResult::where('student_id', $student_id)
            ->with('courseModule', 'student')
            ->get();

        // Process each result: calculate grades and module average
        $certificateResults->each(function ($result) {
            $result->theory_grade = $this->getGrade($result->theory_mark);
            $result->practical_grade = $this->getGrade($result->practical_mark);
            $result->exam_grade = $this->getGrade($result->exam_mark);

            $result->average_mark = round((
                $result->theory_mark + $result->practical_mark + $result->exam_mark
            ) / 3);

            $result->module_grade = $this->getGrade($result->average_mark);
        });

        return view('students_results.certificate.cert_view', compact('certificateResults'));
    }

    
    public function diploma_view($student_id)
    {
        // Regular diploma results
        $diplomaResults = DiplomaResult::where('student_id', $student_id)
            ->with('courseModule', 'student')
            ->get();

        $diplomaResults->each(function ($result) {
            $result->theory_grade = $this->getGrade($result->theory_mark);
            $result->practical_grade = $this->getGrade($result->practical_mark);
            $result->exam_grade = $this->getGrade($result->exam_mark);

            $result->average_mark = round((
                $result->theory_mark + $result->practical_mark + $result->exam_mark
            ) / 3);

            $result->module_grade = $this->getGrade($result->average_mark);
            $result->is_final_module = false;
        });

        // Final modules (additional records)
        $finalModules = DiplomaFinalModule::where('student_id', $student_id)
            ->with('module', 'student')
            ->get();

        $finalModules->each(function ($final) {
            $final->practical_grade = $this->getGrade($final->practical_mark);
            $final->exam_grade = $this->getGrade($final->exam_mark);
            $final->research_grade = $this->getGrade($final->research_mark);

            $final->average_mark = round((
                $final->exam_mark + $final->practical_mark + $final->research_mark
            ) / 3);

            $final->module_grade = $this->getGrade($final->average_mark);
            $final->is_final_module = true;
        });

        // Merge both result types
        $allResults = $diplomaResults->concat($finalModules);

        return view('students_results.diploma.diploma_view', [
            'allResults' => $allResults
        ]);
    }


    public function diplomaresults()
    {
        $students = Student::whereHas('diplomaresultsRecords') 
            ->with('diplomaresultsRecords.courseModule') 
            ->get();

        $studentsData = $students->map(function ($student) {
            $courseModules = $student->diplomaresultsRecords->flatMap(function ($record) {
                if ($record->courseModule) {
                    return [$record->courseModule]; 
                }
                return [];
            })->unique('id'); 
    
            $courseModuleCount = $courseModules->count();
    
            return [
                'id' => $student->id, 
                'candidate_number' => $student->candidate_number,
                'candidate_name' => $student->first_name . ' ' . $student->last_name,
                'course_name' => $student->course,
                'course_module_count' => $courseModuleCount,   
            ];
        });

        return view('students_results.diploma.diploma_results', compact('studentsData'));
    }

    public function advanced_diplomaresults()
    {
        $students = Student::whereHas('advanceddiplomaresultsRecords') 
            ->with('advanceddiplomaresultsRecords.courseModule') 
            ->get();

        $studentsData = $students->map(function ($student) {
            $courseModules = $student->advanceddiplomaresultsRecords->flatMap(function ($record) {
                if ($record->courseModule) {
                    return [$record->courseModule]; 
                }
                return [];
            })->unique('id'); 
    
            $courseModuleCount = $courseModules->count();
    
            return [
                'id' => $student->id, 
                'candidate_number' => $student->candidate_number,
                'candidate_name' => $student->first_name . ' ' . $student->last_name,
                'course_name' => $student->course,
                'course_module_count' => $courseModuleCount,   
            ];
        });

        return view('students_results.advanced_diploma.advanced_diplomaresults', compact('studentsData'));
    }

   public function advanced_diploma_view($student_id)
    {
        $advanced_diplomaResults = AdvancedDiplomaResult::where('student_id', $student_id)
            ->with(['student', 'module', 'courseModule'])
            ->get();

        if ($advanced_diplomaResults->isEmpty()) {
            abort(404, 'No coursework results found.');
        }

        $student = $advanced_diplomaResults->first()->student;

        // Grade each coursework result
        $advanced_diplomaResults->each(function ($result) {
            $result->grade = $this->getGrade($result->mark);
        });

        // Group by module_id
        $groupedByModule = $advanced_diplomaResults->groupBy('module_id');

        $moduleData = [];

        foreach ($groupedByModule as $moduleId => $moduleResults) {
            $moduleName = optional($moduleResults->first()->module)->module_name ?? 'Unknown Module';
            $courseTitles = [];
            $marks = [];

            foreach ($moduleResults as $res) {
                $courseTitles[] = [
                    'title' => optional($res->courseModule)->course_title,
                    'grade' => $res->grade,
                ];
                $marks[] = $res->mark;
            }

            $courseworkAverage = round(collect($marks)->avg());
            $courseworkGrade = $this->getGrade($courseworkAverage);

            // Exam Grading (Modules 1–4 only)
            $exam = AdvancedSemesterExam::where('student_id', $student_id)
                ->where('module_id', $moduleId)
                ->first();

            $examGrade = $exam ? $this->getGrade($exam->exam_mark) : 'N/A';

            // Final evaluation (Module 5)
            $evaluationGrades = [];
            $finalEvaluationAverage = null;

            if (strtolower($moduleName) === 'module 5') {
                $finalEval = AdvancedFinalEvaluation::where('student_id', $student_id)->first();

                if ($finalEval) {
                    $evaluationGrades = [
                        'Applied Research In Family Therapy' => $this->getGrade($finalEval->adft110_internal),
                        'Final Theory' => $this->getGrade($finalEval->final_theory),
                        'Clinicals' => $this->getGrade($finalEval->clinicals),
                    ];

                    $finalMarks = [
                        $finalEval->adft110_internal,
                        $finalEval->final_theory,
                        $finalEval->clinicals,
                    ];

                    $finalEvaluationAverage = round(collect($finalMarks)->avg());

                    // Overall: coursework + final evaluation
                    $overallPass = $this->getGrade(round(($courseworkAverage + $finalEvaluationAverage) / 2));
                } else {
                    $overallPass = 'NAN';
                }

            } else {
                // For other modules: coursework + exam
                if ($exam) {
                    $overallAverage = round(($courseworkAverage + $exam->exam_mark) / 2);
                    $overallPass = $this->getGrade($overallAverage);
                } else {
                    $overallPass = 'NAN';
                }
            }

            $moduleData[] = [
                'module_name' => $moduleName,
                'course_titles' => $courseTitles,
                'exam_grade' => $examGrade,
                'evaluation_grades' => $evaluationGrades,
                'overall_grade' => $overallPass,
            ];
        }

        return view('students_results.advanced_diploma.advanced_diploma_view', compact('moduleData', 'student'));
    }


    // Publishing results.

   public function Diploma_PublishResult(Request $request)
    {
        $moduleId = $request->query('module_id');

        if (!$moduleId) {
            return back()->withErrors(['error' => 'Module ID is required to publish results.']);
        }

        $module = Module::find($moduleId);
        $moduleName = $module?->module_name;

        $isModule4 = strtolower($moduleName) === 'module 4';

        $studentQuery = Student::where('course', 'Diploma In Systemic Family Counselling');

        if ($isModule4) {
            // Check if all students have records in DiplomaFinalModule
            $students = $studentQuery->whereHas('diplomaFinalRecords', function ($query) use ($moduleId) {
                                $query->where('module_id', $moduleId);
                            })->get();

            $studentsNotRecorded = $studentQuery->whereDoesntHave('diplomaFinalRecords', function ($query) use ($moduleId) {
                                        $query->where('module_id', $moduleId);
                                    })->get();

            if ($studentsNotRecorded->isNotEmpty()) {
                return back()->withErrors([
                    'error' => "We can't publish the results because there are " . $studentsNotRecorded->count() . " student(s) who are not recorded for this module."
                ]);
            }

            // Check if already published
            $alreadyPublished = DiplomaFinalModule::where('is_published', true)
                                                    ->where('module_id', $moduleId)
                                                    ->exists();

            if ($alreadyPublished) {
                return back()->withErrors([
                    'error' => "The results for this module are already published."
                ]);
            }

            // Publishing
            DiplomaFinalModule::where('is_published', false)
                                ->where('module_id', $moduleId)
                                ->update(['is_published' => true]);
        } else {
            // Checking If all students have records in DiplomaResult
            $students = $studentQuery->whereHas('diplomaresultsRecords', function ($query) use ($moduleId) {
                                $query->where('module_id', $moduleId);
                            })->get();

            $studentsNotRecorded = $studentQuery->whereDoesntHave('diplomaresultsRecords', function ($query) use ($moduleId) {
                                        $query->where('module_id', $moduleId);
                                    })->get();

            if ($studentsNotRecorded->isNotEmpty()) {
                return back()->withErrors([
                    'error' => "We can't publish the results because there are " . $studentsNotRecorded->count() . " student(s) who are not recorded for this module."
                ]);
            }

            // If already published
            $alreadyPublished = DiplomaResult::where('is_published', true)
                                                ->where('module_id', $moduleId)
                                                ->exists();

            if ($alreadyPublished) {
                return back()->withErrors([
                    'error' => "The results for this module are already published."
                ]);
            }

            // Publishing
            DiplomaResult::where('is_published', false)
                            ->where('module_id', $moduleId)
                            ->update(['is_published' => true]);
        }

        return redirect()->back()->with('success', 'Your results for the selected module have been published successfully!');
    }


    public function diploma_publish(Request $request)
    {
        $moduleId = $request->get('module_id');
        $isPublished = false;
        $studentsData = collect(); 

        // Get selected module 
        $selectedModule = Module::find($moduleId);
        $moduleName = $selectedModule?->module_name;

    if ($moduleId) {
        // For Module 4 ==> DiplomaFinalModule
        if (strtolower($moduleName) === 'module 4') {
            $isPublished = DiplomaFinalModule::where('is_published', true)
                                ->where('module_id', $moduleId)
                                ->exists();

            $students = Student::whereHas('diplomaFinalRecords', function ($query) use ($moduleId) {
                    $query->where('module_id', $moduleId);
                })
                ->with([
                    'diplomaFinalRecords' => function ($query) use ($moduleId) {
                        $query->where('module_id', $moduleId);
                    },
                    'diplomaFinalRecords.courseModule'
                ])
                ->get();

            $studentsData = $students->map(function ($student) {
                $record = $student->diplomaFinalRecords->first();
                $moduleName = optional($record?->courseModule)->module_name;
                return [
                    'candidate_number' => $student->candidate_number,
                    'course_name' => $student->course,
                    'course_module' => $moduleName ?? 'N/A',
                ];
            });

        } else {
            // Normal case: use DiplomaResult
            $isPublished = DiplomaResult::where('is_published', true)
                                ->where('module_id', $moduleId)
                                ->exists();

            $students = Student::whereHas('diplomaresultsRecords', function ($query) use ($moduleId) {
                    $query->where('module_id', $moduleId);
                })
                ->with([
                    'diplomaresultsRecords' => function ($query) use ($moduleId) {
                        $query->where('module_id', $moduleId);
                    },
                    'diplomaresultsRecords.courseModule'
                ])
                ->get();

            $studentsData = $students->map(function ($student) {
                $record = $student->diplomaresultsRecords->first();
                $moduleName = optional($record?->courseModule)->module_name;
                return [
                    'candidate_number' => $student->candidate_number,
                    'course_name' => $student->course,
                    'course_module' => $moduleName ?? 'N/A',
                ];
            });
        }
    }

    // Load modules for dropdown
        $modules = Module::where('course_id', 2)->get();

        return view('students_results.diploma.results_publish', compact(
            'studentsData',
            'isPublished',
            'modules',
            'moduleId'
        ));
    }

    public function certificate_publish(Request $request)
    {
        $moduleId = $request->get('module_id');

        $isPublished = false;
        if ($moduleId) {
            $isPublished = CertificateResult::where('is_published', true)
                                            ->where('module_id', $moduleId)
                                            ->exists();
        }

        $students = Student::whereHas('certresultsRecords', function ($query) use ($moduleId) {
                if ($moduleId) {
                    $query->where('module_id', $moduleId);
                }
            })
            ->with([
                'certresultsRecords' => function ($query) use ($moduleId) {
                    if ($moduleId) {
                        $query->where('module_id', $moduleId);
                    }
                },
                'certresultsRecords.courseModule'
            ])
            ->get();

        $studentsData = $students->map(function ($student) {
            $record = $student->certresultsRecords->first();
            $moduleName = optional($record?->courseModule)->module_name;
            return [
                'candidate_number' => $student->candidate_number,
                'course_name' => $student->course,
                'course_module' => $moduleName ?? 'N/A',
            ];
        });

        // Load modules for dropdown
        $modules = Module::where('course_id', 3)->get();

        return view('students_results.certificate.results_publish', compact(
            'studentsData',
            'isPublished',
            'modules',
            'moduleId' 
        ));
    }



    public function certificate_unpublishResults(Request $request)
    {
        $moduleId = $request->query('module_id');

        if (!$moduleId) {
            return back()->withErrors(['error' => 'Module ID is required to unpublish results.']);
        }

        $certificateResults = CertificateResult::where('is_published', true)
                                            ->where('module_id', $moduleId);

        if ($certificateResults->count() == 0) {
            return back()->withErrors([
                'error' => "There are no published results for this module to unpublish."
            ]);
        }

        $certificateResults->update(['is_published' => false]);

        return redirect()->back()->with('success', 'Results for the selected module have been unpublished successfully.');
    }

    public function diploma_unpublishResults(Request $request)
    {
        $moduleId = $request->query('module_id');

        if (!$moduleId) {
            return back()->withErrors(['error' => 'Module ID is required to unpublish results.']);
        }

        // Get module
        $module = Module::find($moduleId);
        $moduleName = $module?->module_name;

        $isModule4 = strtolower($moduleName) === 'module 4';

        if ($isModule4) {
            $diplomaFinalResults = DiplomaFinalModule::where('is_published', true)
                                                    ->where('module_id', $moduleId);

            if ($diplomaFinalResults->count() === 0) {
                return back()->withErrors([
                    'error' => "There are no published results for this module to unpublish."
                ]);
            }

            $diplomaFinalResults->update(['is_published' => false]);
        } else {
            $diplomaResults = DiplomaResult::where('is_published', true)
                                        ->where('module_id', $moduleId);

            if ($diplomaResults->count() === 0) {
                return back()->withErrors([
                    'error' => "There are no published results for this module to unpublish."
                ]);
            }

            $diplomaResults->update(['is_published' => false]);
        }

        return redirect()->back()->with('success', 'Results for the selected module have been unpublished successfully.');
    }



    public function Certificate_PublishResult(Request $request)
    {
        $moduleId = $request->query('module_id');

        if (!$moduleId) {
            return back()->withErrors(['error' => 'Module ID is required to publish results.']);
        }

        // Get students in the specified module
        $students = Student::where('course', 'Certificate In Systemic Family Counselling')
                            ->whereHas('certresultsRecords', function ($query) use ($moduleId) {
                                $query->where('module_id', $moduleId);
                            })
                            ->get();

        // Checking for students without records for this module
        $studentsNotRecorded = Student::where('course', 'Certificate In Systemic Family Counselling')
            ->whereDoesntHave('certresultsRecords', function ($query) use ($moduleId) {
                $query->where('module_id', $moduleId);
            })
            ->get();

        if ($studentsNotRecorded->isNotEmpty()) {
            return back()->withErrors([
                'error' => "We can't publish the results because there are " . $studentsNotRecorded->count() . " student(s) who are not recorded for this module."
            ]);
        }

        $alreadyPublished = CertificateResult::where('is_published', true)
                                            ->where('module_id', $moduleId)
                                            ->exists();

        if ($alreadyPublished) {
            return back()->withErrors([
                'error' => "The results for this module are already published."
            ]);
        }

        CertificateResult::where('is_published', false)
                        ->where('module_id', $moduleId)
                        ->update(['is_published' => true]);

        return redirect()->back()->with('success', 'Your results for the selected module have been published successfully!');
    }

    

    public function Advanced_Diploma_PublishResult(Request $request)
    {
            $moduleId = $request->query('module_id');

            if (!$moduleId) {
                return back()->withErrors(['error' => 'Module ID is required to publish results.']);
            }

            $courseName = 'Advanced Diploma In Systemic Family Therapy';

            // Get the module
            $module = Module::find($moduleId);
            if (!$module) {
                return back()->withErrors(['error' => 'Invalid module selected.']);
            }

            $moduleName = $module->module_name;
            $isModule5 = strtolower($moduleName) === 'module 5';

            // Get all students in the course
            $students = Student::where('course', $courseName)->get();

            // Rule 1: Ensure each student has at least one result for the module
            $studentsNotRecorded = Student::where('course', $courseName)
                ->whereDoesntHave('advanceddiplomaresultsRecords', function ($query) use ($moduleId) {
                    $query->where('module_id', $moduleId);
                })
                ->get();

            if ($studentsNotRecorded->isNotEmpty()) {
                return back()->withErrors([
                    'error' => "We can't publish the results because " . $studentsNotRecorded->count() . " student(s) have no result for this module."
                ]);
            }

            // Rule 2: Modified for Module 5 with ADFT110 exception
            // Get all required course modules with their codes
            $requiredCourseModules = AdvancedDiplomaModule::where('module_id', $moduleId)
                ->get(['id', 'course_code']);

            $studentsWithCompleteModules = [];
            $studentsWithIncompleteModules = [];

            foreach ($students as $student) {
                // Get course modules the student has completed for this module
                $completedModules = AdvancedDiplomaResult::where('student_id', $student->id)
                    ->where('module_id', $moduleId)
                    ->pluck('course_module')
                    ->toArray();

                // Find missing course modules
                $missingModules = $requiredCourseModules->filter(function ($module) use ($completedModules) {
                    return !in_array($module->id, $completedModules);
                });

                // For Module 5: Allow missing ADFT110
                if ($isModule5) {
                    // Check if missing any module other than ADFT110
                    $hasCriticalMissing = $missingModules->contains(function ($module) {
                        return $module->course_code !== 'ADFT110';
                    });

                    if ($missingModules->isEmpty() || !$hasCriticalMissing) {
                        // Student is complete if no missing modules or only missing ADFT110
                        $studentsWithCompleteModules[] = $student->id;
                    } else {
                        $studentsWithIncompleteModules[] = $student->id;
                    }
                } 
                // Non-Module 5: Strict requirement
                else {
                    if ($missingModules->isEmpty()) {
                        $studentsWithCompleteModules[] = $student->id;
                    } else {
                        $studentsWithIncompleteModules[] = $student->id;
                    }
                }
            }

            if (!empty($studentsWithIncompleteModules)) {
                $count = count($studentsWithIncompleteModules);
                $errorMsg = $isModule5
                    ? "$count student(s) are missing required modules!"
                    : "$count student(s) have not completed all course modules!";
                    
                return back()->withErrors(['error' => "We can't publish because $errorMsg."]);
            }

            // Rule 3: If NOT module 5, check for AdvancedSemesterExam
            if (!$isModule5) {
                $studentIdsWithExam = AdvancedSemesterExam::where('module_id', $moduleId)
                    ->whereIn('student_id', $studentsWithCompleteModules)
                    ->pluck('student_id')
                    ->toArray();

                $studentsMissingExam = array_diff($studentsWithCompleteModules, $studentIdsWithExam);

                if (!empty($studentsMissingExam)) {
                    return back()->withErrors([
                        'error' => "We can't publish because " . count($studentsMissingExam) . " student(s) are missing End of Semester Exam records for this module."
                    ]);
                }

                // Check if already published
                $alreadyPublished = AdvancedSemesterExam::where('is_published', true)
                    ->where('module_id', $moduleId)
                    ->exists();

                if ($alreadyPublished) {
                    return back()->withErrors([
                        'error' => "The results for this module are already published."
                    ]);
                }

                // Publish results
                AdvancedDiplomaResult::where('is_published', false)
                    ->where('module_id', $moduleId)
                    ->update(['is_published' => true]);

                AdvancedSemesterExam::where('is_published', false)
                    ->where('module_id', $moduleId)
                    ->update(['is_published' => true]);

            } else {
                // Module 5: Check Final Evaluation exists
                $studentIdsWithFinalEvaluation = AdvancedFinalEvaluation::whereIn('student_id', $studentsWithCompleteModules)
                    ->pluck('student_id')
                    ->toArray();

                $studentsMissingFinal = array_diff($studentsWithCompleteModules, $studentIdsWithFinalEvaluation);

                if (!empty($studentsMissingFinal)) {
                    return back()->withErrors([
                        'error' => "We can't publish because " . count($studentsMissingFinal) . " student(s) are missing Final Evaluation records for this module."
                    ]);
                }

                // Check if already published
                $alreadyPublished = AdvancedFinalEvaluation::where('is_published', true)
                    ->whereIn('student_id', $studentsWithCompleteModules)
                    ->exists();

                if ($alreadyPublished) {
                    return back()->withErrors([
                        'error' => "The results for this module are already published."
                    ]);
                }

                // Publish results
                AdvancedDiplomaResult::where('is_published', false)
                    ->where('module_id', $moduleId)
                    ->update(['is_published' => true]);

                AdvancedFinalEvaluation::where('is_published', false)
                    ->whereIn('student_id', $studentsWithCompleteModules)
                    ->update(['is_published' => true]);
            }

            return redirect()->back()->with('success', 'Your results for the selected module have been published successfully!');
    }


    public function Advanced_diploma_unpublishResults(Request $request)
    {
        $moduleId = $request->query('module_id');

        if (!$moduleId) {
            return back()->withErrors(['error' => 'Module ID is required to unpublish results.']);
        }

        // Get the module details
        $module = Module::find($moduleId);
        if (!$module) {
            return back()->withErrors(['error' => 'Invalid module selected.']);
        }

        $moduleName = $module->module_name;
        $isModule5 = strtolower($moduleName) === 'module 5';

        // Handling Module 5 
        if ($isModule5) {
            // Get students with published Module 5 results
            $studentIds = AdvancedDiplomaResult::where('is_published', true)
                ->where('module_id', $moduleId)
                ->pluck('student_id')
                ->unique()
                ->toArray();

            // Find published results and final evaluations
            $publishedResults = AdvancedDiplomaResult::where('is_published', true)
                ->where('module_id', $moduleId);

            $publishedFinalEvals = AdvancedFinalEvaluation::where('is_published', true)
                ->whereIn('student_id', $studentIds);

            // Checking records to unpublish
            if ($publishedResults->count() === 0 && $publishedFinalEvals->count() === 0) {
                return back()->withErrors([
                    'error' => "There are no published results or final evaluations for Module 5 to unpublish."
                ]);
            }

            // Unpublish both types of records
            $publishedResults->update(['is_published' => false]);
            $publishedFinalEvals->update(['is_published' => false]);

        } else {
            // Handle non-Module 5 cases
            $publishedResults = AdvancedDiplomaResult::where('is_published', true)
                ->where('module_id', $moduleId);

            $publishedExams = AdvancedSemesterExam::where('is_published', true)
                ->where('module_id', $moduleId);

            // Checking records to unpublish
            if ($publishedResults->count() === 0 && $publishedExams->count() === 0) {
                return back()->withErrors([
                    'error' => "There are no published results or exams for this module to unpublish."
                ]);
            }

            // Unpublish both types of records
            $publishedResults->update(['is_published' => false]);
            $publishedExams->update(['is_published' => false]);
        }

        return redirect()->back()->with('success', 'Results for the selected module have been unpublished successfully.');
    }

    public function Advanced_Diploma_publish(Request $request)
    {
        $moduleId = $request->get('module_id');

        $isPublished = false;
        if ($moduleId) {
            $isPublished = AdvancedDiplomaResult::where('is_published', true)
                                            ->where('module_id', $moduleId)
                                            ->exists();
        }

        $students = Student::whereHas('advanceddiplomaresultsRecords', function ($query) use ($moduleId) {
                if ($moduleId) {
                    $query->where('module_id', $moduleId);
                }
            })
            ->with([
                'advanceddiplomaresultsRecords' => function ($query) use ($moduleId) {
                    if ($moduleId) {
                        $query->where('module_id', $moduleId);
                    }
                },
                'advanceddiplomaresultsRecords.courseModule'
            ])
            ->get();

        $studentsData = $students->map(function ($student) {
            $record = $student->advanceddiplomaresultsRecords->first();
            $moduleName = optional($record?->Module)->module_name;
            return [
                'candidate_number' => $student->candidate_number,
                'course_name' => $student->course,
                'course_module' => $moduleName ?? 'N/A',
            ];
        });

        // Load modules for dropdown
        $modules = Module::where('course_id', 1)->get();

        return view('students_results.advanced_diploma.results_publish', compact(
            'studentsData',
            'isPublished',
            'modules',
            'moduleId' 
        ));
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