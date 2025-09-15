<?php

namespace App\Http\Controllers\Finance;

use App\Models\Fee;
use App\Models\Course;
use App\Models\Student;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeesController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $sortField = $request->input('sort', 'payment_date'); 
        $sortDirection = $request->input('direction', 'desc'); 
    
        $fees = Fee::with('student')
            ->when($search, function ($query) use ($search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('candidate_number', 'like', "%$search%");
                })
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('amount', 'like', "%$search%")
                ->orWhere('status', 'like', "%$search%");
            })
            ->orderBy($sortField, $sortDirection)
            ->paginate(10);
    
        return view('fees.index', compact('fees', 'sortField', 'sortDirection'));
    }

    public function create()
    {
        $courses = Course::all();
        $students = Student::all();
        return view('fees.create', compact('students','courses'));
    }

    public function store(Request $request)
    {
        try {
            $status = $request->amount > 0 ? 1 : 0;
            
            Fee::create([
                'student_id' => $request->student_id,
                'course_id' => $request->course_id,
                'amount' => $request->amount,
                'description' => $request->description,
                'payment_date' => $request->payment_date,
                'status' => $status,
            ]);

            return redirect()->route('fees.index')->with('success', 'Fee record added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('fees.index')->with('error', 'Failed to add fee record.');
        }
    }

    public function show(string $id)
    {
        $fee = Fee::with('student')->findOrFail($id);
        return view('fees.show', compact('fee'));
    }

    public function edit(string $id)
    {
        $courses = Course::all();
        $fee = Fee::findOrFail($id);
        $students = Student::all();
        return view('fees.edit', compact('fee', 'students','courses'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $fee = Fee::findOrFail($id);
            
            $fee->student_id = $request->student_id;
            $fee->course_id = $request->course_id;
            $fee->amount = $request->amount;
            $fee->payment_date = $request->payment_date;
            $fee->status = $request->status;
            $fee->description = $request->description;

            
            
            $fee->save();
            //Success
            return redirect()->route('fees.index')->with('success', 'Fee record updated successfully.');
        } catch (\Exception $e) {
            //Failure    
            return redirect()->route('fees.index')->with('error', 'Failed to update fee record.');
        }
    }
    
    public function destroy(string $id)
    {
        $fee = Fee::findOrFail($id);
        $fee->delete();

        return redirect()->route('fees.index')->with('success', 'Fee record deleted successfully.');
    }

    
    public function search(Request $request)
    {
        $query = $request->input('query');
        $students = Student::where('candidate_number', 'LIKE', "%{$query}%")->get();

        return response()->json($students);
    }

    public function getCourse($id)
    {
        $student = Student::find($id);

        if (!$student || !$student->course_id) {
            return response()->json([
                'course_id' => null,
                'title' => 'Not enrolled'
            ]);
        }

        $course = \App\Models\Course::find($student->course_id);

        if (!$course) {
            return response()->json([
                'course_id' => null,
                'title' => 'Course not found'
            ]);
        }

        return response()->json([
            'course_id' => $course->id,
            'title' => $course->title
        ]);
    }



}
