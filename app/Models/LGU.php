<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LGU extends Model
{
    use HasFactory;

    protected $table = 'lgus'; // must match your table
    protected $fillable = ['name', 'location','envelope_system', 'bac_chairman',];
    
    public function biddings()
{
    return $this->hasMany(Bidding::class, 'lgu_id');
}
}
