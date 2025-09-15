<?php

namespace App\Http\Controllers\Modules;

use App\Models\Module;
use App\Models\CertificateModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CertificateModuleController extends Controller
{
    public function index()
    {
        $certificateModules = CertificateModule::all();
        return view('certificate.index', compact('certificateModules'));
    }

    public function create()
    {
        $modules = Module::where('course_id', 3)->get();
        return view('certificate.create', compact('modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_code' => 'required|string|max:10|unique:certificate_modules,course_code',
            'course_title' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'module_id' => 'required|exists:modules,id', 
        ]);

        CertificateModule::create([
            'course_code' => $request->course_code,
            'course_title' => $request->course_title,
            'credits' => $request->credits,
            'module_id' => $request->module_id, 
        ]);

        return redirect()->route('certificate.index')->with('success', 'Certificate module created successfully.');
    }

    public function show(CertificateModule $certificateModule)
    {
        return view('certificate.show', ['module' => $certificateModule]);
    }

    public function edit(CertificateModule $certificateModule)
    {
        $modules = Module::where('course_id', 3)->get();
        return view('certificate.edit', compact('certificateModule', 'modules'));
    }

    public function update(Request $request, CertificateModule $certificateModule)
    {
        $request->validate([
            'course_code' => 'required|string|max:10|unique:certificate_modules,course_code,' . $certificateModule->id,
            'course_title' => 'required|string|max:255',
            'credits' => 'required|integer|min:1',
            'module_id' => 'required|exists:modules,id',
        ]);

        $certificateModule->update([
            'course_code' => $request->course_code,
            'course_title' => $request->course_title,
            'credits' => $request->credits,
            'module_id' => $request->module_id,
        ]);

        return redirect()->route('certificate.index')->with('success', 'Certificate module updated successfully.');
    }

    public function destroy(CertificateModule $certificateModule)
    {
        $certificateModule->delete();

        return redirect()->route('certificate.index')->with('success', 'Certificate module deleted successfully.');
    }
}
