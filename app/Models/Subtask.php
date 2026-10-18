<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = ['todo_id', 'title', 'due_date', 'completed'];

    protected $casts = ['due_date' => 'date', 'completed' => 'boolean'];

    public function todo(): BelongsTo
    {
        return $this->belongsTo(Todo::class);
    }
}
