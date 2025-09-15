<?php

namespace App\Http\Controllers\Student;

use App\Models\Course;
use App\Models\Student;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    private function assignCourseTitle(Request $request)
    {
        $course = Course::find($request->course_id);
        return $course ? $course->title : null;
    }

    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%")
                  ->orWhere('candidate_number', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('course', 'LIKE', "%{$search}%");
            });
        }

        $sortField = $request->input('sort', 'first_name');
        $sortDirection = $request->input('direction', 'asc');
        $query->orderBy($sortField, $sortDirection);

        $students = $query->paginate(10)->appends($request->query());

        return view('students.index', compact('students', 'sortField', 'sortDirection'));
    }

    public function create(): View
    {
        $courses = Course::all();
        return view('students.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'candidate_number' => 'required|string|max:255|unique:students,candidate_number|regex:/^[a-zA-Z]+\d+$/',
            'course_id' => 'required|exists:courses,id',
            'date_of_birth' => 'required|date',
            'enrollment_date' => 'required|date',
            'cell_No' => 'required|regex:/^\+?[0-9]{10,13}$/',
            'address' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'id_number' => 'required|string|max:255|unique:students,id_number',
            'nationality' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle the profile picture upload
        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $validatedData['profile_picture'] = $profilePicturePath;
        }

        $candidateNumber = strtolower($validatedData['candidate_number']);
        $email = $candidateNumber . '@connect.org.zw';

        $studentEmailPattern = '/^[a-zA-Z]+\d+@connect\.org\.zw$/';
        if (!preg_match($studentEmailPattern, $email)) {
            return redirect()->route('students.create')
                             ->withErrors(['candidate_number' => 'Invalid candidate number format.'])
                             ->withInput();
        }

        $validatedData['email'] = $email;
        $validatedData['course'] = $this->assignCourseTitle($request);

        Student::create($validatedData);

        return redirect()->route('students.index')->with('success', 'Student added successfully.');
    }

    public function show($id): View
    {
        $student = Student::findOrFail($id);
        return view('students.show', compact('student'));
    }

    public function edit(int $id): View
    {
        $student = Student::findOrFail($id);
        $courses = Course::all();
        return view('students.edit', compact('student', 'courses'));
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'candidate_number' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:students,email,' . $student->id,
            'course_id' => 'required|exists:courses,id',
            'date_of_birth' => 'required|date',
            'enrollment_date' => 'required|date',
            'cell_No' => 'required|regex:/^\+?[0-9]{10,13}$/',
            'address' => 'required|string|max:255',
            'group' => 'required|string|max:255',
            'occupation' => 'required|string|max:255',
            'id_number' => 'required|string|max:255|unique:students,id_number,' . $student->id, 
            'nationality' => 'nullable|string|max:255',
            'emergency_contact' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        // Handle the profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($student->profile_picture) {
                Storage::delete('public/' . $student->profile_picture);
            }

            // Save new profile picture
            $profilePicturePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            $request->merge(['profile_picture' => $profilePicturePath]);
        }

        $request->merge(['course' => $this->assignCourseTitle($request)]);

        $student->update($request->all());

        return redirect()->route('students.index')->with('success', 'Student updated successfully.');
    }

    public function destroy($id): RedirectResponse
    {
        try {
            $student = Student::findOrFail($id);

            // Delete profile picture
            if ($student->profile_picture) {
                Storage::delete('public/' . $student->profile_picture);
            }

            $student->delete();
            return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('students.index')->with('error', 'An error occurred while deleting the student.');
        }
    }
}
