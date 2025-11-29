<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordQuestion extends Model
{
    protected $table = 'record_questions';

    protected $fillable = [
        'notes',
        'is_safe',
        'observation_id',
        'form_question_id',
        'conduct_id',
        'barrier_id',
    ];

    public function observation(): BelongsTo
    {
        return $this->belongsTo(Observation::class);
    }

    public function barrier(): BelongsTo
    {
        return $this->belongsTo(Barrier::class);
    }

    public function conduct(): BelongsTo
    {
        return $this->belongsTo(Conduct::class);
    }
}
