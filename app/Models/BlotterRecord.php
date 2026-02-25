<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlotterRecord extends Model
{
    use HasFactory;

    protected $table = 'blotter_records';

    protected $fillable = [
        'complainant_id',
        'respondent_id',
        'incident_date',
        'incident_location',
        'description',
        'status',
        'created_by'
    ];

    protected $casts = [
        'incident_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function complainant(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'complainant_id');
    }

    public function respondent(): BelongsTo
    {
        return $this->belongsTo(Resident::class, 'respondent_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getCaseNumberAttribute(): string
    {
        return '#' . str_pad($this->id, 6, '0', STR_PAD_LEFT);
    }

    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'ongoing' => 'warning',
            'settled' => 'success',
            'filed' => 'info',
            'dismissed' => 'danger'
        ];
        
        return $badges[$this->status] ?? 'secondary';
    }

    public function getStatusColorAttribute(): string
    {
        $colors = [
            'ongoing' => '#f4a20a',
            'settled' => '#1cc88a',
            'filed' => '#0d6efd',
            'dismissed' => '#ff4d6d'
        ];
        
        return $colors[$this->status] ?? '#b0b7cc';
    }
}