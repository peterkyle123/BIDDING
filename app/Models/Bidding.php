<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bidding extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_name',
        'abc',
        'pre_bid',
        'bid_submission',
        'bid_opening',
        'lgu_id',
        'reference_number',
        'delivery_schedule', 
        'solicitation_number',  // ✅ allow saving LGU reference
    ];

    // ✅ Relationship
    public function lgu()
    {
        return $this->belongsTo(LGU::class);
    }
}
