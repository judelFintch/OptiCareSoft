<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientDocument extends Model
{
    protected $fillable = [
        'patient_id', 'name', 'file_path', 'file_type',
        'file_size', 'category', 'notes', 'uploaded_by',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getFileSizeFormattedAttribute(): string
    {
        if (! $this->file_size) {
            return '';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = floor(log($this->file_size, 1024));
        return round($this->file_size / (1024 ** $i), 2) . ' ' . $units[$i];
    }
}
