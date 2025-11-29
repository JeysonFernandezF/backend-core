<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProgramRegister extends Model
{
    protected $table = 'program_registers';

    protected $fillable = [
        'name',
        'start_date',
        'status',
        'form_id',
        'program_detail_id',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function programDetail(): BelongsTo
    {
        return $this->belongsTo(ProgramDetail::class);
    }

    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class);
    }

}
