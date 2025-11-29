<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Turn extends Model
{
    protected $table = 'turns';

    protected $fillable = [
        'name',
    ];

    public function observations(): HasMany
    {
        return $this->hasMany(Observation::class);
    }
}
