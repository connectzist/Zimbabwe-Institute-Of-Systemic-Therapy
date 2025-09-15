<?php
namespace App\Http\Controllers\Transcripts;
use App\Http\Controllers\Controller;

use App\Models\Student;
use setasign\Fpdi\Fpdi;
use Illuminate\Http\Request;
use TheSeer\Tokenizer\Exception;

class TranscriptController extends Controller
{
    public function index()
    {
        return view('transcripts.view');
    }

    public function search(Request $request)
    {
        $request->validate([
            'search' => 'nullable|string|max:255',
            'type' => 'required|in:first_name,last_name,candidate_number,email,course',
        ]);

        $query = Student::query();

        if ($request->type === 'first_name') {
            $query->where('first_name', 'like', '%' . $request->search . '%');
        } elseif ($request->type === 'last_name') {
            $query->where('last_name', 'like', '%' . $request->search . '%');
        } elseif ($request->type === 'candidate_number') {
            $query->where('candidate_number', 'like', '%' . $request->search . '%');
        } elseif ($request->type === 'email') {
            $query->where('email', 'like', '%' . $request->search . '%');
        } elseif ($request->type === 'course') {
            $query->where(function ($query) use ($request) {
                $query->where('course', '=', $request->search)
                    ->orWhereHas('certresultsRecords', function ($query) use ($request) {
                        $query->where('course', '=', $request->search);
                    })
                    ->orWhereHas('diplomaresultsRecords', function ($query) use ($request) {
                        $query->where('course', '=', $request->search);
                    })
                    ->orWhereHas('advanceddiplomaresultsRecords', function ($query) use ($request) {
                        $query->where('course', '=', $request->search);
                    });
            });
        }

        $students = $query->get();

        return view('transcripts.view', compact('students'));
    }

    public function generateTranscriptPDF($student_id)
    {
        $student = Student::with(['diplomaresultsRecords.courseModule', 'certresultsRecords.courseModule', 'advanceddiplomaresultsRecords.courseModule'])->findOrFail($student_id);
       
        $diplomaCount = $student->diplomaresultsRecords->count();
        $certificateCount = $student->certresultsRecords->count();
        $advancedDiplomaCount = $student->advanceddiplomaresultsRecords->count();
   
        if (!($diplomaCount === 8 || $certificateCount === 7 || $advancedDiplomaCount === 11)) {
            return back()->withErrors([
                'error' => 'Student does not have required records for a transcript to be generated.'
            ])->withInput();
            
        }
   
        // Setting up FPDI => importing the PDF template
        $pdf = new Fpdi();
        $pdf->AddPage();
       
        // Path
        $templateFile = public_path('/transcript/ZIST_Transcript.pdf');
       
        // Importing the PDF template
        $pdf->setSourceFile($templateFile);
        $template = $pdf->importPage(1);
        $pdf->useTemplate($template);
   
        // Fonts
        $pdf->SetFont('Arial', '', 12);
   
       // Positions
        $pdf->SetXY(20, 60); 
        $pdf->Write(0, 'Name                     :                         ' . $student->first_name . ' ' . $student->last_name);
   
        $pdf->SetXY(20, 70); 
        $pdf->Write(0, 'Candidate No         :                       ' . $student->candidate_number);
   
        $pdf->SetXY(20, 80); 
        $pdf->Write(0, 'Course                   :                        ' . $student->course);
   
        // Start position => results
        $yPos = 100;
   
        // headers
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->SetXY(20, $yPos);
        $pdf->Write(0, 'Course Title');
        $pdf->SetXY(160, $yPos); 
        $pdf->Write(0, 'Grade');
        $yPos += 10; 
   
         //  Diploma Transcript
        $pdf->SetFont('Arial', '', 12);
        if ($student->diplomaresultsRecords) {
            foreach ($student->diplomaresultsRecords as $result) {
                // Course Name
                $pdf->SetXY(20, $yPos);
                $pdf->Write(0, $result->courseModule->course_title); 
   
                // Grade
                $pdf->SetXY(163, $yPos); 
                $pdf->Write(0, $this->getGrade($result->mark)); 
   
                $yPos += 10;
            }
        }
   
        //  Certificate Transcript
        if ($student->certresultsRecords) {
            foreach ($student->certresultsRecords as $result) {
                // Course Name
                $pdf->SetXY(20, $yPos);
                $pdf->Write(0, $result->courseModule->course_title); 
   
                // Grade
                $pdf->SetXY(163, $yPos);
                $pdf->Write(0, $this->getGrade($result->mark)); 
   
                $yPos += 10;
            }
   
            //  Advanced Diploma Transcript
            if ($student->advanceddiplomaresultsRecords) {
                foreach ($student->advanceddiplomaresultsRecords as $result) {
                    // Course title
                    $pdf->SetXY(20, $yPos);
                    $pdf->Write(0, $result->courseModule->course_title); 
   
                    // Grade
                    $pdf->SetXY(163, $yPos); 
                    $pdf->Write(0, $this->getGrade($result->mark)); 
   
                    $yPos += 10; 
                }
            }
   
            if ($student->certresultsRecords->count() === 7) {
                $totalMarks = $student->certresultsRecords->sum('mark');
                $averageMark = $totalMarks / 7;
                $overallGrade = $this->getGrade($averageMark);
            } elseif ($student->diplomaresultsRecords->count() === 8) {
                $totalMarks = $student->diplomaresultsRecords->sum('mark');
                $averageMark = $totalMarks / 8;
                $overallGrade = $this->getGrade($averageMark);
            } elseif ($student->advanceddiplomaresultsRecords->count() === 11) {
                $totalMarks = $student->advanceddiplomaresultsRecords->sum('mark');
                $averageMark = $totalMarks / 11;
                $overallGrade = $this->getGrade($averageMark);
            } else {
                $overallGrade = 'NAN'; 
            }
   
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetXY(20, $yPos);
            $pdf->Write(0, 'Overall Pass');
            $pdf->SetXY(163, $yPos);
            $pdf->Write(0, $overallGrade); 
   
            $yPos += 10;
        }

         // Importing Page 2
        $pdf->AddPage(); 
   
        $pdf->setSourceFile($templateFile); 
        try {
            $template2 = $pdf->importPage(2); 
            $pdf->useTemplate($template2);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to import page 2 from template: ' . $e->getMessage()], 500);
        }

   
        return $pdf->Output('I', 'transcript.pdf');
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
