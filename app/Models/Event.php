<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'main_image',
        'date',
        'location'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }
}
