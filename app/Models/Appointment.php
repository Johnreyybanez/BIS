<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id', 'appointment_type', 'appointment_date', 'status'
    ];

    protected $casts = [
        'appointment_date' => 'datetime'
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}