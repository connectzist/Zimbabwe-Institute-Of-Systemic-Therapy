<?php

namespace App\Http\Controllers\Courses;

use App\Models\Course;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function PHPUnit\Framework\returnSelf;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        return view('courses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'course_id' => 'required|integer',
            'duration' => 'required|string',
            'instructor' => 'required|string',
        ]);
    
        Course::create([
            'title' => $request->title,
            'course_id' => $request->course_id,
            'duration' => $request->duration,
            'instructor' => $request->instructor,
        ]);
    
        return redirect()->route('courses.index')->with('success', 'Course created successfully');
    }

    public function show(string $id)
    {
        $course = Course::findOrFail($id);
        return view('courses.show', compact('course'));
    }
  
    public function edit(string $id)
    {
        $course = Course::findOrFail($id);
        return view('courses.edit', compact('course'));
    }

    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'course_id' => 'required|integer',
            'duration' => 'required|string',
            'instructor' => 'required|string',
        ]);
    
        $course->update([
            'title' => $request->title,
            'course_id' => $request->course_id,
            'duration' => $request->duration,
            'instructor' => $request->instructor,
        ]);
    
        return redirect()->route('courses.index')->with('success', 'Course updated successfully');
    }
 
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        $course->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted successfully');

    }
}
