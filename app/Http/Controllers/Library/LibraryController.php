<?php

namespace App\Http\Controllers\Library;

use App\Models\Book;
use App\Models\Course;
use App\Models\Ejournal;
use App\Models\Research;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PastExamPaper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class LibraryController extends Controller
{
    public function research()
    {
        $researches = Research::all();
        return view('uploads.research.research', compact('researches'));
    }

    public function Addresearch()
    {
        return view('uploads.research.upload_research');
    }

    public function AddNewBook()
    {
        return view('uploads.librarian.upload_book');
    }

    public function AddNewPastExamPaper()
    {
        $courses = Course::all();
        return view('uploads.librarian.upload_past_exam_papers', compact('courses'));
    }

    public function AddNewJournal()
    {
        return view('uploads.librarian.e_journal');
    }

    public function storeBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn|max:255',
            'year_of_publication' => 'required|digits:4',
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx|max:10000',
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('books', 'public');

            Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'category' => $request->category,
                'isbn' => $request->isbn,
                'year_of_publication' => $request->year_of_publication,
                'file_path' => $filePath,
            ]);

            return redirect()->route('uploads.view')->with('success', 'Book uploaded successfully');
        }

        return back()->withErrors(['file' => 'File upload failed. Please try again.']);
    }

    public function viewLibrary()
    {
        return view('uploads.librarian.library');
    }

    public function viewBooks(Request $request)
    {
        $search = $request->input('search');
        $sortField = $request->input('sort', 'title');
        $sortDirection = $request->input('direction', 'asc');

        $query = Book::query();

        if ($search) {
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhere('isbn', 'like', "%{$search}%");
        }

        $books = $query->orderBy($sortField, $sortDirection)->paginate(10);
        return view('uploads.librarian.books', compact('books', 'sortField', 'sortDirection'));
    }

    public function viewJournals(Request $request)
    {
        $search = $request->input('search');

        $journals = EJournal::when($search, function ($query, $search) {
            return $query->where('title', 'like', "%{$search}%")
                         ->orWhere('author', 'like', "%{$search}%")
                         ->orWhere('subject', 'like', "%{$search}%");
        })
        ->orderBy('created_at', 'desc') 
        ->paginate(10); 
        return view('uploads.librarian.journals', compact('journals'));
    }

    public function viewPastExamPapers(Request $request)
    {
        $search = $request->input('search');

        $pastExamPapers = PastExamPaper::with('course')
            ->when($search, function ($query, $search) {
                return $query->where('exam_title', 'like', "%{$search}%")
                             ->orWhere('module_name', 'like', "%{$search}%")
                             ->orWhereHas('course', function ($q) use ($search) {
                                 $q->where('title', 'like', "%{$search}%");
                             });
            })
            ->orderBy('exam_year', 'desc')
            ->paginate(10);
        return view('uploads.librarian.past_exam_papers', compact('pastExamPapers', 'search'));
    }

    public function storeResearch(Request $request)
    {
        $currentYear = date('Y');

            $request->validate([
            'research_title' => 'required|string|max:255',
            'researcher' => 'required|string|max:255',
            'year_of_research' => [
                'required',
                'digits:4',
                'integer',
                'min:' . ($currentYear - 2),  
                'max:' . $currentYear,      
            ],
            'student_type' => 'required|in:certificate,diploma,advanced',
            'file' => 'required|mimes:pdf,doc,docx,ppt,pptx|max:10240', 
        ]);
    

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->storeAs('researches', time() . '_' . $file->getClientOriginalName(), 'public');
        }
    
        Research::create([
            'research_title' => $request->research_title,
            'researcher' => $request->researcher,
            'year_of_research' => $request->year_of_research,
            'student_type' => $request->student_type,
            'file_path' => $filePath,
        ]);

        return redirect()->route('uploads.research')->with('success', 'Research uploaded successfully.');
    }

    public function listResearches()
    {
        $researches = Research::all();
        return view('uploads.research.list_researches', compact('researches'));
    }

    public function showResearch($id)
    {
        $research = Research::findOrFail($id);
        return view('uploads.research.show_research', compact('research'));
    }

    public function showPastExamPaper($id)
    {
        $paper = PastExamPaper::with('course')->findOrFail($id);
        return view('uploads.librarian.show_past_exam_papers', compact('paper'));
    }

    public function showBook($id)
    {
        $book = Book::findOrFail($id);
        return view('uploads.librarian.show_book', compact('book'));
    }

    public function showJournal($id)
    {
        $journal = EJournal::findOrFail($id);
        return view('uploads.librarian.show_journal', compact('journal'));
    }

    public function deleteResearch($id)
    {
        $research = Research::find($id);


        if ($research) {

            if ($research->file_path) {

                $filePath = 'public/' . $research->file_path;

                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }

            $research->delete();

            return redirect()->route('uploads.research')->with('success', 'Research deleted successfully.');
        }

        return redirect()->route('uploads.research')->with('error', 'Research not found.');
    }

    public function deleteJournal($id)
    {
        $journal = EJournal::find($id);


        if ($journal) {

            if ($journal->file_path) {

                $filePath = 'public/' . $journal->file_path;

                if (Storage::exists($filePath)) {
                    Storage::delete($filePath);
                }
            }
            $journal->delete();

            return redirect()->route('library.journals')->with('success', 'Journal deleted successfully.');
        }

        return redirect()->route('library.journals')->with('error', 'Journal not found.');
    }

    public function storeEjournal(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'nullable|string|max:100',
            'url' => 'nullable|string|max:255|alpha_dash|unique:e_journals,url',
            'file' => 'required|mimes:pdf|max:20480',
        ]);
    
        $file = $request->file('file');
        $fileName = Str::slug($request->title) . '-' . time() . '.' . $file->getClientOriginalExtension();
        $filePath = $file->storeAs('ejournals', $fileName, 'public');

        $url = $request->filled('url')
            ? Str::slug($request->url)
            : Str::slug($request->title) . '-' . uniqid();
    
        EJournal::create([
            'title' => $request->title,
            'author' => $request->author,
            'description' => $request->description,
            'subject' => $request->subject,
            'file_path' => $filePath,
            'url' => $url,
            'published_at' => now(),
        ]);
    
        return redirect()->route('library.journals', $url)->with('success', 'Journal uploaded successfully!');
    }
    
    public function storePastExamPapers(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,course_id',
            'exam_title' => 'required|string|max:255',
            'exam_year' => 'required|integer|min:1900|max:' . date('Y'),
            'module_name' => 'nullable|string|max:255',
            'file_path' => 'required|file|mimes:pdf|max:10240',
        ]);
    
        $path = $request->file('file_path')->store('past_exam_papers', 'public');
    
        PastExamPaper::create([
            'course_id' => $validated['course_id'],
            'exam_title' => $validated['exam_title'],
            'exam_year' => $validated['exam_year'],
            'module_name' => $validated['module_name'],
            'file_path' => $path,
        ]);
    
        return redirect()->route('library.past_exam_papers')->with('success', 'Exam paper uploaded successfully.');
    }

    public function BookEdit($id)
    {
        $book = Book::findOrFail($id);
        return view('uploads.librarian.edit_book', compact('book'));
    }

    public function BookUpdate(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'isbn' => 'nullable|string|max:255',
            'year_of_publication' => 'nullable|integer',
        ]);

        $book->update($validated);

        return redirect()->route('library.all.books')->with('success', 'Book updated successfully.');
    }

    public function PastExamEdit($id)
    {
        $paper = PastExamPaper::findOrFail($id);
        $courses = Course::all(); 
        return view('uploads.librarian.edit_exam_paper', compact('paper', 'courses'));
    }

    public function PastExamUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'exam_title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,course_id',
            'module_name' => 'required|string|max:255',
            'exam_year' => 'required|digits:4|integer',
        ]);

        $paper = PastExamPaper::findOrFail($id);
        $paper->update($validated);

        return redirect()->route('library.past_exam_papers')->with('success', 'Past exam paper updated successfully.');
    }

    public function JournalEdit($id)
    {
        $journal = EJournal::findOrFail($id);
        return view('uploads.librarian.edit_jounal', compact('journal'));
    }

    public function JournalUpdate(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subject' => 'required|string|max:255',
        ]);

        $journal = EJournal::findOrFail($id);
        $journal->update($validated);

        return redirect()->route('library.journals')->with('success', 'Journal updated successfully.');
    }

}

