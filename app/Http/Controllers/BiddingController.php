<?php

namespace App\Http\Controllers;

use App\Models\Bidding;
use App\Models\LGU;
use App\Models\Document;
use App\Traits\DocumentProcessorTrait; // Import the new trait
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class BiddingController extends Controller
{
    use DocumentProcessorTrait; // Use the trait to access shared logic

    public function index(Request $request)
    {
        $sort = $request->query('sort', 'date'); 
        $direction = $request->query('direction', 'asc'); 

        $query = Bidding::with('lgu');

        if ($sort === 'abc') {
            $query->orderBy('abc', $direction);
        } else {
            $query->orderBy('created_at', $direction);
        }

        $biddings = $query->get();
        $lgus = LGU::all();
        $documents = Document::all();
        $openId = $request->query('open'); 

        return view('bidding', compact('biddings', 'lgus', 'documents', 'openId', 'sort', 'direction'));
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
            'prep_date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'project_name', 'abc', 'pre_bid', 'bid_submission', 'bid_opening', 
            'lgu_id', 'reference_number', 'delivery_schedule', 
            'solicitation_number', 'prep_date', 'category',
        ]);

        $data['status'] = 'Draft';
        Bidding::create($data);

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
            'prep_date' => 'required|date',
            'category' => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'project_name', 'abc', 'pre_bid', 'bid_submission', 'bid_opening', 
            'lgu_id', 'reference_number', 'delivery_schedule', 
            'solicitation_number', 'prep_date', 'category',
        ]);

        $bidding->update($data);
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

        // Use trait-style cleaning for the folder name
        $folderName = substr(preg_replace('/[^\w\-]/', '_', $bidding->project_name), 0, 50);
        $zipFileName = $folderName . '_docs.zip';
        $zipPath = storage_path("app/public/tmp/$zipFileName");

        if (!Storage::disk('public')->exists('tmp')) {
            Storage::disk('public')->makeDirectory('tmp');
        }

        $zip = new \ZipArchive;
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($documents as $document) {
                $templatePath = storage_path('app/public/' . $document->file_path);

                if (!file_exists($templatePath)) {
                    \Log::warning("Template not found: $templatePath");
                    continue;
                }

                $templateProcessor = new TemplateProcessor($templatePath);

                // ✅ Apply placeholders using the Trait
                $this->applyPlaceholders($templateProcessor, $bidding);

                // ✅ Generate short filename using the Trait (Fixes the Word Error)
                $fileName = $this->getSafeFileName(
                    $document->title, 
                    $bidding->lgu->name ?? 'LGU', 
                    $bidding->project_name, 
                    $bidding->id, 
                    $document->id
                );

                $tempDocPath = storage_path("app/public/tmp/$fileName");
                $templateProcessor->saveAs($tempDocPath);

                $zip->addFile($tempDocPath, "$folderName/$fileName");
            }
            $zip->close();
        }

        if (!file_exists($zipPath)) {
            abort(404, "ZIP file was not created.");
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}