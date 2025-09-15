<?php

namespace App\Http\Controllers\Uploads;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    public function index()
    {
        $timetables = Timetable::all();
        return view('uploads.timetables.view', compact('timetables'));
    }

    public function create()
    {
        return view('uploads.timetables.upload_timetable');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,doc,docx|max:10240', 
            'student_type' => 'required|in:certificate,diploma,advanced',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');

            $originalName = $file->getClientOriginalName();

            $path = $file->storeAs('timetables', $originalName, 'public');

            Timetable::create([
                'title' => $request->title,
                'file_path' => $path,
                'student_type' => $request->student_type,
            ]);

            return redirect()->route('timetables.view')->with('success', 'Timetable uploaded successfully.');
        }

        return redirect()->route('timetables.view')->with('error', 'Failed to upload timetable.');
    }

    public function edit(Timetable $timetable)
    {
        return view('uploads.timetables.edit', compact('timetable'));
    }

    public function update(Request $request, Timetable $timetable)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'student_type' => 'required|in:certificate,diploma,advanced',
        ]);

        $timetable->update([
            'title' => $request->title,
            'student_type' => $request->student_type,
        ]);

        if ($request->hasFile('file')) {

            if ($timetable->file_path) {
                Storage::delete('public/' . $timetable->file_path);
            }

            $file = $request->file('file');
            $path = $file->store('timetables', 'public');
            $timetable->update(['file_path' => $path]);
        }

        return redirect()->route('timetables.view')->with('success', 'Timetable updated successfully.');
    }

    public function destroy(Timetable $timetable)
    {
        if ($timetable->file_path) {
            Storage::delete('public/' . $timetable->file_path);
        }
        
        $timetable->delete();

        return redirect()->route('timetables.view')->with('success', 'Timetable deleted successfully.');
    }
}
