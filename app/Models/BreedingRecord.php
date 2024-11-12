<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BreedingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'sow_id',
        'boar_id',
        'date_of_breeding',
        'expected_farrowing_date',
    ];

    public function sow()
    {
        return $this->belongsTo(Pig::class, 'sow_id', 'PigId'); // Use 'PigId' as the primary key
    }

    public function boar()
    {
        return $this->belongsTo(Pig::class, 'boar_id', 'PigId'); // Use 'PigId' as the primary key
    }
}