<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Official extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'position',
        'term_start',
        'term_end',
        'status',
    ];

    protected $casts = [
        'term_start' => 'date',
        'term_end'   => 'date',
    ];

    /**
     * The resident who holds this official position.
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    /**
     * Scope: only active officials.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope: only inactive officials.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}