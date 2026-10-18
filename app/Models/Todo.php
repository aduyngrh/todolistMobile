<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todo extends Model
{
    /** @use HasFactory<\Database\Factories\TodoFactory> */
    use HasFactory, SoftDeletes;

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)
            ->orderByRaw('due_date IS NULL')
            ->orderBy('due_date')
            ->orderBy('created_at');
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'due_date',
        'start_time',
        'end_time',
        'reminder_at',
        'priority',
        'completed',
        'archived_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'reminder_at' => 'datetime',
        'archived_at' => 'datetime',
    ];
}
