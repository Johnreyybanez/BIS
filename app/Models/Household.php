<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Household extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_number',
        'head_resident_id',
        'purok',
        'address',
    ];

    /** Resident designated as household head */
    public function headResident()
    {
        return $this->belongsTo(Resident::class, 'head_resident_id');
    }

    /** All residents belonging to this household */
    public function residents()
    {
        return $this->hasMany(Resident::class, 'household_id');
    }
}