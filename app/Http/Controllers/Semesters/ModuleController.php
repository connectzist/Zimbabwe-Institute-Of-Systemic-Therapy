<?php

namespace App\Http\Controllers\Semesters;
use App\Http\Controllers\Controller;
use App\Models\Module;
use App\Models\Course;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index()
    {
        $modules = Module::with('course')->get();
        return view('modules.view_modules', compact('modules'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('modules.create_module', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'reg_start' => 'required|date',
            'reg_due' => 'required|date|after_or_equal:reg_start',
        ]);

        Module::create([
            'module_name' => $request->module_name,
            'course_id' => $request->course_id,
            'reg_start' => $request->reg_start,
            'reg_due' => $request->reg_due,
        ]);

        return redirect()->route('modules.view_modules')->with('success', 'Module created successfully.');
    }

    public function edit($id)
    {
        $module = Module::findOrFail($id);
        $courses = Course::all(); 
        return view('modules.edit_module', compact('module', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'module_name' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'reg_start' => 'required|date',
            'reg_due' => 'required|date|after_or_equal:reg_start',
        ]);

        $module = Module::findOrFail($id);
        $module->update([
            'module_name' => $request->module_name,
            'course_id' => $request->course_id,
            'reg_start' => $request->reg_start,
            'reg_due' => $request->reg_due,
        ]);

        return redirect()->route('modules.view_modules')->with('success', 'Module updated successfully.');
    }

    public function destroy($id)
    {
        $module = Module::findOrFail($id);
        $module->delete();

        return redirect()->route('modules.view_modules')->with('success', 'Module deleted successfully.');
    }

    public function getModulesByCourse($courseId)
    {

    $modules = Module::where('course_id', $courseId)->get();

    return response()->json(['modules' => $modules]);
    }

}
