<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProgramDetail extends Model
{
    protected $fillable = [
        'activity_id',
        'equipment_id',
        'worksite_id',
        'position_id',
        'department_id',
        'management_id',
        'company_id',
        'observed_task_id',
    ];
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class);
    }


    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function worksite(): BelongsTo
    {
        return $this->belongsTo(Worksite::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function management(): BelongsTo
    {
        return $this->belongsTo(Management::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function observedTask(): BelongsTo
    {
        return $this->belongsTo(ObservedTask::class);
    }

    public function programRegisters(): HasOne
    {
        return $this->hasOne(ProgramRegister::class);
    }
}
