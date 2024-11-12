<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'preferred_weight',
        'age_of_pigs',
        'price_range',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}