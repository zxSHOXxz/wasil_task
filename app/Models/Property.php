<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price_per_night',
        'status', // available, unavailable
        'images'
    ];

    protected $casts = [
        'images' => 'array',
        'price_per_night' => 'decimal:2'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
