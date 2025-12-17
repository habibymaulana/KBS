<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_id',
        'assigned_by',
        'assigned_to',
        'title',
        'type',
        'due_date',
        'status',
        'score',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

