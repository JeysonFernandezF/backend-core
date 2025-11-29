<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Observation extends Model
{
    protected $table = 'observations';

    protected $fillable = [
        'people_observed',
        'observed_at',
        'scheduled_at',
        'date_in',
        'date_end',
        'comments',
        'program_register_id',
        'user_id',
        'area_id',
        'critical_risk_id',
        'turn_id',
    ];

    public function programRegister(): BelongsTo
    {
        return $this->belongsTo(ProgramRegister::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function criticalRisk(): BelongsTo
    {
        return $this->belongsTo(CriticalRisk::class);
    }

    public function turn(): BelongsTo
    {
        return $this->belongsTo(Turn::class);
    }

    public function recordQuestions(): HasMany
    {
        return $this->hasMany(RecordQuestion::class);
    }
}
