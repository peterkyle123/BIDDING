<?php

namespace App\Http\Controllers;

use App\Models\Bidding;
use App\Models\LGU;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;

class BiddingController extends Controller
{
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
            'project_name',
            'abc',
            'pre_bid',
            'bid_submission',
            'bid_opening',
            'lgu_id',
            'reference_number',
            'delivery_schedule',
            'solicitation_number',
            'prep_date',
            'category',
        ]);

        // Default status is Draft
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
            'project_name',
            'abc',
            'pre_bid',
            'bid_submission',
            'bid_opening',
            'lgu_id',
            'reference_number',
            'delivery_schedule',
            'solicitation_number',
            'prep_date',
            'category',
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

        $folderName = preg_replace('/[^\w\-]/', '_', $bidding->project_name);
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

                $placeholders = [
                    'project_name'       => $bidding->project_name,
                    'solicitation_number'=> $bidding->solicitation_number ?? '',
                    'reference_number'   => $bidding->reference_number ?? '',
                    'abc'                => $bidding->abc,
                    'pre_bid'            => $bidding->pre_bid ?? '',
                    'prep_date'          => $bidding->prep_date 
                                              ? \Carbon\Carbon::parse($bidding->prep_date)->format('F j, Y') 
                                              : '',
                    'bid_submission'     => $bidding->bid_submission ?? '',
                    'bid_opening'        => $bidding->bid_opening 
                                              ? $this->formatDateWithPartOfDay($bidding->bid_opening) 
                                              : '',
                    'delivery_schedule'  => $bidding->delivery_schedule ?? '',
                    'name'               => $bidding->lgu->name ?? '',
                    'location'           => $bidding->lgu->location ?? '',
                    'bac_chairman'       => $bidding->lgu->bac_chairman ?? '',
                    'category'           => $bidding->category ?? '',
                ];

                foreach ($placeholders as $key => $value) {
                    $templateProcessor->setValue($key, $value);
                    $templateProcessor->setValue(strtoupper($key), strtoupper($value));
                }

                $safeTitle   = preg_replace('/[^\w\-]/', '_', $document->title);
                $safeLgu     = preg_replace('/[^\w\-]/', '_', $bidding->lgu->name ?? 'LGU');
                $safeProject = preg_replace('/[^\w\-]/', '_', $bidding->project_name ?? 'Project');

                $fileName = "{$safeTitle}_{$safeLgu}_{$safeProject}_{$bidding->id}_{$document->id}.docx";
                $tempDocPath = storage_path("app/public/tmp/$fileName");
                $templateProcessor->saveAs($tempDocPath);

                $zip->addFile($tempDocPath, "$folderName/$fileName");
            }

            $zip->close();
        }

        if (!file_exists($zipPath)) {
            abort(404, "ZIP file was not created: $zipPath");
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    private function formatDateWithPartOfDay($dateTime)
    {
        $carbon = \Carbon\Carbon::parse($dateTime);
        $hour = $carbon->format('G');

        $part = match (true) {
            $hour >= 5 && $hour < 12 => 'in the morning',
            $hour >= 12 && $hour < 17 => 'in the afternoon',
            $hour >= 17 && $hour < 21 => 'in the evening',
            default => 'at night',
        };

        return $carbon->format('F d, Y \a\t g:i a') . " {$part}";
    }
    public function allowedStatusTransitions()
{
    $status = $this->status;
    $today = now()->toDateString();
    $bidSubmission = optional($this->bid_submission)->toDateString();

    // Automatic transition to Opening if bid submission is today
    if($status !== 'Cancelled' && $bidSubmission === $today) {
        return ['Opening'];
    }

    switch ($status) {
        case 'Draft':
        case 'Ongoing':
        case 'Closed':
            return ['Draft', 'Ongoing', 'Closed', 'Cancelled'];
        case 'Cancelled':
            return ['Cancelled'];
        case 'Opening':
            return ['Cancelled', 'Awarded'];
        case 'Awarded':
            return ['Completed'];
        case 'Completed':
            return ['Completed'];
        default:
            return [$status];
    }
}

}
