<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'package_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'travel_date',
        'num_people',
        'total_price',
        'dp_amount',
        'unique_code',
        'photo_package_name',
        'photo_package_price',
        'addon_drone',
        'addon_drone_price',
        'payment_deadline',
        'payment_status'
    ];

    protected $casts = [
        'travel_date' => 'date',
        'payment_deadline' => 'datetime',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }
}
