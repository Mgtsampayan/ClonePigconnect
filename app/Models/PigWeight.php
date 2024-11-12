<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PigWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'pig_id',
        'date',
        'weight',
    ];

    public function pig()
    {
        return $this->belongsTo(Pig::class, 'pig_id', 'PigId');
    }
}