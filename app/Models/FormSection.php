<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormSection extends Model
{
    protected $table = 'form_sections';

    protected $fillable = [
        'name',
        'form_id',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class, 'form_id');
    }

    public function formQuestions(): HasMany
    {
        return $this->hasMany(FormQuestion::class, 'section_id');
    }
}
