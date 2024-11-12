<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PigFarmInformation extends Model
{
    use HasFactory;
 /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'feeding_type',
        'frequency_of_feeding',
        'min_price_per_kilo',
        'max_price_per_kilo',
        'latitude',
        'longitude',
    ];
  
}
