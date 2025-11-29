<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function recordQuestions(): HasMany
    {
        return $this->hasMany(RecordQuestion::class);
    }
}
