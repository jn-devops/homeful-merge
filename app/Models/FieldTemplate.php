<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FieldTemplate extends Pivot
{
    public function template(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function field(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Field::class);
    }
}
