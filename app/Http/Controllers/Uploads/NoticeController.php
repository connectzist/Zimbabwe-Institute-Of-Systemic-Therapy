<?php

namespace App\Http\Controllers\Uploads;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::all();
        return view('uploads.notices.view', compact('notices'));
    }

    public function create()
    {
        return view('uploads.notices.upload_notice');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'expiry_date' => 'required|date|after_or_equal:today',
            'student_type' => 'required|in:All,certificate,diploma,advanced',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
    
            $originalName = $file->getClientOriginalName();
    
            $path = $file->storeAs('notices', $originalName, 'public');

            Notice::create([
                'title' => $validated['title'],
                'content' => $validated['content'],
                'file_path' =>$path ,
                'expiry_date' => $validated['expiry_date'],
                'student_type' => $request->student_type,
            ]);
         return redirect()->route('notices.view')->with('success', 'Notice created successfully!');
         }

         return redirect()->route('timetables.view')->with('error', 'Failed to upload timetable.');
    }

    public function edit($id)
    {
        $notice = Notice::findOrFail($id);
        return view('uploads.notices.edit', compact('notice'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
            'expiry_date' => 'required|date|after_or_equal:today',
            'student_type' => 'required|in:All,certificate,diploma,advanced',
        ]);
    
        $notice = Notice::findOrFail($id);

        $notice->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'expiry_date' => $validated['expiry_date'],
            'student_type' => $validated['student_type'],
        ]);

        if ($request->hasFile('file')) {
            if ($notice->file_path) {
                Storage::delete('public/' . $notice->file_path);
            }

            $file = $request->file('file');
            $filePath = $file->store('notices', 'public');

            $notice->update(['file_path' => $filePath]);
        }

        return redirect()->route('notices.view')->with('success', 'Notice updated successfully!');
    }
    
    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);

        if ($notice->file_path && Storage::exists($notice->file_path)) {
            Storage::delete($notice->file_path);
        }

        $notice->delete();

        return redirect()->route('notices.view')->with('success', 'Notice deleted successfully!');
    }

}
