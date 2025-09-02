<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\LGU;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $documents = Document::with('lgu')->get();
        $lgus = LGU::all(); // for dropdown
        return view('documents', compact('documents', 'lgus'));
    }

   public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'file' => 'required|file|mimes:pdf,doc,docx,xlsx,png,jpg,jpeg|max:5120',
        'lgu_id' => 'nullable|exists:lgus,id',
    ]);

    $file = $request->file('file');
    $path = $file->store('documents', 'public');

    Document::create([
        'title' => $request->title,
        'description' => $request->description,
        'file_name' => $file->getClientOriginalName(), // <-- THIS fixes the error
        'file_path' => $path,
        'lgu_id' => $request->lgu_id,
    ]);

    return redirect()->route('documents.index')
                     ->with('success', 'Document uploaded successfully!');
}

    public function destroy(Document $document)
    {
        // Delete file from storage if it exists
        if (file_exists(storage_path('app/public/' . $document->file_path))) {
            unlink(storage_path('app/public/' . $document->file_path));
        }

        // Delete document record
        $document->delete();

        return redirect()->route('documents.index')
                         ->with('success', 'Document deleted successfully!');
    }
}
