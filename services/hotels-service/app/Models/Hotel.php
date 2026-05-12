<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $table = 'hotels';

    protected $fillable = [
        'hotel_name',
        'city',
        'address',
        'price_per_night',
        'rating',
    ];

    protected $casts = [
        'price_per_night' => 'float',
        'rating' => 'float',
    ];
}
