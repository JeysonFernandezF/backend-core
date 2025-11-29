<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateForm extends Model
{
    protected $table = 'template_forms';

    protected $fillable = [
        'name',
        'description',
        'uses',
        'status'
    ];

    public function templateFormSections(): HasMany
    {
        return $this->hasMany(TemplateFormSection::class, 'form_id');
    }
}
