<?php

namespace App\Http\Controllers\Modules;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Models\DiplomaModule;
use App\Http\Controllers\Controller;

class DiplomaModulesController extends Controller
{
    public function index()
    {
        $diplomaModules = DiplomaModule::all();
        return view('diploma.index', compact('diplomaModules'));
    }

    public function create()
    {
        $modules = Module::where('course_id', 2)->get();
        return view('diploma.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:10|unique:diploma_modules,course_code',
            'course_title' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'module_id' => 'required|exists:modules,id',
        ]);
        
        DiplomaModule::create([
            'course_code' => $request->course_code,
            'course_title' => $request->course_title,
            'credits' => $request->credits,
            'module_id' => $request->module_id,
        ]);
    
        return redirect()->route('diploma.index')->with('success', 'Module created successfully.');
    }

    public function show(DiplomaModule $diplomaModule)
    {
        return view('diploma.show', ['module' => $diplomaModule]);
    }

    public function edit(DiplomaModule $diplomaModule)
    {
        $modules = Module::where('course_id', 2)->get();
        return view('diploma.edit', compact('modules', 'diplomaModule'));

    }

    public function update(Request $request, DiplomaModule $diplomaModule)
    {
        $request->validate([
            'course_code' => 'required|unique:diploma_modules,course_code,' . $diplomaModule->id,
            'course_title' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'module_id' => 'required|exists:modules,id',
        ]);

        $diplomaModule->update($request->all());

        return redirect()->route('diploma.index')->with('success', 'Diploma module updated successfully.');
    }

    public function destroy(DiplomaModule $diplomaModule)
    {
        $diplomaModule->delete();

        return redirect()->route('diploma.index')->with('success', 'Diploma module deleted successfully.');
    }
}
