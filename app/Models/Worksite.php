<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worksite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public function programDetails()
    {
        return $this->hasMany(ProgramDetail::class, 'worksite_id');
    }
    public function programRegisters()
    {
        return $this->hasManyThrough(
            ProgramRegister::class, // 1. El modelo final que queremos (ProgramRegister)
            ProgramDetail::class,   // 2. El modelo intermedio (ProgramDetail)
            'worksite_id',          // 3. La clave foránea en la tabla intermedia (program_details.worksite_id)
            'program_detail_id',    // 4. La clave foránea en la tabla final (program_registers.program_detail_id)
            'id',                   // 5. La clave local en este modelo (worksites.id)
            'id'                    // 6. La clave local en el modelo intermedio (program_details.id)
        );
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

}
