<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Bidding extends Model
{
    use HasFactory;

    // default fillable
    protected $fillable = [
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
        'status',
    ];

    // ensure new models default to Draft if not provided
    protected $attributes = [
        'status' => 'Draft',
    ];

    public const STATUSES = [
        'Draft',
        'Ongoing',
        'Closed',
        'Opening',
        'Awarded',
        'Cancelled',
        'Completed',
    ];

    public static function availableStatuses()
    {
        return self::STATUSES;
    }

    public function lgu()
    {
        return $this->belongsTo(LGU::class);
    }

    /**
     * Allowed transitions according to your rules.
     * The returned array includes the current status so the dropdown can
     * safely show the selected value.
     */
    public function allowedStatusTransitions(): array
    {
        $status = $this->status ?? 'Draft';
        $today = Carbon::today()->toDateString();
        $bidSubmission = $this->bid_submission
            ? Carbon::parse($this->bid_submission)->toDateString()
            : null;

        // If the bid submission date is today and bid is not Cancelled,
        // the only allowed status choice should be Opening (system-driven).
        if ($status !== 'Cancelled' && $bidSubmission && $bidSubmission === $today) {
            return ['Opening'];
        }

        return match ($status) {
            // Draft/Ongoing/Closed: freely switch among these plus Cancelled
            'Draft', 'Ongoing', 'Closed' => ['Draft', 'Ongoing', 'Closed', 'Cancelled'],

            // Opening: only Awarded or Cancelled (Opening remains selectable)
            'Opening' => ['Opening', 'Awarded', 'Cancelled'],

            // Awarded => can only go to Completed
            'Awarded' => ['Awarded', 'Completed'],

            // Cancelled and Completed are terminal (only themselves)
            'Cancelled' => ['Cancelled'],
            'Completed' => ['Completed'],

            default => ['Draft', 'Ongoing', 'Closed', 'Cancelled', 'Opening', 'Awarded', 'Completed'],
        };
    }
}
