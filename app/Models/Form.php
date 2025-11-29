<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Form extends Model
{
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function programRegister(): HasOne
    {
        return $this->hasOne(ProgramRegister::class);
    }

    public function formSections(): HasMany
    {
        return $this->hasMany(FormSection::class, 'form_id');
    }
}
