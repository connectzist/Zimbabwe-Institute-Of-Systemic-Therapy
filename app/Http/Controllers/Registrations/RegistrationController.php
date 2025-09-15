<?php

namespace App\Http\Controllers\Registrations;
use App\Http\Controllers\Controller;

use App\Models\Registration;
use App\Models\Student;
use App\Models\Module;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function manualRegistration(Request $request)
    {
        $students = Student::all();
        $modules = collect();

        if ($request->has('student_id')) {
            $student = Student::find($request->student_id);
            $modules = Module::where('course_id', $student->course_id)->get();
        }

        return view('registration.create_reg', compact('students', 'modules'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'module_id' => 'required|exists:modules,id',
        ]);

        Registration::create([
            'student_id' => $request->student_id,
            'module_id' => $request->module_id,
            'registered' => true, 
        ]);

        return redirect()->route('registration.registered_students')->with('success', 'Registration created successfully!');
    }

    public function getCourse($id)
    {
        $student = Student::find($id);
        return response()->json($student->course->name);
    }

    public function edit($id)
    {
        $registration = Registration::findOrFail($id);

        $students = Student::all();

        $modules = [];

        $student = $registration->student;

        if ($student) {
            $course_id = $student->course_id;

            $modules = Module::where('course_id', $course_id)->get();
        }

        return view('registration.edit_reg', compact('registration', 'students', 'modules'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'module_id' => 'required|exists:modules,id',
            'registered' => 'nullable|boolean', 
        ]);

        $registration = Registration::findOrFail($id);

        $registration->student_id = $request->student_id;
        $registration->module_id = $request->module_id;

        $registration->registered = $request->has('registered') ? true : false;

        $registration->save();

        return redirect()->route('registration.registered_students')->with('success', 'Registration updated successfully!');
    }
    
    public function destroy($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->delete();

        return redirect()->route('registration.registered_students')->with('success', 'Registration deleted successfully!');
    }
}
