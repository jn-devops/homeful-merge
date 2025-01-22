<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SetTemplate extends Pivot
{
    public function set(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Set::class);
    }

    public function template(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Template::class);
    }
}
