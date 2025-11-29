<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Ajusta según los campos reales de la tabla activities
    ];

    public function program_details(): HasMany
    {
        return $this->hasMany(ProgramDetail::class);
    }
}
