<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\LGU;
use App\Models\Bidding;
use App\Traits\DocumentProcessorTrait; // Import the trait
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class DocumentController extends Controller
{
    use DocumentProcessorTrait; // Use the trait logic

    // Show documents page
    public function index()
    {
        $documents = Document::with('lgu')->get();
        $lgus = LGU::all();
        return view('documents', compact('documents', 'lgus'));
    }

    // Upload template
    public function store(Request $request)
    {
        $file = $request->file('file');

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|mimetypes:application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/octet-stream|max:5120',
            'lgu_id' => 'nullable|exists:lgus,id',
        ]);

        $path = $file->store('documents', 'public');

        Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'lgu_id' => $request->lgu_id,
        ]);

        return redirect()->route('documents.index')->with('success', 'Document uploaded successfully!');
    }

    // Delete template
    public function destroy(Document $document)
    {
        if (file_exists(storage_path('app/public/' . $document->file_path))) {
            unlink(storage_path('app/public/' . $document->file_path));
        }

        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document deleted successfully!');
    }

    // Generate Word file from selected bidding + chosen template
    public function generateFromTemplate($biddingId, $documentId)
    {
        $bidding = Bidding::with('lgu')->findOrFail($biddingId);
        $document = Document::findOrFail($documentId);

        if (!str_ends_with($document->file_name, '.docx')) {
            return back()->with('error', 'Only .docx templates can be used.');
        }

        $templatePath = storage_path('app/public/' . $document->file_path);
        if (!file_exists($templatePath)) {
            return back()->with('error', 'Template not found.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // ✅ Apply placeholders using the Trait (Shared with BiddingController)
        $this->applyPlaceholders($templateProcessor, $bidding);

        // Ensure output directory exists
        $outputDir = storage_path('app/generated');
        if (!File::exists($outputDir)) {
            File::makeDirectory($outputDir, 0755, true);
        }

        // ✅ Use the Trait to generate a safe, short filename
        $fileName = $this->getSafeFileName(
            $document->title, 
            $bidding->lgu->name ?? 'LGU', 
            $bidding->project_name, 
            $bidding->id, 
            $document->id
        );

        $outputPath = $outputDir . DIRECTORY_SEPARATOR . $fileName;

        $templateProcessor->saveAs($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

    public function dashboard()
    {
        $biddingsCount = Bidding::count();
        $documentsCount = Document::count();

        return view('dashboard', compact('biddingsCount', 'documentsCount'));
    }
}