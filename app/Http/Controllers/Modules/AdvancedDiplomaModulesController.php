<?php

namespace App\Http\Controllers\Modules;

use App\Models\Module;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdvancedDiplomaModule;

class AdvancedDiplomaModulesController extends Controller
{
    public function index()
    {
        $advancedDiplomaModules = AdvancedDiplomaModule::all();
        return view('advanced_diploma.index', compact('advancedDiplomaModules'));
    }

    public function create()
    {
        $modules = Module::where('course_id', 1)->get();
        return view('advanced_diploma.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:10|unique:advanced_diploma_modules,course_code',
            'course_title' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'module_id' => 'required|exists:modules,id',  
        ]);

        AdvancedDiplomaModule::create([
            'course_code' => $request->course_code,
            'course_title' => $request->course_title,
            'credits' => $request->credits,
            'module_id' => $request->module_id,
        ]);

        return redirect()->route('advanced_diploma.index')
            ->with('success', 'Advanced Diploma Module created successfully.');
    }

    public function show(AdvancedDiplomaModule $advancedDiplomaModule)
    {
        return view('advanced_diploma.show', compact('advancedDiplomaModule'));
    }

    public function edit($id)
    {
        $module = AdvancedDiplomaModule::findOrFail($id);
        $modules = Module::all();  

        return view('advanced_diploma.edit', compact('module', 'modules'));
    }

    public function update(Request $request, AdvancedDiplomaModule $advancedDiplomaModule)
    {

        $request->validate([
            'course_code' => 'required|string|max:10|unique:advanced_diploma_modules,course_code,' . $advancedDiplomaModule->id,
            'course_title' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'module_id' => 'required|exists:modules,id', 
        ]);

        $advancedDiplomaModule->update([
            'course_code' => $request->course_code,
            'course_title' => $request->course_title,
            'credits' => $request->credits,
            'module_id' => $request->module_id,  
        ]);

        return redirect()->route('advanced_diploma.index')
            ->with('success', 'Advanced Diploma Module updated successfully.');
    }

    public function destroy(AdvancedDiplomaModule $advancedDiplomaModule)
    {
        $advancedDiplomaModule->delete();

        return redirect()->route('advanced_diploma.index')
            ->with('success', 'Advanced Diploma Module deleted successfully.');
    }
}
