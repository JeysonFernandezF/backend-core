<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormQuestion extends Model
{
    protected $table = 'form_questions';

    protected $fillable = [
        'question',
        'section_id',
    ];

    public function formSection(): BelongsTo
    {
        return $this->belongsTo(FormSection::class, 'section_id');
    }
}
