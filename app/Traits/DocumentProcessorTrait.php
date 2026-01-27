<?php

namespace App\Traits;

use Carbon\Carbon;

trait DocumentProcessorTrait
{
    /**
     * Formats a date with a part of day string.
     */
    public function formatDateWithPartOfDay($dateTime)
    {
        $carbon = Carbon::parse($dateTime);
        $hour = $carbon->format('G');

        $part = match (true) {
            $hour >= 5 && $hour < 12 => 'in the morning',
            $hour >= 12 && $hour < 17 => 'in the afternoon',
            $hour >= 17 && $hour < 21 => 'in the evening',
            default => 'at night',
        };

        return $carbon->format('F d, Y \a\t g:i a') . " {$part}";
    }

    /**
     * Cleans and shortens strings for safe, short filenames.
     */
    public function getSafeFileName($title, $lgu, $project, $biddingId, $docId)
    {
        $cleanTitle = substr(preg_replace('/[^\w\-]/', '_', $title), 0, 30);
        $cleanLgu = substr(preg_replace('/[^\w\-]/', '_', $lgu), 0, 30);
        $cleanProject = substr(preg_replace('/[^\w\-]/', '_', $project), 0, 30);

        return "{$cleanTitle}_{$cleanLgu}_{$cleanProject}_{$biddingId}_{$docId}.docx";
    }

    /**
     * Standardizes the placeholder replacement logic.
     */
    public function applyPlaceholders($processor, $bidding)
    {
        $placeholders = [
            'project_name'        => $bidding->project_name,
            'solicitation_number' => $bidding->solicitation_number ?? '',
            'reference_number'    => $bidding->reference_number ?? '',
            'abc'                 => $bidding->abc,
            'pre_bid'             => $bidding->pre_bid ?? '',
            'prep_date'           => $bidding->prep_date ? Carbon::parse($bidding->prep_date)->format('F j, Y') : '',
            'bid_submission'      => $bidding->bid_submission ?? '',
            'bid_opening'         => $bidding->bid_opening ? $this->formatDateWithPartOfDay($bidding->bid_opening) : '',
            'delivery_schedule'   => $bidding->delivery_schedule ?? '',
            'name'                => $bidding->lgu->name ?? '',
            'location'            => $bidding->lgu->location ?? '',
            'bac_chairman'        => $bidding->lgu->bac_chairman ?? '',
            'category'            => $bidding->category ?? '',
        ];

      // Inside applyPlaceholders in your Trait
foreach ($placeholders as $key => $value) {
    // Convert special characters to XML-safe entities
    $safeValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    
    $processor->setValue($key, $safeValue);
    $processor->setValue(strtoupper($key), strtoupper($safeValue));
}
        
        return $processor;
    }
}