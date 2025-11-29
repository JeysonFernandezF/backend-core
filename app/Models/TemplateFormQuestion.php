<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemplateFormQuestion extends Model
{
    protected $table = 'template_questions';

    protected $fillable = [
        'question',
        'section_id',
    ];

    public function templateFormSection(): BelongsTo
    {
        return $this->belongsTo(TemplateFormSection::class, 'section_id');
    }
}
