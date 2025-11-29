<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateFormSection extends Model
{
    protected $table = 'template_sections';

    protected $fillable = [
        'name',  
        'form_id',
    ];

    public function templateForm(): BelongsTo
    {
        return $this->belongsTo(TemplateForm::class, 'form_id');
    }

    public function templateFormQuestions(): HasMany
    {
        return $this->hasMany(TemplateFormQuestion::class, 'section_id');
    }
}
