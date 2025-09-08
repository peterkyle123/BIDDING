<?php

namespace App\Http\Controllers;

use App\Models\Bidding;
use App\Models\LGU;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use PhpOffice\PhpWord\TemplateProcessor;

class BiddingController extends Controller
{
    public function index(Request $request)
    {
        $biddings = Bidding::with('lgu')->get();
        $lgus = LGU::all();
        $documents = Document::all();

        $openId = $request->query('open'); // 👈 grab ?open=ID

        return view('bidding', compact('biddings', 'lgus', 'documents', 'openId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'abc' => 'required|numeric',
            'pre_bid' => 'nullable|date',
            'bid_submission' => 'nullable|date',
            'bid_opening' => 'required|date',
            'lgu_id' => 'required|exists:lgus,id',
            'delivery_schedule' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'solicitation_number' => 'nullable|string|max:255',
        ]);

        Bidding::create($request->only([
            'project_name',
            'abc',
            'pre_bid',
            'bid_submission',
            'bid_opening',
            'lgu_id',
            'reference_number',
            'delivery_schedule',
            'solicitation_number',
        ]));

        return redirect()->route('biddings.index')->with('success', 'Bidding project added!');
    }

    public function update(Request $request, Bidding $bidding)
    {
        $request->validate([
            'project_name' => 'required|string|max:255',
            'abc' => 'required|numeric',
            'pre_bid' => 'nullable|date',
            'bid_submission' => 'nullable|date',
            'bid_opening' => 'required|date',
            'lgu_id' => 'required|exists:lgus,id',
            'delivery_schedule' => 'nullable|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'solicitation_number' => 'nullable|string|max:255',
        ]);

        $bidding->update($request->only([
            'project_name',
            'abc',
            'pre_bid',
            'bid_submission',
            'bid_opening',
            'lgu_id',
            'reference_number',
            'delivery_schedule',
            'solicitation_number',
        ]));

        return redirect()->route('biddings.index')->with('success', 'Bidding project updated!');
    }

    public function destroy(Bidding $bidding)
    {
        $bidding->delete();
        return redirect()->route('biddings.index')->with('success', 'Bidding project deleted!');
    }

public function downloadZip(Request $request, $biddingId)
{
    $bidding = Bidding::with('lgu')->findOrFail($biddingId);

    $request->validate([
        'document_ids'   => 'required|array|min:1',
        'document_ids.*' => 'exists:documents,id',
    ]);

    $documents = Document::whereIn('id', $request->document_ids)->get();

    // Folder name = project name (safe)
    $folderName = preg_replace('/[^\w\-]/', '_', $bidding->project_name);

    // ZIP file name
    $zipFileName = $folderName . '_docs.zip';
    $zipPath = storage_path("app/public/tmp/$zipFileName");

    // Ensure tmp folder exists
    if (!Storage::disk('public')->exists('tmp')) {
        Storage::disk('public')->makeDirectory('tmp');
    }

    $zip = new \ZipArchive;
    if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
        foreach ($documents as $document) {
            // ✅ Correct template path
            $templatePath = storage_path('app/public/' . $document->file_path);


            if (!file_exists($templatePath)) {
                \Log::warning("Template not found: $templatePath");
                continue;
            }
$templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);

// Replace placeholders
$templateProcessor->setValue('project_name', $bidding->project_name);
$templateProcessor->setValue('solicitation_number', $bidding->solicitation_number ?? '');
$templateProcessor->setValue('reference_number', $bidding->reference_number ?? '');
$templateProcessor->setValue('abc', $bidding->abc);
$templateProcessor->setValue('pre_bid', $bidding->pre_bid ?? '');
$templateProcessor->setValue('bid_submission', $bidding->bid_submission ?? '');
$templateProcessor->setValue(
    'bid_opening',
    $bidding->bid_opening ? $this->formatDateWithPartOfDay($bidding->bid_opening) : ''
);
$templateProcessor->setValue('delivery_schedule', $bidding->delivery_schedule ?? '');
$templateProcessor->setValue('name', $bidding->lgu->name ?? '');
$templateProcessor->setValue('location', $bidding->lgu->location ?? '');
$templateProcessor->setValue(
    'bac_chairman',
    isset($bidding->lgu->bac_chairman) ? strtoupper($bidding->lgu->bac_chairman) : ''
);

// Add this inside your BiddingController (or whichever controller is handling it)

            // Safe filenames
            $safeTitle   = preg_replace('/[^\w\-]/', '_', $document->title);
            $safeLgu     = preg_replace('/[^\w\-]/', '_', $bidding->lgu->name ?? 'LGU');
            $safeProject = preg_replace('/[^\w\-]/', '_', $bidding->project_name ?? 'Project');

            $fileName = "{$safeTitle}_{$safeLgu}_{$safeProject}_{$bidding->id}_{$document->id}.docx";

            // Save temp doc
            $tempDocPath = storage_path("app/public/tmp/$fileName");
            $templateProcessor->saveAs($tempDocPath);

            // Add to ZIP under the project folder
            $zip->addFile($tempDocPath, "$folderName/$fileName");
        }

        $zip->close();
    }

    // ✅ Double-check file exists before download
    if (!file_exists($zipPath)) {
        abort(404, "ZIP file was not created: $zipPath");
    }

    return response()->download($zipPath)->deleteFileAfterSend(true);
}
private function formatDateWithPartOfDay($dateTime)
{
    $carbon = \Carbon\Carbon::parse($dateTime);

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

}


