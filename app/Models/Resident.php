<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Resident extends Model
{
    use HasFactory;

    protected $fillable = [
        'household_id',
        'resident_code',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'gender',
        'birthdate',
        'civil_status',
        'nationality',
        'voter_status',
        'occupation',
        'contact_number',
        'email',
        'photo',
        'is_pwd',
        'is_senior',
        'status',
    ];

    protected $casts = [
        'birthdate'    => 'date',
        'voter_status' => 'boolean',
        'is_pwd'       => 'boolean',
        'is_senior'    => 'boolean',
    ];

    // Append these accessors so they appear in JSON/array output automatically
    protected $appends = ['full_name', 'age'];

    // ──────────────────────────────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────────────────────────────

    public function household()
    {
        return $this->belongsTo(Household::class);
    }

    public function official()
    {
        return $this->hasOne(Official::class);
    }

    public function certificateRequests()
    {
        return $this->hasMany(CertificateRequest::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function complaints()
    {
        return $this->hasMany(BlotterRecord::class, 'complainant_id');
    }

    public function responses()
    {
        return $this->hasMany(BlotterRecord::class, 'respondent_id');
    }

    // ──────────────────────────────────────────────────────────────────────
    // Accessors
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Full name including middle name initial and suffix.
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name ? strtoupper(substr($this->middle_name, 0, 1)) . '.' : null,
            $this->last_name,
            $this->suffix,
        ]);

        return implode(' ', $parts);
    }

    /**
     * Age computed from birthdate.
     */
    public function getAgeAttribute(): ?int
    {
        if (! $this->birthdate) {
            return null;
        }

        return Carbon::parse($this->birthdate)->age;
    }

    // ──────────────────────────────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────────────────────────────

    /**
     * Only active residents.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Only senior citizens.
     */
    public function scopeSeniors($query)
    {
        return $query->where('is_senior', true);
    }

    /**
     * Only PWD residents.
     */
    public function scopePwd($query)
    {
        return $query->where('is_pwd', true);
    }

    /**
     * Search by name or resident code.
     */
    public function scopeSearch($query, string $term)
    {
        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'like', "%{$term}%")
              ->orWhere('last_name', 'like', "%{$term}%")
              ->orWhere('middle_name', 'like', "%{$term}%")
              ->orWhere('resident_code', 'like', "%{$term}%");
        });
    }
}