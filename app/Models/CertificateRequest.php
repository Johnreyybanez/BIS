<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CertificateRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id', 'certificate_type_id', 'purpose', 'control_number',
        'status', 'requested_at', 'approved_by', 'approved_at', 'released_at'
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'released_at' => 'datetime'
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function certificateType()
    {
        return $this->belongsTo(CertificateType::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}