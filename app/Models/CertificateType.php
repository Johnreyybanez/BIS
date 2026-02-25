<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CertificateType extends Model
{
    use HasFactory;

    protected $fillable = [
        'certificate_name',
        'description',
        'fee'
    ];

    protected $casts = [
        'fee' => 'decimal:2'
    ];

    public function certificateRequests(): HasMany
    {
        return $this->hasMany(CertificateRequest::class);
    }
}