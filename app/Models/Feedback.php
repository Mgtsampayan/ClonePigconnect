<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'pig_id',
        'user_id',
        'comment',
        'rating',
    ];

    public function pig()
    {
        return $this->belongsTo(Pig::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}