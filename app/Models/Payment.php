<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_request_id', 'or_number', 'amount',
        'payment_method', 'payment_date', 'received_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime'
    ];

    public function certificateRequest()
    {
        return $this->belongsTo(CertificateRequest::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}