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
        return $this->hasMany(Subtask::class);
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
        'priority',
        'completed',
        'archived_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'archived_at' => 'datetime',
    ];
}
