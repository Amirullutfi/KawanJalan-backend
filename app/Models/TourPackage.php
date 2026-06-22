<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TourPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'price',
        'price_unit',
        'duration',
        'main_image'
    ];

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'package_id');
    }
}
