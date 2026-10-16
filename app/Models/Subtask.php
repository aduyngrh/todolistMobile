<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subtask extends Model
{
    use HasFactory;

    protected $fillable = ['todo_id', 'title', 'completed'];

    protected $casts = ['completed' => 'boolean'];

    public function todo(): BelongsTo
    {
        return $this->belongsTo(Todo::class);
    }
}
