<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\LGU;
use App\Models\Bidding;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
class DocumentController extends Controller
{
    
private function formatDateWithPartOfDay($dateTime)
{
    $carbon = Carbon::parse($dateTime);

    // Determine part of the day
    $hour = $carbon->format('G'); // 0–23
    if ($hour >= 5 && $hour < 12) {
        $part = 'in the morning';
    } elseif ($hour >= 12 && $hour < 17) {
        $part = 'in the afternoon';
    } elseif ($hour >= 17 && $hour < 21) {
        $part = 'in the evening';
    } else {
        $part = 'at night';
    }

    return $carbon->format('F d, Y \a\t g:i a') . " {$part}";
}
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

    if ($file) {
        Log::info('Uploaded file details', [
            'name' => $file->getClientOriginalName(),
            'mime' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ]);
    }

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

    return redirect()->route('documents.index')
                     ->with('success', 'Document uploaded successfully!');
} // Delete template
    public function destroy(Document $document)
    {
        if (file_exists(storage_path('app/public/' . $document->file_path))) {
            unlink(storage_path('app/public/' . $document->file_path));
        }

        $document->delete();

        return redirect()->route('documents.index')
                         ->with('success', 'Document deleted successfully!');
    }

    // Quick manual test of template processor
    public function editWord(Document $document)
    {
        if (!str_ends_with($document->file_name, '.docx')) {
            return back()->with('error', 'Only .docx files can be edited.');
        }

        $templatePath = storage_path('app/public/' . $document->file_path);

        if (!file_exists($templatePath)) {
            return back()->with('error', 'File not found.');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        // Example placeholders (later use bidding data instead)
        $templateProcessor->setValue('ProjectName', 'Sample Project');
        $templateProcessor->setValue('BidDate', now()->format('F j, Y'));

        $outputPath = storage_path('app/public/edited_' . $document->file_name);
        $templateProcessor->saveAs($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }

    // 🆕 Generate Word file from selected bidding + chosen template
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

    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

    // ✅ replace placeholders
    $templateProcessor->setValue('project_name', $bidding->project_name);
    $templateProcessor->setValue('solicitation_number', $bidding->solicitation_number ?? ''); // 🆕 Added
    $templateProcessor->setValue('reference_number', $bidding->reference_number ?? '');
    $templateProcessor->setValue('abc', $bidding->abc);
    $templateProcessor->setValue('pre_bid', $bidding->pre_bid);
    $templateProcessor->setValue(
    'prep_date',
    $bidding->prep_date ? \Carbon\Carbon::parse($bidding->prep_date)->format('F j, Y') : ''
);
    $templateProcessor->setValue('bid_submission', $bidding->bid_submission);
    $templateProcessor->setValue('bid_opening', $this->formatDateWithPartOfDay($bidding->bid_opening));
    $templateProcessor->setValue('delivery_schedule', $bidding->delivery_schedule ?? '');
    $templateProcessor->setValue('name', $bidding->lgu->name ?? '');
    $templateProcessor->setValue('location', $bidding->lgu->location ?? '');
   $templateProcessor->setValue(
    'bac_chairman',
    isset($bidding->lgu->bac_chairman) ? strtoupper($bidding->lgu->bac_chairman) : ''
);

   // Ensure output dir
$outputDir = storage_path('app/generated');
if (!\Illuminate\Support\Facades\File::exists($outputDir)) {
    \Illuminate\Support\Facades\File::makeDirectory($outputDir, 0755, true);
}

// Sanitize values for safe filenames
$title       = preg_replace('/[^A-Za-z0-9_\-]+/', '_', $document->title);
$lguName     = preg_replace('/[^A-Za-z0-9_\-]+/', '_', $bidding->lgu->name ?? 'LGU');
$projectName = preg_replace('/[^A-Za-z0-9_\-]+/', '_', $bidding->project_name ?? 'Project');

// Build final output path with Title + LGU + Project + IDs
$outputPath = $outputDir . DIRECTORY_SEPARATOR .
              $title . '_' . $lguName . '_' . $projectName . '_' .
              $bidding->id . '_' . $document->id . '.docx';

$templateProcessor->saveAs($outputPath);

return response()->download($outputPath)->deleteFileAfterSend(true);

}
public function dashboard()
{
    $biddingsCount = \App\Models\Bidding::count();
    $documentsCount = \App\Models\Document::count();

    return view('dashboard', compact('biddingsCount', 'documentsCount'));
}

}
